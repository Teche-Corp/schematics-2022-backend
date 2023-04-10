<?php

namespace App\Core\Application\Service\RegisterNpcMember;

use Illuminate\Http\UploadedFile;

class RegisterNpcMemberRequest
{
    private string $kode_referral;
    private string $nisn;
    private UploadedFile $surat;
    private string $no_telp;
    private string $no_wa;
    private ?string $id_line;
    private string $alamat;
    private string $info_sch;
    private string $discord_tag;

    /**
     * @param string $kode_referral
     * @param string $nisn
     * @param UploadedFile $surat
     * @param string $no_telp
     * @param string $no_wa
     * @param ?string $id_line
     * @param string $alamat
     * @param string $info_sch
     * @param string $discord_tag
     */
    public function __construct(string $kode_referral, string $nisn, UploadedFile $surat, string $no_telp, string $no_wa, ?string $id_line, string $alamat, string $info_sch, string $discord_tag)
    {
        $this->kode_referral = $kode_referral;
        $this->nisn = $nisn;
        $this->surat = $surat;
        $this->no_telp = $no_telp;
        $this->no_wa = $no_wa;
        $this->id_line = $id_line;
        $this->alamat = $alamat;
        $this->info_sch = $info_sch;
        $this->discord_tag = $discord_tag;
    }

    /**
     * @return string
     */
    public function getKodeReferral(): string
    {
        return $this->kode_referral;
    }

    /**
     * @return string
     */
    public function getNisn(): string
    {
        return $this->nisn;
    }

    /**
     * @return UploadedFile
     */
    public function getSurat(): UploadedFile
    {
        return $this->surat;
    }

    /**
     * @return string
     */
    public function getNoTelp(): string
    {
        return $this->no_telp;
    }

    /**
     * @return string
     */
    public function getNoWa(): string
    {
        return $this->no_wa;
    }

    /**
     * @return string
     */
    public function getIdLine(): ?string
    {
        return $this->id_line;
    }

    /**
     * @return string
     */
    public function getAlamat(): string
    {
        return $this->alamat;
    }

    /**
     * @return string
     */
    public function getInfoSch(): string
    {
        return $this->info_sch;
    }

    public function getDiscordTag(): string
    {
        return $this->discord_tag;
    }
}
