<?php

namespace App\Core\Application\Service\GetNlcTeamUser;

use App\Core\Domain\Models\NLC\Member\NlcMember;
use App\Core\Domain\Models\NLC\Member\NlcMemberType;
use App\Core\Domain\Repository\CitiesRepositoryInterface;
use App\Core\Domain\Repository\NlcMemberRepositoryInterface;
use App\Core\Domain\Repository\NlcTeamRepositoryInterface;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Exceptions\SchematicsException;

class GetNlcTeamService
{
    private NlcTeamRepositoryInterface $nlc_team_repository;
    private NlcMemberRepositoryInterface $nlc_member_repository;
    private UserRepositoryInterface $user_repository;
    private CitiesRepositoryInterface $city_repository;

    public function __construct(NlcTeamRepositoryInterface $nlc_team_repository, NlcMemberRepositoryInterface $nlc_member_repository, UserRepositoryInterface $user_repository, CitiesRepositoryInterface $city_repository)
    {
        $this->nlc_team_repository = $nlc_team_repository;
        $this->nlc_member_repository = $nlc_member_repository;
        $this->user_repository = $user_repository;
        $this->city_repository = $city_repository;
    }

    /**
     * @throws SchematicsException
     */
    public function execute(GetNlcTeamRequest $request): GetNlcTeamResponse
    {
        $nlc_member = $this->nlc_member_repository->findByUserId($request->getAccount()->getUserId());
        if(!$nlc_member)
        {
            throw new SchematicsException("Anda tidak memiliki tim Schematics NLC", 3002, 404);
        }
        $nlc_team = $this->nlc_team_repository->find($nlc_member->getTeamId());
        $nlc_members = $this->nlc_member_repository->getByTeamId($nlc_member->getTeamId());
        $nlc_members_array = array_map(function (NlcMember $member){
            $user = $this->user_repository->find($member->getUserId());
            return new NlcMemberResponse(
                $member->getId()->toString(),
                $member->getStatus()->value,
                $user->getName(),
                $member->getMemberType()->value,
                $member->getNisn(),
                $member->getSuratUrl(),
                $member->getBuktiTwibbonUrl(),
                $member->getBuktiPosterUrl(),
                $member->getBuktiVaksinUrl(),
                $member->getNoTelp(),
                $member->getNoWa(),
                $member->getIdLine(),
                $member->getAlamat(),
                $member->getBingoFileUrl(),
            );
        }, $nlc_members);
        $city = $this->city_repository->find($nlc_team->getIdKota());
        
        if($nlc_member->getMemberType() === NlcMemberType::KETUA){
            return new GetNlcTeamResponse(
                $nlc_team->getId()->toString(),
                $nlc_team->getNamaTeam(),
                $nlc_team->getReferralCode()->getCode(),
                $nlc_team->getStatus()->value,
                $nlc_team->getAsalSekolah(),
                $nlc_team->getNamaGuruPendamping(),
                $nlc_team->getNoTelpGuruPendamping(),
                $nlc_team->getRegion(),
                $city->getName(),
                $nlc_team->getBiaya(),
                $nlc_members_array,
                $nlc_team->getEmailLomba(),
                $nlc_team->getPasswordLomba()
            );
        }else{
            return new GetNlcTeamResponse(
                $nlc_team->getId()->toString(),
                $nlc_team->getNamaTeam(),
                $nlc_team->getReferralCode()->getCode(),
                $nlc_team->getStatus()->value,
                $nlc_team->getAsalSekolah(),
                $nlc_team->getNamaGuruPendamping(),
                $nlc_team->getNoTelpGuruPendamping(),
                $nlc_team->getRegion(),
                $city->getName(),
                $nlc_team->getBiaya(),
                $nlc_members_array,
                null,
                null
            );
        }
    }
}
