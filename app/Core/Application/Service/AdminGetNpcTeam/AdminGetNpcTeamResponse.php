<?php

namespace App\Core\Application\Service\AdminGetNpcTeam;

use JsonSerializable;

class AdminGetNpcTeamResponse implements JsonSerializable
{
    private string $team_id;
    private ?int $unique_payment_code;
    private string $kategori;
    private string $nama_team;
    private string $referral_code;
    private string $status;
    private string $asal_sekolah;
    private ?string $nama_guru_pendamping;
    private ?string $no_telp_guru_pendamping;
    private string $kota;
    private int $biaya;
    /** @var AdminNpcMemberResponse[] $members */
    private array $members;

    /**
     * @param string $team_id
     * @param int|null $unique_payment_code
     * @param string $kategori
     * @param string $nama_team
     * @param string $referral_code
     * @param string $status
     * @param string $asal_sekolah
     * @param string|null $nama_guru_pendamping
     * @param string|null $no_telp_guru_pendamping
     * @param string $kota
     * @param int $biaya
     * @param AdminNpcMemberResponse[] $members
     */
    public function __construct(string $team_id, ?int $unique_payment_code, string $kategori, string $nama_team, string $referral_code, string $status, string $asal_sekolah, ?string $nama_guru_pendamping, ?string $no_telp_guru_pendamping, string $kota, int $biaya, array $members)
    {
        $this->team_id = $team_id;
        $this->unique_payment_code = $unique_payment_code;
        $this->kategori = $kategori;
        $this->nama_team = $nama_team;
        $this->referral_code = $referral_code;
        $this->status = $status;
        $this->asal_sekolah = $asal_sekolah;
        $this->nama_guru_pendamping = $nama_guru_pendamping;
        $this->no_telp_guru_pendamping = $no_telp_guru_pendamping;
        $this->kota = $kota;
        $this->biaya = $biaya;
        $this->members = $members;
    }

    public function jsonSerialize(): array
    {
        return [
            "team_id" => $this->team_id,
            'unique_payment_code' => $this->unique_payment_code,
            'kategori' => $this->kategori,
            'nama_team' => $this->nama_team,
            'referral_code' => $this->referral_code,
            'status' => $this->status,
            'asal_sekolah' => $this->asal_sekolah,
            'nama_guru_pendamping' => $this->nama_guru_pendamping,
            'no_telp_guru_pendamping' => $this->no_telp_guru_pendamping,
            'kota' => $this->kota,
            'biaya' => $this->biaya,
            'members' => $this->members
        ];
    }
}
