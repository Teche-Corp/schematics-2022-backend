<?php

namespace App\Core\Application\Service\GetNpcTeamUser;

use JsonSerializable;

class NpcMemberResponse implements JsonSerializable
{
    private string $member_id;
    private string $name;
    private string $member_type;
    private string $nisn;
    private string $surat_url;
    private string $no_telp;
    private string $no_wa;
    private ?string $id_line;
    private string $alamat;
    private string $discord_tag;

    /**
     * @param string $member_id
     * @param string $name
     * @param string $member_type
     * @param string $nisn
     * @param string $surat_url
     * @param string $no_telp
     * @param string $no_wa
     * @param ?string $id_line
     * @param string $alamat
     * @param string $discord_tag
     */
    public function __construct(string $member_id, string $name, string $member_type, string $nisn, string $surat_url, string $no_telp, string $no_wa, ?string $id_line, string $alamat, string $discord_tag)
    {
        $this->member_id = $member_id;
        $this->name = $name;
        $this->member_type = $member_type;
        $this->nisn = $nisn;
        $this->surat_url = $surat_url;
        $this->no_telp = $no_telp;
        $this->no_wa = $no_wa;
        $this->id_line = $id_line;
        $this->alamat = $alamat;
        $this->discord_tag = $discord_tag;
    }

    public function jsonSerialize(): array
    {
        return [
            "member_id" => $this->member_id,
            "name" => $this->name,
            "member_type" => $this->member_type,
            "nisn" => $this->nisn,
            "surat_url" => $this->surat_url,
            "no_telp" => $this->no_telp,
            "no_wa" => $this->no_wa,
            "id_line" => $this->id_line,
            "alamat" => $this->alamat,
            "discord_tag" => $this->discord_tag
        ];
    }
}
