<?php

namespace App\Infrastrucutre\Repository;

use App\Core\Domain\Models\NPC\Team\NpcKategori;
use App\Core\Domain\Models\NPC\Team\NpcTeam;
use App\Core\Domain\Models\NPC\Team\NpcTeamId;
use App\Core\Domain\Models\NPC\Team\NpcTeamStatus;
use App\Core\Domain\Models\ReferralCode;
use App\Core\Domain\Repository\NpcTeamRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use function ceil;

class SqlNpcTeamRepository implements NpcTeamRepositoryInterface
{

    /**
     * @throws Exception
     */
    public function find(NpcTeamId $id): ?NpcTeam
    {
        $row = DB::table('npc_team')->where('id', $id->toString())->first();

        if (!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws Exception
     */
    public function findByReferralCode(ReferralCode $code): ?NpcTeam
    {
        $row = DB::table('npc_team')->where('referral_code', $code->getCode())->first();

        if (!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws Exception
     */
    public function latestJuniorCreatedAt(): ?NpcTeam
    {
        $row = DB::table('npc_team')->where('kategori', NpcKategori::JUNIOR)->latest('created_at')->first();

        if (!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws Exception
     */
    public function latestSeniorCreatedAt(): ?NpcTeam
    {
        $row = DB::table('npc_team')->where('kategori', NpcKategori::SENIOR)->latest('created_at')->first();

        if (!$row) return null;

        return $this->constructFromRow($row);
    }

    /**
     * @throws Exception
     */
    private function constructFromRow($row): NpcTeam
    {
        return new NpcTeam(
            new NpcTeamId($row->id),
            new ReferralCode($row->referral_code),
            NpcKategori::from($row->kategori),
            NpcTeamStatus::from($row->status),
            $row->nama_team,
            $row->asal_sekolah,
            $row->nama_guru_pendamping,
            $row->no_telp_guru_pendamping,
            $row->id_kota,
            $row->biaya,
            $row->unique_payment_code,
            $row->kode_voucher,
            $row->username_lomba,
            $row->password_lomba
        );
    }

    public function persist(NpcTeam $npc_team): void
    {
        DB::table('npc_team')->upsert(
            [
                "id" => $npc_team->getId()->toString(),
                "referral_code" => $npc_team->getReferralCode()->getCode(),
                "kategori" => $npc_team->getKategori()->value,
                "status" => $npc_team->getStatus()->value,
                "nama_team" => $npc_team->getNamaTeam(),
                "asal_sekolah" => $npc_team->getAsalSekolah(),
                "nama_guru_pendamping" => $npc_team->getNamaGuruPendamping(),
                "no_telp_guru_pendamping" => $npc_team->getNoTelpGuruPendamping(),
                "id_kota" => $npc_team->getIdKota(),
                "unique_payment_code" => $npc_team->getUniquePaymentCode(),
                "biaya" => $npc_team->getBiaya(),
                "kode_voucher" => $npc_team->getKodeVoucher(),
                "username_lomba" => $npc_team->getUsernameLomba(),
                "password_lomba" => $npc_team->getPasswordLomba()
            ],
            'id'
        );
    }

    /**
     * @throws Exception
     */
    public function getWithPaginationByKategori(NpcKategori $kategori, int $page, int $per_page): array
    {
        $rows = DB::table('npc_team')->where('kategori', $kategori->value)
            ->paginate($per_page, ['*'], 'npc_team_page', $page);
        $npc_teams = [];
        foreach ($rows as $row) {
            $npc_teams[] = $this->constructFromRow($row);
        }
        return [$npc_teams, ceil($rows->total() / $per_page)];
    }
}
