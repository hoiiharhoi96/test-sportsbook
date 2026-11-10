<?php

declare(strict_types=1);

namespace App\Requests;

use DateTime;

class PaginationRequest
{
    public DateTime $snapshotDateTime;
    public int $offset;
    public int $limit;

    public int $snapshotId  {
        set => $value;
    }

    readonly private int $page;

    public function __construct()
    {
        $data = explode('_', $_GET['next_cursor'] ?? '');

        $this->page = (int) ($data[0] ?? 1) ?: 1;
        $this->limit = (int) ($_ENV['PAGINATION_LIMIT'] ?? 10);
        $this->offset = ($this->page - 1) * $this->limit;
        $this->snapshotId = (int) ($data[1] ?? 0);

        $dataTime = DateTime::createFromTimestamp((int) ($data[2] ?? 0));
        $this->snapshotDateTime = $dataTime->getTimestamp() !== 0 ? $dataTime : new DateTime();
    }

    public function getNextCursor(int $count): ?string
    {
        if ($count < $this->limit) {
            return null;
        }

        return sprintf(
            '%d_%d_%d',
            $this->page + 1,
            $this->snapshotId,
            $this->snapshotDateTime->getTimestamp()
        );
    }
}
