<?php

namespace App\Http\Controllers;

use App\Core\Application\Service\OrderNstTicket\OrderNstTicketRequest;
use App\Core\Application\Service\OrderNstTicket\OrderNstTicketService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class NstController extends Controller
{
    /**
     * @throws Exception
     */
    public function orderNstTicket(Request $request, OrderNstTicketService $service): JsonResponse
    {
        $input = new OrderNstTicketRequest(
            $request->input('name'),
            $request->input('no_telp'),
            $request->input('email'),
            $request->input('alamat'),
            $request->input('jumlah_tiket'),
            $request->input('tipe_vaksin'),
            $request->file('bukti_vaksin')
        );
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
