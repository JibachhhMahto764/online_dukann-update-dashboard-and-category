<?php
use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Country;
use App\Models\Order;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Mail;

function getCategories(){
    return Category::orderBy('name','ASC')
    ->with('sub_category')
    ->orderBy('id','DESC')
    ->where('showHome','Yes')
    ->where('status',1)
    ->get();
}

function getProductImage($productId){
    return ProductImage::where('product_id',$productId)->first();
}

 function OrderEmail($orderId){
    $order = Order::where('id',$orderId)->with('items')->first();
    
    $mailData = [
        'subject' => 'Thank you for your order',
        'order' => $order
    ];
    Mail::to($order->email)->send(new OrderEmail($mailData));
    //dd($order);
 }

 function getCountryInfo($id){
    return Country::where('id',$id)->first();
 }
