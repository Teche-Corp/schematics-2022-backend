<?php

namespace App\Infrastrucutre\Repository;

use App\Core\Domain\Models\Email;
use App\Core\Domain\Models\NST\NstOrder\NstOrderId;
use App\Core\Domain\Models\NST\NstTicket\NstTicket;
use App\Core\Domain\Models\NST\NstTicket\NstTicketId;
use App\Core\Domain\Repository\NstTicketRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SqlNstTicketRepository implements NstTicketRepositoryInterface
{

    /** 
     * @throws Exception
     */
    public function find(NstTicketId $id): ?NstTicket
    {
        $row = DB::table('nst_ticket')->where('id', $id->toString())->first();

        if(!$row) return null;

        return $this->constructFromRow([$row])(0);
    }

    /** 
     * @throws Exception
     */
    public function getByNstOrderId(NstOrderId $nst_order_id): array
    {
        $rows = DB::table('nst_ticket')->where('nst_order_id', $nst_order_id->toString())->get();

        return $this->constructFromRow($rows->all());
    }

    /**
     * @throws Exception
     */
    private function constructFromRow(array $rows): array
    {
        $nst_ticket = [];
        foreach ($rows as $row) {
                $nst_ticket[] = new NstTicket(
                new NstTicketId($row->id),
                new NstOrderId($row->nst_order_id),
                $row->name,
                new Email($row->email),
                $row->no_telp,
                $row->alamat,
                $row->jenis_vaksinasi,
                $row->bukti_vaksin_url,
                $row->is_used
            );
        }
        return $nst_ticket;
    }

    public function persist(NstTicket $nst_ticket): void
    {
        DB::table('nst_ticket')->upsert(
            [
                "id" => $nst_ticket->getId()->toString(),
                "nst_order_id" => $nst_ticket->getNstOrderId()->toString(),
                "name" => $nst_ticket->getName(),
                "email" => $nst_ticket->getEmail()->toString(),
                "no_telp" => $nst_ticket->getNoTelp(),
                "alamat" => $nst_ticket->getAlamat(),
                "jenis_vaksinasi" => $nst_ticket->getJenisVaksinasi(),
                "bukti_vaksin_url" => $nst_ticket->getBuktiVaksinUrl(),
                "is_used" => $nst_ticket->isUsed(),
            ], 'id'
        );
    }
}
