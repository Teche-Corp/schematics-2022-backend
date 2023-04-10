<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use function feof;
use function fgetcsv;
use function fopen;
use function storage_path;

class SeedNlcPenyisihanSertifikat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sertifikat:nlc-penyisihan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'nge seed data sertif nlc penyisihan ke db';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $chunks = LazyCollection::make(function () {
            $header = true;
            $file = fopen(storage_path('SertifPeserta_nlc_penyisihan.csv'), 'r');
            while (!feof($file)) {
                $data = fgetcsv($file);
                if ($header && !($header = false)) continue;
                yield ['user_id' => $data[1], 'nlc_penyisihan' => $data[6] === "" ? null : $data[6]];
            }
        })->chunk(1000);
        foreach ($chunks as $chunk) {
            DB::table('sertifikat')->insert($chunk->all());
        }
        return 0;
    }
}
