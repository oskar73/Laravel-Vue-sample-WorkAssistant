<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;

class Newsletter extends BaseModel
{
    use Sluggable;

    protected $fillable = ['name', 'slug', 'description', 'subject', 'html', 'modelData', 'status', 'sent_at', 'failed'];

    protected $casts = [
        'modelData' => 'array',
        'failed' => 'array',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function getHasActiveAdsAttribute()
    {
        $now = now();
        $activeListings = NewsletterAdsEvent::where('start_date', '<=', $now)
            ->where(function ($query) use ($now) {
                $query->where('end_date', '>=', $now)
                    ->orWhereNull('end_date');
            })
            ->whereHas('listing', function ($query) {
                $query->where('status', 'approved');
            })
            ->get();

        if (!$activeListings) {
            return null;
        }

        return $activeListings->load('listing.position')->pluck('listing.position.name', 'listing.position.id')->all();
    }
}
