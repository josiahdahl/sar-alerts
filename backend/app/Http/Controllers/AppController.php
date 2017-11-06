<?php

namespace App\Http\Controllers;

use App\Integrations\OpenWeatherMapIntegration;
use App\Jobs\GetTides;
use App\LayoutWidget;
use App\LocationDataSource;
use App\Station;
use Illuminate\Http\Request;

class AppController extends Controller
{

    public function index(Request $request)
    {
        // TODO: Don't hardcode this
        $stationId = 1;

        // TODO: Cache this
        $layoutData = LayoutWidget::with(['widget', 'widgetDataSource.locationDataSource'])
            ->where('station_id', $stationId)
            ->orderBy('row')
            ->get();

        $layout = $layoutData->reduce(function ($rows, $col) {
            $row = $col->row - 1;
            $widget = $col->widget->component_name;
            $sizes = $col->sizes;
            $dataSources = $col->widgetDataSource->map(function ($dataSource) {
                return [
                    'endpoint' => $dataSource->locationDataSource->endpoint,
                    'locationId' => $dataSource->locationDataSource->location_id,
                    'dataType' => $dataSource->locationDataSource->provides,
                ];
            });

            $colData = [
                'widget' => $widget,
                'sizes' => $sizes,
                'dataSources' => $dataSources,
            ];

            if (!isset($rows[$row])) {
                $rows[$row] = [];
            }
            array_push($rows[$row], $colData);
            return $rows;
        }, []);

        return view('app', ['data' => json_encode($pageData)]);

    }

    public function indexOld(Request $request, OpenWeatherMapIntegration $owm)
    {
        $weatherData = $owm->get(['6151264']);

        $weatherDescription = $weatherData['weather'][0]['main'];
        $weatherTemperature = number_format($weatherData['main']['temp'], 1);

        $windCity = $weatherData['name'];
        $windSpeed = number_format(3.6 * $weatherData['wind']['speed'], 1); // m/s to km/h
        $windDirection = $this->windFromDeg($weatherData['wind']['deg']);

        /*
         * Layout is an array of arrays (rows).
         * The rows contain an array of objects which are the columns. The columns include widgets
         */
        $pageData = ['layout' =>
            [
                [
                    [
                        'widget' => 'WidgetWeather',
                        'sizes' => [
                            'sm' => 4,
                        ],
                        'widgetClass' => '',
                        'content' => [
                            'items' => [[
                                'icon' => '/images/icons/heavy_rain_showers.svg',
                                'temperature' => $weatherTemperature,
                                'shortDescription' => $weatherDescription,
                            ]],
                        ]
                    ], [
                    'widget' => 'WidgetTime',
                    'sizes' => [
                        'sm' => 8,
                    ],
                    'widgetClass' => 'w-time',
                    'content' => [
                        'items' => []
                    ]
                ]
                ], [
                [
                    'widget' => 'Widget',
                    'sizes' => [
                        'sm' => 12,
                    ],
                    'widgetClass' => 'w-tides',
                    'content' => [
                        'title' => 'Tides',
                        'items' => [[
                            'label' => 'Flooding',
                            'data' => '3.2m',
                        ], [
                            'label' => 'High Tide',
                            'data' => '14 =>53 3.2m',
                        ]],
                    ],
                ]
            ],
                [
                    [
                        'widget' => 'WidgetWind',
                        'sizes' => [
                            'sm' => 12,
                        ],
                        'widgetClass' => 'w-wind',
                        'content' => [
                            'title' => 'Wind',
                            'items' => [[
                                'direction' => $windDirection,
                                'speed' => $windSpeed,
                                'location' => $windCity,
                            ], [
                                'direction' => 'NW',
                                'speed' => '12',
                                'location' => 'Port Renfrew',
                            ]]
                        ],
                    ]
                ],
                [
                    [
                        'widget' => 'WidgetNotice',
                        'sizes' => [
                            'sm' => 12,
                        ],
                        'widgetClass' => '',
                        'content' => [
                            'title' => 'Warning',
                            'items' => [[
                                'content' => 'Small craft advisory for Juan de Fuca Strait',
                                'created' => 'Wed Nov 01 2017 14:36:36 GMT-0700 (PDT)',
                            ]],
                        ],
                    ]
                ],
            ]
        ];


        return view('app', ['data' => json_encode($pageData)]);
    }

    public function triggerTidesData()
    {
        $tideSources = LocationDataSource::where('provides', 'tides')->get();
        $tideSources->each(function ($tideSource) {
            GetTides::dispatch($tideSource);
        });
    }
}
