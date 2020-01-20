<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Storage;

class SiteBaseModel extends Model
{
    const CUSTOM_VALIDATION_MESSAGE = [

    ];

    public function getCreatedAtAttribute($value)
    {
        $result = Carbon::parse($value);
        if (\Auth::check()) {
            $result->setTimezone(user()->timezone)
                ->format(user()->timeformat);

            return $result;
        } else {
            return $result;
        }
    }
    public function getUpdatedAtAttribute($value)
    {
        $result = Carbon::parse($value);
        if (\Auth::check()) {
            $result->setTimezone(user()->timezone)
                ->format(user()->timeformat);

            return $result;
        } else {
            return $result;
        }
    }
    public function toUserTimezone($value = null)
    {
        if ($value == null) {
            $result = Carbon::now();
        } else {
            $result = Carbon::parse($value);
        }
        if ($this->timezone) {
            $result->setTimezone($this->timezone);
            if ($this->timeformat) {
                $result->format($this->timeformat);
            }

            return $result;
        } else {
            return $result;
        }
    }
    public function scopeMy($query, $user_id = null)
    {
        if ($user_id) {
            return $query->where('user_id', $user_id);
        } else {
            return $query->where('user_id', user()->id);
        }
    }
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->firstorfail();
    }

    public function getLinks()
    {
        if ($this->links !== null) {
            return json_decode($this->links);
        } else {
            return [];
        }
    }
    public function slugToModel($item)
    {
        if ($item == 'lacarte') {
            return \App\Models\Lacarte::class;
        } elseif ($item == 'module') {
            return \App\Models\Module::class;
        } elseif ($item == 'plugin') {
            return \App\Models\Plugin::class;
        } elseif ($item == 'package') {
            return \App\Models\Package::class;
        } elseif ($item == 'readymade') {
            return \App\Models\Package::class;
        } elseif ($item == 'service') {
            return \App\Models\Service::class;
        } elseif ($item == 'portfolio') {
            return \App\Models\Portfolio::class;
        } elseif ($item == 'blogAds') {
            return \App\Models\BlogAdsSpot::class;
        } elseif ($item == 'blogPackage') {
            return \App\Models\BlogPackage::class;
        } else {
            return '';
        }
    }
    public function modelToSlug($item)
    {
        if ($item == \App\Models\Lacarte::class) {
            return 'lacarte';
        } elseif ($item == \App\Models\Module::class) {
            return 'module';
        } elseif ($item == \App\Models\Plugin::class) {
            return 'plugin';
        } elseif ($item == \App\Models\Package::class) {
            return 'package';
        } elseif ($item == \App\Models\Package::class) {
            return 'readymade';
        } elseif ($item == \App\Models\Service::class) {
            return 'service';
        } elseif ($item == \App\Models\BlogPackage::class) {
            return 'blogPackage';
        } elseif ($item == \App\Models\BlogAdsSpot::class) {
            return 'blogAds';
        } else {
            return '1';
        }
    }
    public function userModelToName($item)
    {
        if ($item == \App\Models\UserLacarte::class) {
            return 'A Lacarte';
        } elseif ($item == \App\Models\UserPlugin::class) {
            return 'Plugin';
        } elseif ($item == \App\Models\UserPackage::class) {
            return 'Package';
        } elseif ($item == \App\Models\UserService::class) {
            return 'Service';
        } elseif ($item == \App\Models\UserBlogPackage::class) {
            return 'Blog package';
        } else {
            return '';
        }
    }
    public function nameToUserProduct($item)
    {
        if ($item == 'lacarte') {
            return \App\Models\UserLacarte::class;
        } elseif ($item == 'plugin') {
            return \App\Models\UserPlugin::class;
        } elseif ($item == 'service') {
            return \App\Models\UserService::class;
        } elseif ($item == 'blog') {
            return \App\Models\UserBlogPackage::class;
        } elseif ($item == 'blogPackage') {
            return \App\Models\UserBlogPackage::class;
        } elseif ($item == 'blogAds') {
            return \App\Models\BlogAdsListing::class;
        } else {
            return \App\Models\UserPackage::class;
        }
    }

    public static function fileUpload($file, $name, $path = null)
    {
        if ($path == null) {
            Storage::putFileAs("", $file, $name);
            $file_url = $name;
        } else {
            if (! Storage::exists($path)) {
                Storage::makeDirectory($path);
            }
            Storage::putFileAs($path, $file, $name);
            $file_url = $path."/".$name;
        }

        return Storage::url($file_url);
    }
}
