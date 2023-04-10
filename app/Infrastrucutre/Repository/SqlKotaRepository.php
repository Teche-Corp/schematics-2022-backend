<?php

namespace App\Infrastrucutre\Repository;

use App\Core\Domain\Models\Kota\Kota;
use App\Core\Domain\Repository\CitiesRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SqlKotaRepository implements CitiesRepositoryInterface
{
    /**
     * @param int $provinsi_id
     * @return Kota[]
     * @throws Exception
     */
    public function getByProvinsiId(int $provinsi_id): array
    {
        $row = DB::table('cities')->where('foreign', '=', $provinsi_id)->get();

        return $this->constructFromRows($row->all());
    }

    public function find(int $id): ?Kota
    {
        $row = DB::table('cities')->where('id', $id)->first();
        return $this->constructFromRows([$row])[0];
    }

    /**
     * @param array $rows
     * @return Kota[]
     * @throws Exception
     */
    public function constructFromRows(array $rows): array
    {
        $cities = [];
        foreach ($rows as $row) {
            $cities[] = new Kota(
                $row->id,
                $row->foreign,
                $row->name,
            );
        }
        return $cities;
    }
}
