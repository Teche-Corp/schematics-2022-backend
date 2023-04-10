<?php

namespace App\Http\Controllers;

use App\Core\Application\Service\CheckVoucherCode\CheckVoucherCodeRequest;
use App\Core\Application\Service\CheckVoucherCode\CheckVoucherCodeService;
use App\Core\Application\Service\RegisterNpcMember\RegisterNpcMemberRequest;
use App\Core\Application\Service\RegisterNpcMember\RegisterNpcMemberService;
use App\Core\Application\Service\RegisterNpcTeam\RegisterNpcTeamRequest;
use App\Core\Application\Service\RegisterNpcTeam\RegisterNpcTeamService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class NpcController extends Controller
{

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function createNpcTeam(Request $request, RegisterNpcTeamService $service, CheckVoucherCodeService $voucher_service): JsonResponse
    {
        $input = new RegisterNpcTeamRequest(
            $request->input('kategori'),
            $request->input('id_provinsi'),
            $request->input('id_kota'),
            $request->input('nama_guru_pendamping'),
            $request->input('no_telp_guru_pendamping'),
            $request->input('nama_team'),
            $request->input('asal_sekolah'),
            $request->input('nisn'),
            $request->file('surat'),
            $request->input('no_telp'),
            $request->input('no_wa'),
            $request->input('id_line'),
            $request->input('alamat'),
            $request->input('info_sch'),
            $request->input('discord_tag'),
            $request->input('kode_voucher'),
        );

        if($request->input('kode_voucher')){
            $voucher_input = new CheckVoucherCodeRequest(
                $request->input('kode_voucher'),
                0,
                'npc_'.$request->input('kategori')
            );
            $voucher_service->execute($voucher_input);
        }

        DB::beginTransaction();
        try {
            $response = $service->execute($input, $request->get('account'));
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        return $this->successWithData($response);
    }

    /**
     * @throws Throwable
     */
    public function registerNpcAnggota(Request $request, RegisterNpcMemberService $service): JsonResponse
    {
        $input = new RegisterNpcMemberRequest(
            $request->input('kode_referral'),
            $request->input('nisn'),
            $request->file('surat'),
            $request->input('no_telp'),
            $request->input('no_wa'),
            $request->input('id_line'),
            $request->input('alamat'),
            $request->input('info_sch'),
            $request->input('discord_tag')
        );
        DB::beginTransaction();
        try {
            $service->execute($input, $request->get('account'));
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $this->success();
    }
}
