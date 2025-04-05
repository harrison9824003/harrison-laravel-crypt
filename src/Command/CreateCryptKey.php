<?php

namespace Harrison\LaravelCrypt\Command;

use Harrison\LaravelCrypt\Models\ValueObjects\CryptKeyPathValueObject;
use Harrison\LaravelCrypt\Services\CryptService;
use Illuminate\Console\Command;

class CreateCryptKey extends Command
{
    public function __construct(
        private CryptService $cryptService
    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'harrison:create-crypt-key 
                            {--path= : The optional path where the crypt key will be created} 
                            {--filename= : The optional filename for the crypt key}
                            {--password= : The optional password for the crypt key}';

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
        $path = storage_path($this->option('path') ?? 'crypt/default');
        // 檔案名稱
        $filename = $this->option('filename') ?? 'crypt';
        // 密碼
        $password = $this->option('password') ?? '';

        try {
            // 產生金鑰
            /**
             * @var CryptKeyPathValueObject $cryptKeyPath
             */
            $cryptKeyPath = $this->cryptService->createCryptKey($path, $filename, $password);
        } catch (\Exception $e) {
            // 顯示錯誤訊息
            $this->error('Error: ' . $e->getMessage());
            return;
        }

        $this->info('The path has been created successfully.');
        $this->info('The key will be created at: ' . $cryptKeyPath->getPrivateKeyPath());
        $this->info('The public key will be created at: ' . $cryptKeyPath->getPublicKeyPath());
        $this->info('Please remember to set the correct permissions for the key file.');
    }
}
