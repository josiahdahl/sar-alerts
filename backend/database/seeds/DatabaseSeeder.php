<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TODO: Seed User's table
        // $this->call(UsersTableSeeder::class);
        $this->call(DataSourceSeeder::class);
        $this->call(AppSeeder::class);
    }
}
