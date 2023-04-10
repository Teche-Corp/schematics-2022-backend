<?php

namespace App\Core\Application\Service\Scoreboard;

use Database\Seeders\NlcWarmupScoreboardSeeder;
use App\Core\Application\Service\Scoreboard\ScoreboardResponse;

class WarmupService
{
    public function execute(): array
    {
        $datas = (new NlcWarmupScoreboardSeeder())->data;
        return collect($datas)->map(
            function ($obj) {
                return new ScoreboardResponse( 
                    $obj['username_tim'],
                    $obj['nama_tim'],
                    $obj['nama_sekolah'],
                    $obj['region'],
                    $obj['skor_soal'],
                    $obj['skor_game'],
                    $obj['skor_total'],
                    $obj['status']
                );
            }
        )->all();
    }
}