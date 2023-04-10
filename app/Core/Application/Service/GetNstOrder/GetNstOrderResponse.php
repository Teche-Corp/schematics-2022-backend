<?php

namespace App\Core\Application\Service\GetNstOrder;

use App\Core\Domain\Models\NST\NstOrder\NstOrderStatus;
use JsonSerializable;

class GetNstOrderResponse implements JsonSerializable
{
    private string $order_id;
    private NstOrderStatus $status;
    private float $biaya;
    private int $unique_payment_code;
    private array $tickets;

    /**
     * @param string $order_id
     * @param NstOrderStatus $status
     * @param float $biaya
     * @param int $unique_payment_code
     * @param array $tickets
     */
    public function __construct(string $order_id, NstOrderStatus $status, float $biaya, int $unique_payment_code, array $tickets)
    {
        $this->order_id = $order_id;
        $this->status = $status;
        $this->biaya = $biaya;
        $this->unique_payment_code = $unique_payment_code;
        $this->tickets = $tickets;
    }

    public function jsonSerialize(): array
    {
        return [
            'order_id' => $this->order_id,
            'status' => $this->status,
            'biaya' => $this->biaya,
            'unique_payment_code' => $this->unique_payment_code,
            'tickets' => $this->tickets
        ];
    }
}
