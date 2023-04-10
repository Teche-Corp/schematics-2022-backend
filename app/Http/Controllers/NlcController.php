<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Core\Application\Service\NlcGame\NlcGameService;
use App\Core\Application\Service\Scoreboard\WarmupService;
use App\Core\Application\Service\Scoreboard\PenyisihanService;
use App\Core\Application\Service\UploadFileNlc\UploadFileNlcRequest;
use App\Core\Application\Service\UploadFileNlc\UploadFileNlcService;
use App\Core\Application\Service\NLCInsertBingo\NlcInsertBingoRequest;
use App\Core\Application\Service\NLCInsertBingo\NlcInsertBingoService;
use App\Core\Application\Service\RegisterNlcTeam\RegisterNlcTeamRequest;
use App\Core\Application\Service\RegisterNlcTeam\RegisterNlcTeamService;
use App\Core\Application\Service\CheckVoucherCode\CheckVoucherCodeRequest;
use App\Core\Application\Service\CheckVoucherCode\CheckVoucherCodeService;
use App\Core\Application\Service\RegisterNlcMember\RegisterNlcMemberRequest;
use App\Core\Application\Service\RegisterNlcMember\RegisterNlcMemberService;

class NlcController extends Controller
{

    /**
     * @throws Exception|Throwable
     */
    public function createNlcTeam(Request $request, RegisterNlcTeamService $service, CheckVoucherCodeService $voucher_service): JsonResponse
    {
        // dd($request);
        $input = new RegisterNlcTeamRequest(
            $request->input('region'),
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
            $request->input('jenis_vaksin'),
            $request->file('bukti_twibbon'),
            $request->file('bukti_poster'),
            $request->file('bukti_vaksin'),
            $request->input('kode_voucher')
        );
        if($request->input('kode_voucher')){
            $voucher_input = new CheckVoucherCodeRequest(
                $request->input('kode_voucher'),
                $request->input('region'),
                'nlc'
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
    public function registerNlcAnggota(Request $request, RegisterNlcMemberService $service): JsonResponse
    {
        $input = new RegisterNlcMemberRequest(
            $request->input('kode_referral'),
            $request->input('nisn'),
            $request->file('surat'),
            $request->input('no_telp'),
            $request->input('no_wa'),
            $request->input('id_line'),
            $request->input('alamat'),
            $request->input('info_sch'),
            $request->input('jenis_vaksin'),
            $request->file('bukti_twibbon'),
            $request->file('bukti_poster'),
            $request->file('bukti_vaksin'),
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

    /**
     * @throws Exception
     */
    public function uploadBerkasNlc(Request $request, UploadFileNlcService $service): JsonResponse
    {
        $input = new UploadFileNlcRequest(
            $request->hasFile('bukti_vaksin') ? $request->file('bukti_vaksin') : null,
            $request->hasFile('bukti_poster') ? $request->file('bukti_poster'): null,
            $request->hasFile('bukti_twibbon') ? $request->file('bukti_twibbon'): null
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

    public function uploadBingoNlc(Request $request, NlcInsertBingoService $service) {
        $input = new NlcInsertBingoRequest($request->hasFile('bingo_file') ? $request->file('bingo_file') : null);
        DB::beginTransaction();
        try {
            $service->execute($input, $request->get('account'));
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
        return $this->success();
    }

    public function nlc_game(Request $request, NlcGameService $service): JsonResponse
    {
        $response = $service->execute($request->get('account'));
        return $this->successWithData($response);
    }

    public function scoreboardWarmup(WarmupService $service): JsonResponse
    {
        $response = $service->execute();
        return $this->successWithData($response);
    }

    public function scoreboardPenyisihan(PenyisihanService $service): JsonResponse
    {
        $response = $service->execute();
        return $this->successWithData($response);
    }
}
