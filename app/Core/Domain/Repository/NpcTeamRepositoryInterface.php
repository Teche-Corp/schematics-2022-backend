<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\NPC\Team\NpcKategori;
use App\Core\Domain\Models\NPC\Team\NpcTeam;
use App\Core\Domain\Models\NPC\Team\NpcTeamId;
use App\Core\Domain\Models\ReferralCode;

interface NpcTeamRepositoryInterface
{
    public function find(NpcTeamId $id): ?NpcTeam;

    public function findByReferralCode(ReferralCode $code): ?NpcTeam;

    public function persist(NpcTeam $npc_team): void;

    public function latestJuniorCreatedAt(): ?NpcTeam;

    public function latestSeniorCreatedAt(): ?NpcTeam;

    /**
     * @param NpcKategori $kategori
     * @param int $page
     * @param int $per_page
     * @return array<NpcTeam[], float> $nlc_team, $max_page
     */
    public function getWithPaginationByKategori(NpcKategori $kategori, int $page, int $per_page): array;
}
