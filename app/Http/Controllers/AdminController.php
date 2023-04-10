<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Exceptions\SchematicsException;
use App\Core\Application\Service\AdminVerifyTeamMember\TipeTeamMember;
use App\Core\Application\Service\AdminGetListTeam\AdminGetListTeamType;
use App\Core\Application\Service\AdminGetNlcTeam\AdminGetNlcTeamRequest;
use App\Core\Application\Service\AdminGetNlcTeam\AdminGetNlcTeamService;
use App\Core\Application\Service\AdminGetNpcTeam\AdminGetNpcTeamRequest;
use App\Core\Application\Service\AdminGetNpcTeam\AdminGetNpcTeamService;
use App\Core\Application\Service\AdminGetListTeam\AdminGetListTeamRequest;
use App\Core\Application\Service\AdminGetListTeam\AdminGetListTeamService;
use App\Core\Application\Service\AdminListPembayaranTeam\TipeListPembayaranTeam;
use App\Core\Application\Service\AdminDetailPembayaran\AdminDetailPembayaranRequest;
use App\Core\Application\Service\AdminDetailPembayaran\AdminDetailPembayaranService;
use App\Core\Application\Service\AdminListPembayaranTicket\TipeListPembayaranTicket;
use App\Core\Application\Service\AdminVerifyPembayaran\AdminVerifyPembayaranRequest;
use App\Core\Application\Service\AdminVerifyPembayaran\AdminVerifyPembayaranService;
use App\Core\Application\Service\AdminVerifyTeamMember\AdminVerifyTeamMemberRequest;
use App\Core\Application\Service\AdminVerifyTeamMember\AdminVerifyTeamMemberService;
use App\Core\Application\Service\AdminListPembayaranTeam\AdminListPembayaranTeamRequest;
use App\Core\Application\Service\AdminListPembayaranTeam\AdminListPembayaranTeamService;
use App\Core\Application\Service\AdminListPembayaranTicket\AdminListPembayaranTicketRequest;
use App\Core\Application\Service\AdminListPembayaranTicket\AdminListPembayaranTicketService;
use App\Core\Application\Service\GetReevaTicketDetail\GetReevaTicketDetailRequest;
use App\Core\Application\Service\GetReevaTicketDetail\GetReevaTicketDetailService;
use App\Core\Application\Service\UseReevaTicket\UseReevaTicketRequest;
use App\Core\Application\Service\UseReevaTicket\UseReevaTicketService;

