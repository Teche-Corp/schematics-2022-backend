<?php

namespace Database\Seeders;

use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NlcTeamPemanasanSeeder extends CsvSeeder
{
    public function __construct()
	{
		$this->table = 'nlc_team';
		$this->filename = base_path().'/database/seeders/csv/NlcTeamPemanasan.csv';
	}
    
	public function run()
	{
		// Recommended when importing larger CSVs
		DB::disableQueryLog();

		// Uncomment the below to wipe the table clean before populating
		// DB::table($this->table)->truncate();
		parent::run();


	}
}
