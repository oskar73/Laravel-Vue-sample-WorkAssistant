<?php

namespace App\Http\Controllers\Admin;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Validator;

class AnnouncementController extends AdminController
{
    public function __construct(Announcement $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $announcements = $this->model->where("user_id", 0)->orderBy('id', 'desc')->get();

            $activeAnnouncements = $announcements->where('status', 1);
            $inactiveAnnouncements = $announcements->where('status', 0);

            $all = view('components.admin.announcement', [
                'announcements' => $announcements,
                'selector' => "datatable-all",
            ])->render();
            $active = view('components.admin.announcement', [
                'announcements' => $activeAnnouncements,
                'selector' => "datatable-active",
            ])->render();
            $inactive = view('components.admin.announcement', [
                'announcements' => $inactiveAnnouncements,
                'selector' => "datatable-inactive",
            ])->render();
            $count['all'] = $announcements->count();
            $count['active'] = $activeAnnouncements->count();
            $count['inactive'] = $inactiveAnnouncements->count();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'active' => $active,
                'inactive' => $inactive,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'announcement');
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                $this->model->storeRule(),
                $this->model::CUSTOM_VALIDATION_MESSAGE
            );
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $category = $this->model->storeItem($request);

            return response()->json(['status' => 1, 'data' => $category]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
