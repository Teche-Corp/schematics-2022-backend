<?php

namespace App\Core\Application\Service\OrderReevaTicket;

use Illuminate\Http\UploadedFile;

class OrderReevaTicketRequest
{
    private string $name;
    private string $no_telp;
    private string $email;
    private string $alamat;
    private string $nik;
    private int $jumlah_tiket;
    private ?string $kode_voucher;

    /**
     * @param string $name
     * @param string $no_telp
     * @param string $email
     * @param string $alamat
     * @param string $nik
     * @param ?string $kode_voucher
     */
    public function __construct(string $name, string $no_telp, string $email, string $alamat, string $nik, int $jumlah_tiket, ?string $kode_voucher)
    {
        $this->name = $name;
        $this->no_telp = $no_telp;
        $this->email = $email;
        $this->alamat = $alamat;
        $this->nik = $nik;
        $this->jumlah_tiket = $jumlah_tiket;
        $this->kode_voucher = $kode_voucher;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
    public function getEmail(): string
    {
        return $this->email;
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
    public function getNik(): string
    {
        return $this->nik;
    }

    /**
     * @return string
     */
    public function getJumlahTiket(): int
    {
        return $this->jumlah_tiket;
    }

    /**
     *
     * @return string|null
     */
    public function getKodeVoucher(): ?string 
    {
        return $this->kode_voucher;
    }
}
