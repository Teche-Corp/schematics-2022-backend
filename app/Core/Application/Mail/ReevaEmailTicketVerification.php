<?php

namespace App\Core\Application\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class ReevaEmailTicketVerification extends Mailable
{
    use Queueable, SerializesModels;
    private string $nama;
    private string $email;
    private string $nik;
    private string $jumlah_tiket;
    private string $biaya;
    private string $status;

    public function __construct(string $nama, string $email, string $nik, string $jumlah_tiket, string $biaya, string $status)
    {
        $this->nama = $nama;
        $this->email = $email;
        $this->nik = $nik;
        $this->jumlah_tiket = $jumlah_tiket; 
        $this->biaya = $biaya;
        $this->status = $status;
        
    }

    public function build(): ReevaEmailTicketVerification
    {
        $url = 'https://schematics.its.ac.id';
        return $this->from(config('mail.from'))
            ->markdown('email.reeva_ticket_verification', [
                "nama" => $this->nama,
                "email" => $this->email,
                "nik" => $this->nik,
                "jumlah_tiket" => $this->jumlah_tiket,
                "biaya" => $this->biaya,
                "status" => $this->status,
                "url" => $url
            ]);
    }
}
