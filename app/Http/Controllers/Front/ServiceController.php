<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Integration\Cart;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Session;
use Validator;

class ServiceController extends Controller
{
    public $model;

    public function __construct()
    {
        $this->model = new Service();
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $result = $this->model->filterItem($request);

            return response()->json($result);
        }

        $categories = ServiceCategory::select('id', 'slug', 'name', 'parent_id')
            ->with('approvedSubCategories')
            ->isParent()
            ->status(1)
            ->orderBy('order')
            ->get();

        return view('frontend.service.index', compact('categories'));
    }
    public function detail($slug)
    {
        $item = $this->model->where('slug', $slug)
            ->with('media')
            ->status(1)
            ->firstorfail();

        return view('frontend.service.detail', compact('item'));
    }
    public function cartRule()
    {
        $rule['quantity'] = 'required|numeric|min:1';

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

            $oldCart = Session::get("cart");
            $cart = new Cart($oldCart);
            $cart->add($item, route('service.detail', $item->slug), $request->quantity, $item->price, 'service', $item->getFirstMediaUrl("thumbnail"), 0, $item->name);

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
