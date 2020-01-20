<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Team extends BaseModel implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;
    protected $table = 'teams';

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $appends = ['image'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
    const CUSTOM_VALIDATION_MESSAGE = [

    ];
    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl("thumbnail");
    }
    public function storeRule($slug = null)
    {
        $rule['name'] = 'required|max:45';
        $rule['description'] = 'nullable|max:600';

        return $rule;
    }
    public function saveItem($request, $slug = null)
    {
        $item = $this;
        $item->name = $request->name;
        $item->parent_id = $slug == null? 0 : Team::whereSlug($slug)->whereParentId(0)->firstorfail()->id;
        $item->description = $request->description;
        $item->status = $request->status? 1:0;
        $item->save();

        return $item;
    }
    public function storeItem($request, $slug = null)
    {
        $item = $this->saveItem($request, $slug)
            ->syncProperties($request)
            ->syncUsers($request);

        if ($request->thumbnail) {
            $item->clearMediaCollection('thumbnail')
                ->addMediaFromBase64($request->thumbnail)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('thumbnail');
        }

        return $item;
    }
    public function syncProperties($request)
    {
        $this->properties()->sync($request->properties);

        return $this;
    }
    public function syncUsers($request)
    {
        \DB::table('team_has_user')->where('team_id', $this->id)->delete();
        $data = [];
        $flag = 0;
        if ($request->clients != null) {
            foreach ($request->clients as $client) {
                $data[] = ['team_id' => $this->id, 'user_id' => $client, 'role' => 'client'];
            }
            $flag = 1;
        }
        if ($request->employees != null) {
            foreach ($request->employees as $employee) {
                $data[] = ['team_id' => $this->id, 'user_id' => $employee, 'role' => 'employee'];
            }
            $flag = 1;
        }
        if ($request->users != null) {
            foreach ($request->users as $user) {
                $data[] = ['team_id' => $this->id, 'user_id' => $user, 'role' => 'user'];
            }
            $flag = 1;
        }
        if ($flag != 0) {
            \DB::table('team_has_user')->insert($data);
        }

        return $this;
    }
    public function subTeams()
    {
        return $this->hasMany(Team::class, 'parent_id');
    }
    public function activeSubTeams()
    {
        return $this->hasMany(Team::class, 'parent_id')->where("status", 1);
    }
    public function parent()
    {
        return $this->belongsTo(Team::class, 'parent_id')->withDefault();
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'team_has_user', 'team_id', 'user_id')
            ->withPivot('role');
    }
    public function properties()
    {
        return $this->belongsToMany(TeamProperty::class, 'team_has_properties', 'team_id', 'property_id');
    }
    public function scopeIsParent($query)
    {
        return $query->where('parent_id', 0);
    }
    public function isParent()
    {
        if ($this->parent_id === 0) {
            return true;
        } else {
            return false;
        }
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(50)
            ->height(50)
            ->sharpen(10)
            ->nonQueued();
    }
    public function getImage($thumb = null)
    {
        $avatar = $thumb == null? $this->getFirstMediaUrl('thumbnail') : $this->getFirstMediaUrl('thumbnail', $thumb);
        if ($avatar != "" || $avatar != null) {
            return $avatar;
        } else {
            $size = $thumb == null? 300:30;

            return "https://ui-avatars.com/api/?size={$size}&&name=" . $this->name;
        }
    }

    public function myUnreadFromTeams()
    {
        return $this->hasOne(MessageCount::class, "from_id")
            ->where("from_type", "team")
            ->where("user_id", user()->id);
    }

    public function getLastMessage()
    {
        $id = $this->id;

        return Message::where(function ($query) use ($id) {
            $query->where("from_id", $id);
            $query->where("from_type", "team");
        })
            ->orWhere(function ($query) use ($id) {
                $query->where("to_id", $id);
                $query->where("to_type", "team");
            })->latest()->first();
    }
}
