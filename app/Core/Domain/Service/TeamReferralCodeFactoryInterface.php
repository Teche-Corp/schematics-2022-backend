<?php

namespace App\Core\Domain\Service;

use App\Core\Domain\Models\ReferralCode;
use App\Core\Domain\Repository\NlcTeamRepositoryInterface;
use App\Core\Domain\Repository\NpcTeamRepositoryInterface;

interface TeamReferralCodeFactoryInterface
{
    /**
     * @param NlcTeamRepositoryInterface|NpcTeamRepositoryInterface $repository
     * @return ReferralCode
     */
    public function createReferralCodeFromRepository(NlcTeamRepositoryInterface|NpcTeamRepositoryInterface $repository): ReferralCode;
}
