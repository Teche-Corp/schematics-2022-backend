<?php

namespace App\Core\Application\Service\AdminVerifyPembayaran;

class AdminVerifyPembayaranRequest
{
    private string $pembayaran_id;
    private string $verification_status;

    /**
     * @param string $pembayaran_id
     * @param string $verification_status
     */
    public function __construct(string $pembayaran_id, string $verification_status)
    {
        $this->pembayaran_id = $pembayaran_id;
        $this->verification_status = $verification_status;
    }

    /**
     * @return string
     */
    public function getPembayaranId(): string
    {
        return $this->pembayaran_id;
    }

    /**
     * @return string
     */
    public function getVerificationStatus(): string
    {
        return $this->verification_status;
    }
}
