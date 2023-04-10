<?php

namespace App\Core\Application\Service\AdminGetListTeam;

use App\Core\Application\Service\PaginationResponse;
use App\Core\Domain\Models\NPC\Team\NpcKategori;
use App\Core\Domain\Repository\NlcMemberRepositoryInterface;
use App\Core\Domain\Repository\NlcTeamRepositoryInterface;
use App\Core\Domain\Repository\NpcMemberRepositoryInterface;
use App\Core\Domain\Repository\NpcTeamRepositoryInterface;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Exceptions\SchematicsException;
use Exception;

class AdminGetListTeamService
{
    private NpcTeamRepositoryInterface $npc_team_repository;
    private NpcMemberRepositoryInterface $npc_member_repository;
    private NlcTeamRepositoryInterface $nlc_team_repository;
    private NlcMemberRepositoryInterface $nlc_member_repository;
    private UserRepositoryInterface $user_repository;

    /**
     * @param NpcTeamRepositoryInterface $npc_team_repository
     * @param NpcMemberRepositoryInterface $npc_member_repository
     * @param NlcTeamRepositoryInterface $nlc_team_repository
     * @param NlcMemberRepositoryInterface $nlc_member_repository
     * @param UserRepositoryInterface $user_repository
     */
    public function __construct(NpcTeamRepositoryInterface $npc_team_repository, NpcMemberRepositoryInterface $npc_member_repository, NlcTeamRepositoryInterface $nlc_team_repository, NlcMemberRepositoryInterface $nlc_member_repository, UserRepositoryInterface $user_repository)
    {
        $this->npc_team_repository = $npc_team_repository;
        $this->npc_member_repository = $npc_member_repository;
        $this->nlc_team_repository = $nlc_team_repository;
        $this->nlc_member_repository = $nlc_member_repository;
        $this->user_repository = $user_repository;
    }

    /**
     * @throws Exception
     */
    public function execute(AdminGetListTeamRequest $request, AdminGetListTeamType $team_type): PaginationResponse
    {
        $admin_list_team_response = [];
        switch ($team_type) {
            case AdminGetListTeamType::NPC_JUNIOR:
            case AdminGetListTeamType::NPC_SENIOR:
                if ($team_type === AdminGetListTeamType::NPC_JUNIOR)
                    $pagination_response = $this->npc_team_repository
                        ->getWithPaginationByKategori(NpcKategori::JUNIOR, $request->getPage(), $request->getPerPage());
                else if ($team_type === AdminGetListTeamType::NPC_SENIOR)
                    $pagination_response = $this->npc_team_repository
                        ->getWithPaginationByKategori(NpcKategori::SENIOR, $request->getPage(), $request->getPerPage());
                else SchematicsException::throw("invalid team enum", 1010, 404);

                $max_page = $pagination_response[1];

                foreach ($pagination_response[0] as $npc_team) {
                    $member = $this->npc_member_repository->findKetuaByTeamId($npc_team->getId());
                    $user = $this->user_repository->find($member->getUserId());
                    $admin_list_team_response[] = new AdminGetListTeamResponse(
                        $npc_team->getId()->toString(),
                        $npc_team->getNamaTeam(),
                        $user->getName()
                    );
                }
                break;
            case AdminGetListTeamType::NLC:
                $pagination_response = $this->nlc_team_repository->getWithPagination($request->getPage(), $request->getPerPage());
                $max_page = $pagination_response[1];

                foreach ($pagination_response[0] as $nlc_team) {
                    $member = $this->nlc_member_repository->findKetuaByTeamId($nlc_team->getId());
                    $user = $this->user_repository->find($member->getUserId());
                    $admin_list_team_response[] = new AdminGetListTeamResponse(
                        $nlc_team->getId()->toString(),
                        $nlc_team->getNamaTeam(),
                        $user->getName()
                    );
                }
                break;
            default:
                SchematicsException::throw("invalid team enum", 1011, 404);
        }
        return new PaginationResponse($admin_list_team_response, $request->getPage(), $max_page);
    }
}
