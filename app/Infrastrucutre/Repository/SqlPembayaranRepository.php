<?php

namespace App\Infrastrucutre\Repository;

use App\Core\Domain\Models\Pembayaran\Pembayaran;
use App\Core\Domain\Models\Pembayaran\PembayaranId;
use App\Core\Domain\Models\Pembayaran\StatusPembayaran;
use App\Core\Domain\Models\Pembayaran\SubjectId;
use App\Core\Domain\Models\Pembayaran\TipeBank;
use App\Core\Domain\Models\Pembayaran\TipePembayaran;
use App\Core\Domain\Repository\PembayaranRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SqlPembayaranRepository implements PembayaranRepositoryInterface
{

    /**
     * @throws Exception
     */
    public function find(PembayaranId $id): ?Pembayaran
    {
        $row = DB::table('pembayaran')->where('id', $id->toString())->first();

        if(!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws Exception
     */
    public function findBySubjectIdAndTipePembayaran(SubjectId $subject_id, TipePembayaran $tipe_pembayaran): ?Pembayaran
    {
        $row = DB::table('pembayaran')
            ->where('subject_id', $subject_id->toString())
            ->where('tipe_pembayaran', $tipe_pembayaran->value)
            ->first();

        if(!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws Exception
     */
    private function constructFromRow($row): Pembayaran
    {
        return new Pembayaran(
            new PembayaranId($row->id),
            StatusPembayaran::from($row->status),
            TipePembayaran::from($row->tipe_pembayaran),
            new SubjectId($row->subject_id),
            TipeBank::from($row->tipe_bank),
            $row->nama_rekening,
            $row->no_rekening,
            $row->bukti_bayar_url
        );
    }

    public function persist(Pembayaran $pembayaran): void
    {
        DB::table('pembayaran')->upsert(
            [
                "id" => $pembayaran->getId()->toString(),
                "status" => $pembayaran->getStatusPembayaran()->value,
                "tipe_pembayaran" => $pembayaran->getTipePembayaran()->value,
                "subject_id" => $pembayaran->getSubjectId()->toString(),
                "tipe_bank" => $pembayaran->getTipeBank()->value,
                "nama_rekening" => $pembayaran->getNamaRekening(),
                "no_rekening" => $pembayaran->getNoRekening(),
                "bukti_bayar_url" => $pembayaran->getBuktiBayarUrl(),
            ], 'id'
        );
    }
}
