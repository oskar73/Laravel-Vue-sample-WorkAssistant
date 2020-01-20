<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slider extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'sliders';

    protected $guarded = ['created_at', 'updated_at'];

    const CUSTOM_VALIDATION_MESSAGE = [

    ];
    public function storeRule($request)
    {
        $rule['featured_name'] = 'nullable|string|max:45';
        $rule['name'] = 'nullable|string|max:45';
        $rule['type'] = 'required|in:blogPackage,blogAds,service,plugin,module,lacarte,package,readymade,portfolio,url';
        if ($request->type == 'url') {
            $rule['url'] = 'required|string';
        } else {
            // $rule['product'] = 'nullable|integer';
            $rule['product'] = 'required|integer';
        }
        $rule['thumbnail'] = 'required';

        return $rule;
    }
    public function storeItem($request)
    {
        if ($request->item_id == null) {
            $item = $this;
        } else {
            $item = $this->findorfail($request->item_id);
        }
        $item->featured_name = $request->featured_name;
        $item->name = $request->name;
        if ($request->type == 'url') {
            $item->model_type = 'url';
            $item->model_id = $request->url;
        } else {
            $item->model_type = $this->slugToModel($request->type);
            $item->model_id = $request->product;
        }
        $item->save();

        $item->clearMediaCollection('image');
        $item->addMediaFromBase64(json_decode($request->thumbnail)->output->image)
            ->usingFileName(guid() . ".jpg")
            ->toMediaCollection('image');

        return $item;
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(40)
            ->height(60)
            ->sharpen(10)
            ->nonQueued();
    }
    public function model()
    {
        return $this->morphTo(null, 'model_type', 'model_id');
    }
    public function getPrice()
    {
        if ($this->type === 'url') {
            return null;
        } else {
            $item = $this->model;
            $result['slashed'] = null;
            $result['price'] = null;
            if ($item->price) {
                $result['slashed'] = $item->slished_price;
                $result['price'] = $item->price;
            } else {
                if ($item->title) {
                } else {
                    $price = $item->standardPrice;

                    if ($price->recurrent == 1) {
                        $result['slashed'] = $price->slashed_price;
                        $result['price'] = $price->price . "/" . $price->period . " " . $price->period_unit;
                    } elseif ($price->recurrent === 0) {
                        $result['slashed'] = $price->slashed_price;
                        $result['price'] = $price->price;
                    }
                }
            }

            return $result;
        }
    }
    public function modelToSlug($item)
    {
        $val = parent::modelToSlug($item);
        if($val = '1'){
            if($item == 'url'){
                $val = 'url';
            }
        }

        return $val;
    }
}
