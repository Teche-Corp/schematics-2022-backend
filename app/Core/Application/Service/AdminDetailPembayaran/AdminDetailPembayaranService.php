<?php

namespace App\Core\Application\Service\AdminDetailPembayaran;

use App\Core\Domain\Models\NLC\Team\NlcTeam;
use App\Core\Domain\Models\NLC\Team\NlcTeamId;
use App\Core\Domain\Models\NPC\Team\NpcTeam;
use App\Core\Domain\Models\NPC\Team\NpcTeamId;
use App\Core\Domain\Models\NST\NstOrder\NstOrder;
use App\Core\Domain\Models\NST\NstOrder\NstOrderId;
use App\Core\Domain\Models\Pembayaran\Pembayaran;
use App\Core\Domain\Models\Pembayaran\PembayaranId;
use App\Core\Domain\Models\Pembayaran\TipePembayaran;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrder;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderId;
use App\Core\Domain\Models\User\User;
use App\Core\Domain\Repository\NlcMemberRepositoryInterface;
use App\Core\Domain\Repository\NlcTeamRepositoryInterface;
use App\Core\Domain\Repository\NpcMemberRepositoryInterface;
use App\Core\Domain\Repository\NpcTeamRepositoryInterface;
use App\Core\Domain\Repository\NstOrderRepositoryInterface;
use App\Core\Domain\Repository\PembayaranRepositoryInterface;
use App\Core\Domain\Repository\ReevaOrderRepositoryInterface;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Exceptions\SchematicsException;
use Exception;

class AdminDetailPembayaranService
{
    private PembayaranRepositoryInterface $pembayaran_repository;
    private UserRepositoryInterface $user_repository;
    private NlcTeamRepositoryInterface $nlc_team_repository;
    private NlcMemberRepositoryInterface $nlc_member_repository;
    private NpcTeamRepositoryInterface $npc_team_repository;
    private NpcMemberRepositoryInterface $npc_member_repository;
    private ReevaOrderRepositoryInterface $reeva_order_repository;
    private NstOrderRepositoryInterface $nst_order_repository;

    /**
     * @param PembayaranRepositoryInterface $pembayaran_repository
     * @param UserRepositoryInterface $user_repository
     * @param NlcTeamRepositoryInterface $nlc_team_repository
     * @param NlcMemberRepositoryInterface $nlc_member_repository
     * @param NpcTeamRepositoryInterface $npc_team_repository
     * @param NpcMemberRepositoryInterface $npc_member_repository
     * @param ReevaOrderRepositoryInterface $reeva_order_repository
     * @param NstOrderRepositoryInterface $nst_order_repository
     */
    public function __construct(PembayaranRepositoryInterface $pembayaran_repository, UserRepositoryInterface $user_repository, NlcTeamRepositoryInterface $nlc_team_repository, NlcMemberRepositoryInterface $nlc_member_repository, NpcTeamRepositoryInterface $npc_team_repository, NpcMemberRepositoryInterface $npc_member_repository, ReevaOrderRepositoryInterface $reeva_order_repository, NstOrderRepositoryInterface $nst_order_repository)
    {
        $this->pembayaran_repository = $pembayaran_repository;
        $this->user_repository = $user_repository;
        $this->nlc_team_repository = $nlc_team_repository;
        $this->nlc_member_repository = $nlc_member_repository;
        $this->npc_team_repository = $npc_team_repository;
        $this->npc_member_repository = $npc_member_repository;
        $this->reeva_order_repository = $reeva_order_repository;
        $this->nst_order_repository = $nst_order_repository;
    }

    /**
     * @param AdminDetailPembayaranRequest $request
     * @return AdminDetailPembayaranTeamResponse|AdminDetailPembayaranTicketResponse
     * @throws Exception
     */
    public function execute(AdminDetailPembayaranRequest $request): AdminDetailPembayaranTeamResponse|AdminDetailPembayaranTicketResponse
    {
        $pembayaran = $this->pembayaran_repository->find(new PembayaranId($request->getPembayaranId()));
        switch ($pembayaran->getTipePembayaran()) {
            case TipePembayaran::NLC_TEAM:
                $team = $this->nlc_team_repository->find(new NlcTeamId($pembayaran->getSubjectId()->toString()));
                $ketua = $this->nlc_member_repository->findKetuaByTeamId($team->getId());
                $user = $this->user_repository->find($ketua->getUserId());

                return $this->constructTeamResponse($pembayaran, $team, $user);
            case TipePembayaran::NPC_TEAM:
                $team = $this->npc_team_repository->find(new NpcTeamId($pembayaran->getSubjectId()->toString()));
                $ketua = $this->npc_member_repository->findKetuaByTeamId($team->getId());
                $user = $this->user_repository->find($ketua->getUserId());

                return $this->constructTeamResponse($pembayaran, $team, $user);
            case TipePembayaran::NST_ORDER:
                $order = $this->nst_order_repository->find(new NstOrderId($pembayaran->getSubjectId()->toString()));
                $user = $this->user_repository->find($order->getUserId());

                return $this->constructTicketResponse($pembayaran, $order, $user);
            case TipePembayaran::REEVA_ORDER:
                $order = $this->reeva_order_repository->find(new ReevaOrderId($pembayaran->getSubjectId()->toString()));
                $user = $this->user_repository->find($order->getUserId());

                return $this->constructTicketResponse($pembayaran, $order, $user);
            default:
                SchematicsException::throw("Invalid pembayaran enum", 8000);
        }
    }

    private function constructTeamResponse(Pembayaran $pembayaran, NpcTeam|NlcTeam $team, User $user): AdminDetailPembayaranTeamResponse
    {
        return new AdminDetailPembayaranTeamResponse(
            $pembayaran->getTipePembayaran()->value,
            $pembayaran->getTipeBank()->value,
            $team->getNamaTeam(),
            $user->getName(),
            $user->getNoTelp(),
            $pembayaran->getBuktiBayarUrl(),
            $team->getBiaya()
        );
    }

    private function constructTicketResponse(Pembayaran $pembayaran, NstOrder|ReevaOrder $order, User $user): AdminDetailPembayaranTicketResponse
    {
        return new AdminDetailPembayaranTicketResponse(
            $pembayaran->getTipePembayaran()->value,
            $pembayaran->getTipeBank()->value,
            $user->getName(),
            $user->getNoTelp(),
            $pembayaran->getBuktiBayarUrl(),
            $order->getBiaya()
        );
    }
}
