<?php

namespace App\Core\Application\Service\GetNpcTeamUser;

use JsonSerializable;

class GetNpcTeamResponse implements JsonSerializable
{
    private string $team_id;
    private string $kategori;
    private string $nama_team;
    private string $referral_code;
    private string $status;
    private string $asal_sekolah;
    private ?string $nama_guru_pendamping;
    private ?string $no_telp_guru_pendamping;
    private string $kota;
    private int $biaya;
    /** @var NpcMemberResponse[] $members */
    private array $members;
    private ?string $username_lomba;
    private ?string $password_lomba;

    /**
     * @param string $team_id
     * @param string $kategori
     * @param string $nama_team
     * @param string $referral_code
     * @param string $status
     * @param string $asal_sekolah
     * @param ?string $nama_guru_pendamping
     * @param ?string $no_telp_guru_pendamping
     * @param string $kota
     * @param int $biaya
     * @param NpcMemberResponse[] $members
     * @param ?string $username_lomba 
     * @param ?string $password_lomba
     */
    public function __construct(string $team_id, string $kategori, string $nama_team, string $referral_code, string $status, string $asal_sekolah, ?string $nama_guru_pendamping, ?string $no_telp_guru_pendamping, string $kota, int $biaya, array $members, ?string $username_lomba, ?string $password_lomba)
    {
        $this->team_id = $team_id;
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
        $this->username_lomba = $username_lomba;
        $this->password_lomba = $password_lomba;
    }

    public function jsonSerialize(): array
    {
        return [
            "team_id" => $this->team_id,
            'kategori' => $this->kategori,
            'nama_team' => $this->nama_team,
            'referral_code' => $this->referral_code,
            'status' => $this->status,
            'asal_sekolah' => $this->asal_sekolah,
            'nama_guru_pendamping' => $this->nama_guru_pendamping,
            'no_telp_guru_pendamping' => $this->no_telp_guru_pendamping,
            'kota' => $this->kota,
            'biaya' => $this->biaya,
            'members' => $this->members,
            "username_lomba" => $this->username_lomba,
            "password_lomba" => $this->password_lomba
        ];
    }
}
