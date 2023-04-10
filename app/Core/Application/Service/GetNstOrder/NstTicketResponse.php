<?php

namespace App\Core\Application\Service\GetNstOrder;

use App\Core\Domain\Models\Email;
use JsonSerializable;

class NstTicketResponse implements JsonSerializable
{
    private string $ticket_id;
    private string $name;
    private Email $email;
    private string $no_telp;
    private ?string $alamat;
    private ?string $jenis_vaksinasi;
    private string $bukti_vaksin_url;
    private bool $is_used;

    /**
     * @param string $ticket_id
     * @param string $name
     * @param Email $email
     * @param string $no_telp
     * @param ?string $alamat
     * @param ?string $jenis_vaksinasi
     * @param string $bukti_vaksin_url
     * @param bool $is_used
     */
    public function __construct(string $ticket_id, string $name, Email $email, string $no_telp, ?string $alamat, ?string $jenis_vaksinasi, string $bukti_vaksin_url, bool $is_used)
    {
        $this->ticket_id = $ticket_id;
        $this->name = $name;
        $this->email = $email;
        $this->no_telp = $no_telp;
        $this->alamat = $alamat;
        $this->jenis_vaksinasi = $jenis_vaksinasi;
        $this->bukti_vaksin_url = $bukti_vaksin_url;
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
            'jenis_vaksinasi' => $this->jenis_vaksinasi,
            'bukti_vaksin_url' => $this->bukti_vaksin_url,
            'is_used' => $this->is_used
        ];
    }
}
