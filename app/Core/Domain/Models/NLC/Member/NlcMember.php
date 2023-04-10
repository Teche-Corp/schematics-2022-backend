<?php

namespace App\Core\Domain\Models\NLC\Member;

use App\Core\Domain\Models\NLC\Team\NlcTeamId;
use App\Core\Domain\Models\User\UserId;

class NlcMember
{
    public const MAX_MEMBER = 3;
    private NlcMemberId $id;
    private NlcMemberType $member_type;
    private NlcTeamId $team_id;
    private NlcMemberStatus $status;
    private UserId $user_id;
    private string $nisn;
    private string $surat_url;
    private ?string $bukti_twibbon_url;
    private ?string $bukti_poster_url;
    private string $no_telp;
    private string $no_wa;
    private ?string $id_line;
    private string $alamat;
    private ?string $bukti_vaksin_url;
    private string $info_sch;
    private ?string $jenis_vaksin;
    private ?string $bingo_file_url;

    /**
     * @param NlcMemberId $id
     * @param NlcMemberType $member_type
     * @param NlcTeamId $team_id
     * @param NlcMemberStatus $status
     * @param UserId $user_id
     * @param string $nisn
     * @param string $surat_url
     * @param ?string $bukti_twibbon_url
     * @param ?string $bukti_poster_url
     * @param string $no_telp
     * @param string $no_wa
     * @param string|null $id_line
     * @param string $alamat
     * @param ?string $bukti_vaksin_url
     * @param string $info_sch
     * @param ?string $jenis_vaksin
     * @param ?string $bingo_file
     */
    public function __construct(NlcMemberId $id, NlcMemberType $member_type, NlcTeamId $team_id, NlcMemberStatus $status, UserId $user_id, string $nisn, string $surat_url, ?string $bukti_twibbon_url, ?string $bukti_poster_url, string $no_telp, string $no_wa, ?string $id_line, string $alamat, ?string $bukti_vaksin_url, string $info_sch, ?string $jenis_vaksin, ?string $bingo_file_url)
    {
        $this->id = $id;
        $this->member_type = $member_type;
        $this->team_id = $team_id;
        $this->status = $status;
        $this->user_id = $user_id;
        $this->nisn = $nisn;
        $this->surat_url = $surat_url;
        $this->bukti_twibbon_url = $bukti_twibbon_url;
        $this->bukti_poster_url = $bukti_poster_url;
        $this->no_telp = $no_telp;
        $this->no_wa = $no_wa;
        $this->id_line = $id_line;
        $this->alamat = $alamat;
        $this->bukti_vaksin_url = $bukti_vaksin_url;
        $this->info_sch = $info_sch;
        $this->jenis_vaksin = $jenis_vaksin;
        $this->bingo_file_url = $bingo_file_url;
    }

    /**
     * @param NlcMemberStatus $status
     */
    public function setStatus(NlcMemberStatus $status): void
    {
        $this->status = $status;
    }

    public static function create(NlcMemberId $id, NlcMemberType $member_type, NlcTeamId $team_id, UserId $user_id, string $nisn, string $surat_url, ?string $bukti_twibbon_url, ?string $bukti_poster_url, string $no_telp, string $no_wa, string $id_line, string $alamat, ?string $bukti_vaksin_url, string $info_sch, ?string $jenis_vaksin, ?string $bingo_file_url): self
    {
        return new self(
            $id,
            $member_type,
            $team_id,
            NlcMemberStatus::AWAITING_FILE_UPLOAD,
            $user_id,
            $nisn,
            $surat_url,
            $bukti_twibbon_url,
            $bukti_poster_url,
            $no_telp,
            $no_wa,
            $id_line,
            $alamat,
            $bukti_vaksin_url,
            $info_sch,
            $jenis_vaksin,
            $bingo_file_url
        );
    }

    /**
     * @param string $surat_url
     */
    public function setSuratUrl(string $surat_url): void
    {
        $this->surat_url = $surat_url;
    }

    /**
     * @param string $bukti_twibbon_url
     */
    public function setBuktiTwibbonUrl(string $bukti_twibbon_url): void
    {
        $this->bukti_twibbon_url = $bukti_twibbon_url;
    }

    /**
     * @param string $bukti_poster_url
     */
    public function setBuktiPosterUrl(string $bukti_poster_url): void
    {
        $this->bukti_poster_url = $bukti_poster_url;
    }

    /**
     * @param string $bukti_vaksin_url
     */
    public function setBuktiVaksinUrl(string $bukti_vaksin_url): void
    {
        $this->bukti_vaksin_url = $bukti_vaksin_url;
    }

    public function setBingoFileUrl(string $bingo_file_url): void
    {
        $this->bingo_file_url = $bingo_file_url;
    }

    public function activate(): void
    {
        $this->status = NlcMemberStatus::ACTIVE;
    }

    public function awaitVerification(): void
    {
        $this->status = NlcMemberStatus::AWAITING_VERIFICATION;
    }

    public function needRevision(): void
    {
        $this->status = NlcMemberStatus::NEED_REVISION;
    }

    /**
     * @return NlcMemberStatus
     */
    public function getStatus(): NlcMemberStatus
    {
        return $this->status;
    }

    /**
     * @return NlcMemberId
     */
    public function getId(): NlcMemberId
    {
        return $this->id;
    }

    /**
     * @return NlcMemberType
     */
    public function getMemberType(): NlcMemberType
    {
        return $this->member_type;
    }

    /**
     * @return NlcTeamId
     */
    public function getTeamId(): NlcTeamId
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
     * @return string|null
     */
    public function getBuktiTwibbonUrl(): ?string
    {
        return $this->bukti_twibbon_url;
    }

    /**
     * @return string|null
     */
    public function getBuktiPosterUrl(): ?string
    {
        return $this->bukti_poster_url;
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
     * @return string|null
     */
    public function getBuktiVaksinUrl(): ?string
    {
        return $this->bukti_vaksin_url;
    }

    /**
     * @return string
     */
    public function getInfoSch(): string
    {
        return $this->info_sch;
    }

    public function getJenisVaksin(): ?string
    {
        return $this->jenis_vaksin;
    }

    public function getBingoFileUrl(): ?string
    {
        return $this->bingo_file_url;
    }
}
