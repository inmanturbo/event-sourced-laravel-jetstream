<?php

namespace App\Actions;

use App\Aggregates\TeamAggregate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\AddingTeam;
use Laravel\Jetstream\Jetstream;

class CreateTeam implements CreatesTeams
{
    /**
     * Validate and create a new team for the given user.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function create($user, array $input)
    {
        Gate::forUser($user)->authorize('create', Jetstream::newTeamModel());

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'uuid' => ['nullable'],
        ])->validateWithBag('createTeam');

        AddingTeam::dispatch($user);

        $uuid = Str::uuid();

        TeamAggregate::retrieve($uuid)->createTeam($user->uuid, $input['name'])->persist();

        return $user->fresh()->currentTeam;
    }
}
