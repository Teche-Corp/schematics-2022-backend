<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


// Untuk memindahkan database dari json ke mysql



class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(database_path('seeders/json/cities.json'));
        $cities = json_decode($json, true);

        $payload = [];
        foreach ($cities as $city) {
            $payload[] = [
                'id' => $city['id'],
                'foreign' => $city['foreign'],
                'name' => $city['name']
            ];
        }
        DB::table('cities')->insert($payload);
    }
}
