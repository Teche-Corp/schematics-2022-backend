<?php

namespace App\Core\Application\Service\CreatePembayaran;

use App\Core\Domain\Models\Pembayaran\Pembayaran;
use App\Core\Domain\Models\Pembayaran\PembayaranId;
use App\Core\Domain\Models\Pembayaran\StatusPembayaran;
use App\Core\Domain\Models\Pembayaran\SubjectId;
use App\Core\Domain\Models\Pembayaran\TipeBank;
use App\Core\Domain\Models\Pembayaran\TipePembayaran;
use App\Core\Domain\Models\SchAccount;
use App\Core\Domain\Repository\NlcMemberRepositoryInterface;
use App\Core\Domain\Repository\NlcTeamRepositoryInterface;
use App\Core\Domain\Repository\NpcMemberRepositoryInterface;
use App\Core\Domain\Repository\NpcTeamRepositoryInterface;
use App\Core\Domain\Repository\NstOrderRepositoryInterface;
use App\Core\Domain\Repository\PembayaranRepositoryInterface;
use App\Core\Domain\Repository\ReevaOrderRepositoryInterface;
use App\Exceptions\SchematicsException;
use Exception;
use Illuminate\Support\Facades\Storage;

class CreatePembayaranService
{
    private NlcTeamRepositoryInterface $nlc_team_repository;
    private NlcMemberRepositoryInterface $nlc_member_repository;
    private NpcTeamRepositoryInterface $npc_team_repository;
    private NpcMemberRepositoryInterface $npc_member_repository;
    private PembayaranRepositoryInterface $pembayaran_repository;
    private NstOrderRepositoryInterface $nst_order_repository;
    private ReevaOrderRepositoryInterface $reeva_order_repository;

    /**
     * @param NlcTeamRepositoryInterface $nlc_team_repository
     * @param NlcMemberRepositoryInterface $nlc_member_repository
     * @param NpcTeamRepositoryInterface $npc_team_repository
     * @param NpcMemberRepositoryInterface $npc_member_repository
     * @param PembayaranRepositoryInterface $pembayaran_repository
     * @param NstOrderRepositoryInterface $nst_order_repository
     * @param ReevaOrderRepositoryInterface $reeva_order_repository
     */
    public function __construct(NlcTeamRepositoryInterface $nlc_team_repository, NlcMemberRepositoryInterface $nlc_member_repository, NpcTeamRepositoryInterface $npc_team_repository, NpcMemberRepositoryInterface $npc_member_repository, PembayaranRepositoryInterface $pembayaran_repository, NstOrderRepositoryInterface $nst_order_repository, ReevaOrderRepositoryInterface $reeva_order_repository)
    {
        $this->nlc_team_repository = $nlc_team_repository;
        $this->nlc_member_repository = $nlc_member_repository;
        $this->npc_team_repository = $npc_team_repository;
        $this->npc_member_repository = $npc_member_repository;
        $this->pembayaran_repository = $pembayaran_repository;
        $this->nst_order_repository = $nst_order_repository;
        $this->reeva_order_repository = $reeva_order_repository;
    }

