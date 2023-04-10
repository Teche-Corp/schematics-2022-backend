<?php

namespace Database\Seeders;

use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Support\Facades\DB;

class NlcMemberPemanasanSeeder extends CsvSeeder
{
    public function __construct()
	{
		$this->table = 'nlc_member';
		$this->filename = base_path().'/database/seeders/csv/NlcMemberPemanasan.csv';
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
