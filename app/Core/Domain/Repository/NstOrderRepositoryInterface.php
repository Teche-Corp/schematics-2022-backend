<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\NST\NstOrder\NstOrder;
use App\Core\Domain\Models\NST\NstOrder\NstOrderId;
use App\Core\Domain\Models\User\UserId;

interface NstOrderRepositoryInterface
{
    public function find(NstOrderId $id): ?NstOrder;

    public function findByUserId(UserId $user_id): ?NstOrder;

    public function persist(NstOrder $nst_order): void;
}
