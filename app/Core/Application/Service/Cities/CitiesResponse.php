<?php

namespace App\Core\Application\Service\Cities;

use JsonSerializable;

class CitiesResponse implements JsonSerializable
{
    private int $id;
    private int $provinsi_id;
    private string $kota;

    /**
     * @param int $id
     * @param int $provinsi_id
     * @param string $kota
     */
    public function __construct(int $id, int $provinsi_id, string $kota)
    {
        $this->id = $id;
        $this->provinsi_id = $provinsi_id;
        $this->kota = $kota;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->id,
            "provinsi_id" => $this->provinsi_id,
            "kota" => $this->kota
        ];
    }
}
