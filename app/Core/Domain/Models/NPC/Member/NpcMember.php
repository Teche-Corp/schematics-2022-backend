<?php

namespace App\Core\Domain\Models\NPC\Member;

use App\Core\Domain\Models\NPC\Team\NpcTeamId;
use App\Core\Domain\Models\User\UserId;

class NpcMember
{
    public const MAX_MEMBER = 3;
    private NpcMemberId $id;
    private NpcMemberType $member_type;
    private NpcTeamId $team_id;
    private NpcMemberStatus $status;
    private UserId $user_id;
    private string $nisn;
    private string $surat_url;
    private string $no_telp;
    private string $no_wa;
    private ?string $id_line;
    private string $alamat;
    private string $info_sch;
    private string $discord_tag;

    /**
     * @param NpcMemberId $id
     * @param NpcMemberType $member_type
     * @param NpcTeamId $team_id
     * @param NpcMemberStatus $status
     * @param UserId $user_id
     * @param string $nisn
     * @param string $surat_url
     * @param string $no_telp
     * @param string $no_wa
     * @param string|null $id_line
     * @param string $alamat
     * @param string $info_sch
     * @param string $discord_tag
     */

    public function __construct(NpcMemberId $id, NpcMemberType $member_type, NpcTeamId $team_id, NpcMemberStatus $status, UserId $user_id, string $nisn, string $surat_url, string $no_telp, string $no_wa, ?string $id_line, string $alamat, string $info_sch, string $discord_tag)
    {
        $this->id = $id;
        $this->member_type = $member_type;
        $this->team_id = $team_id;
        $this->status = $status;
        $this->user_id = $user_id;
        $this->nisn = $nisn;
        $this->surat_url = $surat_url;
        $this->no_telp = $no_telp;
        $this->no_wa = $no_wa;
        $this->id_line = $id_line;
        $this->alamat = $alamat;
        $this->info_sch = $info_sch;
        $this->discord_tag = $discord_tag;
    }

    /**
     * @param NpcMemberStatus $status
     */
    public function setStatus(NpcMemberStatus $status): void
    {
        $this->status = $status;
    }

    public static function create(NpcMemberId $id, NpcMemberType $member_type, NpcTeamId $team_id, UserId $user_id, string $nisn, string $surat_url, string $no_telp, string $no_wa, ?string $id_line, string $alamat, string $info_sch, string $discord_tag): self
    {
        return new self(
            $id,
            $member_type,
            $team_id,
            NpcMemberStatus::AWAITING_VERIFICATION,
            $user_id,
            $nisn,
            $surat_url,
            $no_telp,
            $no_wa,
            $id_line,
            $alamat,
            $info_sch,
            $discord_tag,
        );
    }

    public function activate(): void
    {
        $this->status = NpcMemberStatus::ACTIVE;
    }

    public function awaitVerification(): void
    {
        $this->status = NpcMemberStatus::AWAITING_VERIFICATION;
    }

    public function needRevision(): void
    {
        $this->status = NpcMemberStatus::NEED_REVISION;
    }

    /**
     * @return NpcMemberId
     */
    public function getId(): NpcMemberId
    {
        return $this->id;
    }

    /**
     * @return NpcMemberType
     */
    public function getMemberType(): NpcMemberType
    {
        return $this->member_type;
    }

    /**
     * @return NpcMemberStatus
     */
    public function getStatus(): NpcMemberStatus
    {
        return $this->status;
    }

    /**
     * @return NpcTeamId
     */
    public function getTeamId(): NpcTeamId
    {
        return $this->team_id;
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->user_id;
    }

    /**
     * @return string
     */
    public function getNisn(): string
    {
        return $this->nisn;
    }

    /**
     * @return string
     */
    public function getSuratUrl(): string
    {
        return $this->surat_url;
    }

    /**
     * @return string
     */
    public function getNoTelp(): string
    {
        return $this->no_telp;
    }

    /**
     * @return string
     */
    public function getNoWa(): string
    {
        return $this->no_wa;
    }

    /**
     * @return string|null
     */
    public function getIdLine(): ?string
    {
        return $this->id_line;
    }

    /**
     * @return string
     */
    public function getAlamat(): string
    {
        return $this->alamat;
    }

    /**
     * @return string
     */
    public function getInfoSch(): string
    {
        return $this->info_sch;
    }

    /**
     * @return string
     */
    public function getDiscordTag(): string
    {
        return $this->discord_tag;
    }
}
