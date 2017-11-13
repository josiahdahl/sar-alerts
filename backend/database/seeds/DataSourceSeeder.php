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
        // TODO: Add required fields to data source
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
            'name' => 'GCWeatherNotices',
            'description' => 'Government of Canada Weather Alerts and Forecasts',
            'url' => 'http://weather.gc.ca/marine/forecast_e.html',
        ]);

        $timeProviderId = DB::table('data_sources')->insertGetId([
            'name' => 'Local Time',
            'description' => 'Returns the local time at a location',
            'url' => 'n/a',
        ]);

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

        $victoriaId = DB::table('locations')->insertGetId([
            'name' => 'Victoria',
            'description' => 'A great city',
            'province' => 'BC',
            'country' => 'Canada',
            'latitude' => 48.43,
            'longitude' => -123.37,
            'timezone' => 'America/Vancouver',
        ]);

        $juanDeFucaCenterId = DB::table('locations')->insertGetId([
            'name' => 'Juan de Fuca Strait - central strait',
            'description' => '',
            'province' => 'BC',
            'country' => 'Canada',
            'latitude' => 48.3,
            'longitude' => -124.27,
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
            'data_source_id' => $owm_id,
            'location_id' => $victoriaId,
            'location_identifier' => json_encode(['cityId' => 6174041]),
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
            'location_id' => $juanDeFucaCenterId,
            'location_identifier' => json_encode([
                'feedUrl' => 'https://weather.gc.ca/rss/marine/07000_e.xml',
                'locationId' => '07010'
            ]),
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
