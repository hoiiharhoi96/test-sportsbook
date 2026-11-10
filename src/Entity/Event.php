<?php

declare(strict_types=1);

namespace App\Entity;

class Event
{
    public const string DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    public const string ID_FIELD_NAME = 'id';
    public const string EXTERNAL_FIELD_NAME = 'external_id';
    public const string TOURNAMENT_ID_FIELD_NAME = 'tournament_id';
    public const string NAME_FIELD_NAME = 'name';
    public const string START_TIME_FIELD_NAME = 'start_time';
}
