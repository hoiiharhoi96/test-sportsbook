<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\EventFilter;
use App\Repository\EventRepository;

readonly class EventService
{
    public function __construct(public EventRepository $eventRepository)
    {
    }

    public function getData(EventFilter $filter): array
    {
        if (0 === $filter->paginationRequest->snapshotId) {
            $filter->paginationRequest->snapshotId = $this->eventRepository->getIdMax();
        }

        return $this->eventRepository->getByFilter($filter);
    }
}
