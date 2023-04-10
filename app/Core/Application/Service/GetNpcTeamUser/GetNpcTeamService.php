<?php

namespace App\Core\Application\Service\GetNpcTeamUser;

use App\Core\Domain\Models\NPC\Member\NpcMember;
use App\Core\Domain\Models\NPC\Member\NpcMemberType;
use App\Core\Domain\Repository\CitiesRepositoryInterface;
use App\Core\Domain\Repository\NpcMemberRepositoryInterface;
use App\Core\Domain\Repository\NpcTeamRepositoryInterface;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Exceptions\SchematicsException;

class GetNpcTeamService
{
    private NpcTeamRepositoryInterface $npc_team_repository;
    private NpcMemberRepositoryInterface $npc_member_repository;
    private CitiesRepositoryInterface $city_repository;
    private UserRepositoryInterface $user_repository;

    public function __construct(NpcTeamRepositoryInterface $npc_team_repository, NpcMemberRepositoryInterface $npc_member_repository, CitiesRepositoryInterface $city_repository, UserRepositoryInterface $user_repository)
    {
        $this->npc_team_repository = $npc_team_repository;
        $this->npc_member_repository = $npc_member_repository;
        $this->city_repository = $city_repository;
        $this->user_repository = $user_repository;
    }

    /**
     * @throws SchematicsException
     */
    public function execute(GetNpcTeamRequest $request): GetNpcTeamResponse
    {
        $npc_member = $this->npc_member_repository->findByUserId($request->getAccount()->getUserId());
        if(!$npc_member){
            throw new SchematicsException("Anda tidak memiliki tim Schematics NPC", 3001, 404);
        }
        $npc_team = $this->npc_team_repository->find($npc_member->getTeamId());
        $npc_members = $this->npc_member_repository->getByTeamId($npc_member->getTeamId());
        $npc_members_array = array_map(function (NpcMember $member){
            $user = $this->user_repository->find($member->getUserId());
            return new NpcMemberResponse(
                $member->getId()->toString(),
                $user->getName(),
                $member->getMemberType()->value,
                $member->getNisn(),
                $member->getSuratUrl(),
                $member->getNoTelp(),
                $member->getNoWa(),
                $member->getIdLine(),
                $member->getAlamat(),
                $member->getDiscordTag()
            );
        }, $npc_members);
        $city = $this->city_repository->find($npc_team->getIdKota());
        
        if($npc_member->getMemberType() === NpcMemberType::KETUA){
        return new GetNpcTeamResponse(
            $npc_team->getId()->toString(),
            $npc_team->getKategori()->value,
            $npc_team->getNamaTeam(),
            $npc_team->getReferralcode()->getCode(),
            $npc_team->getStatus()->value,
            $npc_team->getAsalSekolah(),
            $npc_team->getNamaGuruPendamping(),
            $npc_team->getNoTelpGuruPendamping(),
            $city->getName(),
            $npc_team->getBiaya(),
            $npc_members_array,
            $npc_team->getUsernameLomba(),
            $npc_team->getPasswordLomba(),
        );
        }else{
            return new GetNpcTeamResponse(
                $npc_team->getId()->toString(),
                $npc_team->getKategori()->value,
                $npc_team->getNamaTeam(),
                $npc_team->getReferralcode()->getCode(),
                $npc_team->getStatus()->value,
                $npc_team->getAsalSekolah(),
                $npc_team->getNamaGuruPendamping(),
                $npc_team->getNoTelpGuruPendamping(),
                $city->getName(),
                $npc_team->getBiaya(),
                $npc_members_array,
                null,
                null
            );
    }
    }
}
