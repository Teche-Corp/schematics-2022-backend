<?php

namespace App\Infrastrucutre\Repository;

use App\Core\Domain\Models\NPC\Member\NpcMember;
use App\Core\Domain\Models\NPC\Member\NpcMemberId;
use App\Core\Domain\Models\NPC\Member\NpcMemberStatus;
use App\Core\Domain\Models\NPC\Member\NpcMemberType;
use App\Core\Domain\Models\NPC\Team\NpcTeamId;
use App\Core\Domain\Models\User\UserId;
use App\Core\Domain\Repository\NpcMemberRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SqlNpcMemberRepository implements NpcMemberRepositoryInterface
{
    /**
     * @throws Exception
     */
    public function find(NpcMemberId $id): ?NpcMember
    {
        $row = DB::table('npc_member')->where('id', $id->toString())->first();

        if (!$row) return null;

        return $this->constructFromRows([$row])[0];
    }

    /**
     * @throws Exception
     */
    public function findByUserId(UserId $user_id): ?NpcMember
    {
        $row = DB::table('npc_member')->where('user_id', $user_id->toString())->first();

        if (!$row) return null;

        return $this->constructFromRows([$row])[0];
    }

    /**
     * @throws Exception
     */
    public function getByTeamId(NpcTeamId $team_id): array
    {
        $rows = DB::table('npc_member')->where('team_id', $team_id->toString())->get();

        return $this->constructFromRows($rows->all());
    }

    /**
     * @param array $rows
     * @return NpcMember[]
     * @throws Exception
     */
    public function constructFromRows(array $rows): array
    {
        $npc_member = [];
        foreach ($rows as $row) {
            $npc_member[] = new NpcMember(
                new NpcMemberId($row->id),
                NpcMemberType::from($row->member_type),
                new NpcTeamId($row->team_id),
                NpcMemberStatus::from($row->status),
                new UserId($row->user_id),
                $row->nisn,
                $row->surat_url,
                $row->no_telp,
                $row->no_wa,
                $row->id_line,
                $row->alamat,
                $row->info_sch,
                $row->discord_tag
            );
        }
        return $npc_member;
    }

    public function persist(NpcMember $member): void
    {
        DB::table('npc_member')->upsert(
            [
                "id" => $member->getId()->toString(),
                "member_type" => $member->getMemberType()->value,
                "team_id" => $member->getTeamId()->toString(),
                "status" => $member->getStatus()->value,
                "user_id" => $member->getUserId()->toString(),
                "nisn" => $member->getNisn(),
                "surat_url" => $member->getSuratUrl(),
                "no_telp" => $member->getNoTelp(),
                "no_wa" => $member->getNoWa(),
                "id_line" => $member->getIdLine(),
                "alamat" => $member->getAlamat(),
                "info_sch" => $member->getInfoSch(),
                "discord_tag" => $member->getDiscordTag()
            ],
            'id'
        );
    }

    /**
     * @throws Exception
     */
    public function findKetuaByTeamId(NpcTeamId $id): ?NpcMember
    {
        $row = DB::table('npc_member')
            ->where('team_id', $id->toString())
            ->where('member_type', NpcMemberType::KETUA->value)
            ->first();

        return $this->constructFromRows([$row])[0];
    }
}
