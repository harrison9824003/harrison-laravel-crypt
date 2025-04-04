<?php

namespace Harrison\LaravelCrypt\Services;

use Harrison\LaravelCrypt\Exceptions\API\PrivateKeyNotFound;
use Harrison\LaravelCrypt\Exceptions\API\PublicKeyNotFound;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\RSA\PrivateKey;
use phpseclib3\Crypt\RSA\PublicKey;

class CryptService {

    /**
     * 讀取私鑰
     */
    private function readPrivateKey(string $path): string
    {
        return file_get_contents($path);
    }

    /**
     * 讀取公鑰
     */
    private function readPublicKey(string $path): string
    {
        return file_get_contents($path);
    }

    /**
     * 取得私鑰
     */
    public function getPrivateKey(string $path): PrivateKey
    {
        // return $this->readPrivateKey($path);
        return RSA::loadPrivateKey($this->readPrivateKey($path));
    }

    /**
     * 取得公鑰
     */
    public function getPublicKey(string $path): PublicKey
    {
        return RSA::loadPublicKey($this->readPublicKey($path));
    }

    /**
     * 加密
     */
    public function encrypt(string $data, string $publicKeyPath = ""): string
    {
        // 判斷路徑是否存在
        if (!file_exists($publicKeyPath)) {
            throw new PublicKeyNotFound();
        }
        /**
         * @var PublicKey $publicKey
         */
        if (empty($publicKeyPath)) {
            $publicKeyPath = config('crypt.public_key.path');
        }
        $publicKey = $this->getPublicKey($publicKeyPath);
        // 設定 ENCRYPTION_PKCS1
        $publicKey->withPadding(RSA::ENCRYPTION_PKCS1);
        return $publicKey->encrypt($data);
    }

    /**
     * 解密
     */
    public function decrypt(string $data, string $privateKeyPath = ""): string
    {
        // 判斷路徑是否存在
        if (!file_exists($privateKeyPath)) {
            throw new PrivateKeyNotFound();
        }
        /**
         * @var PrivateKey $privateKey
         */
        $privateKey = $this->getPrivateKey($privateKeyPath);
        if (empty($privateKeyPath)) {
            $privateKeyPath = config('crypt.private_key.path');
        }
        // 設定 ENCRYPTION_PKCS1
        $privateKey->withPadding(RSA::ENCRYPTION_PKCS1);
        return $privateKey->decrypt($data);
    }
}
