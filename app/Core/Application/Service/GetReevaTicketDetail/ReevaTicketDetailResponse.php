<?php

namespace App\Core\Application\Service\GetReevaTicketDetail;

use App\Core\Domain\Models\Email;
use JsonSerializable;

class ReevaTicketDetailResponse implements JsonSerializable
{
    private string $ticket_id;
    private string $name;
    private Email $email;
    private string $no_telp;
    private string $alamat;
    private string $nik;
    private bool $is_used;

    /**
     * @param string $ticket_id
     * @param string $name
     * @param Email $email
     * @param string $no_telp
     * @param string $alamat
     * @param string $nik
     * @param bool $is_used
     */
    public function __construct(string $ticket_id, string $name, Email $email, string $no_telp, string $alamat, string $nik, bool $is_used)
    {
        $this->ticket_id = $ticket_id;
        $this->name = $name;
        $this->email = $email;
        $this->no_telp = $no_telp;
        $this->alamat = $alamat;
        $this->nik = $nik;
        $this->is_used = $is_used;
    }

    public function jsonSerialize(): array
    {
        return [
            'ticket_id' => $this->ticket_id,
            'name' => $this->name,
            'email' => $this->email->toString(),
            'no_telp' => $this->no_telp,
            'alamat' => $this->alamat,
            'nik' => $this->nik,
            'is_used' => $this->is_used
        ];
    }
}
