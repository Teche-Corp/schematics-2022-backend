<?php

namespace App\Core\Domain\Models\User;

use App\Core\Domain\Models\Email;
use App\Exceptions\SchematicsException;
use Exception;
use Illuminate\Support\Facades\Hash;

class User
{
    private UserId $id;
    private UserType $type;
    private Email $email;
    private string $no_telp;
    private string $name;
    private string $hashed_password;
    private static bool $verifier = false;

    /**
     * @param UserId $id
     * @param UserType $type
     * @param Email $email
     * @param string $no_telp
     * @param string $name
     * @param string $hashed_password
     */
    public function __construct(UserId $id, UserType $type, Email $email, string $no_telp, string $name, string $hashed_password)
    {
        $this->id = $id;
        $this->type = $type;
        $this->email = $email;
        $this->no_telp = $no_telp;
        $this->name = $name;
        $this->hashed_password = $hashed_password;
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
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $hashed_password
     */
    public function setHashedPassword(string $hashed_password): void
    {
        $this->hashed_password = $hashed_password;
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
     * @return bool
     */
    public static function isVerifier(): bool
    {
        return self::$verifier;
    }


    public function beginVerification(): self
    {
        self::$verifier = true;
        return $this;
    }

    public function checkPassword(string $password): self
    {
        self::$verifier &= Hash::check($password, $this->hashed_password);
        return $this;
    }

    public function checkUserType(UserType $type): self
    {
        self::$verifier &= ($this->type->value == $type->value);
        return $this;
    }

    public function checkName(string $name): self
    {
        self::$verifier &= ($this->name == $name);
        return $this;
    }

    public function checkNoTelp(string $no_telp): self
    {
        self::$verifier &= ($this->no_telp == $no_telp);
        return $this;
    }

    public function checkEmail(string $email): self
    {
        self::$verifier &= ($this->email->toString() == $email);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function verify(): void
    {
        if (!self::$verifier) {
            SchematicsException::throw("invalid credential", 1003, 401);
        }
    }

    /**
     * @throws Exception
     */
    public static function create(UserType $type, Email $email, string $no_telp, string $name, string $unhashed_password): self
    {
        return new self(
            UserId::generate(),
            $type,
            $email,
            $no_telp,
            $name,
            Hash::make($unhashed_password)
        );
    }

    /**
     * @return UserId
     */
    public function getId(): UserId
    {
        return $this->id;
    }

    /**
     * @return UserType
     */
    public function getType(): UserType
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHashedPassword(): string
    {
        return $this->hashed_password;
    }

    public function changePassword(string $unhashed_password): void
    {
        $this->hashed_password = Hash::make($unhashed_password);
    }
}
