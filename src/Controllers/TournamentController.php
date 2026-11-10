<?php

namespace App\Controllers;

use App\Repository\TournamentRepository;

class TournamentController extends AbstractController
{
    public function __construct(readonly protected TournamentRepository $repository)
    {
    }

    public function getEventRich(): never
    {
        $this->jsonResponse($this->repository->getEvetRich());
    }
}
