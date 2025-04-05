<?php

namespace Harrison\LaravelCrypt\Services;

use Harrison\LaravelCrypt\Exceptions\API\PrivateKeyNotFound;
use Harrison\LaravelCrypt\Exceptions\API\PublicKeyNotFound;
use Harrison\LaravelCrypt\Models\ValueObjects\CryptKeyPathValueObject;
use Illuminate\Support\Facades\Crypt;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\RSA\PrivateKey;
use phpseclib3\Crypt\RSA\PublicKey;

class CryptService
{

    /**
     * 建立金鑰
     */
    public function createCryptKey(string $path, string $filename, string $password = ''): CryptKeyPathValueObject
    {
        // 取得檔案路徑
        $privateFilePath = $path . '/' . $filename . '.key';

        // 顯示訊息
        $pubFilePath = $path . '/' . $filename . '.pub';

        // 判斷路徑是否存在
        if (!is_dir($path)) {
            // 建立路徑
            if (!mkdir($path, 0755, true) && !is_dir($path)) {
                throw new \Exception('The path does not exist and could not be created.');
            }
            // $this->info('The path has been created successfully.');
        }

        // 判斷檔案是否存在
        if (file_exists($privateFilePath)) {
            throw new \Exception('The file already exists.');
        }

        // 產生金鑰
        if ($password) {
            $private = RSA::createKey()->withPassword($password);
        } else {
            $private = RSA::createKey();
        }
        $privateKey = $private->toString('PKCS8');
        $publicKey = $private->getPublicKey()->toString('PKCS8');
        // 儲存金鑰
        file_put_contents($privateFilePath, $privateKey);
        // 儲存公鑰
        file_put_contents($pubFilePath, $publicKey);

        return new CryptKeyPathValueObject($privateFilePath, $pubFilePath);
    }

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
     * @param string $data 要加密的資料
     * @param string $publicKeyPath 公鑰路徑
     * @param int $encryption 加密方式
     */
    public function encrypt(
        string $data,
        string $publicKeyPath = "",
        int $encryption = RSA::ENCRYPTION_PKCS1
    ): string {
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
        $publicKey->withPadding($encryption);
        return $publicKey->encrypt($data);
    }

    /**
     * 解密
     * @param string $data 要解密的資料
     * @param string $privateKeyPath 私鑰路徑
     * @param int $encryption 解密方式
     */
    public function decrypt(
        string $data,
        string $privateKeyPath = "",
        int $encryption = RSA::ENCRYPTION_PKCS1
    ): string {
        // 判斷路徑是否存在
        if (!file_exists($privateKeyPath)) {
            throw new PrivateKeyNotFound();
        }
        /**
         * @var PrivateKey $privateKey
         */
        $privateKey = $this->getPrivateKey($privateKeyPath);

        // 判斷路徑是否存在
        // 預設路徑 config('crypt.private_key.path')
        if (empty($privateKeyPath)) {
            $privateKeyPath = config('crypt.private_key.path');
        }
        // 設定 ENCRYPTION_PKCS1
        $privateKey->withPadding($encryption);
        return $privateKey->decrypt($data);
    }
}
