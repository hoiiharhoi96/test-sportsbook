<?php

declare(strict_types=1);

namespace App\Dto;

class Tournament
{
    /** @var Event[] */
    private(set) array $events = [];

    public string $id {
        get => sprintf('%s_%s', $this->country->code, $this->sport->code);
    }

    public function __construct(
        readonly public string $name,
        readonly public Country $country,
        readonly public Sport $sport,
    ) {
    }

    public function addEvent(Event $event): self
    {
        $this->events[] = $event;

        return $this;
    }
}
