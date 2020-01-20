<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\NewsletterAdsListing;
use App\Models\NewsletterAdsListingTrack;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;
use Validator;

class NewsletterAdsController extends UserController
{
    public function __construct(NewsletterAdsListing $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            return $this->model->getUserDataTable();
        }

        return view(self::$viewDir . "newsletterAds.index");
    }

    public function detail($id)
    {
        $listing = $this->model->my()->where('id', $id)->firstorfail();

        return view(self::$viewDir . "newsletterAds.detail", compact("listing"));
    }

    public function edit($id)
    {
        $listing = $this->model->my()
            ->where('id', $id)
            ->where("status", "!=", "expired")
            ->firstorfail();

        return view(self::$viewDir . "newsletterAds.edit", compact("listing"));
    }

    public function update(Request $request, $id)
    {
        try {
            $listing = $this->model->my()
                ->where('id', $id)
                ->where("status", "!=", "expired")
                ->firstorfail();

            $validation = Validator::make($request->all(), $listing->updateUserRule($request), $this->model::CUSTOM_VALIDATION_MESSAGE);

            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }

            $item = $listing->updateUserItem($request);
            $notification = new NotificationTemplate();
            $data['url'] = route('admin.newsletterAds.listing.show', $item->id);
            $data['slug'] = $notification::NEWSLETTER_ADS_APPROVAL;

            $notification->sendNotificationToAdmin($data);

            return response()->json([
                'status' => 1,
                'data' => $item,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }

    public function tracking($id)
    {
        $listing = $this->model->my()->where('id', $id)->firstorfail();
        if ($listing->price->type == 'period') {
            abort(404);
        }
        if (request()->wantsJson()) {
            $tracking = new NewsletterAdsListingTrack();

            return $tracking->getUserDataTable($listing->id);
        }

        return view(self::$viewDir . "newsletterAds.tracking", compact("listing"));
    }

    public function getChart($id)
    {
        $listing = $this->model->my()->where('id', $id)->firstorfail();
        $dates = [];
        $trackings = [];
        $devices = [];
        $device_sessions = [];
        $device_colors = [];

        $data1 = NewsletterAdsListingTrack::where("listing_id", $listing->id)
            ->selectRaw('count(id) as count, date(created_at) as "date"')
            ->groupBy("date")
            ->take(10)
            ->get()
            ->sortByDesc("date");
        foreach ($data1 as $item) {
            array_unshift($dates, $item->date);
            array_unshift($trackings, $item->count);
        }
        $data2 = NewsletterAdsListingTrack::where("listing_id", $listing->id)
            ->selectRaw('count(id) as count, device')
            ->groupBy("device")
            ->take(10)
            ->get()
            ->sortByDesc("device");

        foreach ($data2 as $item2) {
            $devices[] = $item2->device;
            $device_sessions[] = $item2->count;
            $device_colors[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        }

        $data['dates'] = $dates;
        $data['trackings'] = $trackings;
        $data['devices'] = $devices;
        $data['device_sessions'] = $device_sessions;
        $data['device_colors'] = $device_colors;

        return response()->json([
            'status' => 1,
            'data' => $data,
        ]);
    }
}
