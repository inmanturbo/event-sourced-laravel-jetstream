<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TeamUpdated extends ShouldBeStored
{
    public function __construct(
        public string $teamUuid,
        public string $name
    ) {
    }
}
