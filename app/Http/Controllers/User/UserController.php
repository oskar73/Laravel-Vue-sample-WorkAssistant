<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Builder\TemplatePage;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public static $viewDir = 'user.';
    public object $sortModel;
    public $model;

    public function getSort()
    {
        try {
            $items = $this->sortModel->select('id', 'name')->orderBy('order')->get();
            $view = '';
            foreach ($items as $item) {
                $view .= '<li data-id="'.$item->id.'">'.$item->name.'</li>';
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
                    $pages = TemplatePage::where('template_id', $item->id)->get();
                    foreach ($pages as $page) {
                        $page->delete();
                    }
                    $item->delete();
                }
            }

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
