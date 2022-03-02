<?php

namespace App\Projectors;

use App\Models\User;
use App\StorableEvents\UserCreated;
use App\StorableEvents\UserDeleted;
use App\StorableEvents\UserPasswordUpdated;
use App\StorableEvents\UserProfileUpdated;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UserProjector extends Projector
{
    public function onUserCreated(UserCreated $event)
    {
        User::forceCreate([
            'uuid' => $event->userUuid,
            'name' => $event->name,
            'email' => $event->email,
            'password' => Hash::make($event->password),
        ]);
    }

    public function onUserProfileUpdated(UserProfileUpdated $event)
    {
        $user = User::whereUuid($event->userUuid)->first();

        if (
            $event->email !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $event);
        } else {
            $user->forceFill([
                'name' => $event->name,
                'email' => $event->email,
            ])->save();
        }
    }

    public function onUserPasswordUpdated(UserPasswordUpdated $event)
    {
        $user = User::whereUuid($event->userUuid)->first();

        $user->forceFill([
            'password' => Hash::make($event->password),
        ])->save();
    }

    public function onUserDeleted(UserDeleted $event)
    {
        $user = User::whereUuid($event->userUuid)->first();
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser(MustVerifyEmail $user, UserProfileUpdated $event)
    {
        $user->forceFill([
            'name' => $event->name,
            'email' => $event->email,
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
