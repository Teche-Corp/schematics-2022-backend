<?php

namespace App\Core\Application\Service\AdminListPembayaranTicket;

use JsonSerializable;

class AdminListPembayaranTicketResponse implements JsonSerializable
{
    private ?string $pembayaran_id;
    private string $order_id;
    private string $status_pembayaran;
    private string $nama_bank;
    private string $nama_pemesan;
    private int $jumlah_ticket;

    /**
     * @param string|null $pembayaran_id
     * @param string $order_id
     * @param string $status_pembayaran
     * @param string $nama_bank
     * @param string $nama_pemesan
     * @param int $jumlah_ticket
     */
    public function __construct(?string $pembayaran_id, string $order_id, string $status_pembayaran, string $nama_bank, string $nama_pemesan, int $jumlah_ticket)
    {
        $this->pembayaran_id = $pembayaran_id;
        $this->order_id = $order_id;
        $this->status_pembayaran = $status_pembayaran;
        $this->nama_bank = $nama_bank;
        $this->nama_pemesan = $nama_pemesan;
        $this->jumlah_ticket = $jumlah_ticket;
    }

    public function jsonSerialize(): array
    {
        return [
            'pembayaran_id' => $this->pembayaran_id,
            'order_id' => $this->order_id,
            'status_pembayaran' => $this->status_pembayaran,
            'nama_bank' => $this->nama_bank,
            'nama_pemesan' => $this->nama_pemesan,
            'jumlah_ticket' => $this->jumlah_ticket,
        ];
    }
}
