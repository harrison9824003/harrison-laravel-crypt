<?php

return [
    "private_key" => [
        "path" => env("CRYPT_PRIVATE_KEY", storage_path("crypt/default/crypt.key")),
    ],
    "public_key" => [
        "path" => env("CRYPT_PUBLIC_KEY", storage_path("crypt/default/crypt.pub")),
    ],
];
