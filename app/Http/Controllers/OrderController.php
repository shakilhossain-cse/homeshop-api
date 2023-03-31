<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate(
            [
                'items*.id' => 'required|integer|exists:products,id',
                'items*.quantity' => 'required|integer|min:1',
                'items*.size' => 'nullable|in:m,l,xl,xxl,2xl',
                'items*.color' => 'nullable|string',
                'subtotal' => 'required|integer|min:0',
                'tax' => 'required|integer|min:0',
                'shipping' => 'required|integer|min:0',
                'total' => 'required|integer|min:0',
            ],
            [
                'items*.id.exists' => 'this product is no longer available.'
            ]
        );
        $TAX_RATE = 0.1;
        $SHIPPING_RATE = 0.05;
        $SUB_TOTAL = 0;
        $TOTAL = 0;
        $errors = [];
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
                $errors[] = ["id" => $item['id'], "message" => "this product does not have enough quantity."];
            }
            $SUB_TOTAL +=  $product->price;
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['id'];
            $orderItem->price = $product->price;
            $orderItem->quantity = $item['quantity'];
            $orderItem->size = $item['size'];
            $orderItem->color = $item['color'] ?? 'N/A';
            $orderItem->save();
            $product->quantity -= $item['quantity'];
            $product->save();
        }

        if (count($errors) > 0) {
            $order->delete();
            return response()->json(['errors' => $errors], 422);
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
