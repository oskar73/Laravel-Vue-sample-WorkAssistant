<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;

class WebsiteUserAppointmentCategory extends BaseModel
{
    use Sluggable;

    protected $connection = 'mysql2';
    protected $table = "appointment_categories";

    protected $guarded = ["id", "created_at", "updated_at", "web_id"];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['name', 'web_id'],
            ],
        ];
    }

    const CUSTOM_VALIDATION_MESSAGE = [

    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($obj) {
            WebsiteAvailableWeekday::where("type", \App\Models\UserAppointmentCategory::class)
                ->where('product_id', $obj->id)
                ->get()
                ->each
                ->delete();
        });
    }

    public function web()
    {
        return $this->belongsTo(Website::class, 'web_id');
    }

    public function storeRule($request)
    {
        if ($request->category_id) {
            $rule['category_id'] = 'required|exists:mysql2.appointment_categories,id';
        }
        $rule['web_id'] = 'required|exists:mysql2.websites,id';
        $rule['name'] = 'required|max:45';
        $rule['description'] = 'nullable|max:600';

        return $rule;
    }

    public function storeItem($request)
    {
        $item = $this;
        $item->name = $request->name;
        $item->description = $request->description;
        $item->status = $request->status ? 1:0;
        $item->web_id = $request->web_id;
        $item->save();

        return $item;
    }

    public function scopeOfWebsite($query)
    {
        return $query->whereIn('web_id', user()->websites()->pluck('id')->all());
    }
}
