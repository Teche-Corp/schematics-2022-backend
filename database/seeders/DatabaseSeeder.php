<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\User50Seeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\UserTableSeeder;
use Database\Seeders\NlcTeamTableSeeder;
use Database\Seeders\NpcTeamTableSeeder;
use Database\Seeders\NlcMemberTableSeeder;
use Database\Seeders\NpcMemberTableSeeder;
use Database\Seeders\NlcTeamPemanasanSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

		//disable foreign key check for this connection before running seeders
		//DB::statement('SET FOREIGN_KEY_CHECKS=0;');
			
        //$this->call(ProvincesTableSeeder::class);
        //$this->call(CitiesTableSeeder::class);
        //$this->call(User50Seeder::class);
        //$this->call(NlcTeamPemanasanSeeder::class);
        //$this->call(UserTableSeeder::class);
        //$this->call(NlcTeamTableSeeder::class);
        //$this->call(NlcMemberTableSeeder::class);
        //$this->call(NpcTeamTableSeeder::class);
        //$this->call(NpcMemberTableSeeder::class);
		$this->call(VoucerReevaSeeder::class);
        // supposed to only apply to a single connection and reset it's self
		// but I like to explicitly undo what I've done for clarity
		//DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
