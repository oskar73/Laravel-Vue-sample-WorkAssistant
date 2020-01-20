<?php

namespace App\Integration;

use Illuminate\Support\Facades\Storage;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\OrderBy;
use Spatie\Analytics\Period;

class GoogleAnalytics
{
    public const CREDENTIAL_JSON_FILE = 'analytics/service-account-credentials.json';
    public const CREDENTIAL_JSON_DISK = 's3-pub-bizinabox';

    public static function country($period)
    {
        $countries = Analytics::get(Period::days($period), ['activeUsers'], ['country'], 10, [
            OrderBy::metric('activeUsers', false),
        ]);

        return $countries->map(function (array $dateRow) {
            return [
                'country' => $dateRow['country'],
                'sessions' => $dateRow['activeUsers'],
            ];
        });
    }

    public static function totalVisitorsAndPageViews($period): array
    {
        $analyticsData_one = Analytics::fetchTotalVisitorsAndPageViews(Period::days($period));

        info($analyticsData_one);

        $dates = $analyticsData_one->pluck('date');

        $dates = $dates->map(function ($date) {
            return $date->format('d/m');
        });

        $visitors = $analyticsData_one->pluck('activeUsers');
        $pageViews = $analyticsData_one->pluck('screenPageViews');

        return [
            'dates' => $dates,
            'visitors' => $visitors,
            'pageViews' => $pageViews,
        ];
    }

    public static function topBrowsers($period)
    {
        $analyticsData = Analytics::fetchTopBrowsers(Period::days($period));
        $array = $analyticsData->toArray();
        $result = [];
        foreach ($array as $k => $v) {
            $result['label'][] = $array[$k] ['browser'] ?? null;
            $result['value'][] = $array[$k] ['sessions'] ?? null;
            $result['color'][] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        }

        return json_encode($result);
    }

    public static function uploadCredentialJsonFile($file): bool
    {
        return Storage::disk(self::CREDENTIAL_JSON_DISK)
            ->putFileAs("", $file, self::CREDENTIAL_JSON_FILE, 'private');
    }

    public static function deleteCredentialJsonFile(): bool
    {
        return Storage::disk(self::CREDENTIAL_JSON_DISK)
            ->delete(self::CREDENTIAL_JSON_FILE);
    }

    public static function credentialJsonFileExists(): bool
    {
        return Storage::disk(self::CREDENTIAL_JSON_DISK)
            ->exists(self::CREDENTIAL_JSON_FILE);
    }

    public static function loadCredentialJsonFile(): void
    {
        if (self::CREDENTIAL_JSON_DISK != 'local') {
            $file = Storage::disk(self::CREDENTIAL_JSON_DISK)
                ->get(self::CREDENTIAL_JSON_FILE);
            if ($file) {
                Storage::disk('local')->put(self::CREDENTIAL_JSON_FILE, $file);
            }
        }
    }
}
