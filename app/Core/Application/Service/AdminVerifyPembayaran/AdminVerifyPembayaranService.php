<?php

namespace App\Core\Application\Service\AdminVerifyPembayaran;

use App\Core\Application\Mail\ReevaEmailTicketVerification;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Core\Domain\Models\SchAccount;
use App\Core\Domain\Models\User\UserId;
use App\Exceptions\SchematicsException;
use App\Core\Domain\Models\NLC\Team\NlcTeamId;
use App\Core\Domain\Models\NPC\Team\NpcTeamId;
use App\Core\Domain\Models\NST\NstOrder\NstOrderId;
use App\Core\Domain\Models\Pembayaran\PembayaranId;
use App\Core\Domain\Models\Pembayaran\TipePembayaran;
use App\Core\Domain\Models\Pembayaran\StatusPembayaran;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderId;
use App\Core\Domain\Repository\NlcTeamRepositoryInterface;
use App\Core\Domain\Repository\NpcTeamRepositoryInterface;
use App\Core\Domain\Repository\NstOrderRepositoryInterface;
use App\Core\Domain\Repository\PembayaranRepositoryInterface;
use App\Core\Domain\Repository\ReevaOrderRepositoryInterface;
use App\Core\Domain\Repository\ReevaTicketRepositoryInterface;

class AdminVerifyPembayaranService
{
    private PembayaranRepositoryInterface $pembayaran_repository;
    private NlcTeamRepositoryInterface $nlc_team_repository;
    private NpcTeamRepositoryInterface $npc_team_repository;
    private NstOrderRepositoryInterface $nst_order_repository;
    private ReevaOrderRepositoryInterface $reeva_order_repository;
    private ReevaTicketRepositoryInterface $reeva_ticket_repository;
    private UserRepositoryInterface $user_repository;

    /**
     * @param PembayaranRepositoryInterface $pembayaran_repository
     * @param NlcTeamRepositoryInterface $nlc_team_repository
     * @param NpcTeamRepositoryInterface $npc_team_repository
     * @param NstOrderRepositoryInterface $nst_order_repository
     * @param ReevaOrderRepositoryInterface $reeva_order_repository
     * @param ReevaTicketRepositoryInterface $reeva_ticket_repository
     * @param UserRepositoryInterface $user_repository
     */
    public function __construct(PembayaranRepositoryInterface $pembayaran_repository, NlcTeamRepositoryInterface $nlc_team_repository, NpcTeamRepositoryInterface $npc_team_repository, NstOrderRepositoryInterface $nst_order_repository, ReevaOrderRepositoryInterface $reeva_order_repository, ReevaTicketRepositoryInterface $reeva_ticket_repository, UserRepositoryInterface $user_repository)
    {
        $this->pembayaran_repository = $pembayaran_repository;
        $this->nlc_team_repository = $nlc_team_repository;
        $this->npc_team_repository = $npc_team_repository;
        $this->nst_order_repository = $nst_order_repository;
        $this->reeva_order_repository = $reeva_order_repository;
        $this->reeva_ticket_repository = $reeva_ticket_repository;
        $this->user_repository = $user_repository;
    }

    /**
     * @throws Exception
     */
    public function execute(AdminVerifyPembayaranRequest $request, SchAccount $account)
    {
        $admin = $this->user_repository->find($account->getUserId());
        if (!$admin) {
            SchematicsException::throw("akun admin not found", 1006, 404);
        }

        $pembayaran = $this->pembayaran_repository->find(new PembayaranId($request->getPembayaranId()));
        if (!$pembayaran) {
            SchematicsException::throw("pembayaran not found", 2050);
        }

        if($pembayaran->getStatusPembayaran() == StatusPembayaran::VERIFIED) {
            SchematicsException::throw("pembayaran already verified", 2051);
        }
        $pembayaran->setStatusPembayaran(StatusPembayaran::from($request->getVerificationStatus()));

        switch ($pembayaran->getTipePembayaran()) {
            case TipePembayaran::NLC_TEAM:
                $nlc_team = $this->nlc_team_repository->find(new NlcTeamId($pembayaran->getSubjectId()->toString()));
                if (!$nlc_team) {
                    SchematicsException::throw("nlc team not found", 6007, 404);
                }
                if ($pembayaran->getStatusPembayaran() === StatusPembayaran::VERIFIED) {
                    $nlc_team->verifiedPayment();
                } elseif ($pembayaran->getStatusPembayaran() === StatusPembayaran::NEED_REVISION) {
                    $nlc_team->needRevision();
                }
                $this->nlc_team_repository->persist($nlc_team);
                break;
            case TipePembayaran::NPC_TEAM:
                $npc_team = $this->npc_team_repository->find(new NpcTeamId($pembayaran->getSubjectId()->toString()));
                if (!$npc_team) {
                    SchematicsException::throw("npc team not found", 6008, 404);
                }
                if ($pembayaran->getStatusPembayaran() === StatusPembayaran::VERIFIED) {
                    $npc_team->verifiedPayment();
                } elseif ($pembayaran->getStatusPembayaran() === StatusPembayaran::NEED_REVISION) {
                    $npc_team->needRevision();
                }
                $this->npc_team_repository->persist($npc_team);
                break;
            case TipePembayaran::NST_ORDER:
                $nst_order = $this->nst_order_repository->find(new NstOrderId($pembayaran->getSubjectId()->toString()));
                if (!$nst_order) {
                    SchematicsException::throw("nst order not found", 6009, 404);
                }
                if ($pembayaran->getStatusPembayaran() === StatusPembayaran::VERIFIED) {
                    $nst_order->activate();
                } elseif ($pembayaran->getStatusPembayaran() === StatusPembayaran::NEED_REVISION) {
                    $nst_order->needRevision();
                }
                $this->nst_order_repository->persist($nst_order);
                break;
            case TipePembayaran::REEVA_ORDER:
                $reeva_order = $this->reeva_order_repository->find(new ReevaOrderId($pembayaran->getSubjectId()->toString()));
                if (!$reeva_order) {
                    SchematicsException::throw("reeva order not found", 6010, 404);
                }
                $reeva_ticket = $this->reeva_ticket_repository->findByReevaOrderId(new ReevaOrderId($pembayaran->getSubjectId()->toString()));
                if ($pembayaran->getStatusPembayaran() === StatusPembayaran::VERIFIED) {
                    $reeva_order->activate();
                    Mail::to($reeva_ticket->getEmail()->toString())->send(new ReevaEmailTicketVerification(
                        $reeva_ticket->getName(),
                        $reeva_ticket->getEmail()->toString(),
                        $reeva_ticket->getNik(),
                        $reeva_order->getJumlahTiket(),
                        $reeva_order->getBiaya(),
                        $reeva_order->getStatus()->value,
                    ));
                } elseif ($pembayaran->getStatusPembayaran() === StatusPembayaran::NEED_REVISION) {
                    $reeva_order->needRevision();
                }
                $this->reeva_order_repository->persist($reeva_order);
                break;
        }
        $this->pembayaran_repository->persist($pembayaran);

        DB::table('admin_log')->insert(
            [
                'admin_user_id' => $admin->getId()->toString(),
                'admin_name' => $admin->getName(),
                'subject_type' => "reeva_order",
                'subject_id' => $request->getPembayaranId(),
                'action' => "verify_order_".$request->getVerificationStatus()
            ]
        );
    }
}
