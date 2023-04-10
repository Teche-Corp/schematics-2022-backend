<?php

namespace App\Infrastrucutre\Repository;

use App\Core\Domain\Models\Voucher\Voucher;
use App\Core\Domain\Models\Voucher\VoucherId;
use App\Core\Domain\Repository\VoucherRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SqlVoucherRepository implements VoucherRepositoryInterface
{
    public function persist(Voucher $voucher): void
    {
        DB::table('vouchers')->upsert([
            'id' => $voucher->getId()->toString(),
            'kode' => $voucher->getKode(),
            'potongan' => $voucher->getPotongan(),
            'kuota' => $voucher->getKuota(),
            'region' => $voucher->getRegion(),
            'start_time' => $voucher->getStartTime()->toDateTimeString(),
            'end_time' => $voucher->getEndTime()->toDateTimeString(),
            'tipe' => $voucher->getTipe()
        ], ['id']);
    }

    /**
     * @throws Exception
     */
    public function find(VoucherId $id): ?Voucher
    {
        $row = DB::table('vouchers')->where('id', $id->toString())->first();

        if (!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws Exception
     */
    public function findByKode(string $kode): ?Voucher
    {
        $row = DB::table('vouchers')->where('kode', $kode)->first();

        if (!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws Exception
     */
    private function constructFromRow($row): Voucher
    {
        return new Voucher(
            new VoucherId($row->id),
            $row->kode,
            $row->potongan,
            $row->kuota,
            $row->region,
            $row->start_time,
            $row->end_time,
            $row->tipe
        );
    }
}
