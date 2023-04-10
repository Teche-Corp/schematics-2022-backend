<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\Email;
use App\Core\Domain\Models\NLC\Game\NlcGameAccount;

interface NlcGameAccountRepositoryInterface
{
    public function findByEmail(Email $email): ?NlcGameAccount;
}
