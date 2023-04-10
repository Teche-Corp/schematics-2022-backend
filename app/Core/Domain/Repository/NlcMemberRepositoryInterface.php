<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\NLC\Member\NlcMember;
use App\Core\Domain\Models\NLC\Member\NlcMemberId;
use App\Core\Domain\Models\NLC\Team\NlcTeamId;
use App\Core\Domain\Models\User\UserId;

interface NlcMemberRepositoryInterface
{
    public function find(NlcMemberId $id): ?NlcMember;

    public function findByUserId(UserId $user_id): ?NlcMember;

    /**
     * @param NlcTeamId $team_id
     * @return NlcMember[]
     */
    public function getByTeamId(NlcTeamId $team_id): array;

    public function persist(NlcMember $member): void;

    public function findKetuaByTeamId(NlcTeamId $id): ?NlcMember;
}
