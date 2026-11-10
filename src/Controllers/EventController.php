<?php

namespace App\Controllers;

use App\Dto\EventFilter;
use App\Requests\PaginationRequest;
use App\Services\EventService;

class EventController extends AbstractController
{
    public function __construct(readonly EventService $eventService)
    {
    }

    public function getAll(PaginationRequest $paginationRequest): never
    {
        $this->jsonPaginationResponse(
            $this->eventService->getData(new EventFilter($paginationRequest)),
            $paginationRequest
        );
    }

    public function getBySport(int $id, PaginationRequest $paginationRequest): never
    {
        $this->jsonPaginationResponse(
            $this->eventService->getData(new EventFilter($paginationRequest, sportId: $id)),
            $paginationRequest
        );
    }

    public function getByTournament(int $id, PaginationRequest $paginationRequest): never
    {
        $this->jsonPaginationResponse(
            $this->eventService->getData(new EventFilter($paginationRequest, tournamentId: $id)),
            $paginationRequest
        );
    }

    public function getById(int $id, PaginationRequest $paginationRequest): never
    {
        $this->jsonPaginationResponse(
            $this->eventService->getData(new EventFilter($paginationRequest, eventId: $id)),
            $paginationRequest
        );
    }
}
