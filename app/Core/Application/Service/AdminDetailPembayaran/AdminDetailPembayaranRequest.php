<?php

namespace App\Core\Application\Service\AdminDetailPembayaran;


class AdminDetailPembayaranRequest
{
    private string $pembayaran_id;

    public function __construct(string $pembayaran_id)
    {
        $this->pembayaran_id = $pembayaran_id;
    }

    /**
     * @return string
     */
    public function getPembayaranId(): string
    {
        return $this->pembayaran_id;
    }
}
