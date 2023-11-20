<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Brand;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Color;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];

        if ($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $attributes = Attribute::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            });
            
            $query_param = ['search' => $request['search']];
        }else{
            $colors = new Color();
        }
        $colors = $colors->latest()->paginate(30)->appends($query_param);
        // $colors = $colors->latest()->paginate(Helpers::pagination_limit(30))->appends($query_param);
        return view('admin-views.color.index', compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->can('size.create'))
        {
            abort(403, 'unauthorized');
        }

        return view('backend.colors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data=$request->validate([
             'name'=> 'required',
             'code'=> 'required',
        ]);
        
        $data['name'] = str_replace(' ','', $data['name']); 

        Color::create($data);
        Toastr::success('Color added successfully!');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $color=Color::find($id);
        return view('admin-views.color.edit', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $color = Color::find($id);
        $data=$request->validate([
             'name'=> 'required',
             'code'=> 'required',
        ]);
       
        $color->update($data);
        
        Toastr::success('Color update successfully!');
        return back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->can('size.delete'))
        {
            abort(403, 'unauthorized');
        }

        $category=Color::find($id);
        $category->delete();
        return response()->json(['status'=>true ,'msg'=>'Color Is Deleted !!']);

    }

}
