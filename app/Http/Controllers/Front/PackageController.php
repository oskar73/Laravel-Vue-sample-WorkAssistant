<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Integration\Cart;
use App\Models\Builder\Template;
use App\Models\Package;
use Illuminate\Http\Request;
use Session;
use Validator;

class PackageController extends Controller
{
    public $model;

    public function __construct()
    {
        $this->model = new Package();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $result = $this->model->filterItem($request);

            return response()->json($result);
        }

        $template_slug = $request->get('template');
        if ($template_slug ?? null) {
            $template = Template::where('slug', $template_slug)->first();
            $request->session()->put('selected_template', $template);
        }

        return view('frontend.package.index');
    }

    public function detail($slug)
    {
        $item = $this->model->where('slug', $slug)
            ->with('media', 'approvedPrices')
            ->wherePackage(1)
            ->status(1)
            ->firstorfail();

        return view('frontend.package.detail', compact('item'));
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
            $cart->add($item, route('package.detail', $item->slug), $request->quantity, $price->price, 'package', $item->getFirstMediaUrl("thumbnail"), $price->recurrent, $item->name, $price);
            Session::put("cart", $cart);

            //Save cart session in text so that it can be retrived later after signed in
            //            saveCartToText(Session::getid(), $cart);

            return response()->json([
                'status' => 1,
                'data' => view('components.front.cart')->render(),
            ]);
        } catch (\Exception $e) {
            info($e->getMessage());

            return response()->json([
                'status' => 0,
                'data' => ['Item not found!'],
            ]);
        }
    }
}
