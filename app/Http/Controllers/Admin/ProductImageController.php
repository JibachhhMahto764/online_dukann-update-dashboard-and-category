<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use File;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProductImageController extends Controller
{
    public function update(Request $request){
        $image = $request->image;
        $ext= $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = 'NULL';
        $productImage->save();

        $imageName = $request->product_id.'-'.$productImage->id.'-'.time().'.'.$ext;
        $productImage->image = $imageName;
        $productImage->save();


        
                // large image 
                
                $destPath = public_path().'/uploads/product/large/'.$imageName;
                $image = Image::make($sourcePath);
                $image->resize(1400,null,function($constraint){
                  $constraint->aspectRatio();

                });
                $image->save($destPath);

              // small image
             
                $destPath = public_path().'/uploads/product/small/'.$imageName;
                $image = Image::make($sourcePath);
                $image->fit(300,300);
                $image->save($destPath);

                 // response display
            $request->session()->flash('success', 'Image saved Successfully');
            return response()->json([
                'status' => true,
                'image_id' => $productImage->id,
                'ImagePath' => asset('uploads/product/small/'.$productImage->image),
                'message' => "Image saved Successfully"
            ]);

    }


    public function destroy(Request $request){
        $productImage = ProductImage::find($request->id);

        $request->session()->flash('error', 'Image not found!!!');
        if(empty($productImage)){
            return response()->json([
               'status' => false,
               'message' => 'Image not found!!!'

            ]);
        }


        // delete image from folder 
        File::delete(public_path('uploads/product/large/'.$productImage->image));
        File::delete(public_path('uploads/product/small/'.$productImage->image));
      // delete from database 
         $productImage->delete();
         $request->session()->flash('success', 'Image Deleted Successfully!!!');
         return response()->json([
         'status' => true,
         'message' => 'Image Deleted Successfully!!!'
         ]);



    }

}
