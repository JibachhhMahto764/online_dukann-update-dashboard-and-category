<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $totalOrders = Order::where('status','!=','cancelled')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role',1)->count();
        $totalRevenue = Order::where('status','!=','cancelled')->sum('grand_total');

         // This month Revenue
         $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
         $currentDate = Carbon::now()->format('Y-m-d');

         $revenueThisMonth = Order::where('status','!=','cancelled')
                               ->whereDate('created_at','>=',$startOfMonth)
                               ->whereDate('created_at','<=',$currentDate)
                               ->sum('grand_total');
         $lastMonthName = Carbon::now()->startOfMonth()->format('M');                      
    // Last Month Revenue
      $lastMonthStartDate =  Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
      $lastMonthEndDate =  Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');

      $revenueLastMonth = Order::where('status','!=','cancelled')
                            ->whereDate('created_at','>=',$lastMonthStartDate )
                            ->whereDate('created_at','<=',$lastMonthEndDate)
                            ->sum('grand_total');

       // Last 30 Days Revenue 
      $lastThirtyDayStartDate = Carbon::now()->subDays(30)->format('Y-m-d');
      $revenueLastThirtyDays = Order::where('status','!=','cancelled')
                            ->whereDate('created_at','>=',$lastThirtyDayStartDate )
                            ->whereDate('created_at','<=',$currentDate)
                            ->sum('grand_total');

         //delete temp images here 
         $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d H:i:s');
         $tempImages = TempImage::where('created_at','<=',$dayBeforeToday)->get();

         foreach($tempImages as $tempImage){
            $path = public_path('/temp/'.$tempImage->name);
            $thumbPath = public_path('/temp/thumb/'.$tempImage->name);

            // Delete Main Images

            if(File::exists($path)){
                File::delete($path);
            }
            // Delete Thumb Images
            if(File::exists($thumbPath)){
                File::delete($thumbPath);
            }

            TempImage::where('id',$tempImage->id)->delete();
         }

        return view('admin.dashboard',[
            'totalOrders' => $totalOrders,
             'totalProducts' => $totalProducts,
             'totalCustomers' => $totalCustomers,
             'totalRevenue' => $totalRevenue,
             'revenueThisMonth' => $revenueThisMonth,
             'revenueLastMonth' => $revenueLastMonth,
             'revenueLastThirtyDays' => $revenueLastThirtyDays,
             'lastMonthName' => $lastMonthName,
        ]);
       
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}

