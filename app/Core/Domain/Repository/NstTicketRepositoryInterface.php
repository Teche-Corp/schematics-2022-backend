<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\NST\NstOrder\NstOrderId;
use App\Core\Domain\Models\NST\NstTicket\NstTicket;
use App\Core\Domain\Models\NST\NstTicket\NstTicketId;

interface NstTicketRepositoryInterface
{
    public function find(NstTicketId $id): ?NstTicket;

    public function persist(NstTicket $nst_ticket): void;

    public function getByNstOrderId(NstOrderId $nst_order_id): array;
}
