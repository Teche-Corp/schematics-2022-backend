<?php

namespace App\Core\Application\Service\AdminListPembayaranTicket;

use App\Core\Application\Service\PaginationResponse;
use Illuminate\Support\Facades\DB;
use function ceil;
use function collect;

class AdminListPembayaranTicketService
{
    public function execute(AdminListPembayaranTicketRequest $request, TipeListPembayaranTicket $tipe_list_pembayaran_ticket): PaginationResponse
    {
        $query_results = DB::select(
            "
            select
                   p.id as pembayaran_id,
                   o.id as order_id,
                   p.status as status_pembayaran,
                   u.name as nama_user_pemesan,
                   tipe_bank,
                   count(1) as jumlah_ticket
            from pembayaran p
            inner join {$tipe_list_pembayaran_ticket->value}_order o, {$tipe_list_pembayaran_ticket->value}_ticket t, user u
            where p.tipe_pembayaran = '{$tipe_list_pembayaran_ticket->value}_order'
                and o.id = p.subject_id
                and t.{$tipe_list_pembayaran_ticket->value}_order_id = o.id
                and u.id = o.user_id
            group by pembayaran_id, order_id, status_pembayaran, nama_user_pemesan, tipe_bank
            union
            select
                   null as pembayaran_id,
                   o2.id as order_id,
                   'belum_bayar' as status_pembayaran,
                   u2.name as nama_user_pemesan,
                   'belum_bayar' as tipe_bank,
                   0 as jumlah_ticket
            from {$tipe_list_pembayaran_ticket->value}_order o2 inner join {$tipe_list_pembayaran_ticket->value}_ticket t2, user u2
            where not exists(select 1 from pembayaran p2 where p2.tipe_pembayaran = '{$tipe_list_pembayaran_ticket->value}_order' and p2.subject_id = o2.id)
                and t2.{$tipe_list_pembayaran_ticket->value}_order_id = o2.id
                and u2.id = o2.user_id
            "
        );
        $query_collection = collect($query_results);
        $data_per_page = $query_collection
            ->forPage($request->getPage(), $request->getPerPage())
            ->map(function ($q) {
                return new AdminListPembayaranTicketResponse(
                    $q->pembayaran_id,
                    $q->order_id,
                    $q->status_pembayaran,
                    $q->tipe_bank,
                    $q->nama_user_pemesan,
                    $q->jumlah_ticket
                );
            })->values()->all();
        return new PaginationResponse(
            $data_per_page,
            $request->getPage(),
            ceil($query_collection->count() / $request->getPerPage())
        );
    }
}
