<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\Kota\Kota;

interface CitiesRepositoryInterface
{
    /**
     * @param string $id
     * @return Kota[]
     */
    public function getByProvinsiId(int $provinsi_id): array;

    public function find(int $id): ?Kota;
}
