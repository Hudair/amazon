<?php

namespace App\Helpers;

use Analytics;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\Analytics\Period;

class GoogleAnalytics
{
    public static function fetchTotalVisitorsSessionsAndPageViews(Period $period, $dimension = 'month'): Collection
    {
        // try {
        $response = Analytics::performQuery(
                $period,
                'ga:users,ga:sessions,ga:pageviews,ga:sessionDuration,ga:bounces',
                ['dimensions' => 'ga:'.$dimension]
            );
        // } catch (\Google\Service\Exception $e) {
        //     echo "<pre>"; print_r($e->getMessage()); echo "<pre>"; exit('end!');
        // }

        return collect($response['rows'] ?? [])->map(function (array $dateRow) {
            return [
                'period' => (int) $dateRow[0],
                'visitors' => (int) $dateRow[1],
                'sessions' => (int) $dateRow[2],
                'pageViews' => (int) $dateRow[3],
            ];
        });
    }

    public static function topDevice(Period $period): Collection
    {
        $data = Analytics::performQuery(
            $period,
            'ga:sessions',
            ['dimensions'=>'ga:deviceCategory', 'sort'=>'-ga:sessions']);

        return collect($data['rows'] ?? [])->map(function (array $dateRow) {
            return [
                'deviceCategory' =>  $dateRow[0],
                'sessions' => (int) $dateRow[1],
            ];
        });
    }

    public static function country(Period $period): Collection
    {
        $country = Analytics::performQuery(
            $period,
            'ga:sessions',
            ['dimensions'=>'ga:country', 'sort'=>'-ga:sessions']);

        $result = collect($country['rows'] ?? [])->map(function (array $dateRow) {
            return [
                'country' =>  $dateRow[0],
                'sessions' => (int) $dateRow[1],
            ];
        });
        /* $data['country'] = $result->pluck('country'); */
        /* $data['country_sessions'] = $result->pluck('sessions'); */
        return $result;
    }
}
