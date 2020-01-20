<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Yajra\DataTables\DataTables;

class NewsletterAdsListing extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'newsletter_ads_listings';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const CUSTOM_VALIDATION_MESSAGE = [
        'start.required' => 'Please pick listing event start date',
        'end.required' => 'Please pick listing event end date',
    ];

    public static function boot()
    {
        parent::boot();

        static::updated(function ($obj) {
            if ($obj->status == "expired") {
                $obj->expiredNotification();
            }
        });
    }

    public function expiredNotification()
    {
        $notification = new NotificationTemplate();
        $data['url'] = route('user.newsletterAds.detail', $this->id);
        $data['username'] = $this->user->name;
        $notification->sendNotification($data, $notification::NEWSLETTER_ADS_EXPIRED, $this->user);
    }

    public function position()
    {
        return $this->belongsTo(NewsletterAdsPosition::class, 'position_id')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function price()
    {
        return $this->belongsTo(NewsletterAdsPrice::class, 'price_id')->withDefault();
    }

    public function events()
    {
        return $this->hasMany(NewsletterAdsEvent::class, 'listing_id');
    }

    public function getDatatable($status, $user)
    {
        switch ($status) {
            case 'pending':
                $listings = $this::with('user', 'position', 'events')->where('status', 'pending');
                break;
            case 'all':
                $listings = $this::with('user', 'position', 'events');
                break;
            case 'approved':
                $listings = $this::with('user', 'position', 'events')->where('status', 'approved');
                break;
            case 'denied':
                $listings = $this::with('user', 'position', 'events')->where('status', 'denied');
                break;
            case 'expired':
                $listings = $this::with('user', 'position', 'events')->where('status', 'expired');
                break;
            case 'new':
                $listings = $this::with('user', 'position', 'events')->where('status', 'new');
                break;
        }

        if ($user != 'all') {
            $listings = $listings->where("user_id", $user);
        }

        return Datatables::of($listings)->addColumn('checkbox', function ($row) {
            return '<input type="checkbox" class="checkbox" data-id="' . $row->id . '">';
        })->addColumn('position', function ($row) {
            $result = "<a href=''>" . $row->position->name . "</a>";

            return $result;
        })->addColumn('user', function ($row) {
            $result = "<a href=''>" . $row->user->name . "</a>";

            return $result;
        })->addColumn('price', function ($row) {

            if ($row->price->type === 'impression') {
                $result = "Total Impressions: " . $row->price->impression_number . "<br> <a href='" . route('admin.blogAds.listing.tracking', $row->id) . "' class='underline'>Current Impressions: {$row->current_number}</a>";
            } else {
                $result = 'Duration:<br>';
                foreach ($row->events as $event) {
                    $result .= $event->start_date . " ~ " . $event->end_date . "<br>";
                }
            }

            return $result;
        })->editColumn('status', function ($row) {
            if ($row->status == 'approved') {
                return '<span class="c-badge c-badge-success">Approved</span>';
            } elseif ($row->status == 'pending') {
                return '<span class="c-badge c-badge-info" >Pending</span>';
            } elseif ($row->status == 'denied') {
                return '<span class="c-badge c-badge-danger" >Denied</span>';
            } elseif ($row->status == 'paid') {
                return '<span class="c-badge c-badge-info" >Newly Paid</span>';
            } elseif ($row->status == 'expired') {
                return '<span class="c-badge c-badge-warning" >Expired</span>';
            }
        })->editColumn('created_at', function ($row) {
            return $row->created_at?->diffForHumans();
        })->addColumn('action', function ($row) {
            return '
                    <a href="' . route('admin.newsletterAds.listing.show', $row->id) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3" title="Detail">
                            <i class="la la-eye"></i>
                    </a>
                    <a href="' . route('admin.newsletterAds.listing.edit', $row->id) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3" title="Edit">
                            <i class="la la-edit"></i>
                    </a>
                    <a href="javascript:void(0);" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3 switchOne" data-action="delete" title="Delete">
                            <i class="la la-remove"></i>
                    </a>';
        })->rawColumns(['checkbox', 'status', 'position', 'user', 'price', 'action'])
            ->make(true);
    }

    public function storeRule($request, $position, $price)
    {
        $type = $position->type;
        $rule['url'] = 'required|max:191|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        if ($price->type === 'period') {
            $rule['start'] = 'required';
            $rule['end'] = 'required';
            $rule['start.*'] = 'required';
            $rule['end.*'] = 'required';
        }
        $rule['price'] = 'required|exists:newsletter_ads_prices,id,status,1';
        $rule['status'] = 'required|in:approved,pending,denied,paid';
        $rule['notify_status'] = 'required|in:0,1,2';
        $rule['customer'] = 'required|exists:users,id,status,active';
        $rule['image'] = 'required';
        if ($request->notify_status != 0) {
            $rule['notification'] = 'required';
        }

        return $rule;
    }

    public function storeItem($request, $position, $price)
    {
        $type = $position->type;
        $item = $this;
        $item->position_id = $position->id;
        $item->user_id = $request->customer;
        $item->url = $request->url;

        $item->status = $request->status;
        $item->price_id = $price->id;
        if ($price->type === 'impression') {
            $item->impression_number = $price->impression;
        }
        $item->save();
        if ($request->image) {
            $item->addMediaFromBase64(json_decode($request->image)->output->image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('image');
        }

        return $item;
    }

    public function storeEvents($starts, $ends)
    {
        $price = json_decode($this->price);
        $data = [];
        if ($price->type === 'period') {
            foreach ($starts as $key => $start) {
                $data[$key]['start_date'] = $start;
                $data[$key]['end_date'] = $ends[$key];
            }
        } else {
            $data[0]['start_date'] = Carbon::now()->toDateString();
            $data[0]['end_date'] = null;
        }
        foreach ($data as $datum) {
            $this->events()->create($datum);
        }

        return $this;
    }

    public function updateRule($request)
    {
        $rule['url'] = 'nullable|max:191|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        if ($this->price->type === 'period') {
            $rule['start'] = 'required';
            $rule['end'] = 'required';
            $rule['start.*'] = 'required';
            $rule['end.*'] = 'required';
        } else {
            $rule['impression_number'] = 'required|integer';
            $rule['current_number'] = 'required|integer';
        }

        $rule['status'] = 'required|in:approved,pending,denied,paid,expired';
        $rule['notify_status'] = 'required|in:0,1,2';
        $rule['customer'] = 'required|exists:users,id,status,active';
        if ($request->notify_status != 0) {
            $rule['notification'] = 'required';
        }

        return $rule;
    }

    public function updateItem($request)
    {
        $item = $this;

        $item->user_id = $request->customer;
        $item->url = $request->url;
        $item->status = $request->status;
        if ($this->price->type === 'impression') {
            $item->impression_number = $request->impression_number;
            $item->current_number = $request->current_number;
        }
        $item->save();
        if ($request->image) {
            $item->clearMediaCollection('image')
                ->addMediaFromBase64(json_decode($request->image)->output->image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('image');
        }

        return $item;
    }

    public function updateEvents($request)
    {
        if ($this->price->type === 'period') {
            $this->events()->delete();
            $this->storeEvents($request->start, $request->end);
        }

        return $this;
    }

    public function storeItemFromPurchase($item, $user, $orderItem)
    {
        $price = json_decode($item['item']['price']);

        for ($k = 0; $k < $item['quantity']; $k++) {
            $listing = new NewsletterAdsListing();
            $listing->order_item_id = $orderItem->id;
            $listing->user_id = $user->id;
            $listing->position_id = $item['item']['id'];
            $listing->price_id = $item['item']['price']['id'];
            $listing->status = 'paid';
            if ($price->type == 'impression') {
                $listing->impression_number = $item['parameter'];
            }
            $listing->save();

            if ($price->type == 'period') {
                $starts = $item['parameter']['start'];
                $ends = $item['parameter']['end'];
            } else {
                $starts = [];
                $ends = [];
            }
            $listing->storeEvents($starts, $ends);

            Todo::storeItem($user, 'newsletterAds', \App\Models\NewsletterAdsListing::class, $listing->id, $orderItem->id);
        }

        return true;
    }

    public function getUserDataTable()
    {
        $listings = $this::with('user', 'position', 'events')->where('user_id', user()->id);

        return Datatables::of($listings)->addColumn('position', function ($row) {
            $result = "<a href=''>" . $row->position->name . "</a>";

            return $result;
        })->addColumn('type', function ($row) {
            return $row->position->type->name;
        })->addColumn('price', function ($row) {
            $price = $row->price;
            if ($price->type === 'impression') {
                $result = "Total Impressions: " . $row->impression_number . "<br> <a href='" . route('user.newsletterAds.tracking', $row->id) . "'  class='underline'>Current Impressions: {$row->current_number}</a> ";
            } else {
                $result = 'Duration: <br>';
                foreach ($row->events as $event) {
                    $result .= $event->start_date . " ~ " . $event->end_date . "<br>";
                }
                //                $result .= "<a href='".route('user.blogAds.tracking', $row->id)."' class='underline'>Current Impressions: {$row->current_number}</a>";
            }

            return $result;
        })->editColumn('status', function ($row) {
            if ($row->status == 'approved') {
                return '<span class="c-badge c-badge-success">Approved</span>';
            } elseif ($row->status == 'pending') {
                return '<span class="c-badge c-badge-info" >Pending</span>';
            } elseif ($row->status == 'denied') {
                return '<span class="c-badge c-badge-danger" >Denied</span>';
            } elseif ($row->status == 'paid') {
                return '<span class="c-badge c-badge-info" >Newly Paid</span>';
            } elseif ($row->status == 'expired') {
                return '<span class="c-badge c-badge-warning" >Expired</span>';
            }
        })->editColumn('created_at', function ($row) {
            return $row->created_at?->diffForHumans();
        })->addColumn('action', function ($row) {
            if ($row->status == 'expired') {
                return '
                    <a href="' . route('user.newsletterAds.detail', $row->id) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3" title="Detail">
                            <i class="la la-eye"></i>
                    </a>';
            } else {
                return '
                    <a href="' . route('user.newsletterAds.detail', $row->id) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3" title="Detail">
                            <i class="la la-eye"></i>
                    </a>
                    <a href="' . route('user.newsletterAds.edit', $row->id) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3" title="Edit">
                            <i class="la la-edit"></i>
                    </a>';
            }
        })->rawColumns(['checkbox', 'status', 'type', 'price', 'position', 'action'])
            ->make(true);
    }

    public function updateUserRule($request)
    {
        $rule['url'] = 'nullable|max:191|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        return $rule;
    }

    public function updateUserItem($request)
    {
        $item = $this;
        $item->url = $request->url;

        $item->status = 'pending';
        $item->save();

        if ($request->image) {
            $item->clearMediaCollection('image')
                ->addMediaFromBase64(json_decode($request->image)->output->image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('image');
        }

        return $item;
    }

    public function track($request)
    {
        $track = new NewsletterAdsListingTrack();
        $track->listing_id = $this->id;
        $track->ip = $request->ip();
        $track->device = getOS();
        $track->save();

        return $track;
    }
}
