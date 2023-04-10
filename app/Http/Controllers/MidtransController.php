<?php

namespace App\Http\Controllers;

use App\Core\Application\Service\HandleMidtransWebhook\HandleMidtransWebhookRequest;
use App\Core\Application\Service\HandleMidtransWebhook\HandleMidtransWebhookService;
use App\Core\Application\Service\RequestMidtransPayment\RequestMidtransPaymentService;
use App\Core\Application\Service\RequestMidtransPayment\RequestMidtransPaymentType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class MidtransController extends Controller
{
    /**
     * @throws Exception
     */
    public function handlePayment(Request $request, HandleMidtransWebhookService $service): JsonResponse
    {
        $input = new HandleMidtransWebhookRequest(
            $request->input('transaction_id'),
            $request->input('transaction_status'),
            $request->input('gross_amount'),
            $request->input('custom_field1'),
        );
        DB::beginTransaction();
        try {
            $service->execute($input);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
        return $this->success();
    }

    /**
     * @throws Throwable
     */
    public function requestPaymentLinkReeva(Request $request, RequestMidtransPaymentService $service): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $service->execute($request->get('account'), RequestMidtransPaymentType::REEVA_ORDER);
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $this->successWithData($data);
    }

    /**
     * @throws Throwable
     */
    public function requestPaymentLinkNst(Request $request, RequestMidtransPaymentService $service): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $service->execute($request->get('account'), RequestMidtransPaymentType::NST_ORDER);
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $this->successWithData($data);
    }
}
