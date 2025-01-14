<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function create(){
        $countries = Country::get();
        $data['countries'] = $countries;

        $shippingCharges = ShippingCharge::select('shipping_charges.*','countries.name')
        ->leftJoin('countries','countries.id','shipping_charges.country_id')->get();
        $data['shippingCharge'] = $shippingCharges;
        return view('admin.shipping.create',$data);
    }
    public function store(Request $request){
       $validator = Validator::make($request->all(),[
            'country' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()){
            $count = ShippingCharge::where('country_id',$request->country)->count();
            if ($count > 0){
                session()->flash('error','Shipping amount already added for this country');
                return response()->json([
                    'status'=>true,
                    'errors' => 'Shipping amount already added for this country'
                ]);
            }
            $shipping = new ShippingCharge;
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success','Shipping amount added successfully');
            return response()->json([
                'status'=>true,
                
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors' =>$validator ->errors()
            ]);
        }
    }
    public function edit($id){
        $countries = Country::get();
        $data['countries'] = $countries;

        $shippingCharges = ShippingCharge::select('shipping_charges.*','countries.name')
        ->leftJoin('countries','countries.id','shipping_charges.country_id')->get();
        $data['shippingCharge'] = $shippingCharges;

        $shipping = ShippingCharge::find($id);
        $data['shipping'] = $shipping;
        return view('admin.shipping.edit',$data);
    }
    public function update(Request $request,$id){
        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()){
            $shipping = ShippingCharge::find($id);
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success','Shipping updated successfully');
            return response()->json([
                'status'=>true,
                
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors' =>$validator ->errors()
            ]);
        }
    }
    public function destroy($id) {
        $shipping = ShippingCharge::find($id);
    
        if (!$shipping) { // If no record is found
            return response()->json([
                'status' => false,
                'error' => 'Shipping not found',
            ], 404); // Return appropriate HTTP status code
        }
    
        // Delete the record
        $shipping->delete();
    
        return response()->json([
            'status' => true,
            'message' => 'Shipping deleted successfully',
        ]);
    }
    
}
