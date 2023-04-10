<?php

namespace App\Core\Domain\Models;

use App\Exceptions\SchematicsException;
use Exception;
use Ramsey\Uuid\Uuid;
use function strlen;
use function strtoupper;
use function substr;

class ReferralCode
{
    private string $code;

    /**
     * @param string $code
     * @throws Exception
     */
    public function __construct(string $code)
    {
        if (Uuid::isValid($code)) {
            $this->code = $code;
            return;
        }
        if (strlen($code) != 6) {
            SchematicsException::throw("invalid code length", 1008);
        }
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @throws Exception
     */
    public static function generate(): self
    {
        return new self(strtoupper(substr(Uuid::uuid4(), -6)));
    }
}
