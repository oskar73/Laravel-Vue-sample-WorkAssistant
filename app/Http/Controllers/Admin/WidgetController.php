<?php

namespace App\Http\Controllers\Admin;

use App\Models\WidgetCategory;
use App\Models\Widget;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;

class WidgetController extends AdminController
{
    public function index()
    {
        $items = WidgetCategory::where('status', 1)->orderBy('order')->with('items')->get();
        return view(self::$viewDir.'widget.index', [
            'items' =>  $items
        ]);
    }

    public function create() {
        return view(self::$viewDir.'widget.create');
    }

    public function store(Request $request) {
        try {
            $validation = Validator::make($request->all(), [
                'title' => [
                    'required',
                    Rule::unique('widget_categories', 'title')->ignore($request->id),
                ],
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }

            if ($request->id) {
                $widget = WidgetCategory::find($request->id);
            } else {
                $widget = new WidgetCategory();
            }
            $widget->title = $request->title;
            $widget->description = $request->description;
            $widget->link = $request->link;
            $widget->status = !empty($request->status);
            $widget->save();

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function sort(Request $request) {
        try {
            $sorts = $request->sorts;
            foreach($sorts as $key => $id) {
                try {
                    $widget = WidgetCategory::findorfail($id);
                    $widget->order = $key;
                    $widget->save();
                } catch (\Exception $e) {}
            }

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function storeItem(Request $request) {
        try {
            $validation = Validator::make($request->all(), [
                'title' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {
                        $query = Widget::where('title', $value)
                                        ->where('category_id', $request->category);
                        if ($request->has('id')) {
                            $query->where('id', '!=', $request->id);
                        }

                        $widgetExists = $query->exists();

                        if ($widgetExists) {
                            $fail('The ' . $attribute . ' has already been taken for this widget.');
                        }
                    },
                ],
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
    
            if ($request->id) {
                $widget = Widget::findorfail($request->id);
            } else {
                $widget = new Widget();
            }
            $widget->category_id = $request->category;
            $widget->title = $request->title;
            $widget->url = $request->url;
            $widget->save();

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function sortItems(Request $request) {
        try {
            $category_id = $request->widget;
            $sorts = $request->sorts;
            foreach($sorts as $key => $id) {
                $widget = Widget::find($id);
                if ($widget->category_id == $category_id) {
                    $widget->order = $key;
                    $widget->save();
                }
            }

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function delete($id) {
        try {
            WidgetCategory::find($id)->delete();

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function deleteItem($id) {
        try {
            Widget::find($id)->delete();

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}