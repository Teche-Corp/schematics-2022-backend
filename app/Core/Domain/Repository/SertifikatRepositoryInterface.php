<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\Sertifikat;
use App\Core\Domain\Models\User\UserId;

interface SertifikatRepositoryInterface
{
    public function findByUserId(UserId $user_id): ?Sertifikat;
}
