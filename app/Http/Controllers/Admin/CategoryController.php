<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;
use Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $categories = Category::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $categories = Category::where(['position' => 0]);
        }

        $categories = $categories->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.category.view', compact('categories','search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required',
            'priority'=>'required'
        ], [
            'name.required' => 'Category name is required!',
            'image.required' => 'Category image is required!',
            'priority.required' => 'Category priority is required!',
        ]);

        $category = new Category;
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->slug = Str::slug($request->name[array_search('en', $request->lang)]);
        
        $image = $request->file('image');
        $image = Image::make($image)->resize(100, 100);
        if ($image != null) {
        $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
        if (!Storage::disk('public')->exists('category/')) {
            Storage::disk('public')->makeDirectory('category/');
        }
        Storage::disk('public')->put('category/' . $imageName, (string) $image->encode('png'));
        }
        
        $image = $request->file('image');
        $image = Image::make($image)->resize(100, 100);
        
        $category->icon = $imageName;
        
        
        $category->parent_id = 0;
        $category->position = 0;
        $category->priority = $request->priority;
        $category->keyword = $request->keyword;
        $category->save();

        $data = [];
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                array_push($data, array(
                    'translationable_type' => 'App\Model\Category',
                    'translationable_id' => $category->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                ));
            }
        }
        if (count($data)) {
            Translation::insert($data);
        }

        Toastr::success('Category added successfully!');
        return back();
    }

    public function edit(Request $request, $id)
    {
        $category = category::withoutGlobalScopes()->find($id);
        return view('admin-views.category.category-edit', compact('category'));
    }

    public function update(Request $request)
    {
        $category = Category::find($request->id);
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->slug = Str::slug($request->name[array_search('en', $request->lang)]);
        if ($request->image) {
            $category->icon = ImageManager::update('category/', $category->icon, 'png', $request->file('image'));
        }
        $category->priority = $request->priority;
        $category->keyword = $request->keyword;
        $category->save();

        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\Category',
                        'translationable_id' => $category->id,
                        'locale' => $key,
                        'key' => 'name'],
                    ['value' => $request->name[$index]]
                );
            }
        }

        Toastr::success('Category updated successfully!');
        return back();
    }

    public function delete(Request $request)
    {
        $categories = Category::where('parent_id', $request->id)->get();
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $categories1 = Category::where('parent_id', $category->id)->get();
                if (!empty($categories1)) {
                    foreach ($categories1 as $category1) {
                        $translation = Translation::where('translationable_type','App\Model\Category')
                                    ->where('translationable_id',$category1->id);
                        $translation->delete();
                        Category::destroy($category1->id);

                    }
                }
                $translation = Translation::where('translationable_type','App\Model\Category')
                                    ->where('translationable_id',$category->id);
                $translation->delete();
                Category::destroy($category->id);

            }
        }
        $translation = Translation::where('translationable_type','App\Model\Category')
                                    ->where('translationable_id',$request->id);
        $translation->delete();
        Category::destroy($request->id);

        return response()->json();
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::where('position', 0)->orderBy('id', 'desc')->get();
            return response()->json($data);
        }
    }

    public function status(Request $request)
    {
        $category = Category::find($request->id);
        $category->home_status = $request->home_status;
        $category->save();
        // Toastr::success('Service status updated!');
        // return back();
        return response()->json([
            'success' => 1,
        ], 200);
    }
    
    public function popular_status(Request $request) {
        $category = Category::find($request->id);
        $category->is_popular = $request->popular_status;
        $category->save();
        // Toastr::success('Service status updated!');
        // return back();
        return response()->json([
            'success' => 1,
        ], 200);
    }
}
