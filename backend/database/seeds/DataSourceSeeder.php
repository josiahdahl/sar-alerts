<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_source_id = DB::table('data_sources')->insertGetId([
            'name' => 'OpenWeatherMap',
            'description' => 'Free weather API',
            'url' => 'https://openweathermap.org/'
        ]);

        $location_id = DB::table('locations')->insertGetId([
            'name' => 'Sooke',
            'description' => 'A great city',
            'province' => 'BC',
            'country' => 'Canada',
            'latitude' => 48.37,
            'longitude' => -123.73,
            'timezone' => 'America/Vancouver',
        ]);

        DB::table('location_data_sources')->insert([
            'data_source_id' => $data_source_id,
            'location_id' => $location_id,
            'location_identifier' => json_encode(['cityId' => 6151264]),
        ]);
    }
}
