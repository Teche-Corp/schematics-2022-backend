<?php

namespace App\Infrastrucutre\Repository;

use App\Core\Domain\Models\NST\NstOrder\NstOrder;
use App\Core\Domain\Models\NST\NstOrder\NstOrderId;
use App\Core\Domain\Models\NST\NstOrder\NstOrderStatus;
use App\Core\Domain\Models\User\UserId;
use App\Core\Domain\Repository\NstOrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SqlNstOrderRepository implements NstOrderRepositoryInterface
{

    /**
     * @throws Exception
     */
    public function find(NstOrderId $id): ?NstOrder
    {
        $row = DB::table('nst_order')->where('id', $id->toString())->first();

        if(!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws Exception
     */
    public function findByUserId(UserId $user_id): ?NstOrder
    {
        $row = DB::table('nst_order')->where('user_id', $user_id->toString())->first();

        if(!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws Exception
     */
    private function constructFromRow($row): NstOrder
    {
        return new NstOrder(
            new NstOrderId($row->id),
            new UserId($row->user_id),
            NstOrderStatus::from($row->status),
            $row->jumlah_tiket,
            $row->biaya,
            $row->unique_payment_code,
            $row->info_sch,
        );
    }

    public function persist(NstOrder $nst_order): void
    {
        DB::table('nst_order')->upsert(
            [
                "id" => $nst_order->getId()->toString(),
                "user_id" => $nst_order->getUserId()->toString(),
                "status" => $nst_order->getStatus()->value,
                "jumlah_tiket" => $nst_order->getJumlahTiket(),
                "biaya" => $nst_order->getBiaya(),
                "unique_payment_code" => $nst_order->getUniquePaymentCode(),
                "info_sch" => $nst_order->getInfoSch(),
            ], 'id'
        );
    }
}
