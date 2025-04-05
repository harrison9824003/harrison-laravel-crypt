<?php

namespace Harrison\LaravelCrypt\Command;

use Harrison\LaravelCrypt\Services\CryptService;
use Illuminate\Console\Command;
use phpseclib3\Crypt\RSA;

class Encrypt extends Command
{
    public function __construct(
        private CryptService $cryptService
    )
    {
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'harrison:encrypt 
                            {plaintext : The text to encrypt} 
                            {key=crypt/default/crypt.pub : The path to the public key e.g. storage/default/crypt.key}
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
        // plaintext
        $plaintext = $this->argument('plaintext');
        $this->info('Plaintext: ' . $plaintext);
        // public key
        $publicKeyPath = storage_path($this->argument('key'));
        $this->info('Public key: ' . $publicKeyPath);
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

        $this->info('Public key path: ' . $publicKeyPath);
        // check if the file exists
        if (!file_exists($publicKeyPath)) {
            $this->error('The public key file does not exist.');
            return;
        }
        // encrypt
        $encryptedText = base64_encode($this->cryptService->encrypt($plaintext, $publicKeyPath));
        // output
        $this->info('Encrypted text: ' . $encryptedText);
    }
}
