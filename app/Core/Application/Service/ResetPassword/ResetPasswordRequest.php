<?php

namespace App\Core\Application\Service\ResetPassword;

class ResetPasswordRequest
{
    private string $token;
    private string $new_password;

    /**
     * @param string $token
     * @param string $new_password
     */
    public function __construct(string $token, string $new_password)
    {
        $this->token = $token;
        $this->new_password = $new_password;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getNewPassword(): string
    {
        return $this->new_password;
    }
}
