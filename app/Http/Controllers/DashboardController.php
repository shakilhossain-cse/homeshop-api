<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function getData()
    {
        $orderNumbers = [];
        $cancelOrder = [];
        $deliveredOrder = [];

        $dates = array();
        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', strtotime("-$i day"));
            array_push($dates, $date);
        }
        $dates = array_reverse($dates);

        foreach ($dates as $date) {
            $count = Order::whereDate('created_at', $date)->count();
            $countCancelOrder = Order::whereDate('updated_at', $date)->where('status', 'cancel')->count();
            $countDeliveredOrder = Order::whereDate('updated_at', $date)->where('status', 'delivered')->count();
            array_push($orderNumbers, $count);
            array_push($cancelOrder, $countCancelOrder);
            array_push($deliveredOrder, $countDeliveredOrder);
        }
        return response()->json([
            'labels' => $dates,
            'datasets' => [
                [
                    'fill' => true,
                    'label' => 'Orders',
                    'data' => $orderNumbers,
                    'borderColor' => 'rgb(53, 162, 235)',
                    'backgroundColor' => 'rgba(53, 162, 235, 0.5)',
                ],
                [
                    'fill' => true,
                    'label' => 'Cancel Orders',
                    'data' => $cancelOrder,
                    'borderColor' => 'rgb(53, 162, 235)',
                    'backgroundColor' => '#8a2be26e',
                ],
                [
                    'fill' => true,
                    'label' => 'Delivered Order',
                    'data' => $deliveredOrder,
                    'borderColor' => 'rgb(53, 162, 235)',
                    'backgroundColor' => 'blue',
                ],
            ],
        ]);
    }
}
