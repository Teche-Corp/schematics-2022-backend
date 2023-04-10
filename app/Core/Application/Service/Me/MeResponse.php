<?php

namespace App\Core\Application\Service\Me;

use App\Core\Domain\Models\Sertifikat;
use App\Core\Domain\Models\User\User;
use JsonSerializable;

class MeResponse implements JsonSerializable
{
    private User $user;
    private bool $is_nlc;
    private bool $is_npc;
    private bool $is_nst;
    private bool $is_reeva;
    private ?Sertifikat $sertifikat;

    /**
     * @param User $user
     * @param bool $is_nlc
     * @param bool $is_npc
     * @param bool $is_nst
     * @param bool $is_reeva
     * @param Sertifikat|null $sertifikat
     */
    public function __construct(User $user, bool $is_nlc, bool $is_npc, bool $is_nst, bool $is_reeva, ?Sertifikat $sertifikat)
    {
        $this->user = $user;
        $this->is_nlc = $is_nlc;
        $this->is_npc = $is_npc;
        $this->is_nst = $is_nst;
        $this->is_reeva = $is_reeva;
        $this->sertifikat = $sertifikat;
    }

    public function jsonSerialize(): array
    {
        $response = [
            'name' => $this->user->getName(),
            'email' => $this->user->getEmail()->toString(),
            'no_telp' => $this->user->getNoTelp(),
            'user_type' => $this->user->getType(),
            'events' => [
                'npc' => $this->is_npc,
                'nlc' => $this->is_nlc,
                'nst' => $this->is_nst,
                'reeva' => $this->is_reeva,
            ]
        ];
        if ($this->sertifikat) {
            $response['sertifikat'] = [
                'nlc_penyisihan' => $this->sertifikat->getNlcPenyisihan(),
            ];
        }
        return $response;
    }
}
