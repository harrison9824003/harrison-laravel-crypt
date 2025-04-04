<?php

namespace Harrison\LaravelCrypt\Exceptions\API;

use JsonException;

class PrivateKeyNotFound extends ApiException
{
    private const ERROR_CODE = 'private_key_not_found';
    private const ERROR_MESSAGE = 'The private key file does not exist.';
    private const ERROR_DETAIL = [];

    public function __construct()
    {
        parent::__construct(
            self::ERROR_CODE,
            self::ERROR_MESSAGE,
            self::ERROR_DETAIL
        );
    }
}
