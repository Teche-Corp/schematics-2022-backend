<?php

namespace App\Http\Middleware;

use App\Exceptions\SchematicsException;
use Closure;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use function config;
use function hash;

class MidtransMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     * @throws Exception
     */
    public function handle(Request $request, Closure $next)
    {
        $this->logToDb($request, "webhook berhasil masuk!");
        if(!$signature_key = $request->input('signature_key')) SchematicsException::throw("signature key not found", 1230, 401);

        if(!$order_id = $request->input('order_id')) {
            $this->logToDb($request, "order_id not sent");
            SchematicsException::throw("order_id not sent", 1231, 401);
        }
        if(!$status_code = $request->input('status_code')) {
            $this->logToDb($request, "status_code not sent");
            SchematicsException::throw("status_code not sent", 1232, 401);
        }
        if(!$gross_amount = $request->input('gross_amount')) {
            $this->logToDb($request, "gross_amount not sent");
            SchematicsException::throw("gross_amount not sent", 1233, 401);
        }

        $hashed_siganture = hash('sha512', $order_id.$status_code.$gross_amount.config('app.midtrans_server_key'));

        if ($hashed_siganture !== $signature_key) {
            $this->logToDb($request, "signature not valid");
            SchematicsException::throw("signature not valid", 1234, 401);
        }
        return $next($request);
    }

    private function logToDb(Request $request, string $context): void
    {
        DB::table('midtrans_exception_log')->insert(
            [
                'midtrans_transaction_id' => $request->input('transaction_id'),
                'pembayaran_id' => $request->input('custom_field1'),
                'context' => $context
            ]
        );
    }
}
