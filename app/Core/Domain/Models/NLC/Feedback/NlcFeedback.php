<?php

namespace App\Core\Domain\Models\NLC\Feedback;

use App\Core\Domain\Models\NLC\Team\NlcTeamId;

class NlcFeedback
{
    private NlcFeedbackId $id;
    private NlcTeamId $nlc_team_id;
    private string $nama_sekolah;
    private int $tingkat_kepuasan;
    private int $babak_soal;
    private int $babak_game;
    private bool $terdapat_kendala;
    private string $kesan;
    private string $kritik_saran;
    private string $nama_ketua;
    private ?string $nama_anggota_1;
    private ?string $nama_anggota_2;

    /**
     * @param NlcFeedbackId $id
     * @param NlcTeamId $nlc_team_id
     * @param string $nama_sekolah
     * @param int $tingkat_kepuasan
     * @param int $babak_soal
     * @param int $babak_game
     * @param bool $terdapat_kendala
     * @param string $kesan
     * @param string $kritik_saran
     * @param string $nama_ketua
     * @param string|null $nama_anggota_1
     * @param string|null $nama_anggota_2
     */
    public function __construct(NlcFeedbackId $id, NlcTeamId $nlc_team_id, string $nama_sekolah, int $tingkat_kepuasan, int $babak_soal, int $babak_game, bool $terdapat_kendala, string $kesan, string $kritik_saran, string $nama_ketua, ?string $nama_anggota_1, ?string $nama_anggota_2)
    {
        $this->id = $id;
        $this->nlc_team_id = $nlc_team_id;
        $this->nama_sekolah = $nama_sekolah;
        $this->tingkat_kepuasan = $tingkat_kepuasan;
        $this->babak_soal = $babak_soal;
        $this->babak_game = $babak_game;
        $this->terdapat_kendala = $terdapat_kendala;
        $this->kesan = $kesan;
        $this->kritik_saran = $kritik_saran;
        $this->nama_ketua = $nama_ketua;
        $this->nama_anggota_1 = $nama_anggota_1;
        $this->nama_anggota_2 = $nama_anggota_2;
    }

    /**
     * @return NlcFeedbackId
     */
    public function getId(): NlcFeedbackId
    {
        return $this->id;
    }

    /**
     * @return NlcTeamId
     */
    public function getNlcTeamId(): NlcTeamId
    {
        return $this->nlc_team_id;
    }

    /**
     * @return string
     */
    public function getNamaSekolah(): string
    {
        return $this->nama_sekolah;
    }

    /**
     * @return int
     */
    public function getTingkatKepuasan(): int
    {
        return $this->tingkat_kepuasan;
    }

    /**
     * @return int
     */
    public function getBabakSoal(): int
    {
        return $this->babak_soal;
    }

    /**
     * @return int
     */
    public function getBabakGame(): int
    {
        return $this->babak_game;
    }

    /**
     * @return bool
     */
    public function isTerdapatKendala(): bool
    {
        return $this->terdapat_kendala;
    }

    /**
     * @return string
     */
    public function getKesan(): string
    {
        return $this->kesan;
    }

    /**
     * @return string
     */
    public function getKritikSaran(): string
    {
        return $this->kritik_saran;
    }

    /**
     * @return string
     */
    public function getNamaKetua(): string
    {
        return $this->nama_ketua;
    }

    /**
     * @return string|null
     */
    public function getNamaAnggota1(): ?string
    {
        return $this->nama_anggota_1;
    }

    /**
     * @return string|null
     */
    public function getNamaAnggota2(): ?string
    {
        return $this->nama_anggota_2;
    }

}
