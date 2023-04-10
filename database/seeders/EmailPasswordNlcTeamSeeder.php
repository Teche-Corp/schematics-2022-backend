<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmailPasswordNlcTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for($i=1 ; $i<=17 ; $i++) {
            $firstName = $faker->firstName;
            $username = $firstName.$i;
            $email = $username.'@gmail.com';
            DB::table('nlc_team')->where('id',$i)->update([
                'email_lomba'=> $email,
                'password_lomba' => $faker->password(6)
            ]);
        }
    }
}
