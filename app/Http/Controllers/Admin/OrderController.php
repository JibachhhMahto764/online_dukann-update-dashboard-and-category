<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){
        $orders = Order::latest('orders.created_at')->select('orders.*','users.name','users.email');
        $orders = $orders->leftJoin('users','users.id','orders.user_id');

        if ($request->get('keyword') != ""){
            $orders = $orders->where('users.name','like','%'.$request->keyword.'%');
            $orders = $orders->orWhere('users.email','like','%'.$request->keyword.'%');
            $orders = $orders->orWhere('orders.id','like','%'.$request->keyword.'%');
        }
        $orders = $orders->paginate(10);
        $data['orders'] = $orders;
        return view('admin.orders.list',$data);
    }
    public function detail($orderId){
        $order = Order::where('id',$orderId)->first();
        $data['order'] = $order;
        return view('admin.orders.detail',$data);

    }
}
