<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvailableWeekday;
use App\Models\Builder\TemplatePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public static string $viewDir = 'admin.';
    public object $sortModel;
    public object $model;

    public function getSort()
    {
        try {
            $items = $this->sortModel->select('id', 'name')->orderBy('order')->get();
            $view = '';
            foreach ($items as $item) {
                $view .= '<li data-id="' . $item->id . '">' . $item->name . '</li>';
            }

            return response()->json(compact('view'));
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function updateSort(Request $request)
    {
        try {
            $sorts = $request->get('sorts');
            foreach ($sorts as $key => $sort) {
                $item = $this->model->find($sort);
                $item->order = $key + 1;
                $item->save();
            }

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function switch(Request $request)
    {
        try {
            $action = $request->action;
            $items = $this->model->whereIn('id', $request->ids)->get();

            if ($action === 'active') {
                $items->each->update(['status' => 1]);
            } elseif ($action === 'inactive') {
                $items->each->update(['status' => 0]);
            } elseif ($action === 'featured') {
                $items->each->update(['featured' => 1]);
            } elseif ($action === 'unfeatured') {
                $items->each->update(['featured' => 0]);
            } elseif ($action === 'new') {
                $items->each->update(['new' => 1]);
            } elseif ($action === 'undonew') {
                $items->each->update(['new' => 0]);
            } elseif ($action === 'delete') {
                foreach ($items as $item) {
                    $item->delete();
                }
            }

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function createPrice(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->priceRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $item = $this->model->find($id);
            $item->savePrice($request);

            return $this->jsonSuccess($item->getPriceRedirectUrl());
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
            if ($price->recurrent == 1) {
                $this->model->deletePlan($price->plan_id);
            }
            $price->delete();

            return $this->jsonSuccess($id);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function updateMeetingForm(Request $request, $id)
    {
        try {
            $rule = array_merge(
                $this->model->updateFormRule($request),
                $this->model->updateMeetingRule($request)
            );

            $weekday = new AvailableWeekday();
            if ($request->meeting) {
                $weekdayRule = $weekday->storeRule($request);
                $rule = array_merge($rule, $weekdayRule);
                $error = $weekday->checkRule($request);
                if (count($error) != 0) {
                    return response()->json([
                        'status' => 0,
                        'data' => $error,
                    ]);
                }
            }
            $customMsg = array_merge(
                $this->model->getUpdateFormValidatoinCustomMessgae(),
                $this->model->getUpdateMeetingValidationMessage()
            );

            $validation = Validator::make($request->all(), $rule, $customMsg);

            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }

            $item = $this->model->find($id);
            $item->updateForm($request)
                ->updateMeeting($request);
            if ($item->step == 1) {
                $item->status = 1;
                $item->step = 2;
                $item->save();
            }
            if ($request->meeting) {
                $req = $request;
            } else {
                $req = null;
            }

            $weekday->storeItem($req, get_class($item), $item->id);

            return $this->jsonSuccess([
                'next' => '#/meeting',
                'item' => $item,
            ]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
