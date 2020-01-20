<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class NewsletterAdsPosition extends BaseModel implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;

    protected $table = 'newsletter_ads_positions';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const CUSTOM_VALIDATION_MESSAGE = [
        'type_id.required' => 'position field is required',
    ];
    const ADDTOCART_VALIDATION_MESSAGE = [
        'start.required' => 'Please pick listing event start date',
        'end.required' => 'Please pick listing event end date',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function type()
    {
        return $this->belongsTo(NewsletterAdsType::class, 'type_id');
    }

    public function createRule($request)
    {
        $rule['name'] = 'required|string|max:45';
        $rule['description'] = 'max:3000';
        if ($request->edit_id) {
            $rule['type'] = 'nullable|exists:newsletter_ads_types,id,status,1';
        } else {
            $rule['type'] = 'required|exists:newsletter_ads_types,id,status,1';
            $rule['image'] = 'required';
        }

        return $rule;
    }

    public function storeItem($request)
    {
        $item = $this;
        $item->name = $request->name;
        $item->description = $request->description;
        $item->type_id = $request->type;
        $item->status = $request->status ? 1 : 0;
        $item->save();

        if ($request->image) {
            $item->clearMediaCollection("thumbnail")
                ->addMediaFromBase64(json_decode($request->image)->output->image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('thumbnail');
        }

        return $item;
    }

    public function createPriceRule($request)
    {
        $rule['price'] = 'required|regex:/^\d+(\.\d{1,2})?$/';
        $rule['slashed_price'] = 'nullable|regex:/^\d+(\.\d{1,2})?$/';
        $rule['payment_type'] = 'required|in:period,impression';
        if ($request->payment_type == 'period') {
            $rule['period'] = 'required|in:1,7,14,30,90,180,365';
        } else {
            $rule['impression'] = 'required|integer|min:1';
        }

        return $rule;
    }

    public function createPrice($request)
    {
        $data['type'] = $request->payment_type;
        $data['price'] = $request->price;
        if ($request->payment_type == 'period') {
            $data['period'] = $request->period;
            $data['impression'] = null;
        } else {
            $data['period'] = null;
            $data['impression'] = $request->impression;
        }
        $data['slashed_price'] = $request->slashed_price;
        $data['status'] = $request->status ? 1 : 0;
        $data['standard'] = $request->standard ? 1 : 0;

        if ($request->standard) {
            $this->prices()->whereStandard(1)->update(['standard' => 0]);
        }

        if ($request->edit_price) {
            $price = $this->prices()->where('id', $request->edit_price)
                ->update($data);
        } else {
            $price = $this->prices()->create($data);
        }

        return $price;
    }

    public function prices()
    {
        return $this->hasMany(NewsletterAdsPrice::class, 'position_id');
    }

    public function approvedPrices()
    {
        return $this->hasMany(NewsletterAdsPrice::class, 'position_id')->where("status", 1);
    }

    public function standardPrice()
    {
        return $this->hasOne(NewsletterAdsPrice::class, 'position_id')
            ->where("status", 1)
            ->orderBy('standard', 'DESC');
    }

    public function listings()
    {
        return $this->hasMany(NewsletterAdsListing::class, 'position_id');
    }

    public function filterItem()
    {
        try {
            $items = NewsletterAdsPosition::where('status', 1)
                ->select('newsletter_ads_positions.id', 'newsletter_ads_positions.name', 'newsletter_ads_positions.slug', 'newsletter_ads_positions.description', 'newsletter_ads_positions.type_id')
                ->with('media', 'standardPrice', 'type')
                ->orderBy('newsletter_ads_positions.created_at', 'DESC')
                ->paginate(15);

            $view = view('components.front.newsletterAdsPositionItem', compact('items'))->render();

            $data['status'] = 1;
            $data['view'] = $view;

            return $data;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }

    public function addToCartRule($price)
    {
        $rule = [];
        if ($price->type === 'period') {
            $rule['end'] = 'required';
            $rule['start'] = 'required';
            $rule['start.*'] = 'required';
            $rule['end.*'] = 'required';
        }

        return $rule;
    }

    public function updateListingRule($request)
    {
        $rule['ads_url'] = 'required|max:191';
        if (!$this->getFirstMediaUrl("image")) {
            $rule['ads_image'] = 'required';
        }

        return $rule;
    }

    public function updateListing($request)
    {
        if ($request->ads_image) {
            $this->clearMediaCollection('image');
            $this->addMediaFromBase64(json_decode($request->ads_image)->output->image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('image');
        }

        $this->default_url = $request->ads_url;
        $this->save();
    }
}
