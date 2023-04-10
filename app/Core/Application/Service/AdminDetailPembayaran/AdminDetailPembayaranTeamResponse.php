<?php

namespace App\Core\Application\Service\AdminDetailPembayaran;

use JsonSerializable;

class AdminDetailPembayaranTeamResponse implements JsonSerializable
{
    private string $tipe_pembayaran;
    private string $nama_bank;
    private string $nama_tim;
    private string $nama_ketua;
    private string $nomor_telp_ketua;
    private string $bukti_pembayaran;
    private int $biaya;

    /**
     * @param string $tipe_pembayaran
     * @param string $nama_bank
     * @param string $nama_tim
     * @param string $nama_ketua
     * @param string $nomor_telp_ketua
     * @param string $bukti_pembayaran
     * @param int $biaya
     */
    public function __construct(string $tipe_pembayaran, string $nama_bank, string $nama_tim, string $nama_ketua, string $nomor_telp_ketua, string $bukti_pembayaran, int $biaya)
    {
        $this->tipe_pembayaran = $tipe_pembayaran;
        $this->nama_bank = $nama_bank;
        $this->nama_tim = $nama_tim;
        $this->nama_ketua = $nama_ketua;
        $this->nomor_telp_ketua = $nomor_telp_ketua;
        $this->bukti_pembayaran = $bukti_pembayaran;
        $this->biaya = $biaya;
    }

    public function jsonSerialize(): array
    {
        return [
            "tipe_pembayaran" => $this->tipe_pembayaran,
            "nama_bank" => $this->nama_bank,
            "nama_tim" => $this->nama_tim,
            "nama_ketua" => $this->nama_ketua,
            "no_telp_ketua" => $this->nomor_telp_ketua,
            "bukti_pembayaran" => $this->bukti_pembayaran,
            "biaya" => $this->biaya
        ];
    }
}
