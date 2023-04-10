<?php

namespace App\Core\Application\Service\AdminDetailPembayaran;

use JsonSerializable;

class AdminDetailPembayaranTicketResponse implements JsonSerializable
{
    private string $tipe_pembayaran;
    private string $nama_bank;
    private string $nama_pemesan;
    private string $nomor_telp_pemesan;
    private string $bukti_pembayaran;
    private float $biaya;

    /**
     * @param string $tipe_pembayaran
     * @param string $nama_bank
     * @param string $nama_pemesan
     * @param string $nomor_telp_pemesan
     * @param string $bukti_pembayaran
     * @param float $biaya
     */
    public function __construct(string $tipe_pembayaran, string $nama_bank, string $nama_pemesan, string $nomor_telp_pemesan, string $bukti_pembayaran, float $biaya)
    {
        $this->tipe_pembayaran = $tipe_pembayaran;
        $this->nama_bank = $nama_bank;
        $this->nama_pemesan = $nama_pemesan;
        $this->nomor_telp_pemesan = $nomor_telp_pemesan;
        $this->bukti_pembayaran = $bukti_pembayaran;
        $this->biaya = $biaya;
    }

    public function jsonSerialize(): array
    {
        return [
            "tipe_pembayaran" => $this->tipe_pembayaran,
            "nama_bank" => $this->nama_bank,
            "nama_ketua" => $this->nama_pemesan,
            "no_telp_ketua" => $this->nomor_telp_pemesan,
            "bukti_pembayaran" => $this->bukti_pembayaran,
            "biaya" => $this->biaya
        ];
    }
}
