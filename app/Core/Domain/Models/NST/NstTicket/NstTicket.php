<?php

namespace App\Core\Domain\Models\NST\NstTicket;


use App\Core\Domain\Models\Email;
use App\Core\Domain\Models\NST\NstOrder\NstOrderId;

class NstTicket
{
    public const QUOTA_TICKET = 1500;
    private NstTicketId $id;
    private NstOrderId $nst_order_id;
    private string $name;
    private Email $email;
    private string $no_telp;
    private ?string $alamat;
    private ?string $jenis_vaksinasi;
    private string $bukti_vaksin_url;
    private bool $is_used;

    /**
     * @param NstTicketId $id
     * @param NstOrderId $nst_order_id
     * @param string $name
     * @param Email $email
     * @param string $no_telp
     * @param string $alamat
     * @param string $jenis_vaksinasi
     * @param string $bukti_vaksin_url
     * @param bool $is_used
     */
    public function __construct(NstTicketId $id, NstOrderId $nst_order_id, string $name, Email $email, string $no_telp, ?string $alamat, ?string $jenis_vaksinasi, string $bukti_vaksin_url, bool $is_used)
    {
        $this->id = $id;
        $this->nst_order_id = $nst_order_id;
        $this->name = $name;
        $this->email = $email;
        $this->no_telp = $no_telp;
        $this->alamat = $alamat;
        $this->jenis_vaksinasi = $jenis_vaksinasi;
        $this->bukti_vaksin_url = $bukti_vaksin_url;
        $this->is_used = $is_used;
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
     * @return NstTicketId
     */
    public function getId(): NstTicketId
    {
        return $this->id;
    }

    /**
     * @return NstOrderId
     */
    public function getNstOrderId(): NstOrderId
    {
        return $this->nst_order_id;
    }

    /**
     * @return string
     */
    public function getAlamat(): ?string
    {
        return $this->alamat;
    }

    /**
     * @return string
     */
    public function getJenisVaksinasi(): ?string
    {
        return $this->jenis_vaksinasi;
    }

    /**
     * @return string
     */
    public function getBuktiVaksinUrl(): string
    {
        return $this->bukti_vaksin_url;
    }
}
