<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\Pembayaran\Pembayaran;
use App\Core\Domain\Models\Pembayaran\PembayaranId;
use App\Core\Domain\Models\Pembayaran\SubjectId;
use App\Core\Domain\Models\Pembayaran\TipePembayaran;

interface PembayaranRepositoryInterface
{
    public function find(PembayaranId $id): ?Pembayaran;

    public function findBySubjectIdAndTipePembayaran(SubjectId $subject_id, TipePembayaran $tipe_pembayaran): ?Pembayaran;

    public function persist(Pembayaran $pembayaran): void;
}
