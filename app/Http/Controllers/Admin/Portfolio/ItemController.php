<?php

namespace App\Http\Controllers\Admin\Portfolio;

use App\Http\Controllers\Admin\AdminController;
use App\Models\NotificationTemplate;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use Illuminate\Http\Request;
use Validator;

class ItemController extends AdminController
{
    public function __construct(Portfolio $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $items = $this->model->select(['id', 'title', 'status', 'featured', 'new', 'approved_at', 'created_at', 'category_id'])
                ->with(['media', 'category.category'])
                ->latest();

            if (request()->user) {
                $items = $items->where('created_by', request()->user);
            }
            $items = $items->get();

            $activeItems = $items->where('status', 1);
            $inactiveItems = $items->where('status', 0);
            $pendingItems = $items->whereNull('approved_at');


            $all = view('components.admin.portfolioItem', [
                'items' => $items,
                'selector' => "datatable-all",
            ])->render();

            $active = view('components.admin.portfolioItem', [
                'items' => $activeItems,
                'selector' => "datatable-active",
            ])->render();

            $inactive = view('components.admin.portfolioItem', [
                'items' => $inactiveItems,
                'selector' => "datatable-inactive",
            ])->render();

            $pending = view('components.admin.portfolioItem', [
                'items' => $pendingItems,
                'selector' => "datatable-pending",
            ])->render();

            $count['all'] = $items->count();
            $count['active'] = $activeItems->count();
            $count['inactive'] = $inactiveItems->count();
            $count['pending'] = $pendingItems->count();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'active' => $active,
                'inactive' => $inactive,
                'pending' => $pending,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'portfolio.item');
    }
    public function create()
    {
        $categories = PortfolioCategory::where('parent_id', '==', 0)
            ->select('id', 'name')
            ->with('approvedSubCategories')
            ->status(1)
            ->get();

        return view(self::$viewDir.'portfolio.itemCreate', compact('categories'));
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $item = $this->model->storeItem($request);
            $item->created_by = 'admin';
            $item->save();

            return $this->jsonSuccess($item);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function preview($id)
    {
        $item = $this->model->with('media')
            ->where('id', $id)
            ->firstorfail();

        $categories = PortfolioCategory::where('parent_id', '==', 0)
            ->select('id', 'name')
            ->with('approvedSubCategories')
            ->status(1)
            ->get();

        return view(self::$viewDir . "portfolio.itemPreview", compact('item', 'categories'));
    }
    public function edit($id)
    {
        $item = $this->model->with('media')
            ->where('id', $id)
            ->firstorfail();

        $categories = PortfolioCategory::where('parent_id', '==', 0)
            ->select('id', 'name')
            ->with('approvedSubCategories')
            ->status(1)
            ->get();

        return view(self::$viewDir . "portfolio.itemEdit", compact('item', 'categories'));
    }
    public function update(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $item = $this->model->find($id)->updateItem($request);

            return $this->jsonSuccess($item);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function approve($id)
    {
        try {
            $item = $this->model->find($id);
            $item->approved_at = date('Y-m-d h:i:s');
            $item->save();
            $notification = new NotificationTemplate();
            $data = ['url' => route('portfolio.index')];
            $notification->sendEmail($data, NotificationTemplate::PORTFOLIO_APPROVAL, $item->creator->email);
            return back()->with('success', 'Portfolio Approved!');
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function deny($id)
    {
        try {
            $item = $this->model->find($id);
            $item->status = 0;
            $item->approved_at = null;
            $item->save();
            $notification = new NotificationTemplate();
            $data = ['url' => route('portfolio.index')];
            $notification->sendEmail($data, NotificationTemplate::PORTFOLIO_APPROVAL, $item->creator->email);

            return back()->with('success', 'Portfolio denied!');
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
                $items->each->update(['approved_at' => date('Y-m-d h:i:s')]);
            } elseif ($action === 'inactive') {
                $items->each->update(['status' => 0]);
                $items->each->update(['approved_at' => null]);
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
