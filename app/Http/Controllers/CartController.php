<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Product;
use Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Validator;


class CartController extends Controller
{
   public function addToCart(Request $request){

      $product = Product::with('product_images')->find($request->id);

       if ($product == null){
         return response()->json([
           'status' => false,
           'message' => ' product not found!!!'
         ]);
      } 
if (Cart::count() > 0){
// products found in cart 
// check if this product already in the cart
// return as message that product already added in your cart
// if product not found in the cart  then add product in cart

$cartContent = Cart::content();
$productAlreadyExist = false;

  foreach($cartContent as $item){
   if ($item->id == $product->id){
      $productAlreadyExist = true;
   }
  }
        
if ($productAlreadyExist == false){
   Cart::add( $product->id, $product->title, 1, $product-> price,['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);

   $status = true;
   $message = $product->title. 'added in your cart successfully!!!';
   session()->flash('success',$message);
}else{

   
   $status = false;
   $message = $product->title. ' already added in cart';

}

      }else{
         
         // when the cart is empty then 
         Cart::add( $product->id,$product->title, 1, $product-> price,['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);

         $status = true;
         $message =$product->title. 'added in your cart successfully!!!';
         session()->flash('success',$message);
      }
      return response()->json([
         'status' => $status,
         'message' => $message
       ]);

   }
   public function Cart(){
  
  $cartContent = Cart::content();
 //dd($cartContent);
  $data['cartContent'] = $cartContent;
   return view('front.cart',$data);
   }
   public function updateCart(Request $request){
        $rowId = $request->rowId;
        $qty = $request->qty;

            $itemInfo = Cart::get($rowId);

            $product = Product::find($itemInfo->id); 
        // check qty available in stock
        
        if ($product->track_qty == 'Yes'){
         if ( $qty <=$product->qty){
         Cart::update($rowId,$qty);
         $message = 'Cart updated successfully';
         $status = true;
         session()->flash('success',$message);
         }else{
           $message = 'Requested qty('.$qty.') not available in stock.';
           $status = false;
           session()->flash('error',$message);
        } 
      } else{
         Cart::update($rowId,$qty);
         $message = 'Cart updated successfully';
         $status = true;
         session()->flash('success',$message);
        } 
             
       
      
       return response()->json([
         'status' => $status,
         'message' => $message
       ]);

   }
   public function deleteItem(Request $request){
      $itemInfo = Cart::get($request->rowId);

      if ($itemInfo == null){
         $errorMessage = 'Item not found in the cart';
         session()->flash('error',$errorMessage);

         return response()->json([
            'status' => false,
            'message' => $errorMessage
         ]);
      }
      cart::remove($request->rowId);

      $message = 'Item removed from cart successfully.';
      session()->flash('success',$message);

      return response()->json([
         'status' => true,
         'message' => $message
      ]);
   }
   public function checkout(){
      //-- if cart is empty redirect to cart page
      if (Cart::count() == 0){
         return redirect()->route('front.cart');
      }
   //-- if user is not logged in then redirect to login page
   if (Auth::check() == false){
        
      if(!session()->has('url.intended')){
         session(['url.intended' => url()->current()]);
      }
      
      return redirect()->route('account.login');
   }
     session()->forget('url.intended');
     $countries = Country::orderBy('name','ASC')->get();
      return view('front.checkout',['countries' => $countries]);
   }
   public function processCheckout(Request $request){
      // step-1 apply validation

      $validator = Validator::make($request->all(),[
      'first_name' => 'required|min:5',
      'last_name' => 'required',
      'email' => 'required|email',
      'country' => 'required',
      'address' => 'required|min:3',
      'city' => 'required',
      'state' => 'required',
      'zip' => 'required',
      'mobile' => 'required',
      ]);

      if ($validator->fails()){
         return response()->json([
            'message' => 'Please fix the errors',
            'status' => false,
            'errors' => $validator->errors()
         ]);
      }

      // step -2 save user address
  // $customerAddress = CustomerAddress::find();
      $user = Auth::user();

   CustomerAddress::updateOrCreate(
      ['user_id' => $user->id],
      [
         'user_id' => $user->id,
         'first_name' => $request->first_name,
         'last_name' => $request->last_name,
         'email' => $request->email,
         'mobile' => $request->mobile,
         'country_id' => $request->country,
         'address' => $request->address,
         'apartment' => $request->apartment,
         'city' => $request->city,
         'state' => $request->state,
         'zip' => $request->zip,
         
         
      ]
   );

   }
}
