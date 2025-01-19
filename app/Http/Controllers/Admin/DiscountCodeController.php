<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class DiscountCodeController extends Controller
{
    public function index()
    {
        return view('admin.coupon.list');
    }
    public function create()
    {
        return view('admin.coupon.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',

        ]);
          if ($validator->passes()){
            // starting date must be greater than current date
               if (!empty($request->starts_at)) {
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);

                if ($startAt->lt($now) ==true) {
                    return response()->json([
                        'status'=>false,
                        'error'=>['starts_at' =>'Starting date must be greater than current date time']
                    ]);
                }
            }

            // expiry date must be greater than starting date
            if (!empty($request->starts_at) && !empty($request->ends_at)) {
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->ends_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);

                if ($expiresAt->gt($startAt) == false) {
                    return response()->json([
                        'status'=>false,
                        'error'=>['ends_at' =>'expiring date must be greater than current date time']
                    ]);
                }
            }

            $discountCode = new DiscountCoupon();
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_users = $request->max_users;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->ends_at = $request->ends_at;
            $discountCode->save();

            session()->flash('success','Discount Coupon Created Successfully');
            return response()->json([
                'status'=>true,
                'message'=>'Discount Coupon Created Successfully'
            ]);

          }else{
            return response()->json([
                'status'=>false,
                'error'=>$validator->errors()
            ]);
          }  
            
        
    }
    public function edit()
    {
        
    }
    public function update(Request $request)
    {
        
    }
    public function destroy()
    {
        
    }
}
