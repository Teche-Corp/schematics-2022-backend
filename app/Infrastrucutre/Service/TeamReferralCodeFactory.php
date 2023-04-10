<?php

namespace App\Infrastrucutre\Service;

use App\Core\Domain\Models\ReferralCode;
use App\Core\Domain\Repository\NlcTeamRepositoryInterface;
use App\Core\Domain\Repository\NpcTeamRepositoryInterface;
use App\Core\Domain\Service\TeamReferralCodeFactoryInterface;
use Exception;
use Ramsey\Uuid\Uuid;

class TeamReferralCodeFactory implements TeamReferralCodeFactoryInterface
{

    /**
     * @throws Exception
     */
    public function createReferralCodeFromRepository(NlcTeamRepositoryInterface|NpcTeamRepositoryInterface $repository): ReferralCode
    {
        for($c = 1;;$c++) {
            if ($c > 4) {
                $referral = Uuid::uuid4();
                break;
            }
            if (!$repository->findByReferralCode($referral = ReferralCode::generate())) break;
        }
        return $referral;
    }
}
