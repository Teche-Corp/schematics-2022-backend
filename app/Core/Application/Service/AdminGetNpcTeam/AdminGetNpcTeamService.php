<?php

namespace App\Core\Application\Service\AdminGetNpcTeam;

use App\Core\Domain\Models\NPC\Member\NpcMember;
use App\Core\Domain\Models\NPC\Team\NpcTeamId;
use App\Core\Domain\Repository\CitiesRepositoryInterface;
use App\Core\Domain\Repository\NpcMemberRepositoryInterface;
use App\Core\Domain\Repository\NpcTeamRepositoryInterface;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Exceptions\SchematicsException;
use Exception;

class AdminGetNpcTeamService
{
    private NpcTeamRepositoryInterface $npc_team_repository;
    private NpcMemberRepositoryInterface $npc_member_repository;
    private CitiesRepositoryInterface $city_repository;
    private UserRepositoryInterface $user_repository;

    /**
     * @param NpcTeamRepositoryInterface $npc_team_repository
     * @param NpcMemberRepositoryInterface $npc_member_repository
     * @param CitiesRepositoryInterface $city_repository
     * @param UserRepositoryInterface $user_repository
     */
    public function __construct(NpcTeamRepositoryInterface $npc_team_repository, NpcMemberRepositoryInterface $npc_member_repository, CitiesRepositoryInterface $city_repository, UserRepositoryInterface $user_repository)
    {
        $this->npc_team_repository = $npc_team_repository;
        $this->npc_member_repository = $npc_member_repository;
        $this->city_repository = $city_repository;
        $this->user_repository = $user_repository;
    }

    /**
     * @throws SchematicsException
     * @throws Exception
     */
    public function execute(AdminGetNpcTeamRequest $request): AdminGetNpcTeamResponse
    {
        $npc_team = $this->npc_team_repository->find(new NpcTeamId($request->getTeamId()));
        if (!$npc_team) {
            SchematicsException::throw("npc team not found", 1100, 404);
        }

        $npc_members = $this->npc_member_repository->getByTeamId($npc_team->getId());
        $npc_members_array = array_map(function (NpcMember $member){
            $user = $this->user_repository->find($member->getUserId());
            return new AdminNpcMemberResponse(
                $member->getId()->toString(),
                $member->getTeamId()->toString(),
                $member->getUserId()->toString(),
                $user->getName(),
                $member->getMemberType()->value,
                $member->getNisn(),
                $member->getSuratUrl(),
                $member->getNoTelp(),
                $member->getNoWa(),
                $member->getIdLine(),
                $member->getAlamat(),
                $member->getDiscordTag(),
                $member->getInfoSch()
            );
        }, $npc_members);

        $city = $this->city_repository->find($npc_team->getIdKota());
        return new AdminGetNpcTeamResponse(
            $npc_team->getId()->toString(),
            $npc_team->getUniquePaymentCode(),
            $npc_team->getKategori()->value,
            $npc_team->getNamaTeam(),
            $npc_team->getReferralcode()->getCode(),
            $npc_team->getStatus()->value,
            $npc_team->getAsalSekolah(),
            $npc_team->getNamaGuruPendamping(),
            $npc_team->getNoTelpGuruPendamping(),
            $city->getName(),
            $npc_team->getBiaya(),
            $npc_members_array
        );
    }
}
