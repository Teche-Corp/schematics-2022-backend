<?php

namespace App\Core\Application\Service\RegisterNpcTeam;

use App\Core\Domain\Models\NPC\Member\NpcMember;
use App\Core\Domain\Models\NPC\Member\NpcMemberId;
use App\Core\Domain\Models\NPC\Member\NpcMemberType;
use App\Core\Domain\Models\NPC\Team\NpcKategori;
use App\Core\Domain\Models\NPC\Team\NpcTeam;
use App\Core\Domain\Models\SchAccount;
use App\Core\Domain\Repository\NpcMemberRepositoryInterface;
use App\Core\Domain\Repository\NpcTeamRepositoryInterface;
use App\Core\Domain\Repository\VoucherRepositoryInterface;
use App\Core\Domain\Service\TeamReferralCodeFactoryInterface;
use App\Core\Domain\Service\UniquePaymentCodeInterface;
use App\Exceptions\SchematicsException;
use Exception;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class RegisterNpcTeamService
{
    private NpcTeamRepositoryInterface $npc_team_repository;
    private NpcMemberRepositoryInterface $npc_member_repository;
    private TeamReferralCodeFactoryInterface $referral_code_factory;
    private UniquePaymentCodeInterface $unique_payment;
    private VoucherRepositoryInterface $voucher_repository;

    /**
     * @param NpcTeamRepositoryInterface $npc_team_repository
     * @param NpcMemberRepositoryInterface $npc_member_repository
     * @param TeamReferralCodeFactoryInterface $referral_code_factory
     * @param VoucherRepositoryInterface $voucher_repository
     */
    public function __construct(NpcTeamRepositoryInterface $npc_team_repository, NpcMemberRepositoryInterface $npc_member_repository, TeamReferralCodeFactoryInterface $referral_code_factory, UniquePaymentCodeInterface $unique_payment, VoucherRepositoryInterface $voucher_repository)
    {
        $this->npc_team_repository = $npc_team_repository;
        $this->npc_member_repository = $npc_member_repository;
        $this->referral_code_factory = $referral_code_factory;
        $this->unique_payment = $unique_payment;
        $this->voucher_repository = $voucher_repository;
    }

    /**
     * @throws Exception
     */
    public function execute(RegisterNpcTeamRequest $request, SchAccount $account): RegisterNpcTeamResponse
    {
        if ($account->getNpcMemberId()) {
            SchematicsException::throw("user sudah mempunyai team npc", 2005);
        }

        $referral_code = $this->referral_code_factory->createReferralCodeFromRepository($this->npc_team_repository);
        $biaya = (NpcTeam::getBaseBiaya($request->getKategori()) + $unique_payment_code = $this->unique_payment->getByEventType('npc_'.$request->getKategori().'_code'));
        if($request->getKodeVoucher()){
            $voucher = $this->voucher_repository->findByKode($request->getKodeVoucher());
            $biaya = $biaya - $voucher->getPotongan();
            $voucher->useVoucher();
            $this->voucher_repository->persist($voucher);
        }
        $npc_team = NpcTeam::create(
            NpcKategori::from($request->getKategori()),
            $referral_code,
            $request->getNamaTeam(),
            $request->getAsalSekolah(),
            $request->getNamaGuruPendamping(),
            $request->getNoTelpGuruPendamping(),
            $request->getIdKota(),
            $biaya,
            $unique_payment_code,
            $request->getKodeVoucher()
        );

        if ($request->getSurat()->getSize() > 1048576) {
            SchematicsException::throw("surat harus dibawah 1Mb", 2000);
        }

        $surat_path = Storage::putFileAs("NPC/Member", $request->getSurat(), 'ketua_surat_'.$account->getUserId()->toString());

        if (!$surat_path) {
            SchematicsException::throw("upload file gagal", 2003);
        }

        $npc_ketua = NpcMember::create(
            new NpcMemberId(Uuid::uuid4()),
            NpcMemberType::KETUA,
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

        $this->npc_team_repository->persist($npc_team);
        $this->npc_member_repository->persist($npc_ketua);

        return new RegisterNpcTeamResponse($npc_team->getReferralCode()->getCode());
    }
}

