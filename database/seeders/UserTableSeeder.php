<?php

namespace Database\Seeders;

use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends CsvSeeder {

	public function __construct()
	{
		$this->table = 'user';
		$this->filename = base_path().'/database/seeders/csv/User.csv';
	}
    
	public function run()
	{
		// Recommended when importing larger CSVs
		DB::disableQueryLog();

		// Uncomment the below to wipe the table clean before populating
		//DB::table($this->table)->truncate();

		parent::run();
	}
}
