<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
   public function addToCart(){

   }
   public function Cart(){
    
    return view('front.cart');
   }
}