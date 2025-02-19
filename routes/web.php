<?php

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductSubCategoryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TempImagesController;
use App\Http\Controllers\Admin\UserController;
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
// Route::get('/test', function () {
//   OrderEmail(50);
// });

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
// applyDiscount route
Route::post('/apply-discount',[CartController::class,'applyDiscount'])->name('front.applyDiscount');
Route::post('/remove-discount',[CartController::class,'removeDiscount'])->name('front.removeDiscount');
// wishlist
Route::post('/add-to-wishlist',[FrontController::class,'addToWishlist'])->name('front.addToWishlist');
//contact us route
Route::post('/send-contact-email',[FrontController::class,'sendContactEmail'])->name('front.sendContactEmail');
// page route
Route::get('/page/{slug}',[FrontController::class,'page'])->name('front.page');
//forgot-password
Route::get('/forgot-password', [AuthController::class,'forgotPassword'])->name('front.forgotPassword');
Route::post('/process-forgot-password', [AuthController::class,'processForgotPassword'])->name('front.processForgotPassword');
Route::get('/reset-password/{token}', [AuthController::class,'resetPassword'])->name('front.resetPassword');
Route::post('/process-reset-password', [AuthController::class,'processResetPassword'])->name('front.processResetPassword');
// rating route
Route::post('/save-rating/{productId}', [ShopController::class,'saveRating'])->name('front.saveRating');


// frontend Authentication
Route::group(['prefix' =>'account'],function(){
     Route::group(['middleware' =>'guest'],function(){
      Route::get('/login',action: [AuthController::class,'login'])->name('account.login');
      Route::post('/login',action: [AuthController::class,'authenticate'])->name('account.authenticate');
      Route::get('/register',[AuthController::class,'register'])->name('account.register');
      Route::post('/process-register',[AuthController::class,'processRegister'])->name('account.processRegister');
      Route::get('/logout',action: [AuthController::class,'logout'])->name('account.logout');


     });
     Route::group(['middleware' =>'auth'],function(){
     
      Route::get('/profile',action: [AuthController::class,'profile'])->name('account.profile');
      Route::post('/update-profile',action: [AuthController::class,'updateProfile'])->name('account.updateProfile');
      Route::post('/update-address',action: [AuthController::class,'updateAddress'])->name('account.updateAddress');
      Route::get('/change-password',action: [AuthController::class,'showChangePasswordForm'])->name('account.changePassword');
      Route::post('/process-change-password',action: [AuthController::class,'changePassword'])->name('account.processChangePassword');



      Route::get('/my-orders',[AuthController::class,'orders'])->name('account.orders');
      Route::get('/my-wishlists',[AuthController::class,'wishlist'])->name('account.wishlists');
      Route::post('/remove-product-from-wishlist',[AuthController::class,'removeProductFromWishlist'])->name('account.removeProductFromWishlist');
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
   // productsRating
   Route::get('/ratings',[ProductController::class,'productRatings'])->name('products.productRatings');
   Route::get('/change-ratings-status',[ProductController::class,'changeRatingStatus'])->name('products.changeRatingStatus');


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
      // Order Routes
      Route::get('/orders',[OrderController::class,'index'])->name('orders.index');
      Route::get('/orders/{id}',[OrderController::class,'detail'])->name('orders.detail');
      Route::post('/order/change-status/{id}',[OrderController::class,'changeOrderStatus'])->name('orders.changeStatus');
      Route::post('order/send-email/{id}',[OrderController::class,'sendInvoiceEmail'])->name('orders.sendInvoiceEmail');
     // Users Routes
     Route::get('/users',[UserController::class,'index'])->name('users.index');
     Route::get('/users/create',[UserController::class,'create'])->name('users.create');
     Route::post('/users',[UserController::class,'store'])->name('users.store');
     Route::get('/users/{user}/edit',[UserController::class,'edit'])->name('users.edit');
     Route::put('/users/{user}',[UserController::class,'update'])->name('users.update');
     Route::delete('/users/{user}',[UserController::class,'destroy'])->name('users.delete');

     // Pages Route 
     
        Route::get('/pages',[PageController::class,'index'])->name('pages.index');
        Route::get('/pages/create',[PageController::class,'create'])->name('pages.create');
        Route::post('/pages',[PageController::class,'store'])->name('pages.store');
        Route::get('/pages/{page}/edit',[PageController::class,'edit'])->name('pages.edit');
        Route::put('/pages/{page}',[PageController::class,'update'])->name('pages.update');
        Route::delete('/pages/{page}',[PageController::class,'destroy'])->name('pages.delete');

     //temp-image-create route
     Route::post('/upload-temp-image',[TempImagesController::class,'create'])->name('temp-images.create');
    // Setting Route
    Route::get('/change-password',[SettingController::class,'showChangePasswordForm'])->name('admin.showChangePasswordForm');
    Route::post('/process-change-password',[SettingController::class,'processChangePassword'])->name('admin.processChangePassword');



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