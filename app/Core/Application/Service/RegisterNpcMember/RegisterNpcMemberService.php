<?php

namespace App\Core\Application\Service\RegisterNpcMember;

use App\Core\Domain\Models\NPC\Member\NpcMember;
use App\Core\Domain\Models\NPC\Member\NpcMemberId;
use App\Core\Domain\Models\NPC\Member\NpcMemberType;
use App\Core\Domain\Models\ReferralCode;
use App\Core\Domain\Models\SchAccount;
use App\Core\Domain\Repository\NpcMemberRepositoryInterface;
use App\Core\Domain\Repository\NpcTeamRepositoryInterface;
use App\Exceptions\SchematicsException;
use Exception;
use Illuminate\Support\Facades\Storage;
use function count;

class RegisterNpcMemberService
{
    private NpcMemberRepositoryInterface $npc_member_repository;
    private NpcTeamRepositoryInterface $npc_team_repository;

    /**
     * @param NpcMemberRepositoryInterface $npc_member_repository
     * @param NpcTeamRepositoryInterface $npc_team_repository
     */
    public function __construct(NpcMemberRepositoryInterface $npc_member_repository, NpcTeamRepositoryInterface $npc_team_repository)
    {
        $this->npc_member_repository = $npc_member_repository;
        $this->npc_team_repository = $npc_team_repository;
    }

    /**
     * @throws Exception
     */
    public function execute(RegisterNpcMemberRequest $request, SchAccount $account)
    {
        $npc_team = $this->npc_team_repository->findByReferralCode(new ReferralCode($request->getKodeReferral()));

        if(!$npc_team){
            SchematicsException::throw("Kode referral tidak ditemukan", 2017);
        }

        $npc_members = $this->npc_member_repository->getByTeamId($npc_team->getId());
        if (count($npc_members) >= NpcMember::MAX_MEMBER) {
            SchematicsException::throw("jumlah member sudah mencapai batas", 1001);
        }

        // if ($npc_team->getStatus() != NpcTeamStatus::ACTIVE) {
        //     SchematicsException::throw("tim belum menyelesaikan proses administrasi", 2016);
        // }

        if ($request->getSurat()->getSize() > 1048576) {
            SchematicsException::throw("surat harus dibawah 1Mb", 2010);
        }

        $surat_path = Storage::putFileAs("NPC/Member", $request->getSurat(), 'anggota_surat_'.$account->getUserId()->toString());

        $npc_member = NpcMember::create(
            NpcMemberId::generate(),
            NpcMemberType::ANGGOTA,
            $npc_team->getId(),
            $account->getUserId(),
            $request->getNisn(),
            $surat_path,
            $request->getNoTelp(),
            $request->getNoWa(),
            $request->getIdLine(),
            $request->getAlamat(),
            $request->getInfoSch(),
            $request->getDiscordTag()
        );

        $this->npc_member_repository->persist($npc_member);
    }
}
