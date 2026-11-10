<?php

namespace App\Controllers;

use App\Repository\SportRepository;

class SportController extends AbstractController
{
    public function __construct(readonly protected SportRepository $repository)
    {
    }

    public function getEventCount(): never
    {
        $this->jsonResponse($this->repository->getEventCount());
    }
}
