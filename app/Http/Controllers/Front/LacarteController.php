<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Integration\Braintree;
use App\Integration\Cart;
use App\Integration\Paypal;
use App\Models\Lacarte;
use App\Models\LacarteCategory;
use Illuminate\Http\Request;
use Session;
use Validator;

class LacarteController extends Controller
{
    public $model;

    public function __construct()
    {
        $this->model = new Lacarte();
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $result = $this->model->filterItem($request);

            return response()->json($result);
        }

        $categories = LacarteCategory::select('id', 'slug', 'name', 'parent_id')
            ->with('approvedSubCategories')
            ->isParent()
            ->status(1)
            ->orderBy('order')
            ->get();

        return view('frontend.lacarte.index', compact('categories'));
    }
    public function detail($slug)
    {
        $item = $this->model->where('slug', $slug)
            ->with('media')
            ->status(1)
            ->firstorfail();
        //
        //        $gateway = new Braintree();
        ////        $customer_id = $gateway->customer()->id;
        //        $clientToken = $gateway->clientToken(292937359);
        return view('frontend.lacarte.detail', compact('item'));
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
            $cart->add($item, route('lacarte.detail', $item->slug), $request->quantity, $item->price, 'lacarte', $item->getFirstMediaUrl("thumbnail"), 0, $item->name);

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
    public function addtoCart123($slug)
    {
        //        $item = $this->model->where('slug', $slug)
        //            ->status(1)
        //            ->firstorfail();
        //
        //        $paypal = new Paypal();
        //        $provider = $paypal->getProvider();
        //        $data = [];
        //        $data['items'] = [
        //            [
        //                'name' => $item->name,
        //                'price' => $item->price,
        //                'desc'  => $item->description,
        //                'qty' => 1
        //            ],
        //        ];
        //        $data['invoice_id'] = 1;
        //        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        //        $data['return_url'] = route('cart.paypal.execute');
        //        $data['cancel_url'] = route('cart.index');
        //        $total = 0;
        //        foreach($data['items'] as $item) {
        //            $total += $item['price']*$item['qty'];
        //        }
        //        $data['total'] = $total;
        //        $data['shipping_discount'] = round((10 / 100) * $total, 2);
        //        $response = $provider->setExpressCheckout($data);
        //        return redirect($response['paypal_link']);
    }
}
