<?php

namespace App\Jobs;

use App\DataWeather;
use App\DataWind;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Integrations\Integration;

class GetWeatherAndWind implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $weather;
    protected $wind;
    protected $integration;

    /**
     * Create a new job instance.
     *
     * @param Integration $integration
     */
    public function __construct(Integration $integration)
    {
        $this->integration = $integration;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // This is obviously terrible once we have more than a few cities.
        // TODO: Update a chunking system for updating API data from cities
        $weather_items = $this->weather->all()->select('')
        $wind_items DataWeather::all();
    }
}
