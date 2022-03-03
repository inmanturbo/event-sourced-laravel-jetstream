<?php

namespace App\Http\Controllers;

use App\Aggregates\UserAggregate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MainUserController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = User::whereUuid($request->user_uuid)->firstOrFail();

        if (Gate::denies('setMainUser', $user)) {
            abort(403);
        }


        UserAggregate::retrieve($user->uuid)
            ->setMainUser()
            ->persist();
    }
}
