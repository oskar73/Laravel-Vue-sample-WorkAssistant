<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;
use Webpatser\Uuid\Uuid;

if (!function_exists('s3_asset')) {
    function s3_asset($path): string
    {
        return config('app.s3_asset_base_url') . $path;
    }
}


if (!function_exists('info')) {
    function info($log)
    {
        \Illuminate\Support\Facades\Log::info($log);
    }
}

if (!function_exists('clearUploadFiles')) {
    function clearUploadFiles($fileNames): void
    {
        foreach ($fileNames as $fileName) {
            if ($fileName && Storage::disk('s3-pub-bizinabox')->exists($fileName)) {
                Storage::disk('s3-pub-bizinabox')->delete($fileName);
            }
        }
    }
}


function uploadUrl($fileName, $defaultImageName = ''): string
{
    if ($fileName && Storage::disk('s3-pub-bizinabox')->exists($fileName)) {
        return Storage::disk('s3-pub-bizinabox')->url($fileName);
    } elseif ($defaultImageName) {
        return '/assets/img/placeholders/' . $defaultImageName;
    }

    return '';
}

function getJsonProp($json, $prop, $default = null)
{
    return isset($json[$prop]) ? $json[$prop] : $default;
}

if (!function_exists('domainEndsWith')) {
    function domainEndsWith($domain, $suffix)
    {
        // Get the length of the suffix
        $suffixLength = strlen($suffix);

        // Check if the end of the domain matches the suffix
        return substr($domain, -$suffixLength) === $suffix;
    }
}

if (!function_exists('getYoutubeImage')) {
    function getYoutubeImage($url)
    {
        preg_match_all('/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/', $url, $result);

        if (!empty($result)) {
            $id = $result['7'][0] ?? 0;
        }
        if ($id) {
            return "https://img.youtube.com/vi/{$id}/hqdefault.jpg";
        }

        return asset('assets/img/youtube.jpg');
    }
}

if (!function_exists('get_string_between')) {
    function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;

        return substr($string, $ini, $len);
    }
}

if (!function_exists('numToDay')) {
    function numToDay($number)
    {
        if ($number == 1) {
            return "1 day";
        } elseif ($number == 7) {
            return "1 week";
        } elseif ($number == 14) {
            return "2 weeks";
        } elseif ($number == 30) {
            return "1 month";
        } elseif ($number == 90) {
            return "3 months";
        } elseif ($number == 180) {
            return "6 months";
        } elseif ($number == 365) {
            return "1 year";
        } else {
            return null;
        }
    }
}
if (!function_exists('hashEncode')) {
    function hashEncode($value)
    {
        return Hashids::encode($value);
    }
}
if (!function_exists('hashDecode')) {
    function hashDecode($value)
    {
        return Hashids::decode($value);
    }
}
if (!function_exists('timeGlobal')) {
    function timeGlobal($time)
    {
        $array = explode(':', $time);
        if ($array[0] > 0 && $array[0] < 10) {
            $result = '0' . intval($array[0]);
        } else {
            $result = $array[0];
        }

        return $result . ':' . $array[1];
    }
}
if (!function_exists('nextDate')) {
    function nextDate($date)
    {
        $next_date = strtotime("+1 day", strtotime($date));
        $result = date("Y-m-d", $next_date);

        return $result;
    }
}

if (!function_exists('uploadPath')) {
    function uploadPath($file = null)
    {
        if ($file == null) {
            return url('/media/uploads');
        } else {
            return url('/media/uploads') . "/" . $file;
        }
    }
}
if (!function_exists('fileDel')) {
    function fileDel($path)
    {
        if (file_exists($path)) {
            File::delete($path);
        }

        return true;
    }
}
if (!function_exists('timePeriodCompare')) {
    function timePeriodCompare($start, $end, $p_start, $p_end)
    {
        $result = 0;
        if (strtotime($start) >= strtotime($p_start) && strtotime($end) <= strtotime($p_end)) {
            $result = 1;
        }

        return $result;
    }
}
if (!function_exists('dirDel')) {
    function dirDel($path)
    {
        File::deleteDirectory($path);

        return true;
    }
}

if (!function_exists('remove_between')) {
    function remove_between($beginning, $end, $string)
    {
        $beginningPos = strpos($string, $beginning);
        $endPos = strpos($string, $end);
        if ($beginningPos === false || $endPos === false) {
            return $string;
        }

        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

        return remove_between($beginning, $end, str_replace($textToDelete, '', $string));
    }
}

if (!function_exists('myTimeZone')) {
    function myTimeZone($date)
    {
        $result = Carbon::parse($date);
        if (\Auth::check()) {
            $result->setTimezone(user()->timezone)
                ->format(user()->timeformat);

            return $result;
        } else {
            return $result;
        }
    }
}
if (!function_exists('fileDel')) {
    function fileDel($path)
    {
        if (file_exists($path)) {
            File::delete($path);
        }

        return true;
    }
}
if (!function_exists('makedir')) {
    function makedir($path)
    {
        if (!file_exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        return true;
    }
}
if (!function_exists('dirDel')) {
    function dirDel($path)
    {
        File::deleteDirectory($path);

        return true;
    }
}

if (!function_exists('formatNumber')) {
    function formatNumber($number)
    {
        return number_format($number, 2, '.', '');
    }
}
if (!function_exists('guid')) {
    function guid()
    {
        return Uuid::generate()->string;
    }
}
if (!function_exists('getDomainTld')) {
    function getDomainTld($domain)
    {
        $first = explode(".", $domain);

        return Str::replaceFirst($first[0] . ".", '', $domain);
    }
}
if (!function_exists('getDomainName')) {
    function getDomainName($domain)
    {
        $first = explode(".", $domain);

        return $first[0];
    }
}
if (!function_exists('makePhoneNum')) {
    function makePhoneNum($code, $number)
    {
        return "+" . $code . "." . $number;
    }
}
if (!function_exists('periodName')) {
    function periodName($period, $period_unit)
    {
        return $period . " " . \Str::plural($period_unit, $period);
    }
}
if (!function_exists('get_before')) {
    function get_before($period, $period_unit)
    {
        $today = Carbon::now();
        $date = $today->sub($period, $period_unit)->addDay();

        return $date->toDateString();
    }
}

if (!function_exists('getOS')) {
    function getOS()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $os_platform = "Unknown OS Platform";
        $os_array = [
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile',
        ];

        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
            }
        }

        return $os_platform;
    }
}

if (!function_exists('extractDesc')) {
    function extractDesc($description, $limit = 160)
    {
        $result = strip_tags($description);

        return Str::limit($result, $limit);
    }
}

