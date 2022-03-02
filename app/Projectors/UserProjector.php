<?php

namespace App\Projectors;

use App\Aggregates\TeamAggregate;
use App\Models\User;
use Illuminate\Support\Str;
use App\StorableEvents\UserCreated;
use App\StorableEvents\UserDeleted;
use Illuminate\Support\Facades\Hash;
use App\StorableEvents\UserProfileUpdated;
use App\StorableEvents\UserPasswordUpdated;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UserProjector extends Projector
{
    public function onUserCreated(UserCreated $event)
    {
        $user = User::forceCreate([
            'uuid' => $event->userUuid,
            'name' => $event->name,
            'email' => $event->email,
            'password' => Hash::make($event->password),
        ]);

        $teamUuid = $event->teamUuid ?
            $event->teamUuid :
            Str::uuid();

        if ($event->withPersonalTeam) {

            $this->withPersonalTeam(
                userUuid: $user->uuid,
                userName: $user->name,
                teamUuid: $teamUuid
            );
        }
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

    private function withPersonalTeam($userUuid, $userName, $teamUuid, $teamName = null)
    {
        $teamName = $teamName ?: explode(' ', $userName, 2)[0] . "'s Team";

        $teamAggregate = TeamAggregate::retrieve($teamUuid);

        $teamAggregate->createTeam(
            name: $teamName,
            ownerUuid: $userUuid,
            personalTeam: true,
        )->persist();
    }
}
