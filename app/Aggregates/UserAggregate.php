<?php

namespace App\Aggregates;

use App\StorableEvents\UserCreated;
use App\StorableEvents\UserDeleted;
use App\StorableEvents\UserPasswordUpdated;
use App\StorableEvents\UserProfileUpdated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class UserAggregate extends AggregateRoot
{
    public function createUser(
        string $name,
        string $email,
        string $password,
        ?bool  $withPersonalTeam = false,
        ?string $teamUuid = null,
        ?string $teamName = null,
    ) {
        $this->recordThat(
            new UserCreated(
                userUuid: $this->uuid(),
                name: $name,
                email: $email,
                password: $password,
                withPersonalTeam: $withPersonalTeam,
                teamUuid: $teamUuid,
                teamName: $teamName,
            )
        );

        return $this;
    }

    public function updateUserProfile(
        string $name,
        string $email,
        ?string $profilePhotoPath = null,
    ) {
        $this->recordThat(
            new UserProfileUpdated(
                userUuid: $this->uuid(),
                name: $name,
                email: $email,
                profilePhotoPath: $profilePhotoPath,
            )
        );

        return $this;
    }

    public function updateUserPassword(
        string $password,
    ) {
        $this->recordThat(
            new UserPasswordUpdated(
                userUuid: $this->uuid(),
                password: $password,
            )
        );

        return $this;
    }

    public function deleteUser()
    {
        $this->recordThat(
            new UserDeleted(
                userUuid: $this->uuid(),
            )
        );

        return $this;
    }
}
