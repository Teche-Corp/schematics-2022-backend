<?php

namespace App\Http\Controllers;

use App\Core\Application\Service\CheckVoucherCode\CheckVoucherCodeRequest;
use App\Core\Application\Service\CheckVoucherCode\CheckVoucherCodeService;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function checkVoucherCode(Request $request, CheckVoucherCodeService $service){
        $input = new CheckVoucherCodeRequest(
            $request->input('kode'),
            $request->input('region'),
            $request->input('tipe'),
            $request->input('jumlah')
        );
        $service->execute($input);
        return $this->success();
    }
}
