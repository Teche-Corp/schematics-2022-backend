<?php

namespace App\Core\Application\Service\GetReevaOrder;

use App\Core\Domain\Models\SchAccount;

class GetReevaOrderRequest
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
