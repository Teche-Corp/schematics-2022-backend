<?php

namespace App\Core\Application\Service\RequestMidtransPayment;

use JsonSerializable;

class RequestMidtransPaymentResponse implements JsonSerializable
{
    private string $payment_link;

    /**
     * @param string $payment_link
     */
    public function __construct(string $payment_link)
    {
        $this->payment_link = $payment_link;
    }

    public function jsonSerialize(): array
    {
        return [
            "payment_url" => $this->payment_link
        ];
    }
}
