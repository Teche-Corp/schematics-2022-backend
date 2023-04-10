<?php

namespace App\Core\Application\Service\AdminListPembayaranTeam;

use App\Core\Application\Service\PaginationResponse;
use Illuminate\Support\Facades\DB;

class AdminListPembayaranTeamService
{
    /**
     * @param AdminListPembayaranTeamRequest $request
     * @param TipeListPembayaranTeam $tipe_list_pembayaran
     * @return PaginationResponse
     */
    public function execute(AdminListPembayaranTeamRequest $request, TipeListPembayaranTeam $tipe_list_pembayaran): PaginationResponse
    {
        $datas = explode("_", $tipe_list_pembayaran->value);
        $tipe_list = $datas[0];
        $kategori = $datas[1] ?? "";
        $isAdaKategori = $kategori != "";

        $check_team_kategori_query = ($isAdaKategori) ? 'and t.kategori = \''.$kategori.'\'':'';

        $query = DB::select(
            "
                select p.id as pembayaran_id, name as nama_ketua, tipe_bank as nama_bank, p.status as status_pembayaran, nama_team
                from pembayaran p
                inner join
                    {$tipe_list}_team t,
                    {$tipe_list}_member m,
                    user u
                where
                    p.tipe_pembayaran = '{$tipe_list}_team'
                    and t.id = p.subject_id
                    $check_team_kategori_query
                    and m.team_id = t.id
                    and m.member_type = 'ketua'
                    and u.id = m.user_id
            union
                select null as pembayaran_id, name as nama_ketua, 'belum_bayar' as nama_bank, 'belum_bayar' as status_pembayaran, nama_team
                from {$tipe_list}_team t
                inner join
                    {$tipe_list}_member m,
                    user u
                where
                    not exists (select 1 from pembayaran p where p.tipe_pembayaran = '{$tipe_list}_team'
                                                              and p.subject_id = t.id)
                    and m.team_id = t.id
                    $check_team_kategori_query
                    and u.id = m.user_id
                "
        );

        $query_collection = collect($query);
        $data_per_page = $query_collection
            ->forPage($request->getPage(), $request->getPerPage())
            ->map(function ($query) {
                return new AdminListPembayaranTeamResponse(
                    $query->pembayaran_id,
                    $query->nama_ketua,
                    $query->nama_bank,
                    $query->status_pembayaran,
                    $query->nama_team
                );
            })->values()->all();
        return new PaginationResponse(
            $data_per_page,
            $request->getPage(),
            ceil($query_collection->count() / $request->getPerPage())
        );
    }
}
