<?php

namespace App\Http\Controllers\Admin\LiveChat;

use App\Http\Controllers\Admin\AdminController;
use App\Models\LiveChatService;
use App\Models\Team;
use Illuminate\Http\Request;
use Validator;

class ServiceController extends AdminController
{
    public function __construct(LiveChatService $model)
    {
        $this->model = $model;
        $this->sortModel = $this->model;
    }
    public function index()
    {
        if (request()->wantsJson()) {
            try {
                $services = $this->model->with('teams.media')
                    ->orderBy("order")
                    ->get();
                $selector = "all";
                $data = view('components.admin.liveChatService', compact("services", "selector"))->render();

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
        $teams = Team::with("activeSubTeams")
            ->isParent()
            ->where("status", 1)
            ->get();

        return view(self::$viewDir . "livechat.service", compact("teams"));
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule(), $this->model::CUSTOM_VALIDATION_MESSAGE);
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $service = $this->model->storeItem($request);

            return response()->json([
                'status' => 1,
                'data' => $service,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
