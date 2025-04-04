<?php

namespace Harrison\LaravelCrypt\Responses;

use Illuminate\Http\JsonResponse;

class ErrorResponse extends JsonResponse
{
    public function __construct(
        private string $errorCode,
        private string $errorMessage,
        private array $errorDetail,
        private int $status = 400
    ) {
        parent::__construct([
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage,
            'errorDetail' => $errorDetail,
        ], $status);
    }
}
