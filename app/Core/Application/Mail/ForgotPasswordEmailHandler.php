<?php

namespace App\Core\Application\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

use function config;

class ForgotPasswordEmailHandler extends Mailable
{
    use Queueable, SerializesModels;

    private string $token;

    /**
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function build(): ForgotPasswordEmailHandler
    {
        $url = 'https://schematics.its.ac.id/dashboard';
        return $this->from(config('mail.from'))
            ->with([
                'token' => $this
            ])
            ->markdown('email.forgot_password', ["token" => $this->token, "url" => $url]);
    }
}
