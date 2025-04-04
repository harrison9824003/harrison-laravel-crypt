<?php

namespace Harrison\LaravelCrypt\Command;

use Illuminate\Console\Command;

class CreateCryptKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'harrison:create-crypt-key {path? : The optional path where the crypt key will be created} {filename? : The optional filename for the crypt key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 取得路徑參數
        $path = storage_path($this->argument('path') ?? 'crypt/default');
        // 檔案名稱
        $filename = $this->argument('filename') ?? 'crypt';

        // 取得檔案路徑
        $filePath = $path . '/' . $filename . '.key';

        $this->info('The key will be created at: ' . $filePath);

        // 判斷路徑是否存在
        if (!is_dir($path)) {
            // 建立路徑
            if (!mkdir($path, 0755, true) && !is_dir($path)) {
                $this->error('The path does not exist and could not be created.');
                return;
            }
            $this->info('The path has been created successfully.');
        }
        
        // 判斷檔案是否存在
        if (file_exists($filePath)) {
            $this->error('The file already exists.');
            return;
        }

        // 產生金鑰
        $private = \phpseclib3\Crypt\RSA::createKey();
        $privateKey = $private->toString('PKCS8');
        $publicKey = $private->getPublicKey()->toString('PKCS8');
        // 儲存金鑰
        file_put_contents($filePath, $privateKey);
        // 儲存公鑰
        file_put_contents($path . '/' . $filename . '.pub', $publicKey);

        // 顯示訊息
        $this->info('The key has been created successfully.');
        $this->info('Private key: ' . $filePath);
        $this->info('Public key: ' . $path . '/' . $filename . '.pub');
        $this->info('Please remember to set the correct permissions for the key file.');
    }
}
