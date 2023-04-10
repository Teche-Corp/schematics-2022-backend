<?php

namespace App\Http\Controllers;

use App\Core\Application\Service\CreatePembayaran\CreatePembayaranRequest;
use App\Core\Application\Service\CreatePembayaran\CreatePembayaranService;
use App\Core\Domain\Models\Pembayaran\TipePembayaran;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    /**
     * @throws Exception
     */
    public function createPembayaran(Request $request, CreatePembayaranService $service): JsonResponse
    {
        $input = new CreatePembayaranRequest(
            $request->file('bukti_bayar'),
            $request->input('nama_rekening'),
            $request->input('no_rekening'),
            $request->input('nama_bank'),
            TipePembayaran::from($request->input('tipe_pembayaran'))
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
}
