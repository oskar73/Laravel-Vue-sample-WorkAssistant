<?php

namespace App\Http\Controllers\Front;

use \Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\AvailableWeekday;
use App\Models\Guest;
use App\Models\LiveChatRequest;
use App\Models\LiveChatService;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class LiveChatController extends Controller
{
    public function index()
    {
        return view('frontend.livechat');
    }
    public function end(Request $request)
    {
        try {
            $alreadyFinished = 1;

            if (Session::has("chat-session")) {
                $session = Session::get("chat-session");

                $guest = Guest::findorfail($session->id);

                if ($guest->status == 1) {
                    $guest->endChat();
                    $alreadyFinished = 0;
                } else {
                    Session::forget("chat-session");
                }
                if ($request->transfer == 1) {
                    $guest->transferEmail();
                }
            }

            return response()->json([
                'status' => 1,
                'data' => $alreadyFinished,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function getSession(Request $request)
    {
        if (Session::has("chat-session")) {
            $session = Session::get("chat-session");
            $guest = Guest::where("status", 1)
                ->whereId($session->id)
                ->first();

            if ($guest != null) {
                $msg = new Message();
                $messages = $msg->guestGetMessage($guest->id, $request->perpage);
                $data['session'] = 1;
                $data['session_id'] = $guest->id;
                $data['name'] = $guest->name;
                $data['messages'] = $messages;

                return response()->json([
                    'status' => 1,
                    'data' => $data,
                ]);
            }
        }
        $data['session'] = 0;
        $data['session_id'] = null;
        $data['messages'] = "<li class=\"support_answer\">
                    <div class=\"message-div\">Thanks! Please select the best option so we can further assist you.</div>
                </li>
                <li class=\"support_answer\">
                    <div class=\"message-div letsgo\" style=\"border-color: red;color: red;\" title=\"Let's Go!\">Start Chat</div>
                </li>";

        return response()->json([
            'status' => 1,
            'data' => $data,
        ]);
    }
    public function getService()
    {
        $weekday = new AvailableWeekday();
        $available = $weekday->isNowAvailable("livechat", null);

        $setting = new LiveChatService();
        $data = $setting->getService($available);

        return response()->json([
            'status' => 1,
            'data' => $data,
        ]);
    }
    public function submitService(Request $request)
    {
        try {
            $service_request = new LiveChatRequest();

            $validation = Validator::make($request->all(), $service_request->rule());
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $service_request->storeItem($request);
            $thankyou = "<div class='thankyou_page'>Thank you for your submission. <br><span class='btn btn-success reinit_btn mt-3'>Reinitialize Chat</span></div>";

            return response()->json([
                'status' => 1,
                'data' => $thankyou,
            ]);
        } catch (\Exception $e) {
            logger($e);

            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function createSession(Request $request)
    {
        try {
            $model = new Guest();
            $validation = Validator::make($request->all(), $model->createSessionRule());

            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $guest = $model->createSession($request);
            if (Session::has("chat-session")) {
                $old_session = Session::get("chat-session");
                $old_guest = $model->find($old_session->id);
                $data['type'] = 'alert';
                $data['file_path'] = '';
                $data['from_type'] = 'guest';
                $data['from_id'] = $old_guest->id;
                $data['from_name'] = $old_guest->name;
                $data['message'] = "Chat ended by ". $old_guest->name . " opening another chat.";
                $data['datetime'] = Carbon::now()->toDateTimeString();

                $this->pushMessage($data);
                $old_guest->endChat();

                $data2['emit'] = "guest-end";
                $data2['id'] = $old_guest->id;

                $this->redisPublish('guest-' .$old_guest->id, json_encode($data2));
            }
            Session::put("chat-session", $guest);
            $user_ids = [1];
            $guest->supports()->attach($user_ids);

            $service_name = $guest->service->name ?? '';
            $this->pushList($user_ids, $service_name);

            $data['service'] = $service_name;
            $data['name'] = $request->name;
            if ($service_name == 'Never received verificaton email') {
                if ($request->type === 'paypal') {
                    $data['data'] = [
                        'Name'  =>  $request->name,
                        'Email' =>  $request->email,
                        'Order Number' =>  $request->order,
                        'Date'  =>  $request->date,
                        'Time'  =>  $request->time,
                    ];
                } else {
                    $data['data'] = [
                        'Name'  =>  $request->name,
                        'Email' =>  $request->email,
                        'Last 4 digits' =>  $request->order,
                        'Date'  =>  $request->date,
                        'Time'  =>  $request->time,
                    ];
                }
            }

            return response()->json([
                'status' => 1,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function sessionClear()
    {
        Session::forget("chat-session");
    }
    public function pushList($user_ids, $service_name)
    {
        $guest = Session::get("chat-session");

        $lastMessage = (object) ['datetime' => Carbon::now()->toDateTimeString(), 'message' => $service_name];

        $guest->last_msg = $lastMessage;
        $guest->emit = "pushList";

        foreach ($user_ids as $u_id) {
            $this->redisPublish('user-' .$u_id, $guest);
        }
    }
    public function sendMessage(Request $request)
    {
        try {
            $data = $request->msgObj;

            $this->pushMessage($data);

            return response()->json([
                'status' => 1,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function pushMessage($data)
    {
        $guest = Session::get("chat-session");

        if ($data['type'] === 'text' || $data['type'] === 'alert') {
            $data['emit'] = "getMessage";
            $data['to_id'] = $guest->id;
            $data['to_type'] = "guest";
            $this->redisPublish('guest-' . $guest->id, json_encode($data));
        }

        $msgObj = new Message();
        $message = $msgObj->storeGuestMsg(json_encode($data), $guest);

        if ($data['type'] !== 'text' && $data['type'] !== 'alert') {
            $message->emit = "getMessage";
            $message->to_id = $guest->id;
            $message->to_type = "guest";
            $this->redisPublish('guest-' . $guest->id, $message);
        }

        return $message;
    }
    public function redisPublish($channel, $data)
    {
        $redis = Redis::connection('socket');
        $redis->publish($channel, $data);
    }
}
