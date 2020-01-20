<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends BaseModel implements HasMedia
{
    use InteractsWithMedia;
    protected $table = "messages";

    protected $guarded = ["id", "created_at", "updated_at"];
    protected $appends = ['datetime', 'image', 'from_name'];

    public function getDatetimeAttribute()
    {
        return $this->created_at->toDateTimeString();
    }
    public function getFromNameAttribute()
    {
        if ($this->from_type === 'guest') {
            return $this->from_guest->name;
        } elseif ($this->from_type === 'user') {
            return $this->from_user->name;
        } else {
            return null;
        }
    }

    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl("image");
    }
    public function storeGuestMsg($data, $guest)
    {
        $data = json_decode($data);

        $msg = $this;
        $msg->type = $data->type;
        if ($data->type === 'text' || $data->type === 'alert') {
            $msg->message = $data->message;
        } else {
            $extension = explode('/', mime_content_type($data->message))[1];
            $filename = guid() . "." . $extension;

            $msg->message = $filename;
        }
        $msg->from_id = $data->from_id;
        $msg->from_type = $data->from_type;
        $msg->to_id = $data->from_id;
        $msg->to_type = 'guest';
        $msg->save();

        if ($data->type !== 'text' && $data->type !== 'alert') {
            $msg->addMediaFromBase64($data->message)
                ->usingFileName($filename)
                ->toMediaCollection('image', 's3-pub-bizinabox');
        }
        $users = $guest->supports->where("pivot.status", 1)->pluck("id");
        foreach ($users as $user) {
            $message_count = MessageCount::firstOrCreate([
               'user_id' => $user,
                'from_id' => $guest->id,
                'from_type' => "guest",
            ]);
            $message_count->increment('count');
        }

        return $msg;
    }
    public function storeUserMsg($data, $tab, $item)
    {
        $data = json_decode($data);

        $msg = $this;
        $msg->type = $data->type;
        if ($data->type === 'text' || $data->type === 'alert') {
            $msg->message = $data->message;
        } else {
            $extension = explode('/', mime_content_type($data->message))[1];
            $filename = guid() . "." . $extension;

            $msg->message = $filename;
        }
        $msg->from_id = user()->id;
        $msg->from_type = 'user';
        $msg->to_id = $item;
        $msg->to_type = $tab;
        $msg->save();

        if ($data->type !== 'text' && $data->type !== 'alert') {
            $msg->addMediaFromBase64($data->message)
                ->usingFileName($filename)
                ->toMediaCollection('image', 's3-pub-bizinabox');
        }
        if ($tab === 'user') {
            $message_count = MessageCount::firstOrCreate([
                'user_id' => $item,
                'from_id' => user()->id,
                'from_type' => "user",
            ]);
            $message_count->increment('count');
        } elseif ($tab === 'team') {
            $team = Team::with('users')->findorfail($item);
            $user_ids = $team->users->pluck("id")->toArray();

            foreach (array_unique($user_ids) as $user_id) {
                if ($user_id != user()->id) {
                    $message_count = MessageCount::firstOrCreate([
                        'user_id' => $user_id,
                        'from_id' => $item,
                        'from_type' => "team",
                    ]);
                    $message_count->increment('count');
                }
            }
        } elseif ($tab === 'guest') {
            $guest = Guest::findorfail($item);
            $users = $guest->supports->where("pivot.status", 1)->pluck("id");
            foreach ($users as $user) {
                if ($user != user()->id) {
                    $message_count = MessageCount::firstOrCreate([
                        'user_id' => $user,
                        'from_id' => $guest->id,
                        'from_type' => "guest",
                    ]);
                    $message_count->increment('count');
                }
            }
        }

        return $msg;
    }

    public function to()
    {
        return $this->hasMany(MessageTo::class, "message_id");
    }
    public function from_guest()
    {
        return $this->belongsTo(Guest::class, "from_id")->withDefault();
    }
    public function from_user()
    {
        return $this->belongsTo(User::class, "from_id")->withDefault();
    }
    public function guestGetMessage($item_id, $perpage = 30)
    {
        $query = Message::with("media", "from_guest", "from_user")
            ->where(function ($q) use ($item_id) {
                $q->where("messages.from_type", "guest");
                $q->where("messages.from_id", $item_id);
            })
            ->orWhere(function ($q) use ($item_id) {
                $q->where("messages.to_type", "guest");
                $q->where("messages.to_id", $item_id);
            })->select("messages.*");

        if ($perpage == -1) {
            return $query->orderBy("messages.created_at", "ASC")->get();
        } else {
            return $query->orderBy("messages.created_at", "DESC")->paginate($perpage);
        }
    }
    public function userGetMessage($item_id, $perpage = 30)
    {
        return Message::with("media")
            ->where(function ($q) use ($item_id) {
                $q->where("messages.from_type", "user");
                $q->where("messages.from_id", $item_id);
                $q->where("messages.to_type", "user");
                $q->where("messages.to_id", user()->id);
            })
            ->orWhere(function ($q) use ($item_id) {
                $q->where("messages.to_type", "user");
                $q->where("messages.to_id", $item_id);
                $q->where("messages.from_type", "user");
                $q->where("messages.from_id", user()->id);
            })
            ->orderBy("messages.created_at", "DESC")
            ->select("messages.*")
            ->paginate($perpage);
    }
    public function teamGetMessage($item_id, $perpage = 30)
    {
        return Message::with("media")
            ->where(function ($q) use ($item_id) {
                $q->where("messages.from_type", "team");
                $q->where("messages.from_id", $item_id);
            })
            ->orWhere(function ($q) use ($item_id) {
                $q->where("messages.to_type", "team");
                $q->where("messages.to_id", $item_id);
            })
            ->orderBy("messages.created_at", "DESC")
            ->select("messages.*")
            ->paginate($perpage);
    }
    public function readMessage($type, $id)
    {
        return MessageCount::where("user_id", user()->id)
            ->where("from_id", $id)
            ->where("from_type", $type)
            ->delete();
    }
}
