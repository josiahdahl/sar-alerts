<?php

namespace App\Http\Controllers;

use App\Integrations\OpenWeatherMapIntegration;
use Illuminate\Http\Request;

class AppController extends Controller
{
    private function windFromDeg($deg)
    {
        if ($deg < 22.5) {
            return 'N';
        }
        if ($deg < 67.5) {
            return 'NE';
        }
        if ($deg < 112.5) {
            return 'E';
        }
        if ($deg < 157.5) {
            return 'SE';
        }
        if ($deg < 202.5) {
            return 'S';
        }
        if ($deg < 247.5) {
            return 'SW';
        }
        if ($deg < 292.5) {
            return 'W';
        }
        if ($deg < 337.5) {
            return 'NW';
        }
        return 'N';
    }

    public function index(Request $request, OpenWeatherMapIntegration $owm)
    {
        $weatherData = $owm->get(['6151264']);

        $weatherDescription = $weatherData['weather'][0]['main'];
        $weatherTemperature = number_format($weatherData['main']['temp'], 1);

        $windCity = $weatherData['name'];
        $windSpeed = number_format(3.6 * $weatherData['wind']['speed'], 1); // m/s to km/h
        $windDirection = $this->windFromDeg($weatherData['wind']['deg']);

        $pageData = ['layout' =>
            [
                [[
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
                ]], [
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
}
