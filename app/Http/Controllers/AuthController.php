<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(){

    }

    public function register(){
        return view ('front.account.register');
        
    }
}
