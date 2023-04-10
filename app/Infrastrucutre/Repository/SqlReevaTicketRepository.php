<?php

namespace App\Infrastrucutre\Repository;

use App\Core\Domain\Models\Email;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderId;
use App\Core\Domain\Models\Reeva\ReevaTicket\ReevaTicket;
use App\Core\Domain\Models\Reeva\ReevaTicket\ReevaTicketId;
use App\Core\Domain\Repository\ReevaTicketRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SqlReevaTicketRepository implements ReevaTicketRepositoryInterface
{

    /**
     * @throws ValidationException
     */
    public function find(ReevaTicketId $id): ?ReevaTicket
    {
        $row = DB::table('reeva_ticket')->where('id', $id->toString())->first();

        if (!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws ValidationException
     */
    public function getByReevaOrderId(ReevaOrderId $reeva_order_id): array
    {
        $rows = DB::table('reeva_ticket')->where('reeva_order_id', $reeva_order_id->toString())->get();
        $reeva_tickets = [];
        foreach ($rows as $row) {
            $reeva_tickets[] = $this->constructFromRow($row);
        }
        return $reeva_tickets;
    }

    /**
     * @throws ValidationException
     */
    public function findByReevaOrderId(ReevaOrderId $reeva_order_id): ?ReevaTicket
    {
        $row = DB::table('reeva_ticket')->where('reeva_order_id', $reeva_order_id->toString())->first();
        
        if (!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    private function constructFromRow(object $row): ReevaTicket
    {
        return new ReevaTicket(
            new ReevaTicketId($row->id),
            new ReevaOrderId($row->reeva_order_id),
            $row->name,
            new Email($row->email),
            $row->no_telp,
            $row->alamat,
            $row->nik,
            $row->is_used
        );
    }

    public function persist(ReevaTicket $reeva_ticket): void
    {
        DB::table('reeva_ticket')->upsert(
            [
                'id' => $reeva_ticket->getId()->toString(),
                'reeva_order_id' => $reeva_ticket->getReevaOrderId()->toString(),
                'name' => $reeva_ticket->getName(),
                'email' => $reeva_ticket->getEmail()->toString(),
                'no_telp' => $reeva_ticket->getNoTelp(),
                'alamat' => $reeva_ticket->getAlamat(),
                'nik' => $reeva_ticket->getNik(),
                'is_used' => $reeva_ticket->isUsed(),
            ], 'id'
        );
    }
}
