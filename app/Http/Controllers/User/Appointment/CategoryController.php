<?php

namespace App\Http\Controllers\User\Appointment;

use App\Http\Controllers\User\UserController;
use App\Models\WebsiteAvailableWeekday;
use App\Models\WebsiteUserAppointmentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends UserController
{
    public $weekday;

    public function __construct(WebsiteUserAppointmentCategory $model)
    {
        $this->model = $model;
        $this->sortModel = $this->model;
        $this->weekday = new WebsiteAvailableWeekday();
    }
    public function index()
    {
        if (request()->wantsJson()) {
            try {
                $categories = $this->model->ofWebsite()->with(['web' => function ($query) {
                    $query->select('id', 'name', 'domain');
                }])->orderBy("order")->get();
                $selector = "all";
                $data = view('components.user.appointmentCategory', compact("categories", "selector"))->render();

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

        return view(self::$viewDir.'appointment.own.category');
    }
    public function create()
    {
        $weekArray = $this->weekday::weekArray;
        $userWebsites = user()->websites()->select('id', 'domain')->get();

        return view(self::$viewDir.'appointment.own.categoryCreate', compact("weekArray", 'userWebsites'));
    }
    public function store(Request $request)
    {
        try {
            $rule1 = $this->model->storeRule($request);
            $rule2 = $this->weekday->storeRule($request);

            $validation = Validator::make($request->all(), array_merge($rule1, $rule2), $this->model::CUSTOM_VALIDATION_MESSAGE);
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                    'yoo' => 1,
                ]);
            }
            $error = $this->weekday->checkRule($request);

            if (count($error) != 0) {
                return response()->json([
                    'status' => 0,
                    'data' => $error,
                    'yoo' => 2,
                ]);
            }

            if ($request->category_id) {
                $item = $this->model->findorfail($request->category_id)
                    ->storeItem($request);
            } else {
                $item = $this->model->storeItem($request);
            }
            $this->weekday->storeItem($request, 'category', $item->id);

            return response()->json([
                'status' => 1,
                'data' => 1,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
                'yoo' => 3,
            ]);
        }
    }
    public function edit($id)
    {
        $weekArray = $this->weekday::weekArray;
        $category = $this->model->findorfail($id);
        $data = $this->weekday->getData('category', $id);
        $userWebsites = user()->websites()->select('id', 'domain')->get();

        return view(self::$viewDir.'appointment.own.categoryEdit', compact("weekArray", "data", "category", 'userWebsites'));
    }

    public function categoryForBuilder(Request $request)
    {
        try {
            $categories = $this->model::select('id as value', 'name as label')
                                        ->my()->orderBy("order")
                                        ->where("status", 1)->get();

            foreach ($categories as $category) {
                $category->weekdays = WebsiteAvailableWeekday::with('hours:id,start,end,weekday_id')
                                    ->select('id', 'weekday')
                                    ->where("type", WebsiteUserAppointmentCategory::class)
                                    ->where('product_id', $category->value)
                                    ->get();
            }

            return response()->json([
                'status' => 1,
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
