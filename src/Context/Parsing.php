<?php

declare(strict_types=1);

namespace App\Context;

use App\Dto\Country;
use App\Dto\Event;
use App\Dto\Sport;
use App\Dto\Structure;
use App\Dto\Tournament;

class Parsing
{
    public Sport $sport {
        set => $value;
    }

    public Country $country {
        set => $value;
    }

    public Tournament $tournament {
        set => $value;
    }

    public Event $event {
        set => $value;
    }

    public function __construct(readonly public Structure $structure)
    {
    }
}
