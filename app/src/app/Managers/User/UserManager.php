<?php

namespace App\Managers\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\ConnectionInterface as Database;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class UserManager.
 *
 * @package App\Managers\User
 */
class UserManager
{
    /**
     * @var Database
     */
    private $database;

    /**
     * @var Hasher
     */
    private $hasher;

    /**
     * @var UserValidator
     */
    private $validator;

    /**
     * UserManager constructor.
     *
     * @param Database $database
     * @param Hasher $hasher
     * @param UserValidator $validator
     */
    public function __construct(Database $database, Hasher $hasher, UserValidator $validator)
    {
        $this->database = $database;
        $this->hasher = $hasher;
        $this->validator = $validator;
    }

    /**
     * Create a user.
     *
     * @param array $attributes
     * @return User
     */
    public function create(array $attributes): User
    {
        // Create a customer user by default.
        if (!isset($attributes['role_id'])) {
            $attributes['role_id'] = (new Role)->newQuery()->whereNameCustomer()->firstOrFail()->id;
        }

        $attributes = $this->validator->validateForCreate($attributes);

        $user = (new User)->fill($attributes);

        $user->password = $this->hasher->make($attributes['password']);

        $this->database->transaction(function () use ($user) {
            $user->save();
        });

        return $user;
    }

    /**
     * Update a user.
     *
     * @param User $user
     * @param array $attributes
     * @return void
     */
    public function update(User $user, array $attributes): void
    {
        $attributes = $this->validator->validateForSave($user, $attributes);

        $user->fill($attributes);

        if (isset($attributes['password'])) {
            $user->password = $this->hasher->make($attributes['password']);
        }

        $this->database->transaction(function () use ($user) {
            $user->save();
        });
    }

    /**
     * Get a user by ID.
     *
     * @param int $id
     * @return User
     */
    public function getById(int $id): User
    {
        $user = (new User)
            ->newQuery()
            ->findOrFail($id);

        return $user;
    }

    /**
     * Get a user by name.
     *
     * @param string $name
     * @return User
     */
    public function getByName(string $name): User
    {
        $user = (new User)
            ->newQuery()
            ->whereNameEquals($name)
            ->firstOrFail();

        return $user;
    }

    /**
     * Get a user by credentials.
     *
     * @param string $email
     * @param string $password
     * @return User
     */
    public function getByCredentials(string $email, string $password): User
    {
        $user = $this->getByEmail($email);

        if (!$this->hasher->check($password, $user->password)) {
            throw new ModelNotFoundException(__('auth.password', ['email' => $email]));
        }

        return $user;
    }

    /**
     * Get a user by email.
     *
     * @param string $email
     * @return User
     */
    public function getByEmail(string $email): User
    {
        $user = (new User)
            ->newQuery()
            ->whereEmailEquals($email)
            ->firstOrFail();

        return $user;
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return void
     */
    public function delete(User $user): void
    {
        $this->database->transaction(function () use ($user) {
            $user->delete();
        });
    }
}
