<?php

namespace App\Core\Domain\Service;

use App\Core\Domain\Models\Pembayaran\Pembayaran;
use App\Core\Domain\Models\User\User;

interface MidtransPaymentServiceInterface
{
    public function createPaymentLink(User $user, Pembayaran $pembayaran): string;
}