if (!function_exists('extractKeyWords')) {
    function extractKeyWords($string)
    {
        mb_internal_encoding('UTF-8');
        $stopwords = ['i', 'a', 'about', 'an', 'and', 'are', 'as', 'at', 'be', 'by', 'com', 'de', 'en', 'for', 'from', 'how', 'in', 'is', 'it', 'la', 'of', 'on', 'or', 'that', 'the', 'this', 'to', 'was', 'what', 'when', 'where', 'who', 'will', 'with', 'und', 'the', 'www'];
        $string = preg_replace('/[\pP]/u', '', trim(preg_replace('/\s\s+/iu', '', mb_strtolower($string))));
        $matchWords = array_filter(explode(' ', $string), function ($item) use ($stopwords) {
            return !($item == '' || in_array($item, $stopwords) || mb_strlen($item) <= 2 || is_numeric($item));
        });
        $wordCountArr = array_count_values($matchWords);
        arsort($wordCountArr);

        $result = array_keys(array_slice($wordCountArr, 0, 10));

        return implode(",", $result);
    }
}
if (!function_exists('moduleName')) {
    function moduleName($type)
    {
        $result = '';
        switch ($type) {
            case "lacarte":
                $result = 'A La Carte';

                break;
            case "service":
                $result = 'Service';

                break;
            case "plugin":
                $result = 'Plugin';

                break;
            case "package":
                $result = 'Package';

                break;
            case "readymade":
                $result = 'Ready Made BIZ';

                break;
            case "module":
                $result = 'Module';

                break;
            case "blogPackage":
                $result = 'Blog Package';

                break;
            case "blogAds":
                $result = 'Blog Advertisement Spot';

                break;
            case "domainRegister":
                $result = 'Domain Register';

                break;
            case "domainRenew":
                $result = 'Domain Renewal';

                break;
            case "url":
                $result = 'URL';

                break;
            case "category":
                $result = 'Category';

                break;
            case "subCategory":
                $result = 'Sub-Category';

                break;
            case "product":
                $result = 'Product';

                break;
        }

        return $result;
    }
}
if (!function_exists('checkMark')) {
    function checkMark($value)
    {
        if ($value) {
            return asset('assets/img/checked.png');
        } else {
            return asset('assets/img/remove1.png');
        }
    }
}
if (!function_exists('saveCartToText')) {
    function saveCartToText($sessionId, $cart)
    {
        $destinationPath = public_path() . "/cart_sessions/";
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath);
        }

        File::put($destinationPath . $sessionId . '.txt', serialize($cart));
    }
}

if (!function_exists('todoTypeToName')) {
    function todoTypeToName($type)
    {
        $result = '';
        switch ($type) {
            case "blogPost":
                $result = 'Blog Posts';

                break;
            case "blogComment":
                $result = 'Blog Comments';

                break;
            case "blogAdsListing":
                $result = 'Blog Ads Listings';

                break;
            case "newsletterAdsListing":
                $result = 'Newsletter Ads Listings';

                break;
            case "appointment":
                $result = 'Appointments';

                break;
            case "appointmentReschedule":
                $result = 'Appointment Reschedule';

                break;
            case "ticket":
                $result = 'Tickets';

                break;
            case "purchaseForm":
                $result = 'Purchase Followup Forms';

                break;
            case "review":
                $result = 'Reviews';

                break;
            case "portfolioApproval":
                $result = "Portfolio Approval";

                break;
            case "directoryApproval":
                $result = "Directory Approval";

                break;
            case "website":
                $result = 'Website';

                break;
            case "domain":
                $result = 'Domain';

                break;
        }

        return $result;
    }
}

