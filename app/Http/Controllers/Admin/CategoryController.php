<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Models\TempImage;

use Illuminate\Http\Request;
use Validator;
use App\Models\Category;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function index(Request $request){
      $categories = Category::latest();
      if (!empty($request->get('keyword'))){
        $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
      }
      $categories = $categories->paginate(10);
      
      return view('admin.category.list',compact('categories'));
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
      $category = new Category();
      $category->name = $request->name;
      $category->slug = $request->slug;
      $category->status = $request->status;
      $category->showHome = $request->showHome;
      $category->save();
      // save images Here 
      if (!empty($request->image_id)){
        $tempImage = TempImage::find($request->image_id);
        $extArray = explode('.',$tempImage->name);
        $ext = last($extArray);
        $newImageName = $category->id.'.'.$ext;
        $sPath = public_path().'/temp/'.$tempImage->name;
        $dPath = public_path().'/uploads/category/'.$newImageName;
        File::copy($sPath,$dPath);

        // generate image thumbnail
       
        $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($sPath );
        $image->cover(450,600);
        $image->save($dPath);
        $category->image = $newImageName;
        $category->save();
      }
      $request ->session()->flash('success','Category added successfully');
      return response()->json([
        'status' =>true,
        'message' => 'Category added successfully'
        
      ]);

    }else{
      return response()->json([
        'status' =>false,
        'errors' => $validator->errors()
      ]);
    }
      
    }
    public function edit($categoryId,Request $request){
      
      $category = Category::find($categoryId);
        
        if(empty($category)){
          return redirect()->route('categories.index');
        }
        return view('admin.category.edit',compact('category'));
    }

    public function update($categoryId, Request $request){
       
      $category = Category::find($categoryId);
        
        if(empty($category)){
          $request ->session()->flash('error','Category Not Found');
          return response()->json([
            'status' => false,
            'notFound' => true,
            'message' => 'Category not Found'
          ]);
        }

      $validator = Validator::make($request->all(),[
        'name' => 'required',
        'slug' => 'required|unique:categories,slug,'.$category->id.',id',
  
      ]);
  
      if ($validator->passes()){
       
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->showHome = $request->showHome;
        $category->save();
        $oldImage = $category->image;
        // save images Here 
        if (!empty($request->image_id)){
          $tempImage = TempImage::find($request->image_id);
          $extArray = explode('.',$tempImage->name);
          $ext = last($extArray);
          $newImageName = $category->id.'-'.time().'.'.$ext;
          $sPath = public_path().'/temp/'.$tempImage->name;
          $dPath = public_path().'/uploads/category/'.$newImageName;
          File::copy($sPath,$dPath);
  
          // generate image thumbnail
          $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
         
          $manager = new ImageManager(new Driver());
            $image = $manager->read($sPath );
            $image->cover(450,600);
            $image->save($dPath);
          $category->image = $newImageName;
          $category->save();
          // Delete old Image Here 
          File::delete(public_path().'/uploads/category/'.$oldImage);
          File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
        }
        $request ->session()->flash('success','Category updated successfully');
        return response()->json([
          'status' =>true,
          'message' => 'Category updated successfully'
          
        ]);
  
      }else{
        return response()->json([
          'status' =>false,
          'errors' => $validator->errors()
        ]);
      }

    }

    public function destroy($categoryId, Request $request) {
      $category = Category::find($categoryId);
  
      if (empty($category)) {
          $request->session()->flash('error', 'Category Not Found');
          return response()->json([
              'status' => false,  // Change status to false to reflect failure
              'notFound' => true,  // Add a flag for not found cases
              'message' => "Category Not Found"
          ]);
      }
  
      // Delete category files
      File::delete(public_path() . '/uploads/category/thumb/' . $category->image);
      File::delete(public_path() . '/uploads/category/' . $category->image);
  
      // Delete category
      $category->delete();
  
      $request->session()->flash('success', 'Category Deleted successfully');
      return response()->json([
          'status' => true,
          'message' => "Category Deleted successfully"
      ]);
  }












}
