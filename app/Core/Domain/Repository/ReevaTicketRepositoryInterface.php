<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderId;
use App\Core\Domain\Models\Reeva\ReevaTicket\ReevaTicket;
use App\Core\Domain\Models\Reeva\ReevaTicket\ReevaTicketId;

interface ReevaTicketRepositoryInterface
{
    public function find(ReevaTicketId $id): ?ReevaTicket;

    public function persist(ReevaTicket $reeva_ticket): void;

    /**
     * @param ReevaOrderId $reeva_order_id
     * @return ReevaTicket[]
     */
    public function getByReevaOrderId(ReevaOrderId $reeva_order_id): array;
    public function findByReevaOrderId(ReevaOrderId $reeva_order_id): ?ReevaTicket;
}
