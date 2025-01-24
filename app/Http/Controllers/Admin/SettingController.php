<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Validator;

class SettingController extends Controller
{
 public function showChangePasswordForm(){
    return view('admin.change-password');
 }

 public function processChangePassword(Request $request){

    $validator = Validator::make($request->all(),[
        'previous_password' => 'required',
        'new_password' => 'required|min:5',
        'confirm_password' => 'required|same:new_password',
    ]);
    $admin = User::select('id','password')->where('id',Auth::guard('admin')->user()->id)->first();
    if ($validator->passes()){

       if (!Hash::check($request->previous_password,$admin->password)){
        session()->flash('error','Your Previous password is incorrect, Please try again. ');
        return response()->json([
            'status' =>true,
        ]);
       }

       User::where('id',Auth::guard('admin')->user()->id)->update([
        'password' => Hash::make($request->new_password)
       ]);
       session()->flash('success','Your have successfully changed your password. ');
       return response()->json([
        'status' =>true,
    ]);

       }else{
        return response()->json([
            'status' =>false,
            'errors' => $validator->errors()
        ]);
    }
 }
}
