<?php

namespace App\Core\Domain\Models\Reeva\ReevaOrder;

enum ReevaOrderStatus: string
{
    case AWAITING_PAYMENT = 'awaiting_payment';
    case AWAITING_VERIFICATION = 'awaiting_verification';
    case NEED_REVISION = 'need_revision';
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
}
