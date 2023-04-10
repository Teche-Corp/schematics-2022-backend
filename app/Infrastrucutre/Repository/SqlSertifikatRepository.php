<?php

namespace App\Infrastrucutre\Repository;

use App\Core\Domain\Models\Sertifikat;
use App\Core\Domain\Models\User\UserId;
use App\Core\Domain\Repository\SertifikatRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SqlSertifikatRepository implements SertifikatRepositoryInterface
{

    /**
     * @throws Exception
     */
    public function findByUserId(UserId $user_id): ?Sertifikat
    {
        $row = DB::table('sertifikat')->where('user_id', $user_id->toString())->first();

        if (!$row) return null;

        return Sertifikat::constructFromObject($row);
    }
}
