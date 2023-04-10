<?php

namespace App\Core\Application\Service\RegisterNpcTeam;

use JsonSerializable;

class RegisterNpcTeamResponse implements JsonSerializable
{
    private string $kode_referral;

    /**
     * @param string $kode_referral
     */
    public function __construct(string $kode_referral)
    {
        $this->kode_referral = $kode_referral;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'kode_referral' => $this->kode_referral
        ];
    }
}
