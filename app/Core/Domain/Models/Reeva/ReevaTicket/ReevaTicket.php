<?php

namespace App\Core\Domain\Models\Reeva\ReevaTicket;


use App\Core\Domain\Models\Email;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderId;

class ReevaTicket
{
    public const QUOTA_TICKET_PRESALE_1 = 850;
    public const QUOTA_TICKET_PRESALE_2 = 3600;
    // Total tiket terjual di database dengan status != expired ada 1273
    public const QUOTA_TICKET_NORMAL_TICKET = 1273 + 1500;
    private ReevaTicketId $id;
    private ReevaOrderId $reeva_order_id;
    private string $name;
    private Email $email;
    private string $no_telp;
    private string $alamat;
    private string $nik;
    private bool $is_used;

    /**
     * @param ReevaTicketId $id
     * @param ReevaOrderId $reeva_order_id
     * @param string $name
     * @param Email $email
     * @param string $no_telp
     * @param string $alamat
     * @param string $nik
     * @param bool $is_used
     */
    public function __construct(ReevaTicketId $id, ReevaOrderId $reeva_order_id, string $name, Email $email, string $no_telp, string $alamat, string $nik, bool $is_used)
    {
        $this->id = $id;
        $this->reeva_order_id = $reeva_order_id;
        $this->name = $name;
        $this->email = $email;
        $this->no_telp = $no_telp;
        $this->alamat = $alamat;
        $this->nik = $nik;
        $this->is_used = $is_used;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param Email $email
     */
    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $no_telp
     */
    public function setNoTelp(string $no_telp): void
    {
        $this->no_telp = $no_telp;
    }

    /**
     * @param string $alamat
     */
    public function setAlamat(string $alamat): void
    {
        $this->alamat = $alamat;
    }

    /**
     * @param string $nik
     */
    public function setNik(string $nik): void
    {
        $this->nik = $nik;
    }

    public function setIsUsed(bool $isUsed): void 
    {
        $this->is_used = $isUsed;
    }

    /**
     * @return string
     */
    public function getNik(): string
    {
        return $this->nik;
    }

    /**
     * @return bool
     */
    public function isUsed(): bool
    {
        return $this->is_used;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getNoTelp(): string
    {
        return $this->no_telp;
    }


    /**
     * @return ReevaTicketId
     */
    public function getId(): ReevaTicketId
    {
        return $this->id;
    }

    /**
     * @return ReevaOrderId
     */
    public function getReevaOrderId(): ReevaOrderId
    {
        return $this->reeva_order_id;
    }

    /**
     * @return string
     */
    public function getAlamat(): string
    {
        return $this->alamat;
    }
}
