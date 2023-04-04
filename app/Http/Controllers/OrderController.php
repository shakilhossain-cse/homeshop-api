<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // get specific user order 
    public function index()
    {
        $orders = Order::with('orderItems.product')->orderBy('created_at', 'desc')->where('user_id', '=', auth()->user()->id)->take(5)->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'items*.id' => 'required|integer|exists:products,id',
                'items*.quantity' => 'required|integer|min:1',
                'items*.size' => 'nullable|in:m,l,xl,xxl,2xl',
                'items*.color' => 'nullable|string',
                'subtotal' => 'required|numeric|min:0',
                'tax' => 'required|numeric|min:0',
                'shipping' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
            ],
            [
                'items*.id.exists' => 'this product is no longer available.'
            ]
        );
        $TAX_RATE = 0.1;
        $SHIPPING_RATE = 0.05;
        $SUB_TOTAL = 0;
        $TOTAL = 0;
        $order = new Order;
        $order->user_id = auth()->user()->id;
        $order->orderId = Str::random(8);;
        $order->tax = 0;
        $order->shipping = 0;
        $order->subtotal = 0;
        $order->total = 0;
        $order->save();

        foreach ($request->items as $item) {
            $product = Product::find($item['id']);
            if (!$product || $product->quantity < $item['quantity']) {
                $errors = ["id" => $item['id'], "message" => "this product does not have enough quantity."];
                $order->delete();
                return response()->json(['errors' => $errors], 422);
            }
            $SUB_TOTAL +=  $product->price;
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['id'];
            $orderItem->price = $product->price;
            $orderItem->quantity = $item['quantity'];
            $orderItem->size = $item['size'] ?? 'N/A';
            $orderItem->color = $item['color'] ?? 'N/A';
            $orderItem->save();
            $product->quantity -= $item['quantity'];
            $product->save();
        }

        $TAX_RATE *= $SUB_TOTAL;
        $SHIPPING_RATE *= $SUB_TOTAL;
        $TOTAL = $SUB_TOTAL + $TAX_RATE + $SHIPPING_RATE;
        $order->tax = $TAX_RATE;
        $order->shipping = $SHIPPING_RATE;
        $order->subtotal = $SUB_TOTAL;
        $order->total = $TOTAL;
        $order->save();

        return response()->json(["message" => "order placed successfully"]);
    }
}
