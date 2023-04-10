<?php

namespace App\Core\Domain\Models\NST\NstOrder;

enum NstOrderStatus: string
{
    case AWAITING_PAYMENT = 'awaiting_payment';
    case AWAITING_VERIFICATION = 'awaiting_verification';
    case NEED_REVISION = 'need_revision';
    case ACTIVE = 'active';
}
