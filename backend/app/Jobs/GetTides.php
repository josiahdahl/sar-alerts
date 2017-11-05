<?php

namespace App\Jobs;

use App\DataTide;
use App\Contracts\Integrations\DataServicesIntegrationContract;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class GetTides implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $locationDataSource;

    public $tries = 3;

    /**
     * Create a new job instance.
     * @param $locationDataSource
     */
    public function __construct($locationDataSource)
    {
        $this->locationDataSource = $locationDataSource;
    }

    /**
     * Execute the job.
     *
     * @param DataServicesIntegrationContract $integration
     * @return void
     * @throws Exception    If job is failed or not ready yet
     */
    public function handle(DataServicesIntegrationContract $integration)
    {
        Log::info('Getting tides!');

        $haveResults = FALSE;
        $attempts = 0;
        $response = null;

        $integration->request($this->locationDataSource);
        sleep(2);

        while (!$haveResults && $attempts < 5) {
            $response = $integration->retrieve();
            if ($response['status'] === 'pending' || $response['status'] === 'started') {
                Log::info('Tides are pending', $response);
            } elseif ($response['status'] === 'failed') {
                Log::info('Tides are failed :(', $response);
            } elseif ($response['status'] !== 'finished') {
                Log::info('Some strange response code!', $response);
            } else {
                Log::info('Got eeeemmm', $response);
                $haveResults = TRUE;
            }

            $attempts++;
            sleep(2);
        }

        if (!$haveResults) {
            Log::error('Couldn\'t get tides. Trying again maybe');
            throw new Exception('Failed getting tides');
        }

        /*  Results
            "result": [
        [
            {
                "datetime": "2017-11-05 03:10:00",
                "height": 2.4,
                "high_low": "high",
                "timezone": "PST",
                "unit": "m"
            },
            {
                "datetime": "2017-11-05 07:33:00",
                "height": 2.0,
                "high_low": "low",
                "timezone": "PST",
                "unit": "m"
            },
        ], [...]...
         */
        $tidesOnDays = $response['result'];

        collect($tidesOnDays)->each(function ($tidesOnDay) {
            collect($tidesOnDay)->each(function ($tide) {
                $newTide = DataTide::create([
                    'location_data_source_id' => $this->locationDataSource->id,
                    'time' => $tide['datetime'],
                    'timezone' => $tide['timezone'],
                    'height' => $tide['height'],
                    'high_low' => $tide['high_low'],
                ]);
                Log::info('Added tide data for ' . $tide['datetime']);
            });
        });
    }
}
