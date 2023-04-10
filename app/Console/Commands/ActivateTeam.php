<?php

namespace App\Console\Commands;

use App\Core\Domain\Models\NLC\Member\NlcMember;
use App\Core\Domain\Models\NLC\Member\NlcMemberStatus;
use App\Core\Domain\Models\NLC\Team\NlcTeam;
use App\Core\Domain\Models\NLC\Team\NlcTeamId;
use App\Core\Domain\Models\NLC\Team\NlcTeamStatus;
use App\Core\Domain\Models\NPC\Member\NpcMember;
use App\Core\Domain\Models\NPC\Member\NpcMemberStatus;
use App\Core\Domain\Models\NPC\Team\NpcKategori;
use App\Core\Domain\Models\NPC\Team\NpcTeam;
use App\Core\Domain\Models\NPC\Team\NpcTeamId;
use App\Core\Domain\Models\NPC\Team\NpcTeamStatus;
use App\Core\Domain\Models\ReferralCode;
use App\Core\Domain\Repository\NlcMemberRepositoryInterface;
use App\Core\Domain\Repository\NpcMemberRepositoryInterface;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class ActivateTeam extends Command
{
    protected $signature = "team:activate";

    protected $description = "activate team yang sudah memenuhi requirement";

    private NpcMemberRepositoryInterface $npc_member_repository;
    private NlcMemberRepositoryInterface $nlc_member_repository;

    /**
     * @param NpcMemberRepositoryInterface $npc_member_repository
     * @param NlcMemberRepositoryInterface $nlc_member_repository
     */
    public function __construct(NpcMemberRepositoryInterface $npc_member_repository, NlcMemberRepositoryInterface $nlc_member_repository)
    {
        parent::__construct();
        $this->npc_member_repository = $npc_member_repository;
        $this->nlc_member_repository = $nlc_member_repository;
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function handle(): int
    {
        DB::beginTransaction();
        try {
            $npc_team_rows = DB::table('npc_team')->get();
            $nlc_team_rows = DB::table('nlc_team')->get();
            $npc_team_payload = [];
            $nlc_team_payload = [];

            /** ngecek team npc */
            foreach ($npc_team_rows as $row) {

                if ($row->status != NpcTeamStatus::PAYMENT_VERIFIED->value) continue;
                $npc_team = $this->constructNpcTeamFromRow($row);

                $npc_members = $this->npc_member_repository->getByTeamId($npc_team->getId());
                /** cek apakah membernya ada yang belum aktif */
                if (!$this->checkIsAllMembersActive($npc_members)) continue;

                $npc_team->activateTeam();
                $npc_team_payload[] = $this->transformNpcTeamToPayload($npc_team);
            }

            /** ngecek team nlc */
            foreach ($nlc_team_rows as $row) {
                if ($row->status != NlcTeamStatus::PAYMENT_VERIFIED->value) continue;
                $nlc_team = $this->constructNlcTeamFromRow($row);

                $nlc_members = $this->nlc_member_repository->getByTeamId($nlc_team->getId());
                /** cek apakah membernya ada yang belum aktif */
                if (!$this->checkIsAllMembersActive($nlc_members)) continue;

                $nlc_team->activateTeam();
                $nlc_team_payload[] = $this->transformNlcTeamToPayload($nlc_team);
            }

            DB::table('npc_team')->upsert($npc_team_payload, 'id');
            DB::table('nlc_team')->upsert($nlc_team_payload, 'id');
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();

        return 0;
    }

    private function transformNlcTeamToPayload(NlcTeam $nlc_team): array
    {
        return [
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
        ];
    }

    private function transformNpcTeamToPayload(NpcTeam $npc_team): array
    {
        return [
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
        ];
    }

    /**
     * @throws Exception
     */
    private function constructNpcTeamFromRow(object $row): NpcTeam
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
            $row->unique_payment_code
        );
    }

    /**
     * @throws Exception
     */
    private function constructNlcTeamFromRow(object $row): NlcTeam
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
            $row->unique_payment_code
        );
    }

    private function checkIsAllMembersActive(array $member): bool
    {
        foreach ($member as $item) {
            if ($item instanceof NpcMember) {
                if ($item->getStatus() != NpcMemberStatus::ACTIVE) return false;
            } elseif ($item instanceof NlcMember) {
                if ($item->getStatus() != NlcMemberStatus::ACTIVE) return false;
            }
        }
        return true;
    }
}
