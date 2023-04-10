<?php

namespace App\Core\Application\Service\UserEdit;

class UserEditRequest
{
    private ?string $email;
    private ?string $no_telp;
    private ?string $name;
    private ?string $password;

    /**
     * @param string|null $email
     * @param string|null $no_telp
     * @param string|null $name
     * @param string|null $password
     */
    public function __construct(?string $email, ?string $no_telp, ?string $name, ?string $password)
    {
        $this->email = $email;
        $this->no_telp = $no_telp;
        $this->name = $name;
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getNoTelp(): ?string
    {
        return $this->no_telp;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
