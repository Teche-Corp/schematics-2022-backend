<?php

namespace App\Core\Application\Service\GetReevaOrder;

use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderStatus;
use JsonSerializable;

class GetReevaOrderResponse implements JsonSerializable
{
    private string $order_id;
    private ReevaOrderStatus $status;
    private float $biaya;
    private int $unique_payment_code;
    private string $jenis_tiket;
    private array $tickets;

    /**
     * @param string $order_id
     * @param ReevaOrderStatus $status
     * @param float $biaya
     * @param int $unique_payment_code
     * @param array $tickets
     */
    public function __construct(string $order_id, ReevaOrderStatus $status, float $biaya, int $unique_payment_code, string $jenis_tiket, array $tickets)
    {
        $this->order_id = $order_id;
        $this->status = $status;
        $this->biaya = $biaya;
        $this->unique_payment_code = $unique_payment_code;
        $this->tickets = $tickets;
        $this->jenis_tiket = $jenis_tiket;
    }

    public function jsonSerialize(): array
    {
        return [
            'order_id' => $this->order_id,
            'status' => $this->status,
            'biaya' => $this->biaya,
            'unique_payment_code' => $this->unique_payment_code,
            'jenis_tiket' => $this->jenis_tiket,
            'tickets' => $this->tickets
        ];
    }
}
