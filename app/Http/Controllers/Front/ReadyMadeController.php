<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Integration\Cart;
use App\Models\Package;
use App\Models\PackageCategory;
use Illuminate\Http\Request;
use Session;
use Validator;

class ReadyMadeController extends Controller
{
    public $model;

    public function __construct()
    {
        $this->model = new Package();
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $result = $this->model->filterBizItem($request);

            return response()->json($result);
        }

        $categories = PackageCategory::select('id', 'slug', 'name', 'parent_id')
            ->with('approvedSubCategories')
            ->isParent()
            ->status(1)
            ->orderBy('order')
            ->get();

        return view('frontend.readymade.index', compact('categories'));
    }
    public function detail($slug)
    {
        $item = $this->model->where('slug', $slug)
            ->with('media', 'prices')
            ->wherePackage(0)
            ->status(1)
            ->firstorfail();

        return view('frontend.readymade.detail', compact('item'));
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
            $item = $this->model->whereId($id)
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
            $cart->add($item, route('readymade.detail', $item->slug), $request->quantity, $price->price, 'readymade', $item->getFirstMediaUrl("thumbnail"), $price->recurrent, $item->name, $price);

            Session::put("cart", $cart);

            return response()->json([
                'status' => 1,
                'data' => view('components.front.cart')->render(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => ['Item not found!'],
            ]);
        }
    }
}
