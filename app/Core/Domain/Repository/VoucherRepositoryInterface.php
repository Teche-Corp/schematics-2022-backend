<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\Voucher\Voucher;
use App\Core\Domain\Models\Voucher\VoucherId;

interface VoucherRepositoryInterface
{
    public function persist(Voucher $voucher): void;

    public function find(VoucherId $id): ?Voucher;

    public function findByKode(string $kode): ?Voucher;
}
