<?php

namespace App\Core\Domain\Models\NLC\Team;

use Exception;
use App\Core\Domain\Models\Email;
use App\Core\Domain\Models\ReferralCode;

class NlcTeam
{
    private NlcTeamId $id;
    private ReferralCode $referral_code;
    private NlcTeamStatus $status;
    private string $nama_team;
    private string $asal_sekolah;
    private ?string $nama_guru_pendamping;
    private ?string $no_telp_guru_pendamping;
    private string $region;
    private int $id_kota;
    private int $biaya;
    private ?int $unique_payment_code;
    private ?string $kode_voucher;
    private ?string $email_lomba;
    private ?string $password_lomba;

    /**
     * @param NlcTeamId $id
     * @param ReferralCode $referral_code
     * @param NlcTeamStatus $status
     * @param string $nama_team
     * @param string $asal_sekolah
     * @param ?string $nama_guru_pendamping
     * @param ?string $no_telp_guru_pendamping
     * @param string $region
     * @param int $id_kota
     * @param int $biaya
     * @param ?int $unique_payment_code
     * @param ?string $kode_voucher
     * @param ?string $email_lomba
     * @param ?string $password_lomba
     */
    public function __construct(NlcTeamId $id, ReferralCode $referral_code, NlcTeamStatus $status, string $nama_team, string $asal_sekolah, ?string $nama_guru_pendamping, ?string $no_telp_guru_pendamping, string $region, int $id_kota, int $biaya, ?int $unique_payment_code=null, ?string $kode_voucher = null, ?string $email_lomba = null, ?string $password_lomba = null)
    {
        $this->id = $id;
        $this->referral_code = $referral_code;
        $this->status = $status;
        $this->nama_team = $nama_team;
        $this->asal_sekolah = $asal_sekolah;
        $this->nama_guru_pendamping = $nama_guru_pendamping;
        $this->no_telp_guru_pendamping = $no_telp_guru_pendamping;
        $this->region = $region;
        $this->id_kota = $id_kota;
        $this->biaya = $biaya;
        $this->unique_payment_code = $unique_payment_code;
        $this->kode_voucher = $kode_voucher;
        $this->email_lomba = $email_lomba;
        $this->password_lomba = $password_lomba;
    }

    /**
     * @throws Exception
     */
    public static function create(ReferralCode $referral_code, string $nama_team, string $asal_sekolah, ?string $nama_guru_pendamping, ?string $no_telp_guru_pendamping, string $region, string $id_kota, string $biaya, ?int $unique_payment_code = null, ?string $kode_voucher = null, ?string $email_lomba=null, ?string $password_lomba=null): self
    {
        return new self(
            NlcTeamId::generate(),
            $referral_code,
            NlcTeamStatus::AWAITING_PAYMENT,
            $nama_team,
            $asal_sekolah,
            $nama_guru_pendamping,
            $no_telp_guru_pendamping,
            $region,
            $id_kota,
            $biaya,
            $unique_payment_code,
            $kode_voucher,
            $email_lomba,
            $password_lomba
        );
    }

    public function verifiedPayment(): void
    {
        $this->status = NlcTeamStatus::PAYMENT_VERIFIED;
    }

    public function activateTeam(): void
    {
        $this->status = NlcTeamStatus::ACTIVE;
    }

    public function awaitVerification(): void
    {
        $this->status = NlcTeamStatus::AWAITING_VERIFICATION;
    }

    public function needRevision(): void
    {
        $this->status = NlcTeamStatus::NEED_REVISION;
    }

    /**
     * @throws Exception
     */
    public function regenerateReferralCode(): void
    {
        $this->referral_code = ReferralCode::generate();
    }

    /**
     * @return NlcTeamId
     */
    public function getId(): NlcTeamId
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
     * @return NlcTeamStatus
     */
    public function getStatus(): NlcTeamStatus
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
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
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

    /**
     * @return integer
     */
    public static function getBaseBiaya(): int
    {
        return 150000;
    }

    public function getUniquePaymentCode(): ?int
    {
        return $this->unique_payment_code;
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
    public function getEmailLomba(): ?string
    {
        return $this->email_lomba;
    }

    /**
     * kode_voucher
     *
     * @return string|null
     */
    public function getKodeVoucher(): ?string
    {
        return $this->kode_voucher;
    }
}
