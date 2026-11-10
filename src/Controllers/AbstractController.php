<?php

namespace  App\Controllers;

use App\Requests\PaginationRequest;

abstract class AbstractController
{
    protected function jsonResponse(array $data = [], int $statusCode = 200): never
    {
        http_response_code($statusCode);

        echo json_encode($data);

        exit();
    }

    protected function jsonPaginationResponse(array $data, PaginationRequest $paginationRequest): never
    {
        $this->jsonResponse([
            'next_cursor' => $paginationRequest->getNextCursor(count($data)),
            'data' => $data,
        ]);
    }
}
