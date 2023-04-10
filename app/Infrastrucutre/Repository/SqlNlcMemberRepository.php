<?php

namespace App\Infrastrucutre\Repository;

use App\Core\Domain\Models\NLC\Member\NlcMember;
use App\Core\Domain\Models\NLC\Member\NlcMemberId;
use App\Core\Domain\Models\NLC\Member\NlcMemberStatus;
use App\Core\Domain\Models\NLC\Member\NlcMemberType;
use App\Core\Domain\Models\NLC\Team\NlcTeamId;
use App\Core\Domain\Models\User\UserId;
use App\Core\Domain\Repository\NlcMemberRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SqlNlcMemberRepository implements NlcMemberRepositoryInterface
{
    /**
     * @throws Exception
     */
    public function find(NlcMemberId $id): ?NlcMember
    {
        $row = DB::table('nlc_member')->where('id', $id->toString())->first();

        if (!$row) return null;

        return $this->constructFromRows([$row])[0];
    }

    /**
     * @throws Exception
     */
    public function findByUserId(UserId $user_id): ?NlcMember
    {
        $row = DB::table('nlc_member')->where('user_id', $user_id->toString())->first();

        if (!$row) return null;

        return $this->constructFromRows([$row])[0];
    }

    /**
     * @throws Exception
     */
    public function getByTeamId(NlcTeamId $team_id): array
    {
        $rows = DB::table('nlc_member')->where('team_id', $team_id->toString())->get();

        return $this->constructFromRows($rows->all());
    }

    /**
     * @param array $rows
     * @return NlcMember[]
     * @throws Exception
     */
    public function constructFromRows(array $rows): array
    {
        $nlc_member = [];
        foreach ($rows as $row) {
            $nlc_member[] = new NlcMember(
                new NlcMemberId($row->id),
                NlcMemberType::from($row->member_type),
                new NlcTeamId($row->team_id),
                NlcMemberStatus::from($row->status),
                new UserId($row->user_id),
                $row->nisn,
                $row->surat_url,
                $row->bukti_twibbon_url,
                $row->bukti_poster_url,
                $row->no_telp,
                $row->no_wa,
                $row->id_line,
                $row->alamat,
                $row->bukti_vaksin_url,
                $row->info_sch,
                $row->jenis_vaksin,
                $row->bingo_file_url
            );
        }
        return $nlc_member;
    }

    public function persist(NlcMember $member): void
    {
        DB::table('nlc_member')->upsert(
            [
                "id" => $member->getId()->toString(),
                "member_type" => $member->getMemberType()->value,
                "team_id" => $member->getTeamId()->toString(),
                "status" => $member->getStatus()->value,
                "user_id" => $member->getUserId()->toString(),
                "nisn" => $member->getNisn(),
                "surat_url" => $member->getSuratUrl(),
                "bukti_twibbon_url" => $member->getBuktiTwibbonUrl(),
                "bukti_poster_url" => $member->getBuktiPosterUrl(),
                "no_telp" => $member->getNoTelp(),
                "no_wa" => $member->getNoWa(),
                "id_line" => $member->getIdLine(),
                "alamat" => $member->getAlamat(),
                "bukti_vaksin_url" => $member->getBuktiVaksinUrl(),
                "info_sch" => $member->getInfoSch(),
                "jenis_vaksin" => $member->getJenisVaksin(),
                "bingo_file_url" => $member->getBingoFileUrl(),
            ],
            'id'
        );
    }

    /**
     * @throws Exception
     */
    public function findKetuaByTeamId(NlcTeamId $id): ?NlcMember
    {
        $row = DB::table('nlc_member')
            ->where('team_id', $id->toString())
            ->where('member_type', NlcMemberType::KETUA->value)
            ->first();

        return $this->constructFromRows([$row])[0];
    }
}
