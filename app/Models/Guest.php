<?php

namespace App\Models;

use App\Jobs\SendEmailJob;
use App\Mail\TranscriptMail;
use App\Traits\UseUuid;

class Guest extends BaseModel
{
    use UseUuid;

    protected $table = "guests";

    protected $guarded = [];

    protected $hidden = ['media'];

    protected $casts = [
        'data'  =>  'array'
    ];

    /**
     * @var mixed
     */


    public function createSessionRule()
    {
        $rule['service'] = 'required|exists:live_chat_services,id';
        $rule['email'] = 'required|email|max:191';
        $rule['name'] = 'required|max:191';
        $rule['session'] = 'required|unique:guests,id';
        $rule['timezone'] = 'required';

        return $rule;
    }
    public function createSession($request)
    {
        $guest = $this;
        $guest->name = $request->name;
        $guest->email = $request->email;
        $guest->id = $request->session;
        $guest->device = getOS();
        $guest->ip = $request->ip();
        $guest->timezone = $request->timezone;
        $guest->service_id = $request->service;
        $service = LiveChatService::find($request->service);
        if ($service->slug == 'never-received-verificaton-email') {
            $guest->data = [
                'date'  =>  $request->date,
                'time'  =>  $request->time,
                'type'  =>  $request->type,
                'order' =>  $request->order,
            ];
        }
        $guest->save();

        return $guest;
    }

    public function service()
    {
        return $this->belongsTo(LiveChatService::class, "service_id");
    }
    public function myUnreadFromGuests()
    {
        return $this->hasOne(MessageCount::class, "from_id")
            ->where("from_type", "guest")
            ->where("user_id", user()->id);
    }

    public function getLastMessage()
    {
        $id = $this->id;

        return Message::where(function ($query) use ($id) {
            $query->where("from_id", $id);
            $query->where("from_type", "guest");
        })
            ->orWhere(function ($query) use ($id) {
                $query->where("to_id", $id);
                $query->where("to_type", "guest");
            })->latest()->first();
    }

    public function supports()
    {
        return $this->belongsToMany(User::class, "guest_supports", "guest_id", "user_id")
            ->withPivot("status");
    }
    public function endChat()
    {
        $guest = $this;
        $guest->status = 0;
        $guest->save();

        return $guest;
    }
    public function transferEmail()
    {
        $msg = new Message();
        $messages = $msg->guestGetMessage($this->id, -1);

        $data2['body'] = $messages;
        $data2['guest'] = $this;
        $data2['email'] = $this->email;
        $data2['subject'] = "Support Chat Transcript";
        $data2['fromName'] = "Bizinabox";
        $data2['fromMail'] = "info@bizinabox.com";

        $object['email'] = $this->email;
        $object['object'] = new TranscriptMail($data2);
        dispatch(new SendEmailJob($object));

        return true;
    }
}
