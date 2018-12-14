<?php

namespace App\Managers\Post;

use App\Models\Builders\PostBuilder;
use App\Models\Photo;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\ConnectionInterface as Database;

/**
 * Class PostManager.
 *
 * @package App\Managers\Post
 */
class PostManager
{
    /**
     * @var Database
     */
    private $database;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var PostValidator
     */
    private $validator;

    /**
     * PostManager constructor.
     *
     * @param Database $database
     * @param Auth $auth
     * @param PostValidator $validator
     */
    public function __construct(Database $database, Auth $auth, PostValidator $validator)
    {
        $this->database = $database;
        $this->auth = $auth;
        $this->validator = $validator;
    }

    /**
     * Create a post.
     *
     * @param array $attributes
     * @return Post
     */
    public function create(array $attributes): Post
    {
        $defaultAttributes = ['created_by_user_id' => $this->auth->id()];

        $attributes = array_merge($attributes, $defaultAttributes);

        $attributes = $this->validator->validateForCreate($attributes);

        $post = (new Post)->fill($attributes);

        $this->database->transaction(function () use ($post, $attributes) {
            $post->save();
            if (isset($attributes['tags'])) {
                $this->syncTags($post, $attributes['tags']);
            }
            if (isset($attributes['photo'])) {
                $this->syncPhotos($post, [$attributes['photo']]);
            }
        });

        $post->load('tags', 'photos');

        return $post;
    }

    /**
     * Synchronize tags relation records.
     *
     * @param Post $post
     * @param array $rawTags
     * @return void
     */
    private function syncTags(Post $post, array $rawTags): void
    {
        $post->tags()->sync(
            collect($rawTags)
                ->map(function (array $attributes) {
                    return (new Tag)->newQuery()->firstOrCreate(['value' => $attributes['value']]);
                })
                ->pluck('id')
                ->toArray()
        );
    }

    /**
     * Synchronize photos relation records.
     *
     * @param Post $post
     * @param array $rawPhotos
     * @return void
     */
    private function syncPhotos(Post $post, array $rawPhotos): void
    {
        $post->photos()->sync(
            collect($rawPhotos)
                ->filter(function (array $attributes) {
                    return (new Photo)->newQuery()->find($attributes['id']);
                })
                ->pluck('id')
                ->toArray()
        );
    }

    /**
     * Update a post.
     *
     * @param Post $post
     * @param array $attributes
     */
    public function update(Post $post, array $attributes = []): void
    {
        $attributes = $this->validator->validateForUpdate($post, $attributes);

        $post->fill($attributes);

        $this->database->transaction(function () use ($post, $attributes) {
            $post->save();
            if (isset($attributes['tags'])) {
                $this->syncTags($post, $attributes['tags']);
            }
            if (isset($attributes['photo'])) {
                $this->syncPhotos($post, [$attributes['photo']]);
            }
        });

        $post->load('tags', 'photos');
    }

    /**
     * Get a post by ID.
     *
     * @param int $id
     * @param array $filters
     * @return Post
     */
    public function getById(int $id, array $filters = []): Post
    {
        $filters = $this->validator->validateForFiltering($filters);

        $post = (new Post)
            ->newQuery()
            ->withPhoto()
            ->withTags()
            ->when(isset($filters['tag']), function (PostBuilder $query) use ($filters) {
                return $query->whereTagValueEquals($filters['tag']);
            })
            ->when(isset($filters['search_phrase']), function (PostBuilder $query) use ($filters) {
                return $query->searchByPhrase($filters['search_phrase']);
            })
            ->when(!$this->auth->user() || !$this->auth->user()->can('view-unpublished-posts'), function (PostBuilder $query) {
                return $query->whereIsPublished();
            })
            ->findOrFail($id);

        return $post;
    }

    /**
     * Get a post before ID.
     *
     * @param int $id
     * @param array $filters
     * @return Post
     */
    public function getBeforeId(int $id, array $filters = []): Post
    {
        $filters = $this->validator->validateForFiltering($filters);

        $post = (new Post)
            ->newQuery()
            ->withPhoto()
            ->withTags()
            ->whereIdLessThan($id)
            ->orderByIdDesc()
            ->when(isset($filters['tag']), function (PostBuilder $query) use ($filters) {
                return $query->whereTagValueEquals($filters['tag']);
            })
            ->when(isset($filters['search_phrase']), function (PostBuilder $query) use ($filters) {
                return $query->searchByPhrase($filters['search_phrase']);
            })
            ->when(!$this->auth->user() || !$this->auth->user()->can('view-unpublished-posts'), function (PostBuilder $query) {
                return $query->whereIsPublished();
            })
            ->firstOrFail();

        return $post;
    }

    /**
     * Get a post after ID.
     *
     * @param int $id
     * @param array $filters
     * @return Post
     */
    public function getAfterId(int $id, array $filters = []): Post
    {
        $filters = $this->validator->validateForFiltering($filters);

        $post = (new Post)
            ->newQuery()
            ->withPhoto()
            ->withTags()
            ->whereIdGreaterThan($id)
            ->orderByIdAsc()
            ->when(isset($filters['tag']), function (PostBuilder $query) use ($filters) {
                return $query->whereTagValueEquals($filters['tag']);
            })
            ->when(isset($filters['search_phrase']), function (PostBuilder $query) use ($filters) {
                return $query->searchByPhrase($filters['search_phrase']);
            })
            ->when(!$this->auth->user() || !$this->auth->user()->can('view-unpublished-posts'), function (PostBuilder $query) {
                return $query->whereIsPublished();
            })
            ->firstOrFail();

        return $post;
    }

    /**
     * Paginate over posts.
     *
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return mixed
     */
    public function paginate(int $page, int $perPage, array $filters = [])
    {
        $filters = $this->validator->validateForFiltering($filters);

        $query = (new Post)
            ->newQuery()
            ->withPhoto()
            ->withTags()
            ->orderByCreatedAtDesc()
            ->when(isset($filters['tag']), function (PostBuilder $query) use ($filters) {
                return $query->whereTagValueEquals($filters['tag']);
            })
            ->when(isset($filters['search_phrase']), function (PostBuilder $query) use ($filters) {
                return $query->searchByPhrase($filters['search_phrase']);
            })
            ->when(!optional($this->auth->user())->can('view-unpublished-posts'), function (PostBuilder $query) {
                return $query->whereIsPublished();
            });

        $paginator = $query->paginate($perPage, ['*'], 'page', $page)->appends($filters);

        return $paginator;
    }

    /**
     * Delete a post.
     *
     * @param Post $post
     * @return void
     */
    public function delete(Post $post): void
    {
        $this->database->transaction(function () use ($post) {
            $post->delete();
        });
    }
}
