<?php

namespace App\Infrastrucutre\Repository;

use App\Core\Domain\Models\Provinsi\Provinsi;
use App\Core\Domain\Repository\ProvincesRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SqlProvinsiRepository implements ProvincesRepositoryInterface
{
    /**
     * @throws Exception
     */
    public function getAll(): array
    {
        $rows = DB::table('provinces')->get();

        return $this->constructFromRows($rows->all());
    }

    /**
     * @param array $rows
     * @return Provinsi[]
     * @throws Exception
     */
    public function constructFromRows(array $rows): array
    {
        $provinces = [];
        foreach ($rows as $row) {
            $provinces[] = new
            Provinsi(
                $row->id,
                $row->name,
            );
        }
        return $provinces;
    }
}
