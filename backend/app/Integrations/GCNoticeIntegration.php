<?php
/**
 * Created by PhpStorm.
 * User: jdahl
 * Date: 11/5/17
 * Time: 3:24 PM
 */

namespace App\Integrations;


use App\Contracts\Integrations\NotificationIntegrationContract;
use App\LocationDataSource;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use function simplexml_load_string;
use function str_contains;

/**
 * Class GCNoticeIntegration
 *
 * This integration expects a feed URL which is the ATOM feed and a location identifier to search for.
 * @package App\Integrations
 */
class GCNoticeIntegration implements NotificationIntegrationContract
{
    protected $feedUrl;
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param int $locationId
     * @param $idTag
     * @return boolean
     */
    private function isWarning($idTag, $locationId)
    {
        return preg_match("/{$locationId}_w\d+/", $idTag) === 1;
    }

    /**
     * @param $idTag
     * @param int $locationId
     * @return boolean
     */
    private function isRegularForecast($idTag, $locationId)
    {
        return preg_match("/{$locationId}_regular_forecast/", $idTag) === 1;
    }

    private function formatSummary($summary)
    {
        return trim(
            str_replace("\n", ' ',
                explode('<br/>', $summary)[0]
            )
        );
    }

    /**
     * @param LocationDataSource $locationDataSource
     * @throws RequestException
     * @return array
     */
    public function get(LocationDataSource $locationDataSource)
    {
        $settings = $locationDataSource->location_identifier;

        $feedUrl = $settings['feedUrl'];
        $locationId = $settings['locationId'];

        $cacheKey = "gcnotice_{$locationId}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $request = $this->client->get($feedUrl);
        $response = $request->getBody();


        $xml = simplexml_load_string($response->getContents());

        $entries = [
            'warnings' => [],
        ];
        foreach ($xml->entry as $entry) {

            if ($this->isWarning($entry->id, $locationId)) {
                $entries['warnings'][] = [
                    'id' => $locationId,
                    'title' => (string)$entry->title,
                    'updated' => (string)$entry->updated,
                    'category_title' => (string)$entry->category['term'],
                ];
            } else if ($this->isRegularForecast($entry->id, $locationId)) {
                $entries['forecast'] = [
                    'id' => $locationId,
                    'title' => (string)$entry->title,
                    'updated' => (string)$entry->updated,
                    'category_title' => (string)$entry->category['term'],
                    'summary' => $this->formatSummary((string)$entry->summary),
                ];
            }
        }

        Cache::put($cacheKey, $entries, Carbon::now()->addHours(1));
        return $entries;
    }

}