if (!function_exists('getTimezoneList')) {
    function getTimezoneList($data = null, $value = null)
    {
        $list = [
            [
                "label" => "General",
                "data" => [
                    ["value" => "GMT", "label" => "GMT timezone"],
                    ["value" => "UTC", "label" => "UTC timezone"],
                ],
            ],
            [
                "label" => "America",
                "data" => [
                    ["value" => "America/Adak", "label" => "(GMT/UTC - 10:00) Adak"],
                    ["value" => "America/Anchorage", "label" => "(GMT/UTC - 09:00) Anchorage"],
                    ["value" => "America/Anguilla", "label" => "(GMT/UTC - 04:00) Anguilla"],
                    ["value" => "America/Antigua", "label" => "(GMT/UTC - 04:00) Antigua"],
                    ["value" => "America/Araguaina", "label" => "(GMT/UTC - 03:00) Araguaina"],
                    ["value" => "America/Argentina/Buenos_Aires", "label" => "(GMT/UTC - 03:00) Argentina/Buenos Aires"],
                    ["value" => "America/Argentina/Catamarca", "label" => "(GMT/UTC - 03:00) Argentina/Catamarca"],
                    ["value" => "America/Argentina/Cordoba", "label" => "(GMT/UTC - 03:00) Argentina/Cordoba"],
                    ["value" => "America/Argentina/Jujuy", "label" => "(GMT/UTC - 03:00) Argentina/Jujuy"],
                    ["value" => "America/Argentina/La_Rioja", "label" => "(GMT/UTC - 03:00) Argentina/La Rioja"],
                    ["value" => "America/Argentina/Mendoza", "label" => "(GMT/UTC - 03:00) Argentina/Mendoza"],
                    ["value" => "America/Argentina/Rio_Gallegos", "label" => "(GMT/UTC - 03:00) Argentina/Rio Gallegos"],
                    ["value" => "America/Argentina/Salta", "label" => "(GMT/UTC - 03:00) Argentina/Salta"],
                    ["value" => "America/Argentina/San_Juan", "label" => "(GMT/UTC - 03:00) Argentina/San Juan"],
                    ["value" => "America/Argentina/San_Luis", "label" => "(GMT/UTC - 03:00) Argentina/San Luis"],
                    ["value" => "America/Argentina/Tucuman", "label" => "(GMT/UTC - 03:00) Argentina/Tucuman"],
                    ["value" => "America/Argentina/Ushuaia", "label" => "(GMT/UTC - 03:00) Argentina/Ushuaia"],
                    ["value" => "America/Aruba", "label" => "(GMT/UTC - 04:00) Aruba"],
                    ["value" => "America/Asuncion", "label" => "(GMT/UTC - 03:00) Asuncion"],
                    ["value" => "America/Atikokan", "label" => "(GMT/UTC - 05:00) Atikokan"],
                    ["value" => "America/Bahia", "label" => "(GMT/UTC - 03:00) Bahia"],
                    ["value" => "America/Bahia_Banderas", "label" => "(GMT/UTC - 06:00) Bahia Banderas"],
                    ["value" => "America/Barbados", "label" => "(GMT/UTC - 04:00) Barbados"],
                    ["value" => "America/Belem", "label" => "(GMT/UTC - 03:00) Belem"],
                    ["value" => "America/Belize", "label" => "(GMT/UTC - 06:00) Belize"],
                    ["value" => "America/Blanc-Sablon", "label" => "(GMT/UTC - 04:00) Blanc-Sablon"],
                    ["value" => "America/Boa_Vista", "label" => "(GMT/UTC - 04:00) Boa Vista"],
                    ["value" => "America/Bogota", "label" => "(GMT/UTC - 05:00) Bogota"],
                    ["value" => "America/Boise", "label" => "(GMT/UTC - 07:00) Boise"],
                    ["value" => "America/Cambridge_Bay", "label" => "(GMT/UTC - 07:00) Cambridge Bay"],
                    ["value" => "America/Campo_Grande", "label" => "(GMT/UTC - 03:00) Campo Grande"],
                    ["value" => "America/Cancun", "label" => "(GMT/UTC - 05:00) Cancun"],
                    ["value" => "America/Caracas", "label" => "(GMT/UTC - 04:30) Caracas"],
                    ["value" => "America/Cayenne", "label" => "(GMT/UTC - 03:00) Cayenne"],
                    ["value" => "America/Cayman", "label" => "(GMT/UTC - 05:00) Cayman"],
                    ["value" => "America/Chicago", "label" => "(GMT/UTC - 06:00) Chicago"],
                    ["value" => "America/Chihuahua", "label" => "(GMT/UTC - 07:00) Chihuahua"],
                    ["value" => "America/Costa_Rica", "label" => "(GMT/UTC - 06:00) Costa Rica"],
                    ["value" => "America/Creston", "label" => "(GMT/UTC - 07:00) Creston"],
                    ["value" => "America/Cuiaba", "label" => "(GMT/UTC - 03:00) Cuiaba"],
                    ["value" => "America/Curacao", "label" => "(GMT/UTC - 04:00) Curacao"],
                    ["value" => "America/Danmarkshavn", "label" => "(GMT/UTC + 00:00) Danmarkshavn"],
                    ["value" => "America/Dawson", "label" => "(GMT/UTC - 08:00) Dawson"],
                    ["value" => "America/Dawson_Creek", "label" => "(GMT/UTC - 07:00) Dawson Creek"],
                    ["value" => "America/Denver", "label" => "(GMT/UTC - 07:00) Denver"],
                    ["value" => "America/Detroit", "label" => "(GMT/UTC - 05:00) Detroit"],
                    ["value" => "America/Dominica", "label" => "(GMT/UTC - 04:00) Dominica"],
                    ["value" => "America/Edmonton", "label" => "(GMT/UTC - 07:00) Edmonton"],
                    ["value" => "America/Eirunepe", "label" => "(GMT/UTC - 05:00) Eirunepe"],
                    ["value" => "America/El_Salvador", "label" => "(GMT/UTC - 06:00) El Salvador"],
                    ["value" => "America/Fort_Nelson", "label" => "(GMT/UTC - 07:00) Fort Nelson"],
                    ["value" => "America/Fortaleza", "label" => "(GMT/UTC - 03:00) Fortaleza"],
                    ["value" => "America/Glace_Bay", "label" => "(GMT/UTC - 04:00) Glace Bay"],
                    ["value" => "America/Godthab", "label" => "(GMT/UTC - 03:00) Godthab"],
                    ["value" => "America/Goose_Bay", "label" => "(GMT/UTC - 04:00) Goose Bay"],
                    ["value" => "America/Grand_Turk", "label" => "(GMT/UTC - 04:00) Grand Turk"],
                    ["value" => "America/Grenada", "label" => "(GMT/UTC - 04:00) Grenada"],
                    ["value" => "America/Guadeloupe", "label" => "(GMT/UTC - 04:00) Guadeloupe"],
                    ["value" => "America/Guatemala", "label" => "(GMT/UTC - 06:00) Guatemala"],
                    ["value" => "America/Guayaquil", "label" => "(GMT/UTC - 05:00) Guayaquil"],
                    ["value" => "America/Guyana", "label" => "(GMT/UTC - 04:00) Guyana"],
                    ["value" => "America/Halifax", "label" => "(GMT/UTC - 04:00) Halifax"],
                    ["value" => "America/Havana", "label" => "(GMT/UTC - 05:00) Havana"],
                    ["value" => "America/Hermosillo", "label" => "(GMT/UTC - 07:00) Hermosillo"],
                    ["value" => "America/Indiana/Indianapolis", "label" => "(GMT/UTC - 05:00) Indiana/Indianapolis"],
                    ["value" => "America/Indiana/Knox", "label" => "(GMT/UTC - 06:00) Indiana/Knox"],
                    ["value" => "America/Indiana/Marengo", "label" => "(GMT/UTC - 05:00) Indiana/Marengo"],
                    ["value" => "America/Indiana/Petersburg", "label" => "(GMT/UTC - 05:00) Indiana/Petersburg"],
                    ["value" => "America/Indiana/Tell_City", "label" => "(GMT/UTC - 06:00) Indiana/Tell City"],
                    ["value" => "America/Indiana/Vevay", "label" => "(GMT/UTC - 05:00) Indiana/Vevay"],
                    ["value" => "America/Indiana/Vincennes", "label" => "(GMT/UTC - 05:00) Indiana/Vincennes"],
                    ["value" => "America/Indiana/Winamac", "label" => "(GMT/UTC - 05:00) Indiana/Winamac"],
                    ["value" => "America/Inuvik", "label" => "(GMT/UTC - 07:00) Inuvik"],
                    ["value" => "America/Iqaluit", "label" => "(GMT/UTC - 05:00) Iqaluit"],
                    ["value" => "America/Jamaica", "label" => "(GMT/UTC - 05:00) Jamaica"],
                    ["value" => "America/Juneau", "label" => "(GMT/UTC - 09:00) Juneau"],
                    ["value" => "America/Kentucky/Louisville", "label" => "(GMT/UTC - 05:00) Kentucky/Louisville"],
                    ["value" => "America/Kentucky/Monticello", "label" => "(GMT/UTC - 05:00) Kentucky/Monticello"],
                    ["value" => "America/Kralendijk", "label" => "(GMT/UTC - 04:00) Kralendijk"],
                    ["value" => "America/La_Paz", "label" => "(GMT/UTC - 04:00) La Paz"],
                    ["value" => "America/Lima", "label" => "(GMT/UTC - 05:00) Lima"],
                    ["value" => "America/Los_Angeles", "label" => "(GMT/UTC - 08:00) Los Angeles"],
                    ["value" => "America/Lower_Princes", "label" => "(GMT/UTC - 04:00) Lower Princes"],
                    ["value" => "America/Maceio", "label" => "(GMT/UTC - 03:00) Maceio"],
                    ["value" => "America/Managua", "label" => "(GMT/UTC - 06:00) Managua"],
                    ["value" => "America/Manaus", "label" => "(GMT/UTC - 04:00) Manaus"],
                    ["value" => "America/Marigot", "label" => "(GMT/UTC - 04:00) Marigot"],
                    ["value" => "America/Martinique", "label" => "(GMT/UTC - 04:00) Martinique"],
                    ["value" => "America/Matamoros", "label" => "(GMT/UTC - 06:00) Matamoros"],
                    ["value" => "America/Mazatlan", "label" => "(GMT/UTC - 07:00) Mazatlan"],
                    ["value" => "America/Menominee", "label" => "(GMT/UTC - 06:00) Menominee"],
                    ["value" => "America/Merida", "label" => "(GMT/UTC - 06:00) Merida"],
                    ["value" => "America/Metlakatla", "label" => "(GMT/UTC - 09:00) Metlakatla"],
                    ["value" => "America/Mexico_City", "label" => "(GMT/UTC - 06:00) Mexico City"],
                    ["value" => "America/Miquelon", "label" => "(GMT/UTC - 03:00) Miquelon"],
                    ["value" => "America/Moncton", "label" => "(GMT/UTC - 04:00) Moncton"],
                    ["value" => "America/Monterrey", "label" => "(GMT/UTC - 06:00) Monterrey"],
                    ["value" => "America/Montevideo", "label" => "(GMT/UTC - 03:00) Montevideo"],
                    ["value" => "America/Montserrat", "label" => "(GMT/UTC - 04:00) Montserrat"],
                    ["value" => "America/Nassau", "label" => "(GMT/UTC - 05:00) Nassau"],
                    ["value" => "America/New_York", "label" => "(GMT/UTC - 05:00) New York"],
                    ["value" => "America/Nipigon", "label" => "(GMT/UTC - 05:00) Nipigon"],
                    ["value" => "America/Nome", "label" => "(GMT/UTC - 09:00) Nome"],
                    ["value" => "America/Noronha", "label" => "(GMT/UTC - 02:00) Noronha"],
                    ["value" => "America/North_Dakota/Beulah", "label" => "(GMT/UTC - 06:00) North Dakota/Beulah"],
                    ["value" => "America/North_Dakota/Center", "label" => "(GMT/UTC - 06:00) North Dakota/Center"],
                    ["value" => "America/North_Dakota/New_Salem", "label" => "(GMT/UTC - 06:00) North Dakota/New Salem"],
                    ["value" => "America/Ojinaga", "label" => "(GMT/UTC - 07:00) Ojinaga"],
                    ["value" => "America/Panama", "label" => "(GMT/UTC - 05:00) Panama"],
                    ["value" => "America/Pangnirtung", "label" => "(GMT/UTC - 05:00) Pangnirtung"],
                    ["value" => "America/Paramaribo", "label" => "(GMT/UTC - 03:00) Paramaribo"],
                    ["value" => "America/Phoenix", "label" => "(GMT/UTC - 07:00) Phoenix"],
                    ["value" => "America/Port-au-Prince", "label" => "(GMT/UTC - 05:00) Port-au-Prince"],
                    ["value" => "America/Port_of_Spain", "label" => "(GMT/UTC - 04:00) Port of Spain"],
                    ["value" => "America/Porto_Velho", "label" => "(GMT/UTC - 04:00) Porto Velho"],
                    ["value" => "America/Puerto_Rico", "label" => "(GMT/UTC - 04:00) Puerto Rico"],
                    ["value" => "America/Rainy_River", "label" => "(GMT/UTC - 06:00) Rainy River"],
                    ["value" => "America/Rankin_Inlet", "label" => "(GMT/UTC - 06:00) Rankin Inlet"],
                    ["value" => "America/Recife", "label" => "(GMT/UTC - 03:00) Recife"],
                    ["value" => "America/Regina", "label" => "(GMT/UTC - 06:00) Regina"],
                    ["value" => "America/Resolute", "label" => "(GMT/UTC - 06:00) Resolute"],
                    ["value" => "America/Rio_Branco", "label" => "(GMT/UTC - 05:00) Rio Branco"],
                    ["value" => "America/Santarem", "label" => "(GMT/UTC - 03:00) Santarem"],
                    ["value" => "America/Santiago", "label" => "(GMT/UTC - 03:00) Santiago"],
                    ["value" => "America/Santo_Domingo", "label" => "(GMT/UTC - 04:00) Santo Domingo"],
                    ["value" => "America/Sao_Paulo", "label" => "(GMT/UTC - 02:00) Sao Paulo"],
                    ["value" => "America/Scoresbysund", "label" => "(GMT/UTC - 01:00) Scoresbysund"],
                    ["value" => "America/Sitka", "label" => "(GMT/UTC - 09:00) Sitka"],
                    ["value" => "America/St_Barthelemy", "label" => "(GMT/UTC - 04:00) St. Barthelemy"],
                    ["value" => "America/St_Johns", "label" => "(GMT/UTC - 03:30) St. Johns"],
                    ["value" => "America/St_Kitts", "label" => "(GMT/UTC - 04:00) St. Kitts"],
                    ["value" => "America/St_Lucia", "label" => "(GMT/UTC - 04:00) St. Lucia"],
                    ["value" => "America/St_Thomas", "label" => "(GMT/UTC - 04:00) St. Thomas"],
                    ["value" => "America/St_Vincent", "label" => "(GMT/UTC - 04:00) St. Vincent"],
                    ["value" => "America/Swift_Current", "label" => "(GMT/UTC - 06:00) Swift Current"],
                    ["value" => "America/Tegucigalpa", "label" => "(GMT/UTC - 06:00) Tegucigalpa"],
                    ["value" => "America/Thule", "label" => "(GMT/UTC - 04:00) Thule"],
                    ["value" => "America/Thunder_Bay", "label" => "(GMT/UTC - 05:00) Thunder Bay"],
                    ["value" => "America/Tijuana", "label" => "(GMT/UTC - 08:00) Tijuana"],
                    ["value" => "America/Toronto", "label" => "(GMT/UTC - 05:00) Toronto"],
                    ["value" => "America/Tortola", "label" => "(GMT/UTC - 04:00) Tortola"],
                    ["value" => "America/Vancouver", "label" => "(GMT/UTC - 08:00) Vancouver"],
                    ["value" => "America/Whitehorse", "label" => "(GMT/UTC - 08:00) Whitehorse"],
                    ["value" => "America/Winnipeg", "label" => "(GMT/UTC - 06:00) Winnipeg"],
                    ["value" => "America/Yakutat", "label" => "(GMT/UTC - 09:00) Yakutat"],
                    ["value" => "America/Yellowknife", "label" => "(GMT/UTC - 07:00) Yellowknife"],
                ],
            ],
            [
                "label" => "Europe",
                "data" => [
                    ["value" => "Europe/Amsterdam", "label" => "(GMT/UTC + 01:00) Amsterdam"],
                    ["value" => "Europe/Andorra", "label" => "(GMT/UTC + 01:00) Andorra"],
                    ["value" => "Europe/Astrakhan", "label" => "(GMT/UTC + 04:00) Astrakhan"],
                    ["value" => "Europe/Athens", "label" => "(GMT/UTC + 02:00) Athens"],
                    ["value" => "Europe/Belgrade", "label" => "(GMT/UTC + 01:00) Belgrade"],
                    ["value" => "Europe/Berlin", "label" => "(GMT/UTC + 01:00) Berlin"],
                    ["value" => "Europe/Bratislava", "label" => "(GMT/UTC + 01:00) Bratislava"],
                    ["value" => "Europe/Brussels", "label" => "(GMT/UTC + 01:00) Brussels"],
                    ["value" => "Europe/Bucharest", "label" => "(GMT/UTC + 02:00) Bucharest"],
                    ["value" => "Europe/Budapest", "label" => "(GMT/UTC + 01:00) Budapest"],
                    ["value" => "Europe/Busingen", "label" => "(GMT/UTC + 01:00) Busingen"],
                    ["value" => "Europe/Chisinau", "label" => "(GMT/UTC + 02:00) Chisinau"],
                    ["value" => "Europe/Copenhagen", "label" => "(GMT/UTC + 01:00) Copenhagen"],
                    ["value" => "Europe/Dublin", "label" => "(GMT/UTC + 00:00) Dublin"],
                    ["value" => "Europe/Gibraltar", "label" => "(GMT/UTC + 01:00) Gibraltar"],
                    ["value" => "Europe/Guernsey", "label" => "(GMT/UTC + 00:00) Guernsey"],
                    ["value" => "Europe/Helsinki", "label" => "(GMT/UTC + 02:00) Helsinki"],
                    ["value" => "Europe/Isle_of_Man", "label" => "(GMT/UTC + 00:00) Isle of Man"],
                    ["value" => "Europe/Istanbul", "label" => "(GMT/UTC + 02:00) Istanbul"],
                    ["value" => "Europe/Jersey", "label" => "(GMT/UTC + 00:00) Jersey"],
                    ["value" => "Europe/Kaliningrad", "label" => "(GMT/UTC + 02:00) Kaliningrad"],
                    ["value" => "Europe/Kiev", "label" => "(GMT/UTC + 02:00) Kiev"],
                    ["value" => "Europe/Lisbon", "label" => "(GMT/UTC + 00:00) Lisbon"],
                    ["value" => "Europe/Ljubljana", "label" => "(GMT/UTC + 01:00) Ljubljana"],
                    ["value" => "Europe/London", "label" => "(GMT/UTC + 00:00) London"],
                    ["value" => "Europe/Luxembourg", "label" => "(GMT/UTC + 01:00) Luxembourg"],
                    ["value" => "Europe/Madrid", "label" => "(GMT/UTC + 01:00) Madrid"],
                    ["value" => "Europe/Malta", "label" => "(GMT/UTC + 01:00) Malta"],
                    ["value" => "Europe/Mariehamn", "label" => "(GMT/UTC + 02:00) Mariehamn"],
                    ["value" => "Europe/Minsk", "label" => "(GMT/UTC + 03:00) Minsk"],
                    ["value" => "Europe/Monaco", "label" => "(GMT/UTC + 01:00) Monaco"],
                    ["value" => "Europe/Moscow", "label" => "(GMT/UTC + 03:00) Moscow"],
                    ["value" => "Europe/Oslo", "label" => "(GMT/UTC + 01:00) Oslo"],
                    ["value" => "Europe/Paris", "label" => "(GMT/UTC + 01:00) Paris"],
                    ["value" => "Europe/Podgorica", "label" => "(GMT/UTC + 01:00) Podgorica"],
                    ["value" => "Europe/Prague", "label" => "(GMT/UTC + 01:00) Prague"],
                    ["value" => "Europe/Riga", "label" => "(GMT/UTC + 02:00) Riga"],
                    ["value" => "Europe/Rome", "label" => "(GMT/UTC + 01:00) Rome"],
                    ["value" => "Europe/Samara", "label" => "(GMT/UTC + 04:00) Samara"],
                    ["value" => "Europe/San_Marino", "label" => "(GMT/UTC + 01:00) San Marino"],
                    ["value" => "Europe/Sarajevo", "label" => "(GMT/UTC + 01:00) Sarajevo"],
                    ["value" => "Europe/Simferopol", "label" => "(GMT/UTC + 03:00) Simferopol"],
                    ["value" => "Europe/Skopje", "label" => "(GMT/UTC + 01:00) Skopje"],
                    ["value" => "Europe/Sofia", "label" => "(GMT/UTC + 02:00) Sofia"],
                    ["value" => "Europe/Stockholm", "label" => "(GMT/UTC + 01:00) Stockholm"],
                    ["value" => "Europe/Tallinn", "label" => "(GMT/UTC + 02:00) Tallinn"],
                    ["value" => "Europe/Tirane", "label" => "(GMT/UTC + 01:00) Tirane"],
                    ["value" => "Europe/Ulyanovsk", "label" => "(GMT/UTC + 04:00) Ulyanovsk"],
                    ["value" => "Europe/Uzhgorod", "label" => "(GMT/UTC + 02:00) Uzhgorod"],
                    ["value" => "Europe/Vaduz", "label" => "(GMT/UTC + 01:00) Vaduz"],
                    ["value" => "Europe/Vatican", "label" => "(GMT/UTC + 01:00) Vatican"],
                    ["value" => "Europe/Vienna", "label" => "(GMT/UTC + 01:00) Vienna"],
                    ["value" => "Europe/Vilnius", "label" => "(GMT/UTC + 02:00) Vilnius"],
                    ["value" => "Europe/Volgograd", "label" => "(GMT/UTC + 03:00) Volgograd"],
                    ["value" => "Europe/Warsaw", "label" => "(GMT/UTC + 01:00) Warsaw"],
                    ["value" => "Europe/Zagreb", "label" => "(GMT/UTC + 01:00) Zagreb"],
                    ["value" => "Europe/Zaporozhye", "label" => "(GMT/UTC + 02:00) Zaporozhye"],
                    ["value" => "Europe/Zurich", "label" => "(GMT/UTC + 01:00) Zurich"],
                ],
            ],
            [
                "label" => "Africa",
                "data" => [
                    ["value" => "Africa/Abidjan", "label" => "(GMT/UTC + 00:00) Abidjan"],
                    ["value" => "Africa/Accra", "label" => "(GMT/UTC + 00:00) Accra"],
                    ["value" => "Africa/Addis_Ababa", "label" => "(GMT/UTC + 03:00) Addis Ababa"],
                    ["value" => "Africa/Algiers", "label" => "(GMT/UTC + 01:00) Algiers"],
                    ["value" => "Africa/Asmara", "label" => "(GMT/UTC + 03:00) Asmara"],
                    ["value" => "Africa/Bamako", "label" => "(GMT/UTC + 00:00) Bamako"],
                    ["value" => "Africa/Bangui", "label" => "(GMT/UTC + 01:00) Bangui"],
                    ["value" => "Africa/Banjul", "label" => "(GMT/UTC + 00:00) Banjul"],
                    ["value" => "Africa/Bissau", "label" => "(GMT/UTC + 00:00) Bissau"],
                    ["value" => "Africa/Blantyre", "label" => "(GMT/UTC + 02:00) Blantyre"],
                    ["value" => "Africa/Brazzaville", "label" => "(GMT/UTC + 01:00) Brazzaville"],
                    ["value" => "Africa/Bujumbura", "label" => "(GMT/UTC + 02:00) Bujumbura"],
                    ["value" => "Africa/Cairo", "label" => "(GMT/UTC + 02:00) Cairo"],
                    ["value" => "Africa/Casablanca", "label" => "(GMT/UTC + 00:00) Casablanca"],
                    ["value" => "Africa/Ceuta", "label" => "(GMT/UTC + 01:00) Ceuta"],
                    ["value" => "Africa/Conakry", "label" => "(GMT/UTC + 00:00) Conakry"],
                    ["value" => "Africa/Dakar", "label" => "(GMT/UTC + 00:00) Dakar"],
                    ["value" => "Africa/Dar_es_Salaam", "label" => "(GMT/UTC + 03:00) Dar es Salaam"],
                    ["value" => "Africa/Djibouti", "label" => "(GMT/UTC + 03:00) Djibouti"],
                    ["value" => "Africa/Douala", "label" => "(GMT/UTC + 01:00) Douala"],
                    ["value" => "Africa/El_Aaiun", "label" => "(GMT/UTC + 00:00) El Aaiun"],
                    ["value" => "Africa/Freetown", "label" => "(GMT/UTC + 00:00) Freetown"],
                    ["value" => "Africa/Gaborone", "label" => "(GMT/UTC + 02:00) Gaborone"],
                    ["value" => "Africa/Harare", "label" => "(GMT/UTC + 02:00) Harare"],
                    ["value" => "Africa/Johannesburg", "label" => "(GMT/UTC + 02:00) Johannesburg"],
                    ["value" => "Africa/Juba", "label" => "(GMT/UTC + 03:00) Juba"],
                    ["value" => "Africa/Kampala", "label" => "(GMT/UTC + 03:00) Kampala"],
                    ["value" => "Africa/Khartoum", "label" => "(GMT/UTC + 03:00) Khartoum"],
                    ["value" => "Africa/Kigali", "label" => "(GMT/UTC + 02:00) Kigali"],
                    ["value" => "Africa/Kinshasa", "label" => "(GMT/UTC + 01:00) Kinshasa"],
                    ["value" => "Africa/Lagos", "label" => "(GMT/UTC + 01:00) Lagos"],
                    ["value" => "Africa/Libreville", "label" => "(GMT/UTC + 01:00) Libreville"],
                    ["value" => "Africa/Lome", "label" => "(GMT/UTC + 00:00) Lome"],
                    ["value" => "Africa/Luanda", "label" => "(GMT/UTC + 01:00) Luanda"],
                    ["value" => "Africa/Lubumbashi", "label" => "(GMT/UTC + 02:00) Lubumbashi"],
                    ["value" => "Africa/Lusaka", "label" => "(GMT/UTC + 02:00) Lusaka"],
                    ["value" => "Africa/Malabo", "label" => "(GMT/UTC + 01:00) Malabo"],
                    ["value" => "Africa/Maputo", "label" => "(GMT/UTC + 02:00) Maputo"],
                    ["value" => "Africa/Maseru", "label" => "(GMT/UTC + 02:00) Maseru"],
                    ["value" => "Africa/Mbabane", "label" => "(GMT/UTC + 02:00) Mbabane"],
                    ["value" => "Africa/Mogadishu", "label" => "(GMT/UTC + 03:00) Mogadishu"],
                    ["value" => "Africa/Monrovia", "label" => "(GMT/UTC + 00:00) Monrovia"],
                    ["value" => "Africa/Nairobi", "label" => "(GMT/UTC + 03:00) Nairobi"],
                    ["value" => "Africa/Ndjamena", "label" => "(GMT/UTC + 01:00) Ndjamena"],
                    ["value" => "Africa/Niamey", "label" => "(GMT/UTC + 01:00) Niamey"],
                    ["value" => "Africa/Nouakchott", "label" => "(GMT/UTC + 00:00) Nouakchott"],
                    ["value" => "Africa/Ouagadougou", "label" => "(GMT/UTC + 00:00) Ouagadougou"],
                    ["value" => "Africa/Porto-Novo", "label" => "(GMT/UTC + 01:00) Porto-Novo"],
                    ["value" => "Africa/Sao_Tome", "label" => "(GMT/UTC + 00:00) Sao Tome"],
                    ["value" => "Africa/Tripoli", "label" => "(GMT/UTC + 02:00) Tripoli"],
                    ["value" => "Africa/Tunis", "label" => "(GMT/UTC + 01:00) Tunis"],
                    ["value" => "Africa/Windhoek", "label" => "(GMT/UTC + 02:00) Windhoek"],
                ],
            ],
            [
                "label" => "Antarctica",
                "data" => [
                    ["value" => "Antarctica/Casey", "label" => "(GMT/UTC + 08:00) Casey"],
                    ["value" => "Antarctica/Davis", "label" => "(GMT/UTC + 07:00) Davis"],
                    ["value" => "Antarctica/DumontDUrville", "label" => "(GMT/UTC + 10:00) DumontDUrville"],
                    ["value" => "Antarctica/Macquarie", "label" => "(GMT/UTC + 11:00) Macquarie"],
                    ["value" => "Antarctica/Mawson", "label" => "(GMT/UTC + 05:00) Mawson"],
                    ["value" => "Antarctica/McMurdo", "label" => "(GMT/UTC + 13:00) McMurdo"],
                    ["value" => "Antarctica/Palmer", "label" => "(GMT/UTC - 03:00) Palmer"],
                    ["value" => "Antarctica/Rothera", "label" => "(GMT/UTC - 03:00) Rothera"],
                    ["value" => "Antarctica/Syowa", "label" => "(GMT/UTC + 03:00) Syowa"],
                    ["value" => "Antarctica/Troll", "label" => "(GMT/UTC + 00:00) Troll"],
                    ["value" => "Antarctica/Vostok", "label" => "(GMT/UTC + 06:00) Vostok"],
                ],
            ],
            [
                "label" => "Arctic",
                "data" => [
                    ["value" => "Arctic/Longyearbyen", "label" => "(GMT/UTC + 01:00) Longyearbyen"],
                ],
            ],
            [
                "label" => "Asia",
                "data" => [
                    ["value" => "Asia/Aden", "label" => "(GMT/UTC + 03:00) Aden"],
                    ["value" => "Asia/Almaty", "label" => "(GMT/UTC + 06:00) Almaty"],
                    ["value" => "Asia/Amman", "label" => "(GMT/UTC + 02:00) Amman"],
                    ["value" => "Asia/Anadyr", "label" => "(GMT/UTC + 12:00) Anadyr"],
                    ["value" => "Asia/Aqtau", "label" => "(GMT/UTC + 05:00) Aqtau"],
                    ["value" => "Asia/Aqtobe", "label" => "(GMT/UTC + 05:00) Aqtobe"],
                    ["value" => "Asia/Ashgabat", "label" => "(GMT/UTC + 05:00) Ashgabat"],
                    ["value" => "Asia/Baghdad", "label" => "(GMT/UTC + 03:00) Baghdad"],
                    ["value" => "Asia/Bahrain", "label" => "(GMT/UTC + 03:00) Bahrain"],
                    ["value" => "Asia/Baku", "label" => "(GMT/UTC + 04:00) Baku"],
                    ["value" => "Asia/Bangkok", "label" => "(GMT/UTC + 07:00) Bangkok"],
                    ["value" => "Asia/Barnaul", "label" => "(GMT/UTC + 07:00) Barnaul"],
                    ["value" => "Asia/Beirut", "label" => "(GMT/UTC + 02:00) Beirut"],
                    ["value" => "Asia/Bishkek", "label" => "(GMT/UTC + 06:00) Bishkek"],
                    ["value" => "Asia/Brunei", "label" => "(GMT/UTC + 08:00) Brunei"],
                    ["value" => "Asia/Chita", "label" => "(GMT/UTC + 09:00) Chita"],
                    ["value" => "Asia/Choibalsan", "label" => "(GMT/UTC + 08:00) Choibalsan"],
                    ["value" => "Asia/Colombo", "label" => "(GMT/UTC + 05:30) Colombo"],
                    ["value" => "Asia/Damascus", "label" => "(GMT/UTC + 02:00) Damascus"],
                    ["value" => "Asia/Dhaka", "label" => "(GMT/UTC + 06:00) Dhaka"],
                    ["value" => "Asia/Dili", "label" => "(GMT/UTC + 09:00) Dili"],
                    ["value" => "Asia/Dubai", "label" => "(GMT/UTC + 04:00) Dubai"],
                    ["value" => "Asia/Dushanbe", "label" => "(GMT/UTC + 05:00) Dushanbe"],
                    ["value" => "Asia/Gaza", "label" => "(GMT/UTC + 02:00) Gaza"],
                    ["value" => "Asia/Hebron", "label" => "(GMT/UTC + 02:00) Hebron"],
                    ["value" => "Asia/Ho_Chi_Minh", "label" => "(GMT/UTC + 07:00) Ho Chi Minh"],
                    ["value" => "Asia/Hong_Kong", "label" => "(GMT/UTC + 08:00) Hong Kong"],
                    ["value" => "Asia/Hovd", "label" => "(GMT/UTC + 07:00) Hovd"],
                    ["value" => "Asia/Irkutsk", "label" => "(GMT/UTC + 08:00) Irkutsk"],
                    ["value" => "Asia/Jakarta", "label" => "(GMT/UTC + 07:00) Jakarta"],
                    ["value" => "Asia/Jayapura", "label" => "(GMT/UTC + 09:00) Jayapura"],
                    ["value" => "Asia/Jerusalem", "label" => "(GMT/UTC + 02:00) Jerusalem"],
                    ["value" => "Asia/Kabul", "label" => "(GMT/UTC + 04:30) Kabul"],
                    ["value" => "Asia/Kamchatka", "label" => "(GMT/UTC + 12:00) Kamchatka"],
                    ["value" => "Asia/Karachi", "label" => "(GMT/UTC + 05:00) Karachi"],
                    ["value" => "Asia/Kathmandu", "label" => "(GMT/UTC + 05:45) Kathmandu"],
                    ["value" => "Asia/Khandyga", "label" => "(GMT/UTC + 09:00) Khandyga"],
                    ["value" => "Asia/Kolkata", "label" => "(GMT/UTC + 05:30) Kolkata"],
                    ["value" => "Asia/Krasnoyarsk", "label" => "(GMT/UTC + 07:00) Krasnoyarsk"],
                    ["value" => "Asia/Kuala_Lumpur", "label" => "(GMT/UTC + 08:00) Kuala Lumpur"],
                    ["value" => "Asia/Kuching", "label" => "(GMT/UTC + 08:00) Kuching"],
                    ["value" => "Asia/Kuwait", "label" => "(GMT/UTC + 03:00) Kuwait"],
                    ["value" => "Asia/Macau", "label" => "(GMT/UTC + 08:00) Macau"],
                    ["value" => "Asia/Magadan", "label" => "(GMT/UTC + 10:00) Magadan"],
                    ["value" => "Asia/Makassar", "label" => "(GMT/UTC + 08:00) Makassar"],
                    ["value" => "Asia/Manila", "label" => "(GMT/UTC + 08:00) Manila"],
                    ["value" => "Asia/Muscat", "label" => "(GMT/UTC + 04:00) Muscat"],
                    ["value" => "Asia/Nicosia", "label" => "(GMT/UTC + 02:00) Nicosia"],
                    ["value" => "Asia/Novokuznetsk", "label" => "(GMT/UTC + 07:00) Novokuznetsk"],
                    ["value" => "Asia/Novosibirsk", "label" => "(GMT/UTC + 06:00) Novosibirsk"],
                    ["value" => "Asia/Omsk", "label" => "(GMT/UTC + 06:00) Omsk"],
                    ["value" => "Asia/Oral", "label" => "(GMT/UTC + 05:00) Oral"],
                    ["value" => "Asia/Phnom_Penh", "label" => "(GMT/UTC + 07:00) Phnom Penh"],
                    ["value" => "Asia/Pontianak", "label" => "(GMT/UTC + 07:00) Pontianak"],
                    ["value" => "Asia/Pyongyang", "label" => "(GMT/UTC + 08:30) Pyongyang"],
                    ["value" => "Asia/Qatar", "label" => "(GMT/UTC + 03:00) Qatar"],
                    ["value" => "Asia/Qyzylorda", "label" => "(GMT/UTC + 06:00) Qyzylorda"],
                    ["value" => "Asia/Rangoon", "label" => "(GMT/UTC + 06:30) Rangoon"],
                    ["value" => "Asia/Riyadh", "label" => "(GMT/UTC + 03:00) Riyadh"],
                    ["value" => "Asia/Sakhalin", "label" => "(GMT/UTC + 11:00) Sakhalin"],
                    ["value" => "Asia/Samarkand", "label" => "(GMT/UTC + 05:00) Samarkand"],
                    ["value" => "Asia/Seoul", "label" => "(GMT/UTC + 09:00) Seoul"],
                    ["value" => "Asia/Shanghai", "label" => "(GMT/UTC + 08:00) Shanghai"],
                    ["value" => "Asia/Singapore", "label" => "(GMT/UTC + 08:00) Singapore"],
                    ["value" => "Asia/Srednekolymsk", "label" => "(GMT/UTC + 11:00) Srednekolymsk"],
                    ["value" => "Asia/Taipei", "label" => "(GMT/UTC + 08:00) Taipei"],
                    ["value" => "Asia/Tashkent", "label" => "(GMT/UTC + 05:00) Tashkent"],
                    ["value" => "Asia/Tbilisi", "label" => "(GMT/UTC + 04:00) Tbilisi"],
                    ["value" => "Asia/Tehran", "label" => "(GMT/UTC + 03:30) Tehran"],
                    ["value" => "Asia/Thimphu", "label" => "(GMT/UTC + 06:00) Thimphu"],
                    ["value" => "Asia/Tokyo", "label" => "(GMT/UTC + 09:00) Tokyo"],
                    ["value" => "Asia/Ulaanbaatar", "label" => "(GMT/UTC + 08:00) Ulaanbaatar"],
                    ["value" => "Asia/Urumqi", "label" => "(GMT/UTC + 06:00) Urumqi"],
                    ["value" => "Asia/Ust-Nera", "label" => "(GMT/UTC + 10:00) Ust-Nera"],
                    ["value" => "Asia/Vientiane", "label" => "(GMT/UTC + 07:00) Vientiane"],
                    ["value" => "Asia/Vladivostok", "label" => "(GMT/UTC + 10:00) Vladivostok"],
                    ["value" => "Asia/Yakutsk", "label" => "(GMT/UTC + 09:00) Yakutsk"],
                    ["value" => "Asia/Yekaterinburg", "label" => "(GMT/UTC + 05:00) Yekaterinburg"],
                    ["value" => "Asia/Yerevan", "label" => "(GMT/UTC + 04:00) Yerevan"],
                ],
            ],
            [
                "label" => "Atlantic",
                "data" => [
                    ["value" => "Atlantic/Azores", "label" => "(GMT/UTC - 01:00) Azores"],
                    ["value" => "Atlantic/Bermuda", "label" => "(GMT/UTC - 04:00) Bermuda"],
                    ["value" => "Atlantic/Canary", "label" => "(GMT/UTC + 00:00) Canary"],
                    ["value" => "Atlantic/Cape_Verde", "label" => "(GMT/UTC - 01:00) Cape Verde"],
                    ["value" => "Atlantic/Faroe", "label" => "(GMT/UTC + 00:00) Faroe"],
                    ["value" => "Atlantic/Madeira", "label" => "(GMT/UTC + 00:00) Madeira"],
                    ["value" => "Atlantic/Reykjavik", "label" => "(GMT/UTC + 00:00) Reykjavik"],
                    ["value" => "Atlantic/South_Georgia", "label" => "(GMT/UTC - 02:00) South Georgia"],
                    ["value" => "Atlantic/St_Helena", "label" => "(GMT/UTC + 00:00) St. Helena"],
                    ["value" => "Atlantic/Stanley", "label" => "(GMT/UTC - 03:00) Stanley"],
                ],
            ],
            [
                "label" => "Australia",
                "data" => [
                    ["value" => "Australia/Adelaide", "label" => "(GMT/UTC + 10:30) Adelaide"],
                    ["value" => "Australia/Brisbane", "label" => "(GMT/UTC + 10:00) Brisbane"],
                    ["value" => "Australia/Broken_Hill", "label" => "(GMT/UTC + 10:30) Broken Hill"],
                    ["value" => "Australia/Currie", "label" => "(GMT/UTC + 11:00) Currie"],
                    ["value" => "Australia/Darwin", "label" => "(GMT/UTC + 09:30) Darwin"],
                    ["value" => "Australia/Eucla", "label" => "(GMT/UTC + 08:45) Eucla"],
                    ["value" => "Australia/Hobart", "label" => "(GMT/UTC + 11:00) Hobart"],
                    ["value" => "Australia/Lindeman", "label" => "(GMT/UTC + 10:00) Lindeman"],
                    ["value" => "Australia/Lord_Howe", "label" => "(GMT/UTC + 11:00) Lord Howe"],
                    ["value" => "Australia/Melbourne", "label" => "(GMT/UTC + 11:00) Melbourne"],
                    ["value" => "Australia/Perth", "label" => "(GMT/UTC + 08:00) Perth"],
                    ["value" => "Australia/Sydney", "label" => "(GMT/UTC + 11:00) Sydney"],
                ],
            ],
            [
                "label" => "Indian",
                "data" => [
                    ["value" => "Indian/Antananarivo", "label" => "(GMT/UTC + 03:00) Antananarivo"],
                    ["value" => "Indian/Chagos", "label" => "(GMT/UTC + 06:00) Chagos"],
                    ["value" => "Indian/Christmas", "label" => "(GMT/UTC + 07:00) Christmas"],
                    ["value" => "Indian/Cocos", "label" => "(GMT/UTC + 06:30) Cocos"],
                    ["value" => "Indian/Comoro", "label" => "(GMT/UTC + 03:00) Comoro"],
                    ["value" => "Indian/Kerguelen", "label" => "(GMT/UTC + 05:00) Kerguelen"],
                    ["value" => "Indian/Mahe", "label" => "(GMT/UTC + 04:00) Mahe"],
                    ["value" => "Indian/Maldives", "label" => "(GMT/UTC + 05:00) Maldives"],
                    ["value" => "Indian/Mauritius", "label" => "(GMT/UTC + 04:00) Mauritius"],
                    ["value" => "Indian/Mayotte", "label" => "(GMT/UTC + 03:00) Mayotte"],
                    ["value" => "Indian/Reunion", "label" => "(GMT/UTC + 04:00) Reunion"],
                ],
            ],
            [
                "label" => "Pacific",
                "data" => [
                    ["value" => "Pacific/Apia", "label" => "(GMT/UTC + 14:00) Apia"],
                    ["value" => "Pacific/Auckland", "label" => "(GMT/UTC + 13:00) Auckland"],
                    ["value" => "Pacific/Bougainville", "label" => "(GMT/UTC + 11:00) Bougainville"],
                    ["value" => "Pacific/Chatham", "label" => "(GMT/UTC + 13:45) Chatham"],
                    ["value" => "Pacific/Chuuk", "label" => "(GMT/UTC + 10:00) Chuuk"],
                    ["value" => "Pacific/Easter", "label" => "(GMT/UTC - 05:00) Easter"],
                    ["value" => "Pacific/Efate", "label" => "(GMT/UTC + 11:00) Efate"],
                    ["value" => "Pacific/Enderbury", "label" => "(GMT/UTC + 13:00) Enderbury"],
                    ["value" => "Pacific/Fakaofo", "label" => "(GMT/UTC + 13:00) Fakaofo"],
                    ["value" => "Pacific/Fiji", "label" => "(GMT/UTC + 12:00) Fiji"],
                    ["value" => "Pacific/Funafuti", "label" => "(GMT/UTC + 12:00) Funafuti"],
                    ["value" => "Pacific/Galapagos", "label" => "(GMT/UTC - 06:00) Galapagos"],
                    ["value" => "Pacific/Gambier", "label" => "(GMT/UTC - 09:00) Gambier"],
                    ["value" => "Pacific/Guadalcanal", "label" => "(GMT/UTC + 11:00) Guadalcanal"],
                    ["value" => "Pacific/Guam", "label" => "(GMT/UTC + 10:00) Guam"],
                    ["value" => "Pacific/Honolulu", "label" => "(GMT/UTC - 10:00) Honolulu"],
                    ["value" => "Pacific/Johnston", "label" => "(GMT/UTC - 10:00) Johnston"],
                    ["value" => "Pacific/Kiritimati", "label" => "(GMT/UTC + 14:00) Kiritimati"],
                    ["value" => "Pacific/Kosrae", "label" => "(GMT/UTC + 11:00) Kosrae"],
                    ["value" => "Pacific/Kwajalein", "label" => "(GMT/UTC + 12:00) Kwajalein"],
                    ["value" => "Pacific/Majuro", "label" => "(GMT/UTC + 12:00) Majuro"],
                    ["value" => "Pacific/Marquesas", "label" => "(GMT/UTC - 09:30) Marquesas"],
                    ["value" => "Pacific/Midway", "label" => "(GMT/UTC - 11:00) Midway"],
                    ["value" => "Pacific/Nauru", "label" => "(GMT/UTC + 12:00) Nauru"],
                    ["value" => "Pacific/Niue", "label" => "(GMT/UTC - 11:00) Niue"],
                    ["value" => "Pacific/Norfolk", "label" => "(GMT/UTC + 11:00) Norfolk"],
                    ["value" => "Pacific/Noumea", "label" => "(GMT/UTC + 11:00) Noumea"],
                    ["value" => "Pacific/Pago_Pago", "label" => "(GMT/UTC - 11:00) Pago Pago"],
                    ["value" => "Pacific/Palau", "label" => "(GMT/UTC + 09:00) Palau"],
                    ["value" => "Pacific/Pitcairn", "label" => "(GMT/UTC - 08:00) Pitcairn"],
                    ["value" => "Pacific/Pohnpei", "label" => "(GMT/UTC + 11:00) Pohnpei"],
                    ["value" => "Pacific/Port_Moresby", "label" => "(GMT/UTC + 10:00) Port Moresby"],
                    ["value" => "Pacific/Rarotonga", "label" => "(GMT/UTC - 10:00) Rarotonga"],
                    ["value" => "Pacific/Saipan", "label" => "(GMT/UTC + 10:00) Saipan"],
                    ["value" => "Pacific/Tahiti", "label" => "(GMT/UTC - 10:00) Tahiti"],
                    ["value" => "Pacific/Tarawa", "label" => "(GMT/UTC + 12:00) Tarawa"],
                    ["value" => "Pacific/Tongatapu", "label" => "(GMT/UTC + 13:00) Tongatapu"],
                    ["value" => "Pacific/Wake", "label" => "(GMT/UTC + 12:00) Wake"],
                    ["value" => "Pacific/Wallis", "label" => "(GMT/UTC + 12:00) Wallis"],
                ],
            ],
        ];
        $str = "<select " . $data . ">";
        foreach ($list as $item) {
            $str .= "<optgroup label=" . $item["label"] . ">";
            foreach ($item["data"] as $each) {
                $selected = $value == $each["value"] ? 'selected' : '';
                $str .= "<option value=" . $each["value"] . " " . $selected . ">" . $each["label"] . "</option>";
            }
            $str .= "</optgroup>";
        }
        $str .= "</select>";

        return $str;
    }
}
