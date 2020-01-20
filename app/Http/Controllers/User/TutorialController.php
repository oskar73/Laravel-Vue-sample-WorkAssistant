<?php

namespace App\Http\Controllers\User;

use App\Models\Module;
use App\Models\Tutorial;
use Illuminate\Http\Request;

class TutorialController extends UserController
{
    public function __construct(Tutorial $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $modules = Module::select(['id', 'name', 'slug'])
            ->status(1)
            ->orderBy('name')
            ->get();

        return view(self::$viewDir . "tutorial.index", compact("modules"));
    }
    public function getData(Request $request)
    {
        $module = $request->module;
        $tutorial = $request->tutorial;

        if ($tutorial == null) {
            if ($module == 'basic') {
                $tutorials = $this->model->where('status', 1)
                    ->with("media")
                    ->where('public', 1)
                    ->orderBy("order")
                    ->get();
            } else {
                $module_id = Module::where("slug", $module)
                    ->where("status", 1)
                    ->firstorfail();

                $tutorials = $module_id->tutorials()->where('status', 1)
                    ->with("media")
                    ->where('public', 0)
                    ->orderBy("order")
                    ->get();
            }
            $data = view("components.user.tutorial", compact("tutorials"))->render();
        } else {
            $item = $this->model->with('media')
                ->where('slug', $tutorial)
                ->where("status", 1)
                ->firstorfail();

            $data = view("components.user.tutorialDetail", compact("item"))->render();
        }

        return response()->json([
            'status' => 1,
            'data' => $data,
        ]);
    }
}
