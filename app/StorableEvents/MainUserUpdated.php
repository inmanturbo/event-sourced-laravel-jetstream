<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class MainUserUpdated extends ShouldBeStored
{
    public function __construct(
        public string $userUuid,
    ) {
    }
}
