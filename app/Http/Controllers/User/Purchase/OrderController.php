<?php

namespace App\Http\Controllers\User\Purchase;

use App\Http\Controllers\User\UserController;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends UserController
{
    public function __construct(Order $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (request()->wantsJson()) {
            return $this->model->getUserDataTable();
        }

        return view(self::$viewDir.'purchase.order');
    }
    public function detail($id)
    {
        $order = $this->model->where('id', $id)
            ->where("user_id", user()->id)
            ->firstorfail();

        return view(self::$viewDir . "purchase.orderDetail", compact("order"));
    }
    public function confirm(Request $request)
    {
        try {
            $order = OrderItem::find($request->id);
            $order->status = 'active';
            $order->save();
    
            return response()->json([
                'status' => 1,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
