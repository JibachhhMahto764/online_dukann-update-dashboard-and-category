<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
class TempImagesController extends Controller
{
    public function create(Request $request){
        $image = $request->image;
        if (!empty($image)){
            $ext= $image->getClientOriginalExtension();

           
            $tempImage = new TempImage();
            $tempImage->name = 'Jibachh';
            $tempImage->save();
            $newName = $tempImage->id.'-'.time().'.'.$ext;
            $tempImage->name = $newName;
            $tempImage->save();
            $image->move(public_path().'/temp',$newName);
              // Generate thumbnails 
              $manager = new ImageManager(new Driver());
              $sourcePath = public_path().'/temp/'.$newName;
              $destPath = public_path().'/temp/thumb/'.$newName;
        
                $image = $manager->read($sourcePath);
                $image->cover(300,275);
                $image->save($destPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' =>assert('/temp/thumb/'.$newName),
                'message' => 'Image Uploaded successfully'
            ]);
        }
    }
}
