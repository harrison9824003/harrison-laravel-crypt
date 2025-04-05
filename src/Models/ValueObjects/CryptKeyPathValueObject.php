<?php

namespace Harrison\LaravelCrypt\Models\ValueObjects;

class CryptKeyPathValueObject
{
    public function __construct(
        private string $privateKeyPath,
        private string $publicKeyPath,
    ) {
    }

    public function getPublicKeyPath(): string
    {
        return $this->publicKeyPath;
    }

    public function getPrivateKeyPath(): string
    {
        return $this->privateKeyPath;
    }
}
