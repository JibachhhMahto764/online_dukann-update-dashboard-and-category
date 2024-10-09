<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Validator;

class SubCategoryController extends Controller
{

    public function index(Request $request){
        $subCategories = SubCategory::select('sub_categories.*','categories.name as categoryName')
                ->latest('sub_categories.id')
                ->leftJoin('categories','categories.id','sub_categories.category_id');
      if (!empty($request->get('keyword'))){
        $subCategories = $subCategories->where('sub_categories.name','like','%'.$request->get('keyword').'%');
        $subCategories = $subCategories->orWhere('categories.name','like','%'.$request->get('keyword').'%');
      }
      $subCategories = $subCategories->paginate(10);
      
      return view('admin.sub_category.list',compact('subCategories'));
    
    }
    public function create(){
        $categories = Category::orderBy('name','ASC')->get();
        $data['categories'] = $categories;

        return view('admin.sub_category.create',$data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
          'name' => 'required',
          'slug' => 'required|unique:sub_categories',
          'category' => 'required',
          'status' => 'required'
    
        ]);
        if($validator->passes()){
            $Subcategory = new SubCategory();
            $Subcategory->name = $request->name;
            $Subcategory->slug = $request->slug;
            $Subcategory->status = $request->status;
            $Subcategory->category_id= $request->category;
            $Subcategory->save();

            $request ->session()->flash('success','Sub-Category created successfully');
            return response()->json([
              'status' =>true,
              'message' => 'Sub-Category created successfully'
              
            ]);
        }else{
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($id , Request $request){
        $subCategory = SubCategory::find($id);
        if(empty($subCategory)){
        $request->session()->flash('error','Record Not found');
        return redirect()->route('sub-categories.index');
    }
        $categories = Category::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['subCategory'] = $subCategory;

        return view('admin.sub_category.edit',$data);
    }
    public function update($id, Request $request){
        
            $subCategory = SubCategory::find($id);

            if(empty($subCategory)){

            $request->session()->flash('error','Record Not found');
            return response([
                'status' => false,
                'notFound' => true
            ]);
            //return redirect()->route('sub-categories.index');
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            'category' => 'required',
            'status' => 'required'
      
          ]);
          if($validator->passes()){
              
              $subCategory->name = $request->name;
              $subCategory->slug = $request->slug;
              $subCategory->status = $request->status;
              $subCategory->category_id= $request->category;
              $subCategory->save();
  
              $request ->session()->flash('success','Sub-Category Updated successfully');
              return response()->json([
                'status' =>true,
                'message' => 'Sub-Category Updated successfully'
                
              ]);
          }else{
              return response([
                  'status' => false,
                  'errors' => $validator->errors()
              ]);
          }
    }
    public function destroy($id, Request $request){
        $subCategory = SubCategory::find($id);

            if(empty($subCategory)){

            $request->session()->flash('error','Record Not found');
            return response([
                'status' => false,
                'notFound' => true
            ]);
            
        }
        $subCategory->delete();  

        $request ->session()->flash('success','Sub-Category deleted successfully');
        return response()->json([
          'status' =>true,
          'message' => 'Sub-Category deleted successfully'
          
        ]);
    }
}
