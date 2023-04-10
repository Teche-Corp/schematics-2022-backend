<?php

namespace App\Core\Application\Service\AdminGetNlcTeam;

use App\Core\Domain\Models\NLC\Member\NlcMember;
use App\Core\Domain\Models\NLC\Team\NlcTeamId;
use App\Core\Domain\Repository\CitiesRepositoryInterface;
use App\Core\Domain\Repository\NlcMemberRepositoryInterface;
use App\Core\Domain\Repository\NlcTeamRepositoryInterface;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Exceptions\SchematicsException;
use Exception;

class AdminGetNlcTeamService
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
     * @throws Exception
     */
    public function execute(AdminGetNlcTeamRequest $request): AdminGetNlcTeamResponse
    {
        $nlc_team = $this->nlc_team_repository->find(new NlcTeamId($request->getTeamId()));
        if (!$nlc_team) {
            SchematicsException::throw("nlc team not found", 1101, 404);
        }

        $nlc_members = $this->nlc_member_repository->getByTeamId($nlc_team->getId());
        $nlc_members_array = array_map(function (NlcMember $member){
            $user = $this->user_repository->find($member->getUserId());
            return new AdminNlcMemberResponse(
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
                $member->getAlamat()
            );
        }, $nlc_members);
        $city = $this->city_repository->find($nlc_team->getIdKota());
        return new AdminGetNlcTeamResponse(
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
            $nlc_members_array
        );
    }
}
