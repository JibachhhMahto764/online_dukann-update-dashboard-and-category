<?php

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductSubCategoryController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TempImagesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
});
 */
// frontend route

Route::get('/',[FrontController::class,'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}',[ShopController::class,'index'])->name('front.shop');
Route::get('/product/{slug}',[ShopController::class,'product'])->name('front.product');
Route::get('/cart',[CartController::class,'cart'])->name('front.cart');
Route::post('/add-to-cart',[CartController::class,'addToCart'])->name('front.addToCart');
Route::post('/update-cart',[CartController::class,'updateCart'])->name('front.updateCart');
Route::post('/delete-item',[CartController::class,'deleteItem'])->name('front.deleteItem.cart');
Route::get('/checkout',[CartController::class,'checkout'])->name('front.checkout');
Route::post('/process-checkout',[CartController::class,'processCheckout'])->name('front.processCheckout'); 
Route::get('/thanks/{orderId}',[CartController::class,'thankyou'])->name('front.thankyou');
Route::post('get-order-summery',[CartController::class,'getOrderSummery'])->name('front.getOrderSummery');
// appplyDiscount route
Route::post('/apply-discount',[CartController::class,'applyDiscount'])->name('front.applyDiscount');
Route::post('/remove-discount',[CartController::class,'removeDiscount'])->name('front.removeDiscount');

Route::group(['prefix' =>'account'],function(){
     Route::group(['middleware' =>'guest'],function(){
    // frontend Authentication
      Route::get('/login',action: [AuthController::class,'login'])->name('account.login');
      Route::post('/login',action: [AuthController::class,'authenticate'])->name('account.authenticate');
      

      Route::get('/register',[AuthController::class,'register'])->name('account.register');
      Route::post('/process-register',[AuthController::class,'processRegister'])->name('account.processRegister');
      Route::get('/logout',action: [AuthController::class,'logout'])->name('account.logout');


     });
     Route::group(['middleware' =>'auth'],function(){
     
      Route::get('/profile',action: [AuthController::class,'profile'])->name('account.profile');
      Route::get('/my-orders',[AuthController::class,'orders'])->name('account.orders');
      Route::get('/order-detail/{id}',[AuthController::class,'orderDetail'])->name('account.orderDetail');
      Route::get('/logout',action: [AuthController::class,'logout'])->name('account.logout');

     });

});






// Backend Route 
Route::group(['prefix' => 'admin'],function(){
  Route::group(['middleware'=>'admin.guest'],function(){

    Route::get('/login',[AdminLoginController::class,'index'])->name('admin.login');
    Route::post('/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');


  });

  Route::group(['middleware'=>'admin.auth'],function(){

    Route::get('/dashboard',[HomeController::class,'index'])->name('admin.dashboard');
    Route::get('/logout',[HomeController::class,'logout'])->name('admin.logout');

    // categories route
    Route::get('/categories',[CategoryController::class,'index'])->name('categories.index');
    Route::get('/categories/create',[CategoryController::class,'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit',[CategoryController::class,'edit'])->name('categories.edit');
    Route::put('/categories/{category}',[CategoryController::class,'update'])->name('categories.update');
    Route::delete('/categories/{category}',[CategoryController::class,'destroy'])->name('categories.delete');

    // Sub_category route 
    Route::get('/sub-categories',[SubCategoryController::class,'index'])->name('sub-categories.index');
    Route::get('/sub-categories/create',[SubCategoryController::class,'create'])->name('sub-categories.create');
    Route::post('/sub-categories',[SubCategoryController::class,'store'])->name('sub-categories.store');
    Route::get('/sub-categories/{subCategory}/edit',[SubCategoryController::class,'edit'])->name('sub-categories.edit');
    Route::put('/sub-categories/{subCategory}',[SubCategoryController::class,'update'])->name('sub-categories.update');
    Route::delete('/sub-categories/{subCategory}',[SubCategoryController::class,'destroy'])->name('sub-categories.delete');
    // Brands route 
    Route::get('/brands',[BrandController::class,'index'])->name('brands.index');
    Route::get('/brands/create',[BrandController::class,'create'])->name('brands.create');
    Route::post('/brands',[BrandController::class,'store'])->name('brands.store');
    Route::get('/brands/{brand}/edit',[BrandController::class,'edit'])->name('brands.edit');
    Route::put('/brands/{brand}',[BrandController::class,'update'])->name('brands.update');
    Route::delete('/brands/{brand}',[BrandController::class,'destroy'])->name('brands.delete');
   // Products Route
   Route::get('/products',[ProductController::class,'index'])->name('products.index');
   Route::get('/products/create',[ProductController::class,'create'])->name('products.create');
   Route::post('/products',[ProductController::class,'store'])->name('products.store');
   Route::get('/products/{product}/edit',[ProductController::class,'edit'])->name('products.edit');
   Route::put('/products/{product}',[ProductController::class,'update'])->name('products.update');
   Route::delete('/products/{product}',[ProductController::class,'destroy'])->name('products.delete');
   Route::get('/get-products',[ProductController::class,'getProducts'])->name('products.getProducts');

 
     // Product SubCategory route
     Route::get('/product-subcategories',[ProductSubCategoryController::class,'index'])->name('product-subcategorie.index');

     //ProductImage routes
     Route::post('/product-images/update',[ProductImageController::class,'update'])->name('product-images.update');
     Route::delete('/product-images',[ProductImageController::class,'destroy'])->name('product-images.delete');
    

     // shipping route 
      Route::get('/shipping/create',[ShippingController::class,'create'])->name('shipping.create');
      Route::post('/shipping',[ShippingController::class,'store'])->name('shipping.store');
      Route::get('/shipping/{id}',[ShippingController::class,'edit'])->name('shipping.edit');
      Route::put('/shipping/{id}',[ShippingController::class,'update'])->name('shipping.update');
      Route::delete('/shipping/{id}',[ShippingController::class,'destroy'])->name('shipping.delete');
      
      // coupon code route
      Route::get('/coupons',[DiscountCodeController::class,'index'])->name('coupons.index'); 
      Route::get('/coupons/create',[DiscountCodeController::class,'create'])->name('coupons.create');
      Route::post('/coupons',[DiscountCodeController::class,'store'])->name('coupons.store'); 
      Route::get('/coupons/{coupon}/edit',[DiscountCodeController::class,'edit'])->name('coupons.edit');
      Route::put('/coupons/{coupon}',[DiscountCodeController::class,'update'])->name('coupons.update');
      Route::delete('/coupons/{coupon}',[DiscountCodeController::class,'destroy'])->name('coupons.delete'); 
      


     //temp-image-create route
     Route::post('/upload-temp-image',[TempImagesController::class,'create'])->name('temp-images.create');


    Route::get('/getSlug',function(Request $request){
      $slug = '';
      if(!empty($request->title)){
        $slug = Str::slug($request->title);
      }
      return response()->json([
        'status' => true,
        'slug' => $slug
      ]);
    })->name('getSlug');


  });

});