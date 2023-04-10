<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\NPC\Member\NpcMember;
use App\Core\Domain\Models\NPC\Member\NpcMemberId;
use App\Core\Domain\Models\NPC\Team\NpcTeamId;
use App\Core\Domain\Models\User\UserId;

interface NpcMemberRepositoryInterface
{
    public function find(NpcMemberId $id): ?NpcMember;

    public function findByUserId(UserId $user_id): ?NpcMember;

    /**
     * @param NpcTeamId $team_id
     * @return NpcMember[]
     */
    public function getByTeamId(NpcTeamId $team_id): array;

    public function persist(NpcMember $member): void;

    public function findKetuaByTeamId(NpcTeamId $id): ?NpcMember;
}
