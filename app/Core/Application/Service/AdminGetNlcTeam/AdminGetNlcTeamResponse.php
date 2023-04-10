<?php

namespace App\Core\Application\Service\AdminGetNlcTeam;

use JsonSerializable;

class AdminGetNlcTeamResponse implements JsonSerializable
{
    private string $team_id;
    private string $nama_team;
    private string $referral_code;
    private string $status;
    private string $asal_sekolah;
    private ?string $nama_guru_pendamping;
    private ?string $no_telp_guru_pendamping;
    private string $region;
    private string $kota;
    private int $biaya;
    /** @var AdminNlcMemberResponse[] $members */
    private array $members;

    /**
     * @param string $team_id
     * @param string $nama_team
     * @param string $referral_code
     * @param string $status
     * @param string $asal_sekolah
     * @param ?string $nama_guru_pendamping
     * @param ?string $no_telp_guru_pendamping
     * @param string $region
     * @param string $kota
     * @param int $biaya
     * @param AdminNlcMemberResponse[] $members
     */
    public function __construct(string $team_id, string $nama_team, string $referral_code, string $status, string $asal_sekolah, ?string $nama_guru_pendamping, ?string $no_telp_guru_pendamping, string $region, string $kota, int $biaya, array $members)
    {
        $this->team_id = $team_id;
        $this->nama_team = $nama_team;
        $this->referral_code = $referral_code;
        $this->status = $status;
        $this->asal_sekolah = $asal_sekolah;
        $this->nama_guru_pendamping = $nama_guru_pendamping;
        $this->no_telp_guru_pendamping = $no_telp_guru_pendamping;
        $this->region = $region;
        $this->kota = $kota;
        $this->biaya = $biaya;
        $this->members = $members;
    }


    public function jsonSerialize(): array
    {
        return [
            'team_id' => $this->team_id,
            'nama_team' => $this->nama_team,
            'referral_code' => $this->referral_code,
            'status' => $this->status,
            'asal_sekolah' => $this->asal_sekolah,
            'nama_guru_pendamping' => $this->nama_guru_pendamping,
            'no_telp_guru_pendamping' => $this->no_telp_guru_pendamping,
            'region' => $this->region,
            'kota' => $this->kota,
            'biaya' => $this->biaya,
            'members' => $this->members
        ];
    }
}
