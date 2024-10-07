<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    public function index(){

    }
    public function create(){
      return view('admin.category.create');
    }

    public function store(Request $request){
    $validator = Validator::make($request->all(),[
      'name' => 'required',
      'slug' => 'required|unique:categories',

    ]);

    if ($validator->passes()){

    }else{
      return response()->json([
        'status' =>false,
        'errors' => $validator->errors()
      ]);
    }
      
    }
    public function edit(){

    }

    public function update(){

    }

    public function destroy(){

    }













}
