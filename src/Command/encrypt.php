<?php

namespace Harrison\LaravelCrypt\Command;

use Harrison\LaravelCrypt\Services\CryptService;
use Illuminate\Console\Command;

class encrypt extends Command
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
    protected $signature = 'harrison:encrypt {plaintext : The text to encrypt} {key=crypt/default/crypt.pub : The path to the public key e.g. storage/default/crypt.key}';

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
