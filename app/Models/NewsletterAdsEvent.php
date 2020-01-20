<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterAdsEvent extends BaseModel
{
    protected $table = 'newsletter_ads_events';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public $timestamps = false;

    const CUSTOM_VALIDATION_MESSAGE = [

    ];

    public function listing()
    {
        return $this->belongsTo(NewsletterAdsListing::class, 'listing_id')->withDefault();
    }
}
