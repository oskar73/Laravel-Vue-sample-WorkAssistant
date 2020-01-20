<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Integration\Cart;
use App\Models\DirectoryCategory;
use App\Models\DirectoryListing;
use App\Models\DirectoryPackage;
use App\Models\DirectoryTag;
use Illuminate\Http\Request;
use Session;
use Validator;

class DirectoryController extends Controller
{
    public $model;

    public function __construct()
    {
        $this->model = new DirectoryListing();

        if ($seo = option("directory.front.seo", null)) {
            view()->share('seo', $seo);
        }
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $listings = $this->model->filterItem($request);

            $data = view("components.front.directoryListing", compact("listings"))->render();

            return response()->json([
                'status' => 1,
                'data' => $data,
            ]);
        }

        $categories = DirectoryCategory::where('parent_id',  0)
            ->with("media")
            ->withCount('approvedItems')
            ->status(1)
            ->orderBy("order")
            ->get(['id', 'name']);

        return view('frontend.directory.index', [
            'categories' => $categories,
        ]);
    }

    public function categories(Request $request)
    {
        if ($request->ajax()) {
            $result = $this->model->filterItem($request);

            return response()->json($result);
        }

        $categories = DirectoryCategory::where('parent_id', 0)
            ->with("media")
            ->withCount('approvedItems')
            ->status(1)
            ->orderBy("order")
            ->get(['id', 'name']);

        if (count($categories)) {
            $category = $categories[0];
        } else {
            $category = null;
        }

        $items = DirectoryListing::whereNotNull('approved_at')
            ->where('status', 'approved')
            ->with("media", "categories")
            ->orderBy("order", "DESC")
            ->latest()
            ->paginate(24);

        return view('frontend.directory.category', compact('category', 'categories', 'items'));
    }

    public function category($slug)
    {
        $category = DirectoryCategory::where("slug", $slug)
            ->where("parent_id", 0)
            ->where("status", 1)
            ->firstorfail();

        $items = $category->items;

        return view('frontend.directory.category', compact("category", "items"));
    }
    public function subCategory($cat_slug, $subCat_slug)
    {
        $parentCategory = DirectoryCategory::where("slug", $cat_slug)
            ->where("parent_id", 0)
            ->where("status", 1)
            ->firstorfail();

        $category = DirectoryCategory::where("parent_id", $parentCategory->id)
            ->where("slug", $subCat_slug)
            ->where("status", 1)
            ->firstorfail();


        return view('frontend.directory.subCategory', compact("category", "parentCategory"));
    }
    public function tag($slug)
    {
        $tag = DirectoryTag::where("slug", $slug)
            ->where("status", 1)
            ->firstorfail();

        return view('frontend.directory.tag', compact("tag"));
    }
    public function detail($slug)
    {
        $listing = $this->model->where("slug", $slug)
            ->frontVisible()
            ->firstOrFail();

        $listing->increment("view_count");


        return view('frontend.directory.detail', compact("listing"));
    }

    public function package(Request $request)
    {
        if ($request->ajax()) {
            $result = DirectoryPackage::filterItem($request);

            return response()->json($result);
        }

        return view('frontend.directory.package');
    }
    public function packageDetail($slug)
    {
        $item = DirectoryPackage::where('slug', $slug)
            ->with('media', 'prices')
            ->status(1)
            ->firstorfail();


        return view('frontend.directory.packageDetail', compact('item'));
    }

    public function cartRule()
    {
        $rule['quantity'] = 'required|numeric|min:1';
        $rule['price'] = 'required';

        return $rule;
    }
    public function addtoCart(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->cartRule());
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $item = DirectoryPackage::whereId($id)
                ->whereStatus(1)
                ->firstorfail();

            if ($request->price == 0) {
                $price = $item->standardPrice;
            } else {
                $price = $item->prices()
                    ->whereStatus(1)
                    ->whereId($request->price)
                    ->firstorfail();
            }
            $oldCart = Session::get("cart");
            $cart = new Cart($oldCart);

            $cart->add(
                $item,
                route('directory.package.detail', $item->slug),
                $request->quantity,
                $price->price,
                'directoryPackage',
                $item->getFirstMediaUrl("thumbnail"),
                $price->recurrent,
                $item->name,
                $price
            );

            Session::put("cart", $cart);

            return response()->json([
                'status' => 1,
                'data' => '',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
