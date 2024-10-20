<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
   public function addToCart(Request $request){
      $product = Product::with('product_images')->find('$request->id');

      if ($product == null){
         return response()->json([
           'status' => false,
           'message' => 'product not found!!!'
         ]);
      }
      if (Cart::count() > 0){

         echo "product already in the cart";


      }else{
         echo "product added  in the cart";
         // when the cart is empty then 
         Cart::add( $product->id, $product->title, 1, $product-> price,['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);

         $status = true;
         $message = $product->title.'added in cart';
      }
      return response()->json([
         'status' => $status,
         'message' => $message
       ]);

   }
   public function Cart(){
    dd(Cart::content());
   // return view('front.cart');
   }
}
