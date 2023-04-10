<?php

namespace App\Http\Controllers;

use App\Core\Application\Service\CheckVoucherCode\CheckVoucherCodeRequest;
use App\Core\Application\Service\CheckVoucherCode\CheckVoucherCodeService;
use App\Core\Application\Service\OrderReevaTicket\OrderReevaTicketRequest;
use App\Core\Application\Service\OrderReevaTicket\OrderReevaTicketService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ReevaController extends Controller
{
    /**
     * @throws Exception
     */
    public function orderReevaTicket(Request $request, OrderReevaTicketService $service, CheckVoucherCodeService $voucher_service): JsonResponse
    {
        $input = new OrderReevaTicketRequest(
                $request->input('name'),
                $request->input('no_telp'),
                $request->input('email'),
                $request->input('alamat'),
                $request->input('nik'),
                $request->input('jumlah_tiket'),
                $request->input('kode_voucher')
            );

        if($request->input('kode_voucher')){
            $voucher_input = new CheckVoucherCodeRequest(
                $request->input('kode_voucher'),
                0,
                'reeva',
                $request->input('jumlah_tiket')
            );
            $voucher_service->execute($voucher_input);
        }
        
        DB::beginTransaction();
        try {
            $service->execute($input, $request->get('account'), $request->input('info_sch'));
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
        return $this->success();
    }
}
