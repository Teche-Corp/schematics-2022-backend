<?php

namespace App\Core\Application\Service\Cities;

use App\Core\Domain\Models\Kota\Kota;
use App\Core\Domain\Repository\CitiesRepositoryInterface;
use App\Exceptions\SchematicsException;
use Exception;
use function array_map;

class CitiesService
{
    private CitiesRepositoryInterface $Cities_repository;

    /**
     * @param CitiesRepositoryInterface $Cities_repository
     */
    public function __construct(CitiesRepositoryInterface $Cities_repository)
    {
        $this->Cities_repository = $Cities_repository;
    }

    /**
     * @param CitiesRequest $request
     * @return array
     * @throws Exception
     */
    public function execute(CitiesRequest $request): array
    {
        $cities = $this->Cities_repository->getByProvinsiId($request->getProvinsiId());
        if (!$cities) {
            SchematicsException::throw("Kota/Kabupaten tidak ketemu", 1006, 404);
        }
        return array_map(function (Kota $kota) {
            return new CitiesResponse(
                $kota->getId(),
                $kota->getForeign(),
                $kota->getName()
            );
        }, $cities);
    }
}
