<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;

class LiveChatService extends BaseModel
{
    use Sluggable;
    protected $table = 'live_chat_services';

    protected $guarded = ['id', 'created_at', 'updated_at'];

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
    public function storeRule()
    {
        $rule['name'] = 'required|max:45';
        $rule['description'] = 'max:600';

        return $rule;
    }
    public function storeItem($request)
    {
        if ($request->service_id == null) {
            $service = $this;
        } else {
            $service = $this->findorfail($request->service_id);
        }
        $service->name = $request->name;
        $service->description = $request->description;
        $service->status = $request->status?1:0;
        $service->save();

        $service->teams()->sync($request->teams);

        return $service;
    }

    public function getService($available)
    {
        $services = $this->select('id', 'name')
            ->whereStatus(1)
            ->orderBy('order')
            ->get();

        $options = '<option selected disabled hidden>Select Service</option>';
        foreach ($services as $service) {
            $options .= '<option value="' . $service->id . '">' . $service->name . '</option>';
        }
        $honeypot = view('honeypot::honeypotFormFields')->render();

        if ($available) {
            $data['available'] = 1;
            $result = '<li class="support_answer"><div class="choose-message-div"><div class="p-2">Thanks! Select the best option, so we can find you the right Guide.</div>';
            $result .= '</div></li><form id="live_chat_start_form" class="live_chat_start_form">
                            <input type="hidden" name="_token" value="'.csrf_token().'">'.$honeypot.'
                            <select name="service" class="form-control livechat-req-form">' . $options . '</select>
                            <span class="text-danger error-service error-div"></span>
                            <input type="text" name="name" class="form-control livechat-req-form" autocomplete="off" placeholder="Name" required>
                            <span class="text-danger error-name error-div"></span>
                            <input type="email" name="email" class="form-control livechat-req-form" autocomplete="off" placeholder="Email" required>
                            <span class="text-danger error-email error-div"></span>
                            <div id="verification_fields"></div>
                            <input type="submit" value="Start" class="form-control livechat-req-form" id="live_chat_request_form_submit_btn"></form>';
            $data['data'] = $result;
        } else {
            $data['available'] = 0;
            $result = '<li class="support_answer"><div class="choose-message-div"><div class="p-2">Sorry. We are unavailable now. Please fill following form. We will contact to you as soon as possible.</div>';

            $result .= '</div></li><form id="live_chat_request_form" class="live_chat_start_form">
                            <input type="hidden" name="_token" value="'.csrf_token().'">'.$honeypot.'
                            <select name="service" class="form-control livechat-req-form">' . $options . '</select>
                            <span class="text-danger error-service error-div"></span>
                            <input type="text" name="name" class="form-control livechat-req-form" autocomplete="off" placeholder="Name" required>
                            <span class="text-danger error-name error-div"></span>
                            <input type="email" name="email" class="form-control livechat-req-form" autocomplete="off" placeholder="Email" required>
                            <span class="text-danger error-email error-div"></span>
                            <input type="text" name="phone" class="form-control livechat-req-form" autocomplete="off" placeholder="Phone Number (Optional)">
                            <span class="text-danger error-phone error-div"></span>
                            <div id="verification_fields"></div>
                            <textarea name="message" rows="5" class="form-control livechat-req-form" autocomplete="off" placeholder="Message" ></textarea>
                            <span class="text-danger error-message error-div"></span>
                            <input type="submit" value="Submit" class="form-control livechat-req-form" id="live_chat_request_form_submit_btn"></form>';
            $data['data'] = $result;
        }

        return $data;
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'livechat_service_teams', 'service_id', 'team_id');
    }
}
