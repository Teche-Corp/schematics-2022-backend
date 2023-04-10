<?php

namespace App\Core\Domain\Models\Reeva\ReevaOrder;

use App\Core\Domain\Models\User\UserId;

class ReevaOrder
{
    const MAX_TICKET = 10;
    const BASE_PRICE = 185000;

    private ReevaOrderId $id;
    private UserId $user_id;
    private ReevaOrderStatus $status;
    private int $jumlah_tiket;
    private float $biaya;
    private int $unique_payment_code;
    private string $info_sch;
    private ?string $kode_voucher;

    /**
     * @param ReevaOrderId $id
     * @param UserId $user_id
     * @param ReevaOrderStatus $status
     * @param float $biaya
     * @param int $unique_payment_code
     * @param string $info_sch
     * @param ?string $kode_voucher
     */
    public function __construct(ReevaOrderId $id, UserId $user_id, ReevaOrderStatus $status, int $jumlah_tiket, float $biaya, int $unique_payment_code, string $info_sch, ?string $kode_voucher)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->status = $status;
        $this->jumlah_tiket = $jumlah_tiket;
        $this->biaya = $biaya;
        $this->unique_payment_code = $unique_payment_code;
        $this->info_sch = $info_sch;
        $this->kode_voucher = $kode_voucher;
    }

    /**
     * @param string|null $kode_voucher
     */
    public function setKodeVoucher(?string $kode_voucher): void
    {
        $this->kode_voucher = $kode_voucher;
    }

    public function awaitPayment(): void
    {
        $this->status = ReevaOrderStatus::AWAITING_PAYMENT;
    }

    /**
     * @param string $info_sch
     */
    public function setInfoSch(string $info_sch): void
    {
        $this->info_sch = $info_sch;
    }

    /**
     * @param int $unique_payment_code
     */
    public function setUniquePaymentCode(int $unique_payment_code): void
    {
        $this->unique_payment_code = $unique_payment_code;
    }

    /**
     * @param float $biaya
     */
    public function setBiaya(float $biaya): void
    {
        $this->biaya = $biaya;
    }

    /**
     * @param int $jumlah_tiket
     */
    public function setJumlahTiket(int $jumlah_tiket): void
    {
        $this->jumlah_tiket = $jumlah_tiket;
    }

    public function awaitingVerification(): void
    {
        $this->status = ReevaOrderStatus::AWAITING_VERIFICATION;
    }

    public function needRevision(): void
    {
        $this->status = ReevaOrderStatus::NEED_REVISION;
    }

    public function activate(): void
    {
        $this->status = ReevaOrderStatus::ACTIVE;
    }

    public function expired(): void
    {
        $this->status = ReevaOrderStatus::EXPIRED;
    }

    /**
     * @return ReevaOrderId
     */
    public function getId(): ReevaOrderId
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
     * @return ReevaOrderStatus
     */
    public function getStatus(): ReevaOrderStatus
    {
        return $this->status;
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

    /**
     * @return string
     */
    public function getJumlahTiket(): int
    {
        return $this->jumlah_tiket;
    }

    /**
     * @return string|null
     */
    public function getKodeVoucher(): ?string
    {
        return $this->kode_voucher;
    }
}
