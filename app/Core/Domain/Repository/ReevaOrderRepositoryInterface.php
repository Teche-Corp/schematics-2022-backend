<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrder;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderId;
use App\Core\Domain\Models\User\UserId;

interface ReevaOrderRepositoryInterface
{
    public function find(ReevaOrderId $id): ?ReevaOrder;

    public function findByUserId(UserId $user_id): ?ReevaOrder;

    public function persist(ReevaOrder $reeva_order): void;
}
