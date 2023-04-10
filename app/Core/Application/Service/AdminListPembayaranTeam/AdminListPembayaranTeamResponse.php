<?php

namespace App\Core\Application\Service\AdminListPembayaranTeam;

use JsonSerializable;

class AdminListPembayaranTeamResponse implements JsonSerializable
{
    private ?string $pembayaran_id;
    private string $nama_ketua;
    private string $nama_bank;
    private string $status_pembayaran;
    private string $nama_tim;

    /**
     * @param string|null $pembayaran_id
     * @param string $nama_ketua
     * @param string $nama_bank
     * @param string $status_pembayaran
     * @param string $nama_tim
     */
    public function __construct(?string $pembayaran_id, string $nama_ketua, string $nama_bank, string $status_pembayaran, string $nama_tim)
    {
        $this->pembayaran_id = $pembayaran_id;
        $this->nama_ketua = $nama_ketua;
        $this->nama_bank = $nama_bank;
        $this->status_pembayaran = $status_pembayaran;
        $this->nama_tim = $nama_tim;
    }

    public function jsonSerialize(): array
    {
        return [
            "pembayaran_id" => $this->pembayaran_id,
            "nama_ketua" => $this->nama_ketua,
            "nama_bank" => $this->nama_bank,
            "status_pembayaran" => $this->status_pembayaran,
            "nama_tim" => $this->nama_tim
        ];
    }
}
