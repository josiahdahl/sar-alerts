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
        $owm_id = DB::table('data_sources')->insertGetId([
            'name' => 'OpenWeatherMap',
            'description' => 'Free weather API',
            'url' => 'https://openweathermap.org/',
        ]);

        $gctides_id = DB::table('data_sources')->insertGetId([
            'name' => 'GCTides',
            'description' => 'Government of Canada Tides - Weekly tide data',
            'url' => 'http://tides.gc.ca/eng/station',
        ]);

        $appNotificationsId = DB::table('data_sources')->insertGetId([
            'name' => 'Notifications/Alerts',
            'description' => 'Manually entered notifications and alerts',
            'url' => 'n/a',
        ]);

        $timeProviderId = DB::table('data_sources')->insertGetId([
            'name' => 'Local Time',
            'description' => 'Returns the local time at a location',
            'url' => 'n/a',
        ]);

        // TODO: Add Beechy Head, Jordan River, and Port Renfrew locations/weather
        $sookeId = DB::table('locations')->insertGetId([
            'name' => 'Sooke',
            'description' => 'A great city',
            'province' => 'BC',
            'country' => 'Canada',
            'latitude' => 48.37,
            'longitude' => -123.73,
            'timezone' => 'America/Vancouver',
        ]);

        $portRenfrewId = DB::table('locations')->insertGetId([
            'name' => 'Port Renfrew',
            'description' => 'A great city',
            'province' => 'BC',
            'country' => 'Canada',
            'latitude' => 48.56,
            'longitude' => -124.4,
            'timezone' => 'America/Vancouver',
        ]);



        DB::table('location_data_sources')->insert([
            'data_source_id' => $owm_id,
            'location_id' => $sookeId,
            'location_identifier' => json_encode(['cityId' => 6151264]),
            'provides' => 'weather',
            'endpoint' => '/api/v1/weather'
        ]);

        DB::table('location_data_sources')->insert([
            'data_source_id' => $owm_id,
            'location_id' => $portRenfrewId,
            'location_identifier' => json_encode(['cityId' => 6111995]),
            'provides' => 'weather',
            'endpoint' => '/api/v1/weather'
        ]);

        DB::table('location_data_sources')->insert([
            'data_source_id' => $gctides_id,
            'location_id' => $sookeId,
            'location_identifier' => json_encode(['locationId' => 7020]),
            'provides' => 'tides',
            'endpoint' => '/api/v1/tides'
        ]);

        DB::table('location_data_sources')->insert([
            'data_source_id' => $appNotificationsId,
            'location_id' => $sookeId,
            'location_identifier' => json_encode([]),
            'provides' => 'notices',
            'endpoint' => '/api/v1/notices',
        ]);

        DB::table('location_data_sources')->insert([
            'data_source_id' => $timeProviderId,
            'location_id' => $sookeId,
            'location_identifier' => json_encode([]),
            'provides' => 'time',
            'endpoint' => '/api/v1/notices',
        ]);
    }
}
