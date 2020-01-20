<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogAdsSpot;
use App\Models\BlogPackage;
use App\Models\Lacarte;
use App\Models\Module;
use App\Models\Package;
use App\Models\Plugin;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Slider;
use Illuminate\Http\Request;
use Validator;

class SliderController extends AdminController
{
    public function __construct(Slider $model)
    {
        $this->model = $model;
        $this->sortModel = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $sliders = $this->model->with('media')
                ->latest()
                ->orderBy('order')
                ->get();

            $selector = "datatable-all";
            $view = view('components.admin.sliderTable', compact('sliders', 'selector'))->render();
            $count['all'] = $sliders->count();

            return response()->json([
                'status' => 1,
                'all' => $view,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'content-management.slider');
    }
    public function product(Request $request)
    {
        try {
            $products = [];
            switch ($request->type) {
                case "package":
                    $products = Package::with('media')->whereStatus(1)->wherePackage(1)->select('id', 'name')->get();

                    break;
                case "readymade":
                    $products = Package::with('media')->whereStatus(1)->wherePackage(0)->select('id', 'name')->get();

                    break;
                case "plugin":
                    $products = Plugin::with('media')->whereStatus(1)->select('id', 'name')->get();

                    break;
                case "lacarte":
                    $products = Lacarte::with('media')->whereStatus(1)->select('id', 'name')->get();

                    break;
                case "module":
                    $products = Module::with('media')->whereStatus(1)->select('id', 'name')->get();

                    break;
                case "service":
                    $products = Service::with('media')->whereStatus(1)->select('id', 'name')->get();

                    break;
                case "portfolio":
                    $products = Portfolio::with('media')->whereStatus(1)->selectRaw('id, title as name')->get();

                    break;
                case "blogPackage":
                    $products = BlogPackage::with('media')->whereStatus(1)->select('id', 'name')->get();

                    break;
                case "blogAds":
                    $products = BlogAdsSpot::with('media')->whereStatus(1)->select('id', 'name')->get();

                    break;
            }
            $result = '<option selected disabled hidden>Select Product Item</option>';
            foreach ($products as $product) {
                if ($request->type === 'blogAds') {
                    $result .= "<option value='{$product->id}' data-image='{$product->getFirstMediaUrl('image')}'>{$product->name}</option>";
                } else {
                    $result .= "<option value='{$product->id}' data-image='{$product->getFirstMediaUrl('thumbnail')}'>{$product->name}</option>";
                }
            }

            return response()->json([
                'status' => 1,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                $this->model->storeRule($request),
                $this->model::CUSTOM_VALIDATION_MESSAGE
            );
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $coupon = $this->model->storeItem($request);

            return response()->json([
                'status' => 1,
                'data' => $coupon,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
