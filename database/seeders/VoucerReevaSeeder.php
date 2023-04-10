<?php

namespace Database\Seeders;

use App\Core\Domain\Models\Voucher\Voucher;
use App\Infrastrucutre\Repository\SqlVoucherRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucerReevaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [
            /*[
                "kode" => "TESTINGREEVA",
                "potongan" => 33500,
                "kuota" => 10,
                "region" => 0,
                "start_time" => "2022-09-08  00:00:00",
                "end_time" => "2022-09-12  00:00:00",
                "tipe" => "reeva",
            ],
            [
                "kode" => "NOBARSCHREEVA",
                "potongan" => 33500,
                "kuota" => 40,
                "region" => 0,
                "start_time" => "2022-09-10  16:00:00",
                "end_time" => "2022-09-16  16:00:00",
                "tipe" => "reeva",
            ],*/
            [
                "kode" => "COUPLE",
                "potongan" => 30000,
                "kuota" => 1000,
                "region" => 0,
                "start_time" => "2022-11-03 09:30:00",
                "end_time" => "2022-11-12 16:00:00",
                "tipe" => "reeva",
            ],
            [
                "kode" => "GROUP",
                "potongan" => 0,
                "kuota" => 1000,
                "region" => 0,
                "start_time" => "2022-11-03 09:30:00",
                "end_time" => "2022-11-12 16:00:00",
                "tipe" => "reeva",
            ],
        ];

        $repository = new SqlVoucherRepository();

        foreach ($records as $record) {
            $voucher = Voucher::create(
                $record['kode'],
                $record['potongan'],
                $record['kuota'],
                $record['region'],
                $record['start_time'],
                $record['end_time'],
                $record['tipe']
            );
            $repository->persist($voucher);
        }
    }
}
