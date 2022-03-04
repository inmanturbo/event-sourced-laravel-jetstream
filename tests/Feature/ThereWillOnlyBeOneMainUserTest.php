<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('there will only be one main user', function () {
    User::factory(3)->create([ 'is_main_user' => true ]);

    $user = User::factory()->create([ 'is_main_user' => false ]);

    actingAs($user)->put('/main-user', [
        'user_uuid' => $user->uuid,
    ]);

    $this->assertCount(1, User::whereIsMainUser(true)->get());
    $this->assertEquals($user->uuid, User::whereIsMainUser(true)->first()->uuid);
});
