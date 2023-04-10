<?php

namespace App\Core\Application\Service\RegisterNpcTeam;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class RegisterNpcTeamRequest
{
    private string $kategori;
    private int $id_provinsi;
    private int $id_kota;
    private ?string $nama_guru_pendamping;
    private ?string $no_telp_guru_pendamping;
    private string $nama_team;
    private string $asal_sekolah;
    private string $nisn;
    private UploadedFile $surat;
    private string $no_telp;
    private string $no_wa;
    private ?string $id_line;
    private string $alamat;
    private string $info_sch;
    private string $discord_tag;
    private ?string $kode_voucher;

    /**
     * @param string $kategori
     * @param int $id_provinsi
     * @param int $id_kota
     * @param string $nama_guru_pendamping
     * @param string $no_telp_guru_pendamping
     * @param string $nama_team
     * @param string $asal_sekolah
     * @param string $nisn
     * @param UploadedFile $surat
     * @param string $no_telp
     * @param string $no_wa
     * @param ?string $id_line
     * @param string $alamat
     * @param string $info_sch
     * @param string $discord_tag
     * @param ?string $kode_voucher
     */
    public function __construct(string $kategori, int $id_provinsi, int $id_kota, ?string $nama_guru_pendamping, ?string $no_telp_guru_pendamping, string $nama_team, string $asal_sekolah, string $nisn, UploadedFile $surat, string $no_telp, string $no_wa, ?string $id_line, string $alamat, string $info_sch, string $discord_tag, ?string $kode_voucher)
    {
        $this->kategori = $kategori;
        $this->id_provinsi = $id_provinsi;
        $this->id_kota = $id_kota;
        $this->nama_guru_pendamping = $nama_guru_pendamping;
        $this->no_telp_guru_pendamping = $no_telp_guru_pendamping;
        $this->nama_team = $nama_team;
        $this->asal_sekolah = $asal_sekolah;
        $this->nisn = $nisn;
        $this->surat = $surat;
        $this->no_telp = $no_telp;
        $this->no_wa = $no_wa;
        $this->id_line = $id_line;
        $this->alamat = $alamat;
        $this->info_sch = $info_sch;
        $this->discord_tag = $discord_tag;
        $this->kode_voucher = $kode_voucher;
    }

    /**
     * @return string
     */
    public function getKategori(): string
    {
        return $this->kategori;
    }

    /**
     * @return int
     */
    public function getIdProvinsi(): int
    {
        return $this->id_provinsi;
    }

    /**
     * @return int
     */
    public function getIdKota(): int
    {
        return $this->id_kota;
    }

    /**
     * @return string
     */
    public function getNamaGuruPendamping(): ?string
    {
        return $this->nama_guru_pendamping;
    }

    /**
     * @return string
     */
    public function getNoTelpGuruPendamping(): ?string
    {
        return $this->no_telp_guru_pendamping;
    }

    /**
     * @return string
     */
    public function getNamaTeam(): string
    {
        return $this->nama_team;
    }

    /**
     * @return string
     */
    public function getAsalSekolah(): string
    {
        return $this->asal_sekolah;
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

    /**
     * @return string
     */
    public function getDiscordTag(): string
    {
        return $this->discord_tag;
    }

    /**
     * get kode voucher team
     *
     * @return string
     */
    public function getKodeVoucher(): ?string
    {
        return $this->kode_voucher;
    }
}
