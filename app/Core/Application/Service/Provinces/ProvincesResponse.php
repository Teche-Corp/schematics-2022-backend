<?php

namespace App\Core\Application\Service\Provinces;

use JsonSerializable;

class ProvincesResponse implements JsonSerializable
{
    private int $id;
    private string $provinsi;

    /**
     * @param int $id
     * @param string $provinsi
     */
    public function __construct(int $id, string $provinsi)
    {
        $this->id = $id;
        $this->provinsi = $provinsi;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'provinsi' => $this->provinsi
        ];
    }
}
