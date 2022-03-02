<?php

namespace App\Aggregates;

use App\StorableEvents\TeamCreated;
use App\StorableEvents\TeamDeleted;
use App\StorableEvents\TeamMemberAdded;
use App\StorableEvents\TeamMemberInvited;
use App\StorableEvents\TeamMemberRemoved;
use App\StorableEvents\TeamUpdated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class TeamAggregate extends AggregateRoot
{
    public function createTeam(
        string $ownerUuid,
        string $name,
    ) {
        $this->recordThat(new TeamCreated(
            teamUuid: $this->uuid(),
            name: $name,
            ownerId: $ownerUuid,
        ));

        return $this;
    }

    public function addMember(
        string $teamUuid,
        string $email,
        string $role,
    ) {
        $this->recordThat(new TeamMemberAdded(
            teamUuid: $teamUuid,
            email: $email,
            role: $role,
        ));

        return $this;
    }

    public function deleteTeam()
    {
        $this->recordThat(new TeamDeleted(
            teamUuid: $this->uuid(),
        ));

        return $this;
    }

    public function inviteTeamMember(
        string $email,
        string $role,
        string $invitationUuid,
    ) {
        $this->recordThat(new TeamMemberInvited(
            teamUuid: $this->uuid(),
            email: $email,
            role: $role,
            invitationUuid: $invitationUuid,
        ));

        return $this;
    }

    public function removeTeamMember(
        string $teamMemberUuid,
    ) {
        $this->recordThat(new TeamMemberRemoved(
            teamUuid: $this->uuid(),
            teamMemberUuid: $teamMemberUuid,
        ));

        return $this;
    }

    public function updateTeam(
        string $name,
    ) {
        $this->recordThat(new TeamUpdated(
            teamUuid: $this->uuid(),
            name: $name,
        ));

        return $this;
    }
}
