<?php

namespace App\Core\Application\Service\RegisterNlcMember;

use Illuminate\Http\UploadedFile;

class RegisterNlcMemberRequest
{
    private string $kode_referral;
    private string $nisn;
    private UploadedFile $surat;
    private string $no_telp;
    private string $no_wa;
    private string $id_line;
    private string $alamat;
    private string $info_sch;
    private ?string $jenis_vaksin;
    private ?UploadedFile $bukti_twibbon;
    private ?UploadedFile $bukti_poster;
    private ?UploadedFile $bukti_vaksin;

    /**
     * @param string $kode_referral
     * @param string $nisn
     * @param UploadedFile $surat
     * @param string $no_telp
     * @param string $no_wa
     * @param string $id_line
     * @param string $alamat
     * @param string $info_sch
     * @param ?string $jenis_vaksin
     * @param ?UploadedFile $bukti_twibbon
     * @param ?UploadedFile $bukti_poster
     * @param ?UploadedFile $bukti_vaksin
     */
    public function __construct(string $kode_referral, string $nisn, UploadedFile $surat, string $no_telp, string $no_wa, string $id_line, string $alamat, string $info_sch, ?string $jenis_vaksin, ?UploadedFile $bukti_twibbon, ?UploadedFile $bukti_poster, ?UploadedFile $bukti_vaksin)
    {
        $this->kode_referral = $kode_referral;
        $this->nisn = $nisn;
        $this->surat = $surat;
        $this->no_telp = $no_telp;
        $this->no_wa = $no_wa;
        $this->id_line = $id_line;
        $this->alamat = $alamat;
        $this->info_sch = $info_sch;
        $this->jenis_vaksin = $jenis_vaksin;
        $this->bukti_twibbon = $bukti_twibbon;
        $this->bukti_poster = $bukti_poster;
        $this->bukti_vaksin = $bukti_vaksin;
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
    public function getIdLine(): string
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
     * @return UploadedFile
     */
    public function getBuktiTwibbon(): UploadedFile
    {
        return $this->bukti_twibbon;
    }

    /**
     * @return UploadedFile
     */
    public function getBuktiPoster(): UploadedFile
    {
        return $this->bukti_poster;
    }

    /**
     * @return UploadedFile
     */
    public function getBuktiVaksin(): UploadedFile
    {
        return $this->bukti_vaksin;
    }

    /**
     * @return string
     */
    public function getInfoSch(): string
    {
        return $this->info_sch;
    }

    public function getJenisVaksin(): ?string
    {
        return $this->jenis_vaksin;
    }
}
