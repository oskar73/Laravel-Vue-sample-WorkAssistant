<?php

namespace App\Http\Controllers\Admin\LiveChat;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Guest;
use App\Models\Message;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class ChatController extends AdminController
{
    public function index()
    {
        return view(self::$viewDir . "livechat.chat");
    }
    public function chatbox(Request $request)
    {
        if ($request->ajax()) {
            if ($request->tab === 'guest') {
                $item = Guest::with('myUnreadFromGuests')
                    ->orderBy("status", "DESC")
                    ->latest()
                    ->paginate($request->perPage);

                foreach ($item as $i) {
                    $i->unread_count = $i->myUnreadFromGuests->count ?? 0;
                    $i->last_msg = $i->getLastMessage();
                }
            } elseif ($request->tab === 'user') {
                $item = User::with("media", "myUnreadFromUsers")
                    ->role(['admin', 'employee', 'client'])
                    ->where("status", 'active')
                    ->where("id", "!=", user()->id)
                    ->paginate($request->perPage);

                foreach ($item as $i) {
                    $i->unread_count = $i->myUnreadFromUsers->count ?? 0;
                    $i->last_msg = $i->getLastMessage();
                }
            } elseif ($request->tab === 'team') {
                $item = Team::with("media", "myUnreadFromTeams")
                    ->where("status", 1)
                    ->paginate($request->perPage);

                foreach ($item as $i) {
                    $i->unread_count = $i->myUnreadFromTeams->count ?? 0;
                    $i->last_msg = $i->getLastMessage();
                }
            }

            return response()->json([
                'status' => 1,
                'data' => $item,
            ]);
        }

        return view(self::$viewDir . "livechat.getFrame");
    }
    public function getContent(Request $request)
    {
        try {
            $tab = $request->tab;
            $item = $request->item;
            $perPage = $request->perpage;
            $msg = new Message();
            $messages = [];
            if ($tab === 'guest') {
                $messages = $msg->guestGetMessage($item, $perPage);
            } elseif ($tab === 'user') {
                $messages = $msg->userGetMessage($item, $perPage);
            } elseif ($tab === 'team') {
                $messages = $msg->teamGetMessage($item, $perPage);
            }

            return response()->json([
                'status' => 1,
                'data' => $messages,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function readMessage(Request $request)
    {
        $tab = $request->tab;
        $item = $request->item;
        $msg = new Message();
        $msg->readMessage($tab, $item);

        return response()->json([
            'status' => 1,
            'data' => 1,
        ]);
    }
    public function updateUnreads(Request $request)
    {
        try {
            $tab = $request->tab;
            $item = $request->item;

            if ($request->tab === 'user') {
                $channel = "user-" . user()->id;
            } else {
                $channel = $tab . "-" . $item;
            }
            $data['emit'] = "updateUnreads-" . user()->id;
            $data['tab'] = $tab;
            $data['item'] = $item;
            $data['count'] = user()->getUnreads($tab, $item);

            $this->redisPublish($channel, json_encode($data));

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
    public function sendMessage(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
               'tab' => 'required|in:guest,user,team',
                'item' => 'required',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $msg = $this->pushMessage($request);

            return response()->json([
                'status' => 1,
                'data' => $msg,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function pushMessage($request)
    {
        $data = $request->msgObj;
        $channels = [];
        if ($request->tab == 'guest') {
            $channels[] = "guest-" . $request->item;
        } elseif ($request->tab == 'user') {
            $channels[] = "user-" . $request->item;
            $channels[] = "user-" . user()->id;
        } elseif ($request->tab == 'team') {
            $channels[] = "team-" . $request->item;
        }
        if ($data['type'] === 'text') {
            $data['emit'] = "getMessage";
            $data['to_id'] = $request->item;
            $data['to_type'] = $request->tab;

            foreach ($channels as $channel) {
                $this->redisPublish($channel, json_encode($data));
            }
        }
        $msgObj = new Message();
        $message = $msgObj->storeUserMsg(json_encode($data), $request->tab, $request->item);

        if ($data['type'] !== 'text') {
            $message->emit = "getMessage";
            $message->to_id = $request->item;
            $message->to_type = $request->tab;
            foreach ($channels as $channel) {
                $this->redisPublish($channel, $message);
            }
        }

        return $message;
    }
    public function endGuestChat(Request $request)
    {
        try {
            $guest = Guest::where("id", $request->item)
                ->firstorfail();
            $guest->endChat();

            return response()->json([
                'status' => 1,
                'data' => 1,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function transcriptChat(Request $request)
    {
        try {
            $guest = Guest::where("id", $request->item)
                ->firstorfail();

            $guest->transferEmail();

            return response()->json([
                'status' => 1,
                'data' => 1,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function getDetail(Request $request)
    {
        try {
            $data = "<div>This is from server.</div>";

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
    public function redisPublish($channel, $data)
    {
        $redis = Redis::connection('socket');
        $redis->publish($channel, $data);
    }
}
