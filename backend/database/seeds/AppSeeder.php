<?php

use App\Location;
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
            'location_id' => Location::where('name', 'Sooke')->first()->id,
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

        $widgetWindId = DB::table('widgets')->insertGetId([
            'name' => 'Current Wind',
            'component_name' => 'WidgetWind',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $widgetTidesId = DB::table('widgets')->insertGetId([
            'name' => 'Tides',
            'component_name' => 'WidgetTides',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);


        // Setup the default layout
        $layoutWeatherId = DB::table('layout_widgets')->insertGetId([
            'station_id' => $stationId,
            'widget_id' => $widgetWeatherId,
            'row' => 1,
            'col_sm' => 6,
            'col_md' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $layoutTimeId = DB::table('layout_widgets')->insertGetId([
            'station_id' => $stationId,
            'widget_id' => $widgetTimeId,
            'row' => 1,
            'col_xs' => 0,
            'col_sm' => 6,
            'col_md' => 8,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $layoutTidesId = DB::table('layout_widgets')->insertGetId([
            'station_id' => $stationId,
            'widget_id' => $widgetTidesId,
            'row' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $layoutWindId = DB::table('layout_widgets')->insertGetId([
            'station_id' => $stationId,
            'widget_id' => $widgetWindId,
            'row' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);


        $layoutNoticesId = DB::table('layout_widgets')->insertGetId([
            'station_id' => $stationId,
            'widget_id' => $widgetNoticeId,
            'row' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Setup the default data to display
        $sookeWeatherDataSource = LocationDataSource::where([
            ['provides', 'weather'],
            ['location_id', 1]
        ])->first();
        $sookeTideDataSource = LocationDataSource::where('provides', 'tides')->first();
        $sookeNotificationDataSource = LocationDataSource::where('provides', 'notices')->first();
        $sookeTimeDataSource = LocationDataSource::where('provides', 'time')->first();

        $portRenfrewWeatherDataSource = LocationDataSource::where([
            ['provides', 'weather'],
            ['location_id', 2]
        ])->first();

        $victoriaWeatherDataSource = LocationDataSource::where([
            ['provides', 'weather'],
            ['location_id', 3]
        ])->first();

        /// SOOKE
        // Weather
        // TODO: Update with additional cities from DataSource Seeder
        DB::table('widget_data_sources')->insertGetId([
            'layout_widget_id' => $layoutWeatherId,
            'location_data_source_id' => $sookeWeatherDataSource->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('widget_data_sources')->insertGetId([
            'layout_widget_id' => $layoutWindId,
            'location_data_source_id' => $sookeWeatherDataSource->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Time
        DB::table('widget_data_sources')->insertGetId([
            'layout_widget_id' => $layoutTimeId,
            'location_data_source_id' => $sookeTimeDataSource->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Tides
        DB::table('widget_data_sources')->insertGetId([
            'layout_widget_id' => $layoutTidesId,
            'location_data_source_id' => $sookeTideDataSource->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Notices
        DB::table('widget_data_sources')->insertGetId([
            'layout_widget_id' => $layoutNoticesId,
            'location_data_source_id' => $sookeNotificationDataSource->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        /// PORT RENFREW
        DB::table('widget_data_sources')->insertGetId([
            'layout_widget_id' => $layoutWindId,
            'location_data_source_id' => $portRenfrewWeatherDataSource->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        /// Victoria
        DB::table('widget_data_sources')->insertGetId([
            'layout_widget_id' => $layoutWindId,
            'location_data_source_id' => $victoriaWeatherDataSource->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
