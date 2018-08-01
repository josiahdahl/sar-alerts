<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Integrations\DataServicesIntegrationContract;
use App\DataTide;
use App\Station;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StationTidesController extends Controller
{

    public function view($id)
    {
        $referenceTime = Carbon::now();
        $initialLowerLimit = $referenceTime->copy()->subDays(3);
        $initialUpperLimit = $referenceTime->copy()->addDays(3);
        /** @var Collection $locationData */
        $locationData = Station::find($id)
            ->widgets()
            ->with([
                'data' => function ($query) {
                    $query->provides('tides');
                },
                'data.location',
                'data.tides' => function ($query) use ($initialLowerLimit, $initialUpperLimit) {
                    $query->between($initialLowerLimit, $initialUpperLimit);
                }])
            ->get()
            ->pluck('data')
            ->flatten()
            ->map(function ($dataSource) {
                $timezone = $dataSource->location->timezone;
                $currentTimeInTimezone = Carbon::now($timezone);
                $lowerLimit = $currentTimeInTimezone->copy()->subDay()->subHours(12);
                $uppperLimit = $currentTimeInTimezone->copy()->addDay()->addHours(12);
                $filteredTides = collect($dataSource->tides)
                    ->filter(function ($tideData) use ($lowerLimit, $uppperLimit, $timezone) {
                        $tideDataTime = Carbon::parse($tideData['time'])->timezone($timezone);
                        return $tideDataTime->gte($lowerLimit) && $tideDataTime->lte($uppperLimit);
                    })
                    ->values();
                return [
                    'location' => $dataSource->location->toArray(),
                    'tides' => $filteredTides->toArray(),
                ];
            });

        return response()->json($locationData->toArray(), 200);
    }
}