    /**
     * @throws Exception
     */
    public function execute(CreatePembayaranRequest $request, SchAccount $account)
    {
        if ($request->getBuktiBayar()->getSize() > 1048576) {
            SchematicsException::throw("bukti bayar harus dibawah 1Mb", 2043);
        }

        switch ($request->getTipePembayaran()) {
            case TipePembayaran::NLC_TEAM:
                if (!$account->getNlcMemberId()) {
                    SchematicsException::throw("akun belum mendaftar tim nlc", 2040);
                }

                $nlc_member = $this->nlc_member_repository->find($account->getNlcMemberId());
                if (!$nlc_member) {
                    SchematicsException::throw('member nlc team not found', 2041);
                }

                $nlc_team = $this->nlc_team_repository->find($nlc_member->getTeamId());
                if(!$nlc_team) {
                    SchematicsException::throw('team nlc not found', 2042);
                }

                $found_pembayaran = $this->pembayaran_repository->findBySubjectIdAndTipePembayaran(
                    new SubjectId($nlc_team->getId()->toString()),
                    TipePembayaran::NLC_TEAM
                );

                $pembayaran_id = $found_pembayaran != null
                    ? $found_pembayaran->getId()
                    : PembayaranId::generate();

                $path = Storage::putFileAs('NLC/Pembayaran', $request->getBuktiBayar(), $pembayaran_id->toString());
                if (!$path) {
                    SchematicsException::throw('gagal menyimpan bukti bayar nlc team', 2044);
                }

                $pembayaran = new Pembayaran(
                    $pembayaran_id,
                    StatusPembayaran::UNVERIFIED,
                    TipePembayaran::NLC_TEAM,
                    new SubjectId($nlc_team->getId()->toString()),
                    TipeBank::from($request->getNamaBank()),
                    $request->getNamaRekening(),
                    $request->getNoRekening(),
                    $path
                );
                $this->pembayaran_repository->persist($pembayaran);

                $nlc_team->awaitVerification();
                $this->nlc_team_repository->persist($nlc_team);
                break;
            case TipePembayaran::NPC_TEAM:
                if (!$account->getNpcMemberId()) {
                    SchematicsException::throw("akun belum mendaftar tim npc", 2040);
                }

                $npc_member = $this->npc_member_repository->find($account->getNpcMemberId());
                if (!$npc_member) {
                    SchematicsException::throw('member npc team not found', 2041);
                }

                $npc_team = $this->npc_team_repository->find($npc_member->getTeamId());
                if(!$npc_team) {
                    SchematicsException::throw('team npc not found', 2042);
                }

                $found_pembayaran = $this->pembayaran_repository->findBySubjectIdAndTipePembayaran(
                    new SubjectId($npc_team->getId()->toString()),
                    TipePembayaran::NPC_TEAM
                );
                $pembayaran_id = $found_pembayaran != null
                    ? $found_pembayaran->getId()
                    : PembayaranId::generate();

                $path = Storage::putFileAs('NPC/Pembayaran', $request->getBuktiBayar(), $pembayaran_id->toString());
                if (!$path) {
                    SchematicsException::throw('gagal menyimpan bukti bayar npc team', 2044);
                }

                $pembayaran = new Pembayaran(
                    $pembayaran_id,
                    StatusPembayaran::UNVERIFIED,
                    TipePembayaran::NPC_TEAM,
                    new SubjectId($npc_team->getId()->toString()),
                    TipeBank::from($request->getNamaBank()),
                    $request->getNamaRekening(),
                    $request->getNoRekening(),
                    $path
                );
                $this->pembayaran_repository->persist($pembayaran);

                $npc_team->awaitVerification();
                $this->npc_team_repository->persist($npc_team);
                break;
            case TipePembayaran::NST_ORDER:
                $nst_order = $this->nst_order_repository->findByUserId($account->getUserId());
                if (!$nst_order) {
                    SchematicsException::throw("nst order not found", 6004, 404);
                }
                $found_pembayaran = $this->pembayaran_repository->findBySubjectIdAndTipePembayaran(
                    new SubjectId($nst_order->getId()->toString()),
                    TipePembayaran::NST_ORDER
                );
                $pembayaran_id = $found_pembayaran != null
                    ? $found_pembayaran->getId()
                    : PembayaranId::generate();
                $path = Storage::putFileAs("NST/Pembayaran", $request->getBuktiBayar(), $pembayaran_id->toString());
                if (!$path) {
                    SchematicsException::throw("gagal menyimpan bukti bayar nst order", 6005);
                }

                $pembayaran = new Pembayaran(
                    $pembayaran_id,
                    StatusPembayaran::UNVERIFIED,
                    TipePembayaran::NST_ORDER,
                    new SubjectId($nst_order->getId()->toString()),
                    TipeBank::from($request->getNamaBank()),
                    $request->getNamaRekening(),
                    $request->getNoRekening(),
                    $path
                );
                $this->pembayaran_repository->persist($pembayaran);

                $nst_order->awaitingVerification();
                $this->nst_order_repository->persist($nst_order);
                break;
            case TipePembayaran::REEVA_ORDER:
                $reeva_order = $this->reeva_order_repository->findByUserId($account->getUserId());
                if (!$reeva_order) {
                    SchematicsException::throw("reeva order not found", 6004, 404);
                }
                $found_pembayaran = $this->pembayaran_repository->findBySubjectIdAndTipePembayaran(
                    new SubjectId($reeva_order->getId()->toString()),
                    TipePembayaran::REEVA_ORDER
                );
                $pembayaran_id = $found_pembayaran != null
                    ? $found_pembayaran->getId()
                    : PembayaranId::generate();
                $path = Storage::putFileAs("REEVA/Pembayaran", $request->getBuktiBayar(), $pembayaran_id->toString());
                if (!$path) {
                    SchematicsException::throw("gagal menyimpan bukti bayar reeva order", 6005);
                }

                $pembayaran = new Pembayaran(
                    $pembayaran_id,
                    StatusPembayaran::UNVERIFIED,
                    TipePembayaran::REEVA_ORDER,
                    new SubjectId($reeva_order->getId()->toString()),
                    TipeBank::from($request->getNamaBank()),
                    $request->getNamaRekening(),
                    $request->getNoRekening(),
                    $path
                );
                $this->pembayaran_repository->persist($pembayaran);

                $reeva_order->awaitingVerification();
                $this->reeva_order_repository->persist($reeva_order);
                break;
        }
    }
}
