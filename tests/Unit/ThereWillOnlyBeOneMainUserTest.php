<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('there will only be one main user', function () {
    User::factory(3)->create([ 'is_main_user' => true ]);

    $this->assertCount(1, User::whereIsMainUser(true)->get());
});
