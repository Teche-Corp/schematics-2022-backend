<?php

namespace App\Infrastrucutre\Repository;

use App\Core\Domain\Models\Email;
use App\Core\Domain\Models\NLC\Team\NlcTeam;
use App\Core\Domain\Models\NLC\Team\NlcTeamId;
use App\Core\Domain\Models\NLC\Team\NlcTeamStatus;
use App\Core\Domain\Models\ReferralCode;
use App\Core\Domain\Repository\NlcTeamRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use function ceil;

class SqlNlcTeamRepository implements NlcTeamRepositoryInterface
{

    /**
     * @throws Exception
     */
    public function find(NlcTeamId $id): ?NlcTeam
    {
        $row = DB::table('nlc_team')->where('id', $id->toString())->first();

        if (!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws Exception
     */
    public function findByReferralCode(ReferralCode $code): ?NlcTeam
    {
        $row = DB::table('nlc_team')->where('referral_code', $code->getCode())->first();

        if (!$row) return null;

        return $this->constructFromRow($row);
    }

    public function latestCreatedAt(): ?NlcTeam
    {
        $row = DB::table('nlc_team')->latest('created_at')->first();

        if(!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws Exception
     */
    private function constructFromRow($row): NlcTeam
    {
        return new NlcTeam(
            new NlcTeamId($row->id),
            new ReferralCode($row->referral_code),
            NlcTeamStatus::from($row->status),
            $row->nama_team,
            $row->asal_sekolah,
            $row->nama_guru_pendamping,
            $row->no_telp_guru_pendamping,
            $row->region,
            $row->id_kota,
            $row->biaya,
            $row->unique_payment_code,
            $row->kode_voucher,
            $row->email_lomba,
            $row->password_lomba
        );
    }

    public function persist(NlcTeam $nlc_team): void
    {
        DB::table('nlc_team')->upsert(
            [
                "id" => $nlc_team->getId()->toString(),
                "referral_code" => $nlc_team->getReferralCode()->getCode(),
                "status" => $nlc_team->getStatus()->value,
                "nama_team" => $nlc_team->getNamaTeam(),
                "asal_sekolah" => $nlc_team->getAsalSekolah(),
                "nama_guru_pendamping" => $nlc_team->getNamaGuruPendamping(),
                "no_telp_guru_pendamping" => $nlc_team->getNoTelpGuruPendamping(),
                "region" => $nlc_team->getRegion(),
                "id_kota" => $nlc_team->getIdKota(),
                "biaya" => $nlc_team->getBiaya(),
                "unique_payment_code" => $nlc_team->getUniquePaymentCode(),
                "kode_voucher" => $nlc_team->getKodeVoucher(),
            ],
            'id'
        );
    }

    /**
     * @throws Exception
     */
    public function getWithPagination(int $page, int $per_page): array
    {
        $rows = DB::table('nlc_team')
            ->paginate($per_page, ['*'], 'npc_team_page', $page);
        $nlc_teams = [];

        foreach ($rows as $row) {
            $nlc_teams[] = $this->constructFromRow($row);
        }
        return [$nlc_teams, ceil($rows->total() / $per_page)];
    }
}
