<?php

namespace App\Core\Application\Service\GetNlcTeamUser;

use App\Core\Domain\Models\SchAccount;

class GetNlcTeamRequest
{
    private SchAccount $account;

    /**
     * @param SchAccount $account
     */
    public function __construct(SchAccount $account)
    {
        $this->account = $account;
    }

    /**
     * @return SchAccount
     */
    public function getAccount(): SchAccount
    {
        return $this->account;
    }
}
