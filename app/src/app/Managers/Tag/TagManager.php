<?php

namespace App\Managers\Tag;

use App\Models\Tag;
use Illuminate\Database\ConnectionInterface as Database;

/**
 * Class TagManager.
 *
 * @package App\Managers\Tag
 */
class TagManager
{
    /**
     * @var Database
     */
    private $database;

    /**
     * TagManager constructor.
     *
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Paginate over tags.
     *
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return mixed
     */
    public function paginate(int $page, int $perPage, array $filters = [])
    {
        $query = (new Tag)
            ->newQuery()
            ->defaultSelect()
            ->orderByPopularity();

        $paginator = $query->paginate($perPage, ['*'], 'page', $page)->appends($filters);

        return $paginator;
    }
}
