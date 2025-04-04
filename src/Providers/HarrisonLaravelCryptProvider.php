<?php

namespace Harrison\LaravelCrypt\Providers;

use Illuminate\Support\ServiceProvider;

class HarrisonLaravelCryptProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // $private = RSA::createKey();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load the configuration file
        $this->mergeConfigFrom(__DIR__.'/../config/crypt.php', 'crypt');

        // 註冊 command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Harrison\LaravelCrypt\Command\CreateCryptKey::class,
                \Harrison\LaravelCrypt\Command\Encrypt::class,
                \Harrison\LaravelCrypt\Command\Decrypt::class,
            ]);
        }
    }
}