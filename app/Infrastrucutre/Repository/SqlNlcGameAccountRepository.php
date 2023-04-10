<?php

namespace App\Infrastrucutre\Repository;

use Exception;
use App\Core\Domain\Models\Email;
use Illuminate\Support\Facades\DB;
use App\Core\Domain\Models\NLC\Game\NlcGameAccount;
use App\Core\Domain\Repository\NlcGameAccountRepositoryInterface;

class SqlNlcGameAccountRepository implements NlcGameAccountRepositoryInterface
{
    /**
     * @throws Exception
     */
    public function findByEmail(Email $email): ?NlcGameAccount
    {
        $row = DB::connection('nlc_game')->table('accounts')->where('email', $email->toString())->first();

        if (!$row) return null;

        return $this->constructFromRows($row);
    }

    public function constructFromRows($row): NlcGameAccount
    {
        return new NlcGameAccount(
            $row->account_id,
            new Email($row->email),
            $row->team_name,
            $row->region_id,
            $row->first_login
        );
    }
}
