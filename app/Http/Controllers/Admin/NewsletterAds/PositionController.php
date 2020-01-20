<?php

namespace App\Http\Controllers\Admin\NewsletterAds;

use App\Http\Controllers\Admin\AdminController;
use App\Models\NewsletterAdsPosition;
use App\Models\NewsletterAdsType;
use Illuminate\Http\Request;
use Validator;

class PositionController extends AdminController
{
    public function __construct(NewsletterAdsPosition $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $positions = $this->model->with('type')->get();

            $activePositions = $positions->where('status', 1);
            $inactivePositions = $positions->where('status', 0);

            $all = view('components.admin.newsletterAdsPosition', [
                'positions' => $positions,
                'selector' => "datatable-all",
            ])->render();

            $active = view('components.admin.newsletterAdsPosition', [
                'positions' => $activePositions,
                'selector' => "datatable-active",
            ])->render();

            $inactive = view('components.admin.newsletterAdsPosition', [
                'positions' => $inactivePositions,
                'selector' => "datatable-inactive",
            ])->render();

            $count['all'] = $positions->count();
            $count['active'] = $activePositions->count();
            $count['inactive'] = $inactivePositions->count();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'active' => $active,
                'inactive' => $inactive,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir . "newsletterAds.position");
    }

    public function create()
    {
        $types = NewsletterAdsType::where('status', 1)->get();

        return view(self::$viewDir . "newsletterAds.positionCreate", compact('types'));
    }

    public function store(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                $this->model->createRule($request),
                $this->model::CUSTOM_VALIDATION_MESSAGE
            );
            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $position = $this->model->storeItem($request);
            $url = route('admin.newsletterAds.position.edit', $position->id) . "#/price";

            return $this->jsonSuccess($url);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function edit($id)
    {
        $position = $this->model->findOrFail($id);

        if (request()->wantsJson()) {
            $prices = $position->prices;
            $view = view("components.admin.newsletterAdsPrice", compact("prices"))->render();

            return $this->jsonSuccess($view);
        }

        $types = NewsletterAdsType::whereStatus(1)->get();

        return view(self::$viewDir . "newsletterAds.positionEdit", [
            'position' => $position,
            'types' => $types,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->createRule($request), $this->model::CUSTOM_VALIDATION_MESSAGE);
            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $position = $this->model->findOrFail($id)->storeItem($request);
            $url = route('admin.newsletterAds.position.edit', $position->id) . "#/price";

            return $this->jsonSuccess($url);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function createPrice(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->createPriceRule($request));
            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $price = $this->model->findOrFail($id)->createPrice($request);

            return $this->jsonSuccess($price);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function deletePrice(Request $request, $id)
    {
        try {
            $price = $this->model->find($id)
                ->prices()
                ->where("id", $request->id)
                ->firstorfail();

            $price->delete();

            return $this->jsonSuccess($id);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function updateListing(Request $request, $id)
    {
        try {
            $position = $this->model->findorfail($id);
            $validation = Validator::make($request->all(), $position->updateListingRule($request));
            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $position->updateListing($request);

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
