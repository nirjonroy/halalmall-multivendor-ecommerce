<?php

namespace App\Http\Controllers\Seller;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Shop;
use App\Model\Seller;
use App\Model\Category;
use App\Model\Product;
use App\PopularCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function view()
    {
        $shop = Shop::where(['seller_id' => auth('seller')->id()])->first();
        if (isset($shop) == false) {
            DB::table('shops')->insert([
                'seller_id' => auth('seller')->id(),
                'name' => auth('seller')->user()->f_name,
                'address' => '',
                'contact' => auth('seller')->user()->phone,
                'image' => 'def.png',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $shop = Shop::where(['seller_id' => auth('seller')->id()])->first();
            
                            
            
        }
        $shop_cat = Shop::join('categories', 'categories.id', 'shops.category_id')
                            
                            ->select('categories.name as cat_name')
                            ->first();
        $shop_cat2 = Shop::join('categories', 'categories.id', 'shops.category_id2')
                            
                            ->select('categories.name as cat_name2')
                            ->first();    
        $shop_cat3 = Shop::join('categories', 'categories.id', 'shops.category_id3')
                            
                            ->select('categories.name as cat_name3')
                            ->first();
        $shop_cat4 = Shop::join('categories', 'categories.id', 'shops.category_id4')
                            
                            ->select('categories.name as cat_name4')
                            ->first();                                                          
        // dd($shop_cat);
        $data = Seller::where('id', auth('seller')->id())->first();

        return view('seller-views.shop.shopInfo', compact('shop','data', 'shop_cat', 'shop_cat2', 'shop_cat3', 'shop_cat4'));
    }

    public function edit($id)
    {
        
        $seller_id = auth('seller')->id();
        $popularCategories = PopularCategory::with("category")->where('seller_id', $seller_id)->get();
        $categories = Category::all();
        
        
        $shop = Shop::where(['seller_id' =>  auth('seller')->id()])->first();
        $product_ids = Product::active()
        ->when($id == 0, function ($query) {
            return $query->where(['added_by' => 'admin']);
        })
        ->when($id != 0, function ($query) use ($id) {
            return $query->where(['added_by' => 'seller'])
                ->where('user_id', $id);
        })
        ->pluck('id')->toArray();
        
                $products = Product::whereIn('id', $product_ids)->paginate(12);

                $category_info = [];
                foreach ($products as $product) {
                    array_push($category_info, $product['category_ids']);
                }

                $category_info_decoded = [];
                foreach ($category_info as $info) {
                    array_push($category_info_decoded, json_decode($info));
                }



                $category_ids = [];
                foreach ($category_info_decoded as $decoded) {
                    foreach ($decoded as $info) {
                        array_push($category_ids, $info->id);
                    }
                }

                $categories = [];
                foreach ($category_ids as $category_id) {
                    $category = Category::with(['childes.childes'])->where('position', 0)->find($category_id);
                    if ($category != null) {
                        array_push($categories, $category);
                    }
                }
            $categories = array_unique($categories);
        $category = Category::all();
        // dd($category);
        return view('seller-views.shop.edit', compact('shop', 'category', 'categories', 'popularCategories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'banner'      => 'mimes:png,jpg,jpeg|max:2048',
            'image'       => 'mimes:png,jpg,jpeg|max:2048',
        ], [
            'banner.mimes'   => 'Banner image type jpg, jpeg or png',
            'banner.max'     => 'Banner Maximum size 2MB',
            'image.mimes'    => 'Image type jpg, jpeg or png',
            'image.max'      => 'Image Maximum size 2MB',
        ]);

        $shop = Shop::find($id);
        $shop->name = $request->name;
        $shop->address = $request->address;
        $shop->contact = $request->contact;
        $shop->category_id = $request->category_id;
        $shop->category_id3 = $request->category_id3;
        $shop->category_id4 = $request->category_id4;
        $shop->nid = $request->nid;
        if ($request->image) {
            $shop->image = ImageManager::update('shop/', $shop->image, 'png', $request->file('image'));
        }

        if ($request->banner) {
            $shop->banner = ImageManager::update('shop/banner/', $shop->banner, 'png', $request->file('banner'));
        } 
        
        if ($request->side_banner) {
            $shop->side_banner = ImageManager::update('shop/banner/', $shop->side_banner, 'png', $request->file('side_banner'));
        }

        if ($request->side_banner2) {
            $shop->side_banner2 = ImageManager::update('shop/banner/', $shop->side_banner2, 'png', $request->file('side_banner2'));
        }

        if ($request->side_banner3) {
            $shop->side_banner3 = ImageManager::update('shop/banner/', $shop->side_banner3, 'png', $request->file('side_banner3'));
        }

        if ($request->side_banner4) {
            $shop->side_banner4 = ImageManager::update('shop/banner/', $shop->side_banner4, 'png', $request->file('side_banner4'));
        }

        if ($request->trade_license) {
            $shop->trade_license = ImageManager::update('shop/trade_license/', $shop->trade_license, 'png', $request->file('trade_license'));
        }
        
        if ($request->bank_cheque) {
            $shop->bank_cheque = ImageManager::update('shop/bank_cheque/', $shop->bank_cheque, 'png', $request->file('bank_cheque'));
        }

        $shop->save();

        Toastr::info('Shop updated successfully!');
        return redirect()->route('seller.shop.view');
    }

}
