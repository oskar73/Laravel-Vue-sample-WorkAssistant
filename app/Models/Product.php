<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = ["id", "created_at", "updated_at"];

    const CUSTOM_VALIDATION_MESSAGE = [

    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function category()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'category_id');
    }

    public function unit()
    {
        return $this->hasOne(ProductUnit::class, 'id', 'unit_id');
    }

    public function additionalPrices()
    {
        return $this->hasMany(ProductAdditionalPrice::class);
    }

    public function images()
    {
        return $this->getMedia('image');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($obj) {
            ProductAdditionalPrice::where('product_id', $obj->id)
                ->get()
                ->each
                ->delete();
        });
    }

    public function storeRule($request)
    {
        $rule['name'] = 'required|max:200';
        $rule['price'] = 'required|numeric';
        $rule['quantity'] = 'required|numeric';
        $rule['unit_id'] = 'nullable|exists:product_units,id';
        $rule['category_id'] = 'required|exists:product_categories,id';
        $rule['description'] = 'nullable|max:600';
        $rule['images.*'] = 'nullable|image|mimes:jpg,png,jpeg,gif|max:10240';

        return $rule;
    }

    public function storeItem($request)
    {
        $item = $this;
        $item->name = $request->name;
        $item->price = $request->price;
        $item->quantity = $request->quantity;
        $item->unit_id = $request->unit_id;
        $item->category_id = $request->category_id;
        $item->status = $request->status? 1:0;
        $item->description = $request->description;
        $item->user_id = user()->id;
        $item->save();

        if ($request->has('sizeIds') && is_array($sizeIds = $request->get('sizeIds'))) {
            $sizePrices = $request->get('sizePrices');
            foreach ($sizeIds as $key => $sizeId) {
                ProductAdditionalPrice::updateOrCreate([
                    'product_id' => $item->id,
                    'model_id' => $sizeId,
                    'model_type' => \App\Models\ProductSize::class,
                ], [
                    'price' => $sizePrices[$key],
                ]);
            }
        }

        if ($request->has('colorIds') && is_array($colorIds = $request->get('colorIds'))) {
            $colorPrices = $request->get('colorPrices');
            foreach ($colorIds as $key => $colorId) {
                ProductAdditionalPrice::updateOrCreate([
                    'product_id' => $item->id,
                    'model_id' => $colorId,
                    'model_type' => \App\Models\ProductColor::class,
                ], [
                    'price' => $colorPrices[$key],
                ]);
            }
        }
        ProductAdditionalPrice::where('model_type', \App\Models\ProductSize::class)
            ->where('product_id', $item->id)
            ->whereNotIn('model_id', $request->get('sizeIds') ?? [])
            ->delete();
        ProductAdditionalPrice::where('model_type', \App\Models\ProductColor::class)
            ->where('product_id', $item->id)
            ->whereNotIn('model_id', $request->get('colorIds') ?? [])
            ->delete();

        $ids = $request->oldItems ?? [];
        $collections = ['image', 'video', 'thumbnail'];
        $item->media()
            ->whereNotIn("id", $ids)
            ->whereIn("collection_name", $collections)
            ->get()
            ->each
            ->delete();

        $this->saveMedia($request);

        return $item;
    }

    public function saveMedia($request)
    {
        if ($request->image) {
            $this->addMediaFromBase64(json_decode($request->image)->output->image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('thumbnail');
        }
        foreach ($request->images ?? [] as $image) {
            $this->addMedia($image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('image');
        }
        foreach ($request->videos ?? [] as $video) {
            $this->addMedia($video)
                ->toMediaCollection('video');
        }

        return $this;
    }
}
