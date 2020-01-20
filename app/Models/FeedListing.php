<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *  What is Feed Listing and Why we need, What is it for?
 */
class FeedListing extends Model
{
    use HasFactory;

    protected $table = 'feed_listing';

    public function storeFeedData($data)
    {
        $this->user_id = \Auth::user()->id;
        if (array_key_exists('website_id', $data)) {
            $this->website_id = $data['website_id'];
        }
        if (array_key_exists('template_id', $data)) {
            $this->template_id = $data['template_id'];
        }

        if (array_key_exists('website_domain', $data)) {
            $this->website_domain = $data['website_domain'];
        }
        $this->text = $data['message'];
        $this->status = 1;
        $this->save();

        return true;
    }
}
