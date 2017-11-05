<?php

use App\LocationDataSource;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // The default station
        $stationId = DB::table('stations')->insertGetId([
            'name' => 'RCM-SAR Station 37 Sooke',
            'timezone' => 'Americas/Vancouver',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Setup the basic widgets
        $widgetNoticeId = DB::table('widgets')->insertGetId([
            'name' => 'Notices/Alerts',
            'component_name' => 'WidgetNotice',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $widgetTimeId = DB::table('widgets')->insertGetId([
            'name' => 'Local Time & Date',
            'component_name' => 'WidgetTime',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $widgetWeatherId = DB::table('widgets')->insertGetId([
            'name' => 'Current Weather',
            'component_name' => 'WidgetWeather',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $widgetTidesId = DB::table('widgets')->insertGetId([
            'name' => 'Tides',
            'component_name' => 'WidgetTides',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $widgetWindId = DB::table('widgets')->insertGetId([
            'name' => 'Current Wind',
            'component_name' => 'WidgetWind',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Setup the default layout
        $layoutWeatherId = DB::table('layout_widgets')->insertGetId([
            'station_id' => $stationId,
            'widget_id' => $widgetWeatherId,
            'row' => 1,
            'cols_default' => 12,
            'cols_xs' => 6,
            'cols_sm' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $layoutTimeId = DB::table('layout_widget')->insertGetId([
            'station_id' => $stationId,
            'widget_id' => $widgetTimeId,
            'row' => 1,
            'cols_default' => 0,
            'cols_xs' => 6,
            'cols_sm' => 8,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $layoutTidesId = DB::table('layout_widget')->insertGetId([
            'station_id' => $stationId,
            'widget_id' => $widgetTidesId,
            'row' => 2,
            'cols_default' => 12,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $layoutWindId = DB::table('layout_widget')->insertGetId([
            'station_id' => $stationId,
            'widget_id' => $widgetWindId,
            'row' => 3,
            'cols_default' => 12,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $layoutNoticesId = DB::table('layout_widget')->insertGetId([
            'station_id' => $stationId,
            'widget_id' => $widgetNoticeId,
            'row' => 4,
            'cols_default' => 12,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Setup the default data to display
        // TODO: Get the additional data sources for the other locations
        $weatherDataSource = LocationDataSource::where('provides', 'weather')->first();
        $tideDataSource = LocationDataSource::where('provides', 'tides')->first();

        // Weather
        // TODO: Update with additional cities from DataSource Seeder
        DB::table('widget_data_sources')->insertGetId([
            'layout_widget_id' => $layoutWeatherId,
            'location_data_source_id' => $weatherDataSource->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Time
        // TODO: Create a provider for time data
        DB::table('widget_data_sources')->insertGetId([
            'layout_widget_id' => $layoutTimeId,
            'location_data_source_id' => $weatherDataSource->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Wind
        DB::table('widget_data_sources')->insertGetId([
            'layout_widget_id' => $layoutWindId,
            'location_data_source_id' => $weatherDataSource->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Tides
        DB::table('widget_data_sources')->insertGetId([
            'layout_widget_id' => $layoutTidesId,
            'location_data_source_id' => $weatherDataSource->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Notices
        DB::table('widget_data_sources')->insertGetId([
            'layout_widget_id' => $layoutWeatherId,
            'location_data_source_id' => $weatherDataSource->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);





    }
}
