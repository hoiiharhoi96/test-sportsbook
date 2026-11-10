<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Event;
use App\Requests\PaginationRequest;

class EventFilter
{
    public array $values {
        get => [
            $this->sportId,
            $this->tournamentId,
            $this->eventId,
            $this->paginationRequest->limit,
            $this->paginationRequest->offset,
            $this->paginationRequest->snapshotId,
            $this->paginationRequest->snapshotDateTime->format(Event::DATE_TIME_FORMAT),
        ];
    }

    public function __construct(
        public PaginationRequest $paginationRequest,
        public ?int $sportId = null,
        public ?int $tournamentId = null,
        public ?int $eventId = null,
    ) {
    }
}
