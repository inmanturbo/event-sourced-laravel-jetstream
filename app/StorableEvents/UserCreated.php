<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserCreated extends ShouldBeStored
{
    public function __construct(
        public string $userUuid,
        public string $name,
        public string $email,
        public string $password,
    ) {
    }
}
