<?php

namespace App\Core\Application\Service\AdminGetNlcTeam;

use JsonSerializable;

class AdminNlcMemberResponse implements JsonSerializable
{
    private string $member_id;
    private string $status;
    private string $name;
    private string $member_type;
    private string $nisn;
    private string $surat_url;
    private ?string $bukti_twibbon_url;
    private ?string $bukti_poster_url;
    private ?string $bukti_vaksin_url;
    private string $no_telp;
    private string $no_wa;
    private string $id_line;
    private string $alamat;

    /**
     * @param string $member_id
     * @param string $status
     * @param string $name
     * @param string $member_type
     * @param string $nisn
     * @param string $surat_url
     * @param string|null $bukti_twibbon_url
     * @param string|null $bukti_poster_url
     * @param string|null $bukti_vaksin_url
     * @param string $no_telp
     * @param string $no_wa
     * @param string $id_line
     * @param string $alamat
     */
    public function __construct(string $member_id, string $status, string $name, string $member_type, string $nisn, string $surat_url, ?string $bukti_twibbon_url, ?string $bukti_poster_url, ?string $bukti_vaksin_url, string $no_telp, string $no_wa, string $id_line, string $alamat)
    {
        $this->member_id = $member_id;
        $this->status = $status;
        $this->name = $name;
        $this->member_type = $member_type;
        $this->nisn = $nisn;
        $this->surat_url = $surat_url;
        $this->bukti_twibbon_url = $bukti_twibbon_url;
        $this->bukti_poster_url = $bukti_poster_url;
        $this->bukti_vaksin_url = $bukti_vaksin_url;
        $this->no_telp = $no_telp;
        $this->no_wa = $no_wa;
        $this->id_line = $id_line;
        $this->alamat = $alamat;
    }


    public function jsonSerialize(): array
    {
        return [
            "member_id" => $this->member_id,
            "status" => $this->status,
            "name" => $this->name,
            "member_type" => $this->member_type,
            "nisn" => $this->nisn,
            "surat_url" => $this->surat_url,
            "bukti_twibbon_url" => $this->bukti_twibbon_url,
            "bukti_poster_url" => $this->bukti_poster_url,
            "bukti_vaksin_url" => $this->bukti_vaksin_url,
            "no_telp" => $this->no_telp,
            "no_wa" => $this->no_wa,
            "id_line" => $this->id_line,
            "alamat" => $this->alamat
        ];
    }
}
