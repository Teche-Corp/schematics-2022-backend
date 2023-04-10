<?php

namespace App\Infrastrucutre\Repository;

use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrder;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderId;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderStatus;
use App\Core\Domain\Models\User\UserId;
use App\Core\Domain\Repository\ReevaOrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SqlReevaOrderRepository implements ReevaOrderRepositoryInterface
{

    /**
     * @throws Exception
     */
    public function find(ReevaOrderId $id): ?ReevaOrder
    {
        $row = DB::table('reeva_order')->where('id', $id->toString())->first();

        if (!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws Exception
     */
    public function findByUserId(UserId $user_id): ?ReevaOrder
    {
        $row = DB::table('reeva_order')->where('user_id', $user_id->toString())->first();

        if (!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws Exception
     */
    private function constructFromRow(object $row): ReevaOrder
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

    public function persist(ReevaOrder $reeva_order): void
    {
        DB::table('reeva_order')->upsert(
            [
                'id' => $reeva_order->getId()->toString(),
                'user_id' => $reeva_order->getUserId()->toString(),
                'status' => $reeva_order->getStatus()->value,
                'jumlah_tiket' => $reeva_order->getJumlahTiket(),
                'biaya' => $reeva_order->getBiaya(),
                'unique_payment_code' => $reeva_order->getUniquePaymentCode(),
                'info_sch' => $reeva_order->getInfoSch(),
                'kode_voucher' => $reeva_order->getKodeVoucher()
            ], 'id'
        );
    }
}
