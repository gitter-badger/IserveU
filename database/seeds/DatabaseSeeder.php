<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query;


class DatabaseSeeder extends Seeder {


	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call(EthnicOriginTableSeeder::class);
		$this->command->info('Ethnic origins seeded'); 

		$this->call(DepartmentTableSeeder::class);
		$this->command->info('Departments seeded'); 

		$this->call(NWTCommunitiesTableSeeder::class);
		$this->command->info('NWT Communitites seeded'); 

	}

}

