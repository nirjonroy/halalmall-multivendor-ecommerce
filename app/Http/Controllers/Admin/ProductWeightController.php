<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ProductWeight;
use Brian2694\Toastr\Facades\Toastr;

class ProductWeightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_weights = ProductWeight::latest()->paginate(10);
        return view('admin-views.product-weight.list', compact('product_weights'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-views.product-weight.add-new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|unique:product_weights,title',
            'amount' => 'required|min:1|numeric',
        ]);

        
        ProductWeight::create($data);
        Toastr::success('Product weight added successfully!');
        return redirect('admin/product-weight');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product_weight = ProductWeight::findOrFail($id);
        return view('admin-views.product-weight.edit', compact('product_weight'));
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
        $data = $request->validate([
            'title' => 'required|unique:product_weights,title,'.$id,
            'amount' => 'required|min:1|numeric',
        ]);

        
        ProductWeight::findOrFail($id)->update($data);
        Toastr::success('Product weight updated successfully!');
        return redirect('admin/product-weight');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProductWeight::destroy($id);
        Toastr::success('Product weight has been deleted!');
        return redirect('admin/product-weight');
    }
}
