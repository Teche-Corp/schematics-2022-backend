<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\NLC\Team\NlcTeam;
use App\Core\Domain\Models\NLC\Team\NlcTeamId;
use App\Core\Domain\Models\ReferralCode;

interface NlcTeamRepositoryInterface
{
    public function find(NlcTeamId $id): ?NlcTeam;

    public function findByReferralCode(ReferralCode $code): ?NlcTeam;

    public function persist(NlcTeam $nlc_team): void;

    public function latestCreatedAt(): ?NlcTeam;

    /**
     * @param int $page
     * @param int $per_page
     * @return array<NlcTeam[], float> $nlc_team, $max_page
     */
    public function getWithPagination(int $page, int $per_page): array;
}
