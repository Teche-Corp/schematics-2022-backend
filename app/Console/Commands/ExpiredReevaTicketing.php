<?php

namespace App\Console\Commands;

use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrder;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderId;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderStatus;
use App\Core\Domain\Models\User\UserId;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class ExpiredReevaTicketing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:expired-missed-reeva-ticketing-deadline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used for expiring missed Reeva ticket deadline';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function handle(): int
    {
        DB::beginTransaction();
        try{
            $reeva_order_rows = DB::table('reeva_order')->get();
            $reeva_order_payload = array();

            /** ngecek team npc */
            foreach ($reeva_order_rows as $row) {

                $updated_at = Carbon::createFromFormat('Y-m-d H:i:s', $row->updated_at);
                if (($row->status == ReevaOrderStatus::AWAITING_PAYMENT->value || $row->status == ReevaOrderStatus::NEED_REVISION->value)
                    && $updated_at->diffInDays(Carbon::now()) >= 3
                ){
                    $reeva_order = $this->constructReevaOrderFromRow($row);
                    $reeva_order->expired();
                    $reeva_order_payload[] = $this->transformReevaOrderToPayload($reeva_order);
                }
            }
            if($reeva_order_payload){
                DB::table('reeva_order')->upsert($reeva_order_payload, 'id');
            }

        }catch (Throwable $exception){
            DB::rollBack();
            throw $exception;
        }
        DB::commit();

        return 0;
    }

    private function transformReevaOrderToPayload(ReevaOrder $reeva_order): array
    {
        return [
            "id" => $reeva_order->getId()->toString(),
            "user_id" => $reeva_order->getUserId()->toString(),
            "status" => $reeva_order->getStatus()->value,
            "jumlah_tiket" => $reeva_order->getJumlahTiket(),
            "biaya" => $reeva_order->getBiaya(),
            "unique_payment_code" => $reeva_order->getUniquePaymentCode(),
            "info_sch" => $reeva_order->getInfoSch(),
            "kode_voucher" => $reeva_order->getKodeVoucher()
        ];
    }

    private function constructReevaOrderFromRow(object $row): ReevaOrder
    {
        return new ReevaOrder( 
            new ReevaOrderId($row->id),
            new UserId($row->user_id),
            ReevaOrderStatus::from($row->status),
            $row->jumlah_tiket,
            $row->biaya,
            $row->unique_payment_code,
            $row->info_sch,
            $row->kode_voucher
        );
    }

}
