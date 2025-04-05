<?php

namespace Harrison\LaravelCrypt\Command;

use Harrison\LaravelCrypt\Services\CryptService;
use Illuminate\Console\Command;
use phpseclib3\Crypt\RSA;

class Decrypt extends Command
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
    protected $signature = 'harrison:decrypt 
                            {encryptedText : The text to decrypt} 
                            {key=crypt/default/crypt.key : The path to the private key}
                            {--encryption= : The encryption algorithm to use, default is pkcs1, also can be oaep or none, please refer to phpseclib3 documentation}';

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

        // encryption algorithm
        $encryption = $this->option('encryption') ?? 'pkcs1';
        $this->info('Encryption algorithm: ' . $encryption);

        switch($encryption) {
            case 'oaep':
                $this->info('Encryption algorithm: oaep');
                $encryption = RSA::ENCRYPTION_OAEP;
                break;
            case 'pkcs1':
                $this->info('Encryption algorithm: pkcs1');
                $encryption = RSA::ENCRYPTION_PKCS1;
                break;
            case 'none':
                $this->info('Encryption algorithm: none');
                $encryption = RSA::ENCRYPTION_NONE;
                break;
            default:
                $this->error('Invalid encryption algorithm. Please use oaep, pkcs1 or none.');
                return;
        }

        // check if the file exists
        if (!file_exists($privateKeyPath)) {
            $this->error('The private key file does not exist.');
            return;
        }
        // decrypt
        $decryptedText = $this->cryptService->decrypt(base64_decode($encryptedText), $privateKeyPath, $encryption);
        // output
        $this->info('Decrypted text: ' . $decryptedText);
    }
}
