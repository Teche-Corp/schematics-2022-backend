<?php

namespace App\Core\Application\Service\Provinces;

use App\Core\Domain\Models\Provinsi\Provinsi;
use App\Core\Domain\Repository\ProvincesRepositoryInterface;
use App\Exceptions\SchematicsException;
use Exception;

class ProvincesService
{
    private ProvincesRepositoryInterface $Provinces_repository;

    /**
     * @param ProvincesRepositoryInterface $Provinces_repository
     */
    public function __construct(ProvincesRepositoryInterface $Provinces_repository)
    {
        $this->Provinces_repository = $Provinces_repository;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function execute(): array
    {
        $provinces = $this->Provinces_repository->getAll();
        if (count($provinces) < 1) {
            SchematicsException::throw("Provinsi tidak ketemu", 1006, 404);
        }
        return array_map(function (Provinsi $provinces) {
            return new ProvincesResponse($provinces->getId(), $provinces->getName());
        }, $provinces);
    }
}
