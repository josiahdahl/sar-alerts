<?php

namespace App\Jobs;

use App\DataSource;
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
     * @param DataSource $dataSource
     * @return void
     */
    public function handle(DataSource $dataSource)
    {

    }
}
