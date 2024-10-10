<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Validator;

class BrandController extends Controller
{
    public function create(){
        return view('admin.brands.create');
    }
    public function store(Request $request){
       $validator = Validator::make($request->all(),[
        'name' => 'required',
        'slug' => 'required|unique:brands',

       ]);
       if($validator->passes()){
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = $request->slug;
        $brand->save();

        return response()->json([
            'status' => true,
            'message' => 'Brands added successfully.'
        ]);

       }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
       }
   

    }
        
}
