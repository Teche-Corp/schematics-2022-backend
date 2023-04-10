<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use function base64_encode;
use function env;
use function openssl_random_pseudo_bytes;

class GenerateJwtKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:jwt-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate jwt key on config';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $byte = openssl_random_pseudo_bytes(32);
        $key = base64_encode($byte);
        $this->putPermanentEnv('JWT_KEY', $key);
        return 1;
    }

    public function putPermanentEnv($key, $value)
    {
        $path = app()->environmentFilePath();

        $escaped = preg_quote('='.env($key), '/');

        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));
    }
}
