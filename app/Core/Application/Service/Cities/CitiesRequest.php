<?php

namespace App\Core\Application\Service\Cities;

class CitiesRequest
{
    private int $provinsi_id;

    /**
     * @param int $provinsi_id
     */
    public function __construct(int $provinsi_id)
    {
        $this->provinsi_id = $provinsi_id;
    }

    /**
     * @return int
     */
    public function getProvinsiId(): int
    {
        return $this->provinsi_id;
    }
}
