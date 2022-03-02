<?php

namespace App\Projectors;

use App\Models\Team;
use App\Models\User;
use App\StorableEvents\TeamCreated;
use App\StorableEvents\TeamDeleted;
use App\StorableEvents\TeamMemberAdded;
use App\StorableEvents\TeamMemberInvited;
use App\StorableEvents\TeamMemberRemoved;
use App\StorableEvents\TeamUpdated;
use Laravel\Jetstream\Jetstream;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class TeamProjector extends Projector
{
    public function onTeamCreated(TeamCreated $event)
    {
        $user = User::whereUuid($event->ownerUuid)->first();

        $user->switchTeam($team = $user->ownedTeams()->create([
            'uuid' => $event->teamUuid,
            'name' => $event->name,
            'personal_team' => $event->personalTeam,
        ]));
    }

    public function onTeamMemberAdded(TeamMemberAdded $event)
    {
        $team = Team::whereUuid($event->teamUuid)->first();

        $newTeamMember = Jetstream::findUserByEmailOrFail($event->email);

        $team->users()->attach($newTeamMember, ['role' => $event->role]);
    }

    public function onTeamDeleted(TeamDeleted $event)
    {
        $team = Team::whereUuid($event->teamUuid)->first();

        $team->purge();
    }

    public function onTeamMemberInvited(TeamMemberInvited $event)
    {
        $team = Team::whereUuid($event->teamUuid)->firstOrFail();
        
        $team->teamInvitations()->create([
            'email' => $event->email,
            'role' => $event->role,
            'uuid' => $event->invitationUuid,
        ]);
    }

    public function onTeamMemberRemoved(TeamMemberRemoved $event)
    {
        $team = Team::whereUuid($event->teamUuid)->first();

        $teamMember = User::whereUuid($event->teamMemberUuid)->first();

        $team->removeUser($teamMember);
    }

    public function onTeamUpdated(TeamUpdated $event)
    {
        $team = Team::whereUuid($event->teamUuid)->first();

        $team->forceFill([
            'name' => $event->name,
        ])->save();
    }
}
