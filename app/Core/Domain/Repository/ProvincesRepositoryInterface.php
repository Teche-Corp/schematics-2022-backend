<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\Provinsi\Provinsi;

interface ProvincesRepositoryInterface
{
    /**
     * @return Provinsi[]
     */
    public function getAll(): array;
}
