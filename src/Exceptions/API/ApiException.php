<?php

namespace Harrison\LaravelCrypt\Exceptions\API;

use Harrison\LaravelCrypt\Responses\ErrorResponse;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ApiException extends HttpException implements Responsable
{
    private const STATUS_CODE = 200;

    public function __construct(
        private string $errorCode,
        private string $errorMessage,
        private array $errorDetail = [],
        private ?Throwable $throwable = null
    ) {
        parent::__construct(self::STATUS_CODE, $errorMessage, $throwable);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getErrorDetail(): array
    {
        return $this->errorDetail;
    }

    public function toResponse($request)
    {
        return new ErrorResponse(
            $this->errorCode, 
            $this->errorMessage, 
            $this->errorDetail
        );
    }
}
