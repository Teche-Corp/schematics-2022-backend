<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use File;

// Untuk memindahkan database dari json ke mysql

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(database_path('seeders/json/provinces.json'));
        $provinces = json_decode($json, true);

        $payload = [];
        foreach ($provinces as $prov) {
            $payload[] = [
                'id' => $prov['id'],
                'name' => $prov['name']
            ];
        }
        DB::table('provinces')->insert($payload);
    }
}
