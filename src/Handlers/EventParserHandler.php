<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Context\Parsing;
use App\Dto\Event;

class EventParserHandler extends AbstractParserHandler
{
    protected function parse(array $data, Parsing $context): self
    {
        $item = $data['data'] ?? [];
        if ([] === $item) {
            return $this;
        }

        $event = new Event(eventId: (int) $item['id'], name: $item['name'], startTime: $item['time']);
        $context->event = $event;
        $context->tournament->addEvent($event);

        return $this;
    }
}
