<?php

namespace App\Core\Application\Service\AdminVerifyTeamMember;

use App\Core\Domain\Models\NLC\Member\NlcMemberId;
use App\Core\Domain\Models\NLC\Member\NlcMemberStatus;
use App\Core\Domain\Models\NPC\Member\NpcMemberId;
use App\Core\Domain\Models\NPC\Member\NpcMemberStatus;
use App\Core\Domain\Models\SchAccount;
use App\Core\Domain\Repository\NlcMemberRepositoryInterface;
use App\Core\Domain\Repository\NpcMemberRepositoryInterface;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Exceptions\SchematicsException;
use Exception;
use Illuminate\Support\Facades\DB;

class AdminVerifyTeamMemberService
{
    private NlcMemberRepositoryInterface $nlc_member_repository;
    private NpcMemberRepositoryInterface $npc_member_repository;
    private UserRepositoryInterface $user_repository;

    /**
     * @param NlcMemberRepositoryInterface $nlc_member_repository
     * @param NpcMemberRepositoryInterface $npc_member_repository
     * @param UserRepositoryInterface $user_repository
     */
    public function __construct(NlcMemberRepositoryInterface $nlc_member_repository, NpcMemberRepositoryInterface $npc_member_repository, UserRepositoryInterface $user_repository)
    {
        $this->nlc_member_repository = $nlc_member_repository;
        $this->npc_member_repository = $npc_member_repository;
        $this->user_repository = $user_repository;
    }

    /**
     * @throws Exception
     */
    public function execute(AdminVerifyTeamMemberRequest $request, SchAccount $account, TipeTeamMember $tipe_team_member)
    {
        $admin = $this->user_repository->find($account->getUserId());
        if (!$admin) {
            SchematicsException::throw("akun admin not found", 1006, 404);
        }
        switch ($tipe_team_member) {
            case TipeTeamMember::NLC:
                $member = $this->nlc_member_repository->find(new NlcMemberId($request->getMemberId()));
                if (!$member) {
                    SchematicsException::throw("nlc member not found", 1003, 404);
                }
                $member->setStatus(NlcMemberStatus::from($request->getNewStatus()));
                $this->nlc_member_repository->persist($member);
                break;
            case TipeTeamMember::NPC:
                $member = $this->npc_member_repository->find(new NpcMemberId($request->getMemberId()));
                if (!$member) {
                    SchematicsException::throw("npc member not found", 1004, 404);
                }
                $member->setStatus(NpcMemberStatus::from($request->getNewStatus()));
                $this->npc_member_repository->persist($member);
                break;
            default:
                SchematicsException::throw("invalid team", 1005);
        }

        DB::table('admin_log')->insert(
            [
                'admin_user_id' => $admin->getId()->toString(),
                'admin_name' => $admin->getName(),
                'subject_type' => "{$tipe_team_member->value}_member",
                'subject_id' => $request->getMemberId(),
                'action' => "member_verify_{$request->getNewStatus()}"
            ]
        );
    }
}
