<?php

namespace App\Http\Controllers\Admin\NewsletterAds;

use App\Http\Controllers\Admin\AdminController;
use App\Models\BlogAdsListingTrack;
use App\Models\NewsletterAdsEvent;
use App\Models\NewsletterAdsListing;
use App\Models\NewsletterAdsListingTrack;
use App\Models\NewsletterAdsPosition;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;
use Validator;

class ListingController extends AdminController
{
    public function __construct(NewsletterAdsListing $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            return $this->model->getDatatable(request()->get('status'), request()->get('user'));
        }

        return view(self::$viewDir . 'newsletterAds.listing');
    }

    public function select()
    {
        $positions = NewsletterAdsPosition::where('status', 1)->with('media', 'standardPrice', 'type')->get();

        return view(self::$viewDir . 'newsletterAds.listingSelect', compact('positions'));
    }

    public function create(Request $request, $slug)
    {
        $position = NewsletterAdsPosition::where('slug', $slug)->with('media', 'prices', 'type')->first();

        if (request()->ajax()) {
            $start = $request->start;
            $end = $request->end;

            $events = NewsletterAdsEvent::join("newsletter_ads_listings", "newsletter_ads_events.listing_id", "newsletter_ads_listings.id")
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween("newsletter_ads_events.start_date", [$start, $end]);
                    $q->orWhereBetween("newsletter_ads_events.end_date", [$start, $end]);
                })->where("newsletter_ads_listings.position_id", $position->id)
                ->select("newsletter_ads_events.id", "newsletter_ads_events.start_date", "newsletter_ads_events.end_date")
                ->get();

            $lists = [];
            foreach ($events as $event) {
                if ($event->start_date != null && $event->end_date != null) {
                    $lists[] = [
                        'id' => 'e' . $event->id,
                        'start' => $event->start_date . " 00:00:00",
                        'end' => $event->end_date . " 24:00:00",
                        'color' => '#ff0000',
                        'rendering' => 'background',
                        'allDay' => true,
                    ];
                }
            }

            return $lists;
        }

        return view(self::$viewDir . 'newsletterAds.listingCreate', compact('position'));
    }

    public function store(Request $request, $slug)
    {
        try {
            $position = NewsletterAdsPosition::where('slug', $slug)->where('status', 1)->firstOrFail();

            $price = $position->prices->where('id', $request->price)->where('status', 1)->firstOrFail();

            $validation = Validator::make(
                $request->all(),
                $this->model->storeRule($request, $position, $price),
                $this->model::CUSTOM_VALIDATION_MESSAGE
            );

            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }

            $item = $this->model->storeItem($request, $position, $price)
                ->storeEvents($request->start, $request->end);

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

    public function show($id)
    {
        $listing = $this->model->with('media', 'events', 'position')->whereId($id)->firstOrFail();

        return view(self::$viewDir . 'newsletterAds.listingShow', compact('listing'));
    }

    public function switchListing(Request $request)
    {
        try {
            $action = $request->action;

            $notification = new NotificationTemplate();
            $slug = '';
            $notify = 0;

            $listings = $this->model->with('user')
                ->whereIn('id', $request->ids)
                ->get();

            if ($action === 'approve') {
                $listings->each->update(['status' => 'approved', 'reason' => null]);
                $slug = $notification::NEWSLETTER_ADS_APPROVED;
                $notify = 1;
            } elseif ($action === 'deny') {
                $listings->each->update(['status' => 'denied', 'reason' => $request->reason]);
                $data['reason'] = $request->reason;
                $slug = $notification::NEWSLETTER_ADS_DENIED;
                $notify = 1;
            } elseif ($action === 'pending') {
                $listings->each->update(['status' => 'pending']);
            } elseif ($action === 'expired') {
                $listings->each->update(['status' => 'expired']);
            } elseif ($action === 'paid') {
                $listings->each->update(['status' => 'paid']);
            } elseif ($action === 'delete') {
                $listings->each->delete();
            }

            if ($notify == 1) {
                foreach ($listings as $listing) {
                    $data['url'] = route('user.newsletterAds.detail', $listing->id);
                    $data['username'] = $listing->user->name;
                    $notification->sendNotification($data, $slug, $listing->user);
                }
            }

            return response()->json(['status' => 1]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $listing = $this->model->with('media', 'events', 'position')->whereId($id)->firstOrFail();

        if ($request->ajax()) {

        }

        return view(self::$viewDir . 'newsletterAds.listingEdit', compact('listing'));
    }

    public function update(Request $request, $id)
    {
        try {
            $listing = $this->model->whereId($id)
                ->firstorfail();

            $validation = Validator::make($request->all(), $listing->updateRule($request), $this->model::CUSTOM_VALIDATION_MESSAGE);

            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $item = $listing->updateItem($request)
                ->updateEvents($request);

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
        $listing = $this->model->whereId($id)->firstorfail();
        if ($listing->price->type == 'period') {
            abort(404);
        }
        if (request()->wantsJson()) {
            $tracking = new NewsletterAdsListingTrack();

            return $tracking->getUserDataTable($listing->id);
        }

        return view(self::$viewDir . "newsletterAds.listingTracking", compact("listing"));
    }
}
