<?php

namespace App\Http\Controllers\seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Category;
use App\PopularCategory;
use Brian2694\Toastr\Facades\Toastr;



class popularController extends Controller
{
    public function create(){
        $seller_id = auth('seller')->id();
        $popularCategories = PopularCategory::with("category")->where('seller_id', $seller_id)->get();
        $categories = Category::all();
        // $banner = Setting::select("popular_category_banner")->first();
        return view('seller-views.popularCategory.create', compact('popularCategories', 'categories'));
    }

    public function store(Request $request){
       $request->validate([
            'category_id' => 'required',
            'seller_id' => 'required',
            'priority'=>''
        ], [
            'category_id.required' => 'Category name is required!',
            'seller_id.required' => ' Seller  is required!',
            
        ]);

        $popular = new PopularCategory();  
        $popular->category_id = $request->category_id;
        $popular->seller_id = $request->seller_id;
        $popular->priority = $request->priority;
        
        $popular->save();
        return back();
    }
    
    public function delete(Request $request, $id){
        $delete = PopularCategory::where('id', $id)
            ->delete();
        if ($delete == true) {
            Toastr::success('successfully deleted');
        } else {
            Toastr::error('delete is faild');
        }
        return back();
    }
}
