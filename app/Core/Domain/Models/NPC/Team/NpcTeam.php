<?php

namespace App\Core\Domain\Models\NPC\Team;

use App\Core\Domain\Models\ReferralCode;
use Exception;

class NpcTeam
{
    const BASE_PRICE = [
        'junior' => 50000,
        'senior' => 120000
    ];
    private NpcTeamId $id;
    private ReferralCode $referral_code;
    private NpcKategori $kategori;
    private NpcTeamStatus $status;
    private string $nama_team;
    private string $asal_sekolah;
    private ?string $nama_guru_pendamping;
    private ?string $no_telp_guru_pendamping;
    private int $id_kota;
    private int $biaya;
    private ?int $unique_payment_code;
    private ?string $kode_voucher;
    private ?string $username_lomba;
    private ?string $password_lomba;

    /**
     * @param NpcTeamId $id
     * @param ReferralCode $referral_code
     * @param NpcKategori $kategori
     * @param NpcTeamStatus $status
     * @param string $nama_team
     * @param string $asal_sekolah
     * @param ?string $nama_guru_pendamping
     * @param ?string $no_telp_guru_pendamping
     * @param int $id_kota
     * @param int $biaya
     * @param ?int $unique_payment_code
     * @param ?string $kode_voucher
     * @param ?string $username_lomba
     * @param ?string $password_lomba
     */
    public function __construct(NpcTeamId $id, ReferralCode $referral_code, NpcKategori $kategori, NpcTeamStatus $status, string $nama_team, string $asal_sekolah, ?string $nama_guru_pendamping, ?string $no_telp_guru_pendamping, int $id_kota, int $biaya, ?int $unique_payment_code=null, ?string $kode_voucher = null, ?string $username_lomba = null, ?string $password_lomba = null)
    {
        $this->id = $id;
        $this->referral_code = $referral_code;
        $this->kategori = $kategori;
        $this->status = $status;
        $this->nama_team = $nama_team;
        $this->asal_sekolah = $asal_sekolah;
        $this->nama_guru_pendamping = $nama_guru_pendamping;
        $this->no_telp_guru_pendamping = $no_telp_guru_pendamping;
        $this->id_kota = $id_kota;
        $this->biaya = $biaya;
        $this->unique_payment_code = $unique_payment_code;
        $this->kode_voucher = $kode_voucher;
        $this->username_lomba = $username_lomba;
        $this->password_lomba = $password_lomba;
    }

    /**
     * @throws Exception
     */
    public static function create(NpcKategori $kategori, ReferralCode $referral_code, string $nama_team, string $asal_sekolah, ?string $nama_guru_pendamping, ?string $no_telp_guru_pendamping, int $id_kota, int $biaya, ?int $unique_payment_code=null, ?string $kode_voucher = null, ?string $username_lomba=null, ?string $password_lomba=null): self
    {
        return new self(
            NpcTeamId::generate(),
            $referral_code,
            $kategori,
            NpcTeamStatus::AWAITING_PAYMENT,
            $nama_team,
            $asal_sekolah,
            $nama_guru_pendamping,
            $no_telp_guru_pendamping,
            $id_kota,
            $biaya,
            $unique_payment_code,
            $kode_voucher,
            $username_lomba,
            $password_lomba
        );
    }

    public function verifiedPayment(): void
    {
        $this->status = NpcTeamStatus::PAYMENT_VERIFIED;
    }

    public function activateTeam(): void
    {
        $this->status = NpcTeamStatus::ACTIVE;
    }

    public function awaitVerification(): void
    {
        $this->status = NpcTeamStatus::AWAITING_VERIFICATION;
    }

    public function needRevision(): void
    {
        $this->status = NpcTeamStatus::NEED_REVISION;
    }

    /**
     * @throws Exception
     */
    public function regenerateReferralCode(): void
    {
        $this->referral_code = ReferralCode::generate();
    }

    /**
     * @return NpcTeamId
     */
    public function getId(): NpcTeamId
    {
        return $this->id;
    }

    /**
     * @return ReferralCode
     */
    public function getReferralCode(): ReferralCode
    {
        return $this->referral_code;
    }

    /**
     * @return NpcKategori
     */
    public function getKategori(): NpcKategori
    {
        return $this->kategori;
    }

    /**
     * @return NpcTeamStatus
     */
    public function getStatus(): NpcTeamStatus
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getNamaTeam(): string
    {
        return $this->nama_team;
    }

    /**
     * @return string
     */
    public function getAsalSekolah(): string
    {
        return $this->asal_sekolah;
    }

    /**
     * @return string
     */
    public function getNamaGuruPendamping(): ?string
    {
        return $this->nama_guru_pendamping;
    }

    /**
     * @return string
     */
    public function getNoTelpGuruPendamping(): ?string
    {
        return $this->no_telp_guru_pendamping;
    }

    /**
     * @return int
     */
    public function getIdKota(): int
    {
        return $this->id_kota;
    }

    /**
     * @return integer
     */
    public function getBiaya(): int
    {
        return $this->biaya;
    }

    public static function getBaseBiaya(string $kategori): int
    {
        return self::BASE_PRICE[$kategori];
    }

    public function getUniquePaymentCode(): ?int
    {
        return $this->unique_payment_code;
    }

    /**
     * get kode voucher
     *
     * @return string|null
     */
    public function getKodeVoucher(): ?string
    {
        return $this->kode_voucher;
    }

    /**
     * @param string|null $password_lomba
     */
    public function getPasswordLomba(): ?string
    {
        return $this->password_lomba;
    }

    /**
     * @return string
     */
    public function getUsernameLomba(): ?string
    {
        return $this->username_lomba;
    }
}
