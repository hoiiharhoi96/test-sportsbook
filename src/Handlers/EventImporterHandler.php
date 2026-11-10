<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Context\Importing;
use App\Entity\Event;
use App\Repository\EventRepository;
use DateTime;
use DateMalformedStringException;
use DateInvalidTimeZoneException;
use DateTimeZone;

class EventImporterHandler extends AbstractImporterHandler
{
    public function __construct(readonly protected EventRepository $eventRepository)
    {
    }


    /**
     * @throws DateMalformedStringException
     * @throws DateInvalidTimeZoneException
     */
    protected function import(Importing $context): self
    {
        if ([] === $context->tournamentEvents) {
            return $this;
        }

        $data = [];
        $allEvents = [];
        foreach ($context->tournamentEvents as $tournamentId => $events) {
            foreach ($events as $event) {
                $data[$event->eventId] = [
                    $event->eventId,
                    $tournamentId,
                    $event->name,
                    new DateTime($event->startTime, new DateTimeZone($_ENV['TIME_ZONE']))->format(Event::DATE_TIME_FORMAT),
                ];

                $allEvents[] = $event;
            }
        }

        $eventKeys = [];
        foreach (array_chunk($data, 500, true) as $events) {
            $this->eventRepository->insertList(array_values($events));
            $eventKeys += $this->eventRepository->getIds(array_keys($events));
        }

        foreach ($allEvents as $event) {
            $context->addEventScopes($eventKeys[$event->eventId], $event->scopes);
        }

        return $this;
    }
}
