<?php

namespace App\Jobs;

use App\DataTide;
use App\Contracts\Integrations\DataServicesIntegrationContract;
use App\Mail\DataTidesMailable;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            $errorMailData = [
                'date' => Carbon::now()->toIso8601String(),
                'message' => 'Error getting tide data',
                'subject' => 'Error Getting Tide Data',
            ];
            Mail::to(config('mail.admins'))
                ->queue(new DataTidesMailable($errorMailData));

            Log::error('Couldn\'t get tides. Trying again maybe');
            throw new Exception('Failed getting tides');
        }

        /*  Results
            "result": [
                [
                    {
                        "date": "2017-11-05",
                        "time": "03:10:00"
                        "height": 2.4,
                        "high_low": "high",
                        "timezone": "PST",
                        "unit": "m"
                    }, ...
                ], [...]...
            ]
         */
        $tidesOnDays = $response['result'];
        collect($tidesOnDays)->each(function ($tidesOnDay) {
            DB::transaction(function () use ($tidesOnDay) {
                $locationDataSourceId = $this->locationDataSource->id;
                // Remove old records for the day
                DataTide::where([
                    ['location_data_source_id', $locationDataSourceId],
                    ['time', Carbon::parse("{$tidesOnDay[0]['date']} {$tidesOnDay[0]['time']}", $tidesOnDay[0]['timezone'])],
                ])->delete();
                collect($tidesOnDay)->each(function ($tide) {
                    Log::debug('Timezone: ' . $tide['timezone']);
                    $dataArray = [
                        'location_data_source_id' => $this->locationDataSource->id,
                        'time' => Carbon::parse("{$tide['date']} {$tide['time']}"),
                        'tz' => $tide['timezone'],
                        'height' => $tide['height'],
                        'high_low' => $tide['high_low'],
                    ];
                    Log::debug($dataArray);
                    $newTide = DataTide::create($dataArray);
                    Log::info('Added tide data for ' . $tide['date']);
                });
            });
        });

        $successMailData = [
            'date' => Carbon::now()->toIso8601String(),
            'message' => 'Successfully updated tide data',
            'subject' => 'Updated Tide Data',
        ];
        Mail::to(config('mail.admins'))
            ->queue(new DataTidesMailable($successMailData));
    }
}
