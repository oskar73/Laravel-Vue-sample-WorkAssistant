<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PortfolioController extends AdminController
{


    /**
     * PortfolioController constructor.
     */
    public function __construct(Portfolio $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            return $this->model->getUserDataTable();
        }

        return view('user.portfolio.index');
    }

    public function create()
    {

        $categories = PortfolioCategory::where('parent_id', '==', 0)
            ->select('id', 'name')
            ->with('approvedSubCategories')
            ->status(1)
            ->get();

        return view('user.portfolio.create', compact('categories'));
    }

    public function edit(Request $request, $slug)
    {
        $portfolio = auth()->user()->portfolio->where('slug', $slug)->firstOrFail();

        $categories = PortfolioCategory::where('parent_id', '==', 0)
            ->select('id', 'name')
            ->with('approvedSubCategories')
            ->status(1)
            ->get();

        return view('user.portfolio.edit', compact('categories', 'portfolio'));
    }

    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }
            if ($request->id) {
                $item = $this->model::find($request->id);
                $item->updateItem($request);
            } else {
                $item = $this->model->storeItem($request);
                $item->created_by = auth()->user()->id;
                $item->approved_at = null;
                $item->save();
            }


            return $this->jsonSuccess(['item' => $item, 'redirect_url' => route('user.portfolio.index')]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
