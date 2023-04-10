<?php

namespace App\Core\Application\Service\Scoreboard;

use Illuminate\Support\Facades\Storage;

class CsvToArray
{
    /** jalanin pake tinker */
    public function execute()
    {
        $file = 'result_penyisihan';
        // kalo gaada bikin dulu foler "ScoreboardNlc" di public
        $path = storage_path("app/public/ScoreboardNlc/{$file}.csv");
        $handle = fopen($path, "r");

        $arr = [];
        $nasional_inc = 1;
        $region_inc = 1;
        while ($line = fgetcsv($handle, 1000, ",")) {

            $status = 1;
            if ($line[8] == "Gagal Lolos") $status = 0;
            else if ($line[8] == "Tidak Ikut") $status = -1;

            $bruh = new ScoreboardResponse(
                $line[5] == "Nasional" ? $nasional_inc++ : $region_inc++,
                $line[0],
                $line[1],
                $line[2],
                $line[3],
                $line[4] == '' ? null : $line[4],
                $line[8],
                $status 
            );
            $arr[] = $bruh->jsonSerialize();
        }
        try {
            Storage::put("public/ScoreboardNlc/{$file}.txt", var_export($arr, true));
        } catch (\Throwable$exception) {
            throw $exception;
        }
    }
}
