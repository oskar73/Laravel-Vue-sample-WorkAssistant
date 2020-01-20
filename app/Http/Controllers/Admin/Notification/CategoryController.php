<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Http\Controllers\Admin\AdminController;
use App\Models\NotificationCategory;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends AdminController
{
    public function __construct(NotificationCategory $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $categories = $this->model->latest()->get();

            $all = view('components.admin.notificationCategory', [
                'categories' => $categories,
                'selector' => "datatable-all",
            ])->render();
            $count['all'] = $categories->count();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'count' => $count,
            ]);
        }
        //        $templates = NotificationTemplate::get();
        //        foreach($templates as $template)
        //        {
        //            $body = $template->body;
        //            $body = str_replace('http://bizinabox.localhost', 'https://bizinabox.site', $body);
        //            $body = str_replace('https://supervisoroncall.com/logo.png', 'https://bizinabox.site/assets/img/logo.png', $body);
        //
        //            $template->body =$body;
        //            $template->save();
        //        }
        return view(self::$viewDir.'notification.category');
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
