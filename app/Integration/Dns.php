<?php


namespace App\Integration;

class Dns
{
    public static function getDns($domain)
    {
        $dns = new \Spatie\Dns\Dns($domain);

        return $dns->getRecords(['A', 'CNAME']);
    }
}
