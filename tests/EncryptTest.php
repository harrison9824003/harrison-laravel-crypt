<?php

namespace Harrison\LaravelCrypt\Tests;

use Harrison\LaravelCrypt\Services\CryptService;
use Orchestra\Testbench\TestCase;

class EncryptTest extends TestCase
{
    private CryptService $cryptService;

    protected function setUp(): void
    {
        parent::setUp();

        // 初始化測試環境
        $this->cryptService = new CryptService();
    }

    /**
     * 配置測試環境
     */
    protected function getPackageProviders($app)
    {
        // 註冊你的包的服務提供者
        return [
            \Harrison\LaravelCrypt\Providers\HarrisonLaravelCryptProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // 配置測試環境，例如設置 storage 路徑
        $app->useStoragePath(base_path('storage'));
    }

    /**
     * 測試加密功能
     */
    public function testEncrypt()
    {
        $text = 'Hello, World!';

        // 當下路徑
        $path = __DIR__;
        // 公鑰路徑
        $publicKeyPath = $path . '/test.pub';
        // 私鑰路徑
        $privateKeyPath = $path . '/test.key';

        $encryptedText = $this->cryptService->encrypt($text, $publicKeyPath);

        $this->assertNotEmpty($encryptedText, '加密後的文本不應為空');
        $this->assertNotEquals($text, $encryptedText, '加密後的文本應與原文本不同');

        // 解密
        $decryptedText = $this->cryptService->decrypt($encryptedText, $privateKeyPath);
        $this->assertEquals($text, $decryptedText, '解密後的文本應與原文本相同');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}