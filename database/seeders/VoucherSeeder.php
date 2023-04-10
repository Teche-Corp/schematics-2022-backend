<?php

namespace Database\Seeders;

use App\Core\Domain\Models\Voucher\Voucher;
use App\Infrastrucutre\Repository\SqlVoucherRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use League\Csv\Reader;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $repository = new SqlVoucherRepository();
        $records = Reader::createFromPath(
            "database/seeders/csv/kodepromo.csv",
            "r",
        );
        $records->setDelimiter(";");
        $records->setHeaderOffset(0);
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
