<?php

namespace Harrison\LaravelCrypt\Command;

use Harrison\LaravelCrypt\Services\CryptService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;

class decrypt extends Command
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
    protected $signature = 'harrison:decrypt {encryptedText : The text to decrypt} {key=crypt/default/crypt.key : The path to the private key}';

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
        // encrypted text
        $encryptedText = $this->argument('encryptedText');
        // private key
        $privateKeyPath = storage_path($this->argument('key'));
        // check if the file exists
        if (!file_exists($privateKeyPath)) {
            $this->error('The private key file does not exist.');
            return;
        }
        // decrypt
        $decryptedText = $this->cryptService->decrypt(base64_decode($encryptedText), $privateKeyPath);
        // output
        $this->info('Decrypted text: ' . $decryptedText);
    }
}