class AdminController extends Controller
{
    /**
     * @throws Exception
     */
    public function verifyPembayaran(Request $request, AdminVerifyPembayaranService $service): JsonResponse
    {
        $input = new AdminVerifyPembayaranRequest(
            $request->input('pembayaran_id'),
            $request->input('verification_status')
        );
        DB::beginTransaction();
        try {
            $service->execute($input, $request->get('account'));
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
        return $this->success();
    }

    /**
     * @throws Exception
     */
    public function getListPembayaranNpcJunior(Request $request, AdminListPembayaranTeamService $service): JsonResponse
    {
        $input = new AdminListPembayaranTeamRequest($request->input('page'), $request->input('per_page'));
        return $this->successWithData(
            $service->execute($input, TipeListPembayaranTeam::NPC_JUNIOR)
        );
    }

    /**
     * @throws Exception
     */
    public function getListPembayaranNpcSenior(Request $request, AdminListPembayaranTeamService $service): JsonResponse
    {
        $input = new AdminListPembayaranTeamRequest($request->input('page'), $request->input('per_page'));
        return $this->successWithData(
            $service->execute($input, TipeListPembayaranTeam::NPC_SENIOR)
        );
    }

    /**
     * @throws Exception
     */
    public function getListPembayaranNlc(Request $request, AdminListPembayaranTeamService $service): JsonResponse
    {
        $input = new AdminListPembayaranTeamRequest($request->input('page'), $request->input('per_page'));
        return $this->successWithData(
            $service->execute($input, TipeListPembayaranTeam::NLC)
        );
    }

    public function getListPembayaranNst(Request $request, AdminListPembayaranTicketService $service): JsonResponse
    {
        $input = new AdminListPembayaranTicketRequest($request->input('page'), $request->input('per_page'));
        return $this->successWithData(
            $service->execute($input, TipeListPembayaranTicket::NST)
        );
    }

    public function getListPembayaranReeva(Request $request, AdminListPembayaranTicketService $service): JsonResponse
    {
        $input = new AdminListPembayaranTicketRequest($request->input('page'), $request->input('per_page'));
        return $this->successWithData(
            $service->execute($input, TipeListPembayaranTicket::REEVA)
        );
    }

    /**
     * @throws SchematicsException
     */
    public function getNpcTeam(Request $request, AdminGetNpcTeamService $service): JsonResponse
    {
        $input = new AdminGetNpcTeamRequest($request->input('team_id'));
        return $this->successWithData(
            $service->execute($input)
        );
    }

    /**
     * @throws SchematicsException
     */
    public function getNlcTeam(Request $request, AdminGetNlcTeamService $service): JsonResponse
    {
        $input = new AdminGetNlcTeamRequest($request->input('team_id'));
        return $this->successWithData(
            $service->execute($input)
        );
    }

    /**
     * @throws Exception
     */
    public function verifyNlcMember(Request $request, AdminVerifyTeamMemberService $service): JsonResponse
    {
        $input = new AdminVerifyTeamMemberRequest(
            $request->input('member_id'),
            $request->input('new_status')
        );
        DB::beginTransaction();
        try {
            $service->execute($input, $request->get('account'), TipeTeamMember::NLC);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
        return $this->success();
    }

    /**
     * @throws Exception
     */
    public function verifyNpcMember(Request $request, AdminVerifyTeamMemberService $service): JsonResponse
    {
        $input = new AdminVerifyTeamMemberRequest(
            $request->input('member_id'),
            $request->input('new_status')
        );
        DB::beginTransaction();
        try {
            $service->execute($input, $request->get('account'), TipeTeamMember::NPC);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
        return $this->success();
    }

    /**
     * @throws Exception
     */
    public function listNlcTeam(Request $request, AdminGetListTeamService $service): JsonResponse
    {
        return $this->successWithData($service->execute(
            new AdminGetListTeamRequest(
            $request->input('page'),
            $request->input('per_page')
            ),
            AdminGetListTeamType::NLC
        ));
    }

    /**
     * @throws Exception
     */
    public function listNpcSeniorTeam(Request $request, AdminGetListTeamService $service): JsonResponse
    {
        return $this->successWithData($service->execute(
            new AdminGetListTeamRequest(
            $request->input('page'),
            $request->input('per_page')
            ),
            AdminGetListTeamType::NPC_SENIOR
        ));
    }

    /**
     * @throws Exception
     */
    public function listNpcJuniorTeam(Request $request, AdminGetListTeamService $service): JsonResponse
    {
        return $this->successWithData($service->execute(
            new AdminGetListTeamRequest(
            $request->input('page'),
            $request->input('per_page')
            ),
            AdminGetListTeamType::NPC_JUNIOR
        ));
    }

    /**
     * @throws Exception
     */
    public function getDetailPembayaran(Request $request, AdminDetailPembayaranService $service): JsonResponse
    {
        return $this->successWithData($service->execute(
            new AdminDetailPembayaranRequest(
                $request->input('pembayaran_id'),
            )
        ));
    }

    public function getDetailTicketReeva(Request $request, GetReevaTicketDetailService $service): JsonResponse
    {
        return $this->successWithData($service->execute(
            new GetReevaTicketDetailRequest(
                $request->input('ticket_id'),
            )
        ));
    }

    public function useReevaTicket(Request $request, UseReevaTicketService $service): JsonResponse
    {
        $service->execute(new UseReevaTicketRequest($request->input('ticket_id')));
        return $this->success();
    }
}
