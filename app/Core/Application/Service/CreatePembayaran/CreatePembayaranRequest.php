<?php

namespace App\Core\Application\Service\CreatePembayaran;

use App\Core\Domain\Models\Pembayaran\TipePembayaran;
use Illuminate\Http\UploadedFile;

class CreatePembayaranRequest
{
    private UploadedFile $bukti_bayar;
    private ?string $nama_rekening;
    private ?string $no_rekening;
    private string $nama_bank;
    private TipePembayaran $tipe_pembayaran;

    /**
     * @param UploadedFile $bukti_bayar
     * @param ?string $nama_rekening
     * @param ?string $no_rekening
     * @param string $nama_bank
     * @param TipePembayaran $tipe_pembayaran
     */
    public function __construct(UploadedFile $bukti_bayar, ?string $nama_rekening, ?string $no_rekening, string $nama_bank, TipePembayaran $tipe_pembayaran)
    {
        $this->bukti_bayar = $bukti_bayar;
        $this->nama_rekening = $nama_rekening;
        $this->no_rekening = $no_rekening;
        $this->nama_bank = $nama_bank;
        $this->tipe_pembayaran = $tipe_pembayaran;
    }

    /**
     * @return UploadedFile
     */
    public function getBuktiBayar(): UploadedFile
    {
        return $this->bukti_bayar;
    }

    /**
     * @return string
     */
    public function getNamaRekening(): ?string
    {
        return $this->nama_rekening;
    }

    /**
     * @return string
     */
    public function getNoRekening(): ?string
    {
        return $this->no_rekening;
    }

    /**
     * @return string
     */
    public function getNamaBank(): string
    {
        return $this->nama_bank;
    }

    /**
     * @return TipePembayaran
     */
    public function getTipePembayaran(): TipePembayaran
    {
        return $this->tipe_pembayaran;
    }
}
