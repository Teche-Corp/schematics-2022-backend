<?php

namespace App\Core\Domain\Models\NST\NstOrder;

use App\Core\Domain\Models\User\UserId;

class NstOrder
{
    const BASE_PRICE = 60000;
    const MAX_TICKET = 6;
    
    private NstOrderId $id;
    private UserId $user_id;
    private NstOrderStatus $status;
    private int $jumlah_tiket;
    private float $biaya;
    private int $unique_payment_code;
    private string $info_sch;

    /**
     * @param NstOrderId $id
     * @param UserId $user_id
     * @param NstOrderStatus $status
     * @param float $biaya
     * @param int $unique_payment_code
     * @param string $info_sch
     */
    public function __construct(NstOrderId $id, UserId $user_id, NstOrderStatus $status, int $jumlah_tiket, float $biaya, int $unique_payment_code, string $info_sch)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->status = $status;
        $this->jumlah_tiket = $jumlah_tiket;
        $this->biaya = $biaya;
        $this->unique_payment_code = $unique_payment_code;
        $this->info_sch = $info_sch;
    }

    public function awaitingVerification(): void
    {
        $this->status = NstOrderStatus::AWAITING_VERIFICATION;
    }

    public function needRevision(): void
    {
        $this->status = NstOrderStatus::NEED_REVISION;
    }

    public function activate(): void
    {
        $this->status = NstOrderStatus::ACTIVE;
    }

    /**
     * @return NstOrderId
     */
    public function getId(): NstOrderId
    {
        return $this->id;
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->user_id;
    }

    /**
     * @return NstOrderStatus
     */
    public function getStatus(): NstOrderStatus
    {
        return $this->status;
    }

     /**
     * @return string
     */
    public function getJumlahTiket(): int
    {
        return $this->jumlah_tiket;
    }
    
    /**
     * @return float
     */
    public function getBiaya(): float
    {
        return $this->biaya;
    }

    /**
     * @return int
     */
    public function getUniquePaymentCode(): int
    {
        return $this->unique_payment_code;
    }

    /**
     * @return string
     */
    public function getInfoSch(): string
    {
        return $this->info_sch;
    }
}
