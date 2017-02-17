<?php

namespace Core\DAL\DataService\User\Events;

use Core\DAL\Models\User;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Validator as ValidatorFactory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Class UserDataServiceSubscriber.
 *
 * @package Core\DAL\DataService\User\Events
 */
class UserDataServiceSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen('events.photoDataService.beforeSave', static::class . '@onBeforeSave');
    }

    /**
     * Handle before save event.
     *
     * @param User $user
     * @param array $attributes
     * @param array $options
     * @throws ValidationException
     */
    public function onBeforeSave($user, array $attributes = [], array $options = [])
    {
        $validator = ValidatorFactory::make(['email' => $user->email], [
            'email' => $user->exists
                ? Rule::unique('users')->ignore($user->getOriginal('email'), 'email')
                : Rule::unique('users'),
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if ($user->getOriginal('password') !== $user->password) {
            $user->password = $this->hasher->make($user->password);
        }
    }
}
