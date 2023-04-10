<?php

namespace App\Core\Application\Service\OrderNstTicket;

use Illuminate\Http\UploadedFile;

class OrderNstTicketRequest
{
    private string $name;
    private string $no_telp;
    private string $email;
    private ?string $alamat;
    private int $jumlah_tiket;
    private ?string $tipe_vaksin;
    private UploadedFile $bukti_vaksin;

    /**
     * @param string $name
     * @param string $no_telp
     * @param string $email
     * @param string $jumlah_tiket
     * @param string $tipe_vaksin
     * @param UploadedFile $bukti_vaksin
     */
    public function __construct(string $name, string $no_telp, string $email, ?string $alamat, int $jumlah_tiket, ?string $tipe_vaksin, UploadedFile $bukti_vaksin)
    {
        $this->name = $name;
        $this->no_telp = $no_telp;
        $this->email = $email;
        $this->alamat = $alamat;
        $this->jumlah_tiket = $jumlah_tiket;
        $this->tipe_vaksin = $tipe_vaksin;
        $this->bukti_vaksin = $bukti_vaksin;
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
    public function getAlamat(): ?string
    {
        return $this->alamat;
    }

    /**
     * @return int
     */
    public function getJumlahTiket(): int
    {
        return $this->jumlah_tiket;
    }

    /**
     * @return string
     */
    public function getTipeVaksin(): ?string
    {
        return $this->tipe_vaksin;
    }

    /**
     * @return UploadedFile
     */
    public function getBuktiVaksin(): UploadedFile
    {
        return $this->bukti_vaksin;
    }
}
