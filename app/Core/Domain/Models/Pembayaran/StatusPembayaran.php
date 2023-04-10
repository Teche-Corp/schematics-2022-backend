<?php

namespace App\Core\Domain\Models\Pembayaran;

enum StatusPembayaran: string
{
    case UNVERIFIED = 'unverified';
    case NEED_REVISION = 'need_revision';
    case VERIFIED = 'verified';
}
