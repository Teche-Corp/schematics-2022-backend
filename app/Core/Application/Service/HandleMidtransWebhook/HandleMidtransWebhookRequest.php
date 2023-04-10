<?php

namespace App\Core\Application\Service\HandleMidtransWebhook;

class HandleMidtransWebhookRequest
{
    private string $transaction_id;
    private string $transaction_status;
    private string $gross_amount;
    private string $pembayaran_id;

    /**
     * @param string $transaction_id
     * @param string $transaction_status
     * @param string $gross_amount
     * @param string $pembayaran_id
     */
    public function __construct(string $transaction_id, string $transaction_status, string $gross_amount, string $pembayaran_id)
    {
        $this->transaction_id = $transaction_id;
        $this->transaction_status = $transaction_status;
        $this->gross_amount = $gross_amount;
        $this->pembayaran_id = $pembayaran_id;
    }

    /**
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transaction_id;
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
    public function getTransactionStatus(): string
    {
        return $this->transaction_status;
    }

    /**
     * @return string
     */
    public function getGrossAmount(): string
    {
        return $this->gross_amount;
    }
}
