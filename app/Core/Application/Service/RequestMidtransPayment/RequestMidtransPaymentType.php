<?php

namespace App\Core\Application\Service\RequestMidtransPayment;

enum RequestMidtransPaymentType: string
{
    case REEVA_ORDER = 'reeva_order';
    case NST_ORDER = 'nst_order';
}
