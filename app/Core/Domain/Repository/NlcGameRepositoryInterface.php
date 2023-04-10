<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\NLC\Game\NlcGame;

interface NlcGameRepositoryInterface
{
    public function findByAccountId(string $account_id): ?NlcGame;
}
