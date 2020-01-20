<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public $model;

    public function __construct()
    {
        $this->model = new Portfolio();

        if ($seo = option("portfolio.front.seo", null)) {
            view()->share('seo', $seo);
        }
    }

    public function index(Request $request)
    {
        return view('frontend.portfolio.index');
    }

    public function categories(Request $request)
    {
        if ($request->ajax()) {
            $result = $this->model->filterItem($request);

            return response()->json($result);
        }

        $categories = PortfolioCategory::select('id', 'slug', 'name', 'parent_id')
            ->with('approvedSubCategories')
            ->isParent()
            ->status(1)
            ->orderBy('order')
            ->get();

        $category = $categories[0];

        $items = Portfolio::with("media", "categories")
            ->orderBy("order", "DESC")
            ->latest()
            ->paginate(24);



        return view('frontend.portfolio.category', compact('categories', 'items', 'category'));
    }

    public function category($slug)
    {
        $category = PortfolioCategory::where("slug", $slug)->firstorfail();

        $items = $category->items;

        return view("frontend.portfolio.category", compact("category", "items"));
    }

    public function detail($slug)
    {
        $item = $this->model->where('slug', $slug)
            ->with('media')
            ->status(1)
            ->firstorfail();

        return view('frontend.portfolio.detail', compact('item'));
    }
}
