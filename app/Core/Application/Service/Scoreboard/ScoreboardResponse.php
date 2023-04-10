<?php


namespace App\Core\Application\Service\Scoreboard;


use JsonSerializable;

class ScoreboardResponse implements JsonSerializable
{
    private string $username_team;
    private string $nama_team;
    private string $nama_sekolah;
    private string $region;
    private ?float $skor_soal;
    private ?float $skor_game;
    private ?float $skor_total;
    private string $status;

    /**
     * ScoreboardResponse constructor.
     * @param string $username_team
     * @param string $nama_team
     * @param string $nama_sekolah
     * @param string $region
     * @param float|null $skor_soal
     * @param float|null $skor_game
     * @param float|null $skor_total
     * @param string $status
     */
    public function __construct(string $username_team, string $nama_team, string $nama_sekolah, string $region, ?float $skor_soal, ?float $skor_game, ?float $skor_total, string $status)
    {
        $this->username_team = $username_team;
        $this->nama_team = $nama_team;
        $this->nama_sekolah = $nama_sekolah;
        $this->region = $region;
        $this->skor_soal = $skor_soal;
        $this->skor_game = $skor_game;
        $this->skor_total = $skor_total;
        $this->status = $status;
    }


    public function jsonSerialize(): array
    {
        return [
            "username_team" => $this->username_team,
            "nama_team" => $this->nama_team,
            "nama_sekolah" => $this->nama_sekolah,
            "region" => $this->region,
            "skor_soal" => $this->skor_soal,
            "skor_game" => $this->skor_game,
            "skor_total" => $this->skor_total,
            "status" => $this->status
        ];
    }
}
