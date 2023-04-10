<?php

namespace App\Core\Domain\Models\NLC\Team;

enum NlcTeamStatus: string
{
    case AWAITING_PAYMENT = 'awaiting_payment';
    case AWAITING_VERIFICATION = 'awaiting_verification';
    case NEED_REVISION = 'need_revision';
    case PAYMENT_VERIFIED = 'payment_verified';
    case ACTIVE = 'active';
}
