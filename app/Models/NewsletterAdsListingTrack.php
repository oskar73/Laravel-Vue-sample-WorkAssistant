<?php

namespace App\Models;

use Yajra\DataTables\DataTables;

class NewsletterAdsListingTrack extends BaseModel
{
    protected $table = 'newsletter_ads_listing_tracks';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getUserDataTable($listing_id)
    {
        $trackings = $this::where('listing_id', $listing_id);

        return Datatables::of($trackings)->editColumn('created_at', function ($row) {
            return $row->created_at?->diffForHumans();
        })->make(true);
    }
}
