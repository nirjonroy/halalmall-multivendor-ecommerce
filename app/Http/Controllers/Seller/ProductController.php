<?php

namespace App\Http\Controllers\Seller;

use App\CPU\BackEndHelper;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Brand;
use App\Model\BusinessSetting;
use App\Model\Category;
use App\Model\Color;
use App\Model\DealOfTheDay;
use App\Model\FlashDealProduct;
use App\Model\Product;
use App\Model\ProductWeight;
use App\Model\ProductVariationImage;
use App\Model\Review;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Model\Cart;
use function App\CPU\translate;
use Image;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function add_new()
    {
        $cat = Category::where(['parent_id' => 0])->get();
        $br = Brand::orderBY('name', 'ASC')->get();
        $brand_setting = BusinessSetting::where('type', 'product_brand')->first()->value;
        $digital_product_setting = BusinessSetting::where('type', 'digital_product')->first()->value;
        $weights = ProductWeight::all();
        return view('seller-views.product.add-new', compact('cat', 'br', 'brand_setting', 'digital_product_setting', 'weights'));
    }

    public function status_update(Request $request)
    {
        if ($request['status'] == 0) {
            Product::where(['id' => $request['id'], 'added_by' => 'seller', 'user_id' => \auth('seller')->id()])->update([
                'status' => $request['status'],
            ]);
            return response()->json([
                'success' => 1,
            ], 200);
        } elseif ($request['status'] == 1) {
            if (Product::find($request['id'])->request_status == 1) {
                Product::where(['id' => $request['id']])->update([
                    'status' => $request['status'],
                ]);
                return response()->json([
                    'success' => 1,
                ], 200);
            } else {
                return response()->json([
                    'success' => 0,
                ], 200);
            }
        }
    }


    public function featured_status(Request $request)
    {
        if ($request->ajax()) {
            $product = Product::find($request->id);
            $product->featured_status = $request->status;
            $product->save();
            $data = $request->status;
            return response()->json($data);
        }
    }
    
    public function store_thumb_image(Request $request) {
        
        $image = $request->file('file');
        $image = Image::make($image)->resize(200, 200);
            
        if ($image != null) {
        $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';

        if (!Storage::disk('public')->exists('product/thumbnail/')) {
            Storage::disk('public')->makeDirectory('product/thumbnail/');
        }

        // Check if the image instance is valid
      
        // Save the image data directly, no need for file_get_contents
        Storage::disk('public')->put('product/thumbnail/' . $imageName, (string) $image->encode('png'));
    
        } else {
            $imageName = 'def.png';
        }
        
        return response()->json($imageName);
    }
    
    public function store_image(Request $request) {
        
        $image = $request->file('file');
        $image = Image::make($image)->resize(800, 800);
        if ($image != null) {
        $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
        if (!Storage::disk('public')->exists('product/')) {
            Storage::disk('public')->makeDirectory('product/');
        }
        Storage::disk('public')->put('product/' . $imageName, (string) $image->encode('png'));
        } else {
            $imageName = 'def.png';
        }
        return response()->json($imageName);
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name'                  => 'required',
            'category_id'           => 'required',
            'product_type'          => 'required',
            'digital_product_type'  => 'required_if:product_type,==,digital',
            'digital_file_ready'    => 'required_if:digital_product_type,==,ready_product|mimes:jpg,jpeg,png,gif,zip,pdf',
            'unit'                  => 'required_if:product_type,==,physical',
            'images'                => 'required',
            // 'image'                 => 'required',
            'tax'                   => 'required|min:0',
            'unit_price'            => 'required|numeric|min:1',
            'purchase_price'        => 'required|numeric|min:1',
            'discount'              => 'required|gt:-1',
            'shipping_cost'         => 'required_if:product_type,==,physical|gt:-1',
            'code'                  => 'required|numeric|min:1|digits_between:6,20|unique:products',
            'minimum_order_qty'     => 'required|numeric|min:1',
            'weight_id'             => 'required',

        ], [
            'name.required'                     => 'Product name is required!',
            'category_id.required'              => 'category  is required!',
            'images.required'                   => 'Product images is required!',
            // 'image.required'                    => 'Product thumbnail is required!',
            'unit.required_if'                  => 'Unit is required!',
            'code.min'                          => 'The code must be positive!',
            'code.digits_between'               => 'The code must be minimum 6 digits!',
            'minimum_order_qty.required'        => 'The minimum order quantity is required!',
            'minimum_order_qty.min'             => 'The minimum order quantity must be positive!',
            'digital_file_ready.required_if'    => 'Ready product upload is required!',
            'digital_file_ready.mimes'          => 'Ready product upload must be a file of type: pdf, zip, jpg, jpeg, png, gif.',
            'digital_product_type.required_if'  => 'Digital product type is required!',
            'shipping_cost.required_if'         => 'Shipping Cost is required!',
            'weight_id.required'               => 'Please choose any weight!',
        ]);

        $brand_setting = BusinessSetting::where('type', 'product_brand')->first()->value;
        if ($brand_setting && empty($request->brand_id)) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'brand_id', 'Brand is required!'
                );
            });
        }

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['unit_price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['unit_price'] <= $dis) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'unit_price', 'Discount can not be more or equal to the price!'
                );
            });
        }

        if (is_null($request->name[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'name', 'Name field is required!'
                );
            });
        }

        $product = new Product();
        $product->user_id = auth('seller')->id();
        $product->added_by = "seller";
        $product->name = $request->name[array_search('en', $request->lang)];
        $product->slug = Str::slug($request->name[array_search('en', $request->lang)], '-') . '-' . Str::random(6);

        $category = [];
        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        } else {
            array_push($category, [
                'id' => 0,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        } else {
            array_push($category, [
                'id' => 0,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        } else {
            array_push($category, [
                'id' => 0,
                'position' => 3,
            ]);
        }

        $product->category_ids          = json_encode($category);
        $product->category_id          = $request->category_id;

        $product->brand_id              = $request->brand_id;
        $product->unit                  = $request->product_type == 'physical' ? $request->unit : null;
        $product->digital_product_type  = $request->product_type == 'digital' ? $request->digital_product_type : null;
        $product->product_type          = $request->product_type;
        $product->code                  = $request->code;
        $product->minimum_order_qty     = $request->minimum_order_qty;
        $product->details               = $request->description[array_search('en', $request->lang)];
        //$product->short_desc            = $request->short_desc[array_search('en', $request->lang)];
        $product->in_the_box            = $request->in_the_box;
        $product->warranty_type         = $request->warranty_type;
        $product->warranty              = $request->warranty;
        $product->warranty_policy       = $request->warranty_policy;
        $product->weight_id             = $request->weight_id;
        $product->length                = $request->length;
        $product->height                = $request->height;
        $product->width                 = $request->width;

        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $product->colors = $request->product_type == 'physical' ? json_encode($request->colors) : json_encode([]);
        } else {
            $colors = [];
            $product->colors = $request->product_type == 'physical' ? json_encode($colors) : json_encode([]);
        }
        $choice_options = [];
        if ($request->has('choice')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;
                $item['name'] = 'choice_' . $no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', $request[$str]));
                array_push($choice_options, $item);
            }
        }
        $product->choice_options = $request->product_type == 'physical' ? json_encode($choice_options) : json_encode([]);
        //combinations start
        $options = [];
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        //Generates the combinations of customer choice options
        $combinations = Helpers::combinations($options);
        $variations = [];
        $stock_count = 0;
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $k => $item) {
                    if ($k > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                            $color_name = Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                $item = [];
                $item['type'] = $str;
                $item['price'] = Convert::usd(abs($request['price_' . str_replace('.', '_', $str)]));
                $item['sku'] = $request['sku_' . str_replace('.', '_', $str)];
                $item['qty'] = abs($request['qty_' . str_replace('.', '_', $str)]);
                array_push($variations, $item);
                $stock_count += $item['qty'];
            }
        } else {
            $stock_count = (integer)$request['current_stock'];
        }

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        //combinations end
        $product->variation      = $request->product_type == 'physical' ? json_encode($variations) : json_encode([]);
        $product->unit_price     = Convert::usd($request->unit_price);
        $product->purchase_price = Convert::usd($request->purchase_price);
        $product->tax            = $request->tax;
        $product->tax_type       = $request->tax_type;
        $product->discount       = $request->discount_type == 'flat' ? Convert::usd($request->discount) : $request->discount;
        $product->discount_type  = $request->discount_type;
        $product->attributes     = $request->product_type == 'physical' ? json_encode($request->choice_attributes) : json_encode([]);
        $product->current_stock  = $request->product_type == 'physical' ? abs($stock_count) : 0;
        $product->video_provider = 'youtube';
        $product->video_url      = $request->video_link;
        $product->request_status = Helpers::get_business_settings('new_product_approval')==1?0:1;
        $product->status         = 0;
        $product->shipping_cost  = $request->product_type == 'physical' ? Convert::usd($request->shipping_cost) : 0;
        $product->multiply_qty   = ($request->product_type == 'physical') ? ($request->multiplyQTY=='on'?1:0) : 0;

        if ($request->ajax()) {
            return response()->json([], 200);
        } else {
            
            $img_value = $request->products_image_name[0];
            
            $image_value_name =  explode(",",$img_value);
                
                foreach($image_value_name as $img) {
                    $product_img[] = $img;
                }
                
            $product->images = json_encode($product_img);
            
            // foreach($request->products_image_name as $key => $pro_img) {
            //     $img_value = $pro_img;
            //     $image_value_name =  explode(",",$img_value);
            
            //     foreach($image_value_name as $img) {
            //         $product_img[] = $img;
            //     }
            // }
            
            // $product->images = json_encode($product_img);
            
            $product->thumbnail = $request->product_thumb_image_name[0];
            // $product->thumbnail = ImageManager::upload('product/thumbnail/', 'png', $request->file('image'));

            if($request->product_type == 'digital' && $request->digital_product_type == 'ready_product') {
                $product->digital_file_ready = ImageManager::upload('product/digital-product/', $request->digital_file_ready->getClientOriginalExtension(), $request->digital_file_ready);
            }

            $product->meta_title = $request->meta_title;
            $product->meta_description = $request->meta_description;
            $product->meta_image = ImageManager::upload('product/meta/', 'png', $request->meta_image);

            $product->save();
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Model\Product',
                        'translationable_id' => $product->id,
                        'locale' => $key,
                        'key' => 'name',
                        'value' => $request->name[$index],
                    ));
                }
                if ($request->description[$index] && $key != 'en') {
                    array_push($data, array(
                        'translationable_type' => 'App\Model\Product',
                        'translationable_id' => $product->id,
                        'locale' => $key,
                        'key' => 'description',
                        'value' => $request->description[$index],
                    ));
                }
            }

            Translation::insert($data);
            $variation_images = [];

            if(!empty($request->products_variation_image_name))
          {
              foreach($request->products_variation_image_name as $key => $pro_variation_img) {
                $variation_img_value = $pro_variation_img;
                $variation_image_value_name =  explode(",",$variation_img_value);
                
               if (!empty($variation_image_value_name)) {
                    $variationImages = [];
                    
                foreach ($variation_image_value_name as $key => $imageName) {
                    $variationImages[] = [
                        'variation' => $request->variation_code[$key],
                        'image' =>     $imageName
                    ];
                }
                
                    // Use createMany to create multiple related records
                    $product->variation_images()->createMany($variationImages);
                }
            Toastr::success(translate('Product added successfully!'));
            return redirect()->route('seller.product.list', ['in_house']);
               
               
            }
          } else {
              Toastr::success(translate('Product added successfully!'));
            return redirect()->route('seller.product.list', ['in_house']);
          }
        }
    }

    function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $products = Product::where(['added_by' => 'seller', 'user_id' => \auth('seller')->id()])
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('name', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            $products = Product::where(['added_by' => 'seller', 'user_id' => \auth('seller')->id()]);
        }
        $products = $products->orderBy('id', 'DESC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('seller-views.product.list', compact('products', 'search'));
    }

    public function stock_limit_list(Request $request, $type)
    {
        $stock_limit = Helpers::get_business_settings('stock_limit');
        $sort_oqrderQty = $request['sort_oqrderQty'];
        $query_param = $request->all();
        $search = $request['search'];
        $pro = Product::where(['added_by' => 'seller', 'product_type'=>'physical', 'user_id' => auth('seller')->id()])
            ->where('request_status',1)
            ->when($request->has('status') && $request->status != null, function ($query) use ($request) {
                $query->where('request_status', $request->status);
            });

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $pro = $pro->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }

        $request_status = $request['status'];

        $pro = $pro->withCount('order_details')->when($request->sort_oqrderQty == 'quantity_asc', function ($q) use ($request) {
            return $q->orderBy('current_stock', 'asc');
        })
            ->when($request->sort_oqrderQty == 'quantity_desc', function ($q) use ($request) {
                return $q->orderBy('current_stock', 'desc');
            })
            ->when($request->sort_oqrderQty == 'order_asc', function ($q) use ($request) {
                return $q->orderBy('order_details_count', 'asc');
            })
            ->when($request->sort_oqrderQty == 'order_desc', function ($q) use ($request) {
                return $q->orderBy('order_details_count', 'desc');
            })
            ->when($request->sort_oqrderQty == 'default', function ($q) use ($request) {
                return $q->orderBy('id');
            })->where('current_stock', '<', $stock_limit);


        $products = $pro->orderBy('id', 'DESC')->paginate(Helpers::pagination_limit())->appends(['status' => $request['status']])->appends($query_param);
        return view('seller-views.product.stock-limit-list', compact('products', 'search', 'request_status', 'sort_oqrderQty'));
    }

    /**
     * Product total stock report export by excel
     * @param Request $request
     * @return string|\Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function stock_limit_export(Request $request){

        $sort = $request['sort'] ?? 'ASC';

        $products = Product::when(empty($request['seller_id']) || $request['seller_id'] == 'all',function ($query){
            $query->whereIn('added_by', ['admin', 'seller']);
        })
            ->when($request['seller_id'] == 'in_house',function ($query){
                $query->where(['added_by' => 'admin']);
            })
            ->when($request['seller_id'] != 'in_house' && isset($request['seller_id']) && $request['seller_id'] != 'all',function ($query) use($request){
                $query->where(['added_by' => 'seller', 'user_id' => $request['seller_id']]);
            })
            ->orderBy('current_stock', $sort)->get();

        $data = array();
        foreach($products as $product){
            $data[] = array(
                'Product Name'   => $product->name,
                'Date'           => date('d M Y',strtotime($product->created_at)),
                'Total Stock'    => $product->current_stock,
            );
        }

        return (new FastExcel($data))->download('total_product_stock.xlsx');
    }

    public function update_quantity(Request $request)
    {
        $variations = [];
        $stock_count = $request['current_stock'];
        if ($request->has('type')) {
            foreach ($request['type'] as $key => $str) {
                $item = [];
                $item['type'] = $str;
                $item['price'] = BackEndHelper::currency_to_usd(abs($request['price_' . str_replace('.', '_', $str)]));
                $item['sku'] = $request['sku_' . str_replace('.', '_', $str)];
                $item['qty'] = abs($request['qty_' . str_replace('.', '_', $str)]);
                array_push($variations, $item);
            }
        }

        $product = Product::find($request['product_id']);
        if ($stock_count >= 0) {
            $product->current_stock = $stock_count;
            $product->variation = json_encode($variations);
            $product->save();
            Toastr::success(\App\CPU\translate('product_quantity_updated_successfully!'));
            return back();
        } else {
            Toastr::warning(\App\CPU\translate('product_quantity_can_not_be_less_than_0_!'));
            return back();
        }
    }

    public function get_categories_click(Request $request) {
        $res = '';
        $ids = '';
        $sub_categories = Category::where('parent_id', $request->id)->get();

        foreach ($sub_categories as $key => $s_cat) {
            $res .= '<option value="'. $s_cat->id .'">'. $s_cat->name .'</option>';
            $ids .= 'sub-category-select';
        }

        return response()->json([
            'select_tag' => $res,
            'id' => $ids
        ]);

    }

    public function get_sub_categories_click(Request $request) {
        $res = '';
        $ids = '';
        $sub_sub_categories = Category::where('parent_id', $request->id)->get();

        foreach ($sub_sub_categories as $key => $s_s_cat) {
            $res .= '<option value="'. $s_s_cat->id .'">'. $s_s_cat->name .'</option>';
            $ids .= 'sub-sub-category-select';
        }

        return response()->json([
            'select_tag' => $res,
            'id' => $ids
        ]);

    }

    public function get_sub_sub_categories_click(Request $request) {
        $res1 = '';
        $ids1 = '';
        $sub_sub_categories = Category::where('parent_id', $request->id)->get();

        foreach ($sub_sub_categories as $key => $s_s_cat) {
            $res .= '<option value="'. $s_s_cat->id .'">'. $s_s_cat->name .'</option>';
            $ids .= 'sub-sub-category-select';
        }

        return response()->json([
            'select_tag' => $res1,
            'id' => $ids1
        ]);

    }



    public function get_categories(Request $request)
    {
       $res = '';
       $ress = '';
       $resss = '';
       $idss = '';
       $idsss = '';
       $idst = '';
       $idst2 = '';
       $cat_id = [];
       $all_cats = Category::all();
       $all_cats_original = Category::where('parent_id', 0)->get();

    foreach($all_cats as $key=>$cat){
        $cat_id[]=$cat->id;
    }

       $single_cat = Category::find($request->id);

       if($single_cat->parent_id == 0) {
           $res .= '<option value="'. $single_cat->id .'">'. $single_cat->name .'</option>';
           foreach($all_cats_original as $key=>$cat){
            $res .= '<option value="'. $cat->id .'">'. $cat->name .'</option>';
        }
           $idss .= 'category-select';
           return response()->json([
            'select_tag' => $res,
            'id' => $idss,
            'sign' => 'cat'
        ]);
       }  else {
            if (in_array($single_cat->parent_id, $cat_id)) {
                $chk_cat = Category::find($single_cat->parent_id);

                if (in_array($chk_cat->parent_id, $cat_id)) {
                    $chk2_cat = Category::find($chk_cat->parent_id);
                    $res .= '<option value="'. $single_cat->id .'">'. $single_cat->name .'</option>';
                    $idss .= 'sub-sub-category-select';
                    $ress .= '<option value="'. $chk_cat->id .'">'. $chk_cat->name .'</option>';
                    $idst .= 'sub-category-select';
                    $resss .= '<option value="'. $chk2_cat->id .'">'. $chk2_cat->name .'</option>';
                    foreach($all_cats_original as $key=>$cat){
                        $resss .= '<option value="'. $cat->id .'">'. $cat->name .'</option>';
                    }
                    $idst2 .= 'category-select';

                    return response()->json([
                        'select_tag' => $res,
                        'id' => $idss,
                        'idt' => $idst,
                        'idt2' => $idst2,
                        'select_tagvia' => $ress,
                        'select_tagvia2' => $resss,
                        'sign' => 'doublesub-cat'
                    ]);


                } else {
                    $ress .= '<option value="'. $chk_cat->id .'">'. $chk_cat->name .'</option>';
                    foreach($all_cats_original as $key=>$cat){
                        $ress .= '<option value="'. $cat->id .'">'. $cat->name .'</option>';
                    }
                    $res .= '<option value="'. $single_cat->id .'">'. $single_cat->name .'</option>';
                    $idss .= 'sub-category-select';
                    $idsss .= 'category-select';

                    return response()->json([
                        'select_tag' => $res,
                        'id' => $idss,
                        'idt' => $idsss,
                        'select_tagvia' => $ress,
                        'sign' => 'sub-cat'
                    ]);
                }
            }
       }

    }

    public function get_variations(Request $request)
    {
        $product = Product::find($request['id']);
        return response()->json([
            'view' => view('seller-views.product.partials._update_stock', compact('product'))->render()
        ]);
    }

    public function sku_combination(Request $request)
    {
        $options = [];
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name[array_search('en', $request->lang)];

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = Helpers::combinations($options);

        $colors = $request->colors;

        return response()->json([
            'view' => view('seller-views.product.partials._sku_combinations', compact('colors','combinations', 'unit_price', 'colors_active', 'product_name'))->render(),
        ]);
    }

    public function rec_active($id){
        $data = Product::where('id', $id)
                    ->update(['is_recomended' => 1]);
                // dd($data);
                Toastr::success('Product Recomended active successfully!');
                return back();       

    }

    public function rec_inactive($id){
        $data = Product::where('id', $id)
                    ->update(['is_recomended' => 0]);
                // dd($data);
                Toastr::success('Product Recomended inactive successfully!');
                return back();      

    }

    public function edit($id)
    {
        $product = Product::withoutGlobalScopes()->with('translations', 'variation_images')->find($id);
        $product_category = json_decode($product->category_ids);
        $product->colors = json_decode($product->colors);
        $categories = Category::where(['parent_id' => 0])->get();

        $sub_cat_test = $product_category[1]->id;
        $sub_sub_cat_test = $product_category[2]->id;

        $sub_cat = [];
        $sub_sub_cat = [];
        $cat_id = [];
        $all_cats = Category::all();
        foreach($all_cats as $key=>$cat){
        $cat_id[]=$cat->id;
    }
    
    foreach($all_cats as $key => $value) {

        if($value->parent_id == 0) {

        } else {
            if(in_array($value->parent_id,$cat_id)) {
            $chk_cat = Category::find($value->parent_id);
            if (in_array($chk_cat->parent_id, $cat_id)) {
                $sub_sub_cat[] = $value->id;
            } else {
                $sub_cat[] = $value->id;

            }
        }
        }
    }

        $br = Brand::orderBY('name', 'ASC')->get();
        $brand_setting = BusinessSetting::where('type', 'product_brand')->first()->value;
        $digital_product_setting = BusinessSetting::where('type', 'digital_product')->first()->value;
        $weights = ProductWeight::all();
        return view('seller-views.product.edit', compact('categories','sub_cat_test', 'sub_sub_cat_test','sub_cat','sub_sub_cat' ,'br', 'product', 'product_category', 'brand_setting', 'digital_product_setting', 'weights'));

    }

    public function update(Request $request, $id)
    {
        $product = Product::with('variation_images')->find($id);
        $validator = Validator::make($request->all(), [
            'name'                  => 'required',
            'category_id'           => 'required',
            'product_type'          => 'required',
            'digital_product_type'  => 'required_if:product_type,==,digital',
            'digital_file_ready'    => 'mimes:jpg,jpeg,png,gif,zip,pdf',
            'unit'                  => 'required_if:product_type,==,physical',
            'tax'                   => 'required|min:0',
            'unit_price'            => 'required|numeric|min:1',
            'purchase_price'        => 'required|numeric|min:1',
            'discount'              => 'required|gt:-1',
            'shipping_cost'         => 'required_if:product_type,==,physical|gt:-1',
            'code'                  => 'required|numeric|min:1|digits_between:6,20|unique:products,code,'.$product->id,
            'minimum_order_qty'     => 'required|numeric|min:1',
            'weight_id'             => 'required',
        ], [
            'name.required'                     => 'Product name is required!',
            'category_id.required'              => 'Category is required!',
            'unit.required_if'                  => 'Unit is required!',
            'code.min'                          => 'Code must be positive!',
            'code.digits_between'               => 'Code must be minimum 6 digits!',
            'minimum_order_qty.required'        => 'Minimum order quantity is required!',
            'minimum_order_qty.min'             => 'Minimum order quantity must be positive!',
            'digital_file_ready.mimes'          => 'Ready product upload must be a file of type: pdf, zip, jpg, jpeg, png, gif.',
            'digital_product_type.required_if'  => 'Digital product type is required!',
            'shipping_cost.required_if'         => 'Shipping Cost is required!',
            'weight_id.required'               => 'Please choose any weight!',
        ]);

        $brand_setting = BusinessSetting::where('type', 'product_brand')->first()->value;
        if ($brand_setting && empty($request->brand_id)) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'brand_id', 'Brand is required!'
                );
            });
        }

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['unit_price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['unit_price'] <= $dis) {
            $validator->after(function ($validator) {
                $validator->errors()->add('unit_price', 'Discount can not be more or equal to the price!');
            });
        }

        if (is_null($request->name[array_search('en', $request->lang)])) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'name', 'Name field is required!'
                );
            });
        }

        $product->name = $request->name[array_search('en', $request->lang)];

        $category = [];
        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        } else {
            array_push($category, [
                'id' => 0,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        } else {
            array_push($category, [
                'id' => 0,
                'position' => 3,
            ]);
        }

        $product->product_type          = $request->product_type;
        $product->category_ids          = json_encode($category);
        $product->category_id          = $request->category_id;
        $product->brand_id              = isset($request->brand_id) ? $request->brand_id : null;
        $product->unit                  = $request->product_type == 'physical' ? $request->unit : null;
        $product->digital_product_type  = $request->product_type == 'digital' ? $request->digital_product_type : null;
        $product->details               = $request->description[array_search('en', $request->lang)];
       // $product->short_desc            = $request->short_desc[array_search('en', $request->lang)];
        $product->in_the_box            = $request->in_the_box;
        $product->warranty_type         = $request->warranty_type;
        $product->warranty              = $request->warranty;
        $product->warranty_policy       = $request->warranty_policy;
        $product->weight_id             = $request->weight_id;
        $product->length                = $request->length;
        $product->height                = $request->height;
        $product->width                 = $request->width;
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $product->colors = $request->product_type == 'physical' ? json_encode($request->colors) : json_encode([]);
        } else {
            $colors = [];
            $product->colors = $request->product_type == 'physical' ? json_encode($colors) : json_encode([]);
        }
        $choice_options = [];
        if ($request->has('choice')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;
                $item['name'] = 'choice_' . $no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', $request[$str]));
                array_push($choice_options, $item);
            }
        }
        $product->choice_options = $request->product_type == 'physical' ? json_encode($choice_options) : json_encode([]);
        $variations = [];
        //combinations start
        $options = [];
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        //Generates the combinations of customer choice options
        $combinations = Helpers::combinations($options);
        $variations = [];
        $stock_count = 0;
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $k => $item) {
                    if ($k > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                            $color_name = Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                $item = [];
                $item['type'] = $str;
                $item['price'] = Convert::usd(abs($request['price_' . str_replace('.', '_', $str)]));
                $item['sku'] = $request['sku_' . str_replace('.', '_', $str)];
                $item['qty'] = abs($request['qty_' . str_replace('.', '_', $str)]);
                array_push($variations, $item);
                $stock_count += $item['qty'];
            }
        } else {
            $stock_count = (integer)$request['current_stock'];
        }

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        //combinations end
        $product->variation         = $request->product_type == 'physical' ? json_encode($variations) : json_encode([]);
        $product->unit_price        = Convert::usd($request->unit_price);
        $product->purchase_price    = Convert::usd($request->purchase_price);
        $product->tax               = $request->tax;
        $product->code              = $request->code;
        $product->minimum_order_qty = $request->minimum_order_qty;
        $product->tax_type          = $request->tax_type;
        $product->discount          = $request->discount_type == 'flat' ? Convert::usd($request->discount) : $request->discount;
        $product->attributes        = $request->product_type == 'physical' ? json_encode($request->choice_attributes) : json_encode([]);
        $product->discount_type     = $request->discount_type;
        $product->current_stock     = $request->product_type == 'physical' ? abs($stock_count) : 0;
        $product->shipping_cost     = $request->product_type == 'physical' ? (Helpers::get_business_settings('product_wise_shipping_cost_approval')==1?$product->shipping_cost:Convert::usd($request->shipping_cost)) : 0;
        $product->multiply_qty      = ($request->product_type == 'physical') ? ($request->multiplyQTY=='on'?1:0) : 0;

        if(Helpers::get_business_settings('product_wise_shipping_cost_approval')==1 && $product->shipping_cost != Convert::usd($request->shipping_cost))
        {
            $product->temp_shipping_cost = Convert::usd($request->shipping_cost);
            $product->is_shipping_cost_updated = 0;

        }

        $product->video_provider = 'youtube';
        $product->video_url = $request->video_link;
        if ($product->request_status == 2) {
            $product->request_status = 0;
        }

        if ($request->ajax()) {
            return response()->json([], 200);
        } else {
            
            $img_value = $request->products_image_name[0];
            if($img_value != null)
            {
                $image_value_name =  explode(",",$img_value);
                foreach($image_value_name as $img) {
                    $product_img[] = $img;
                }  
            }
            
            // dd(json_encode($product_img));
            $product_images = json_decode($request->products_previous_image_name[0]);
            $ano_img = [];
            foreach($product_images as $p_img) {
                $product_img[] = $p_img;
            }
            
            $product->images = json_encode($product_img);
            
            
            
            $product_thumb_image_name = $request->product_thumb_image_name[0];
            
            if($product_thumb_image_name == null) {
                $product->thumbnail = $request->product_previous_thumb_image_name[0];
            } else {
                $product->thumbnail = $request->product_thumb_image_name[0];
            }

            if ($request->file('image')) {
                $product->thumbnail = ImageManager::update('product/thumbnail/', $product->thumbnail, 'png', $request->file('image'));
            }

            if($request->product_type == 'digital') {
                if($request->digital_product_type == 'ready_product' && $request->hasFile('digital_file_ready')){
                    $product->digital_file_ready = ImageManager::update('product/digital-product/', $product->digital_file_ready, $request->digital_file_ready->getClientOriginalExtension(), $request->file('digital_file_ready'));
                }elseif(($request->digital_product_type == 'ready_after_sell') && $product->digital_file_ready){
                    ImageManager::delete('product/digital-product/'.$product->digital_file_ready);
                    $product->digital_file_ready = null;
                }
            }elseif($request->product_type == 'physical' && $product->digital_file_ready){
                ImageManager::delete('product/digital-product/'.$product->digital_file_ready);
                $product->digital_file_ready = null;
            }

            $product->meta_title = $request->meta_title;
            $product->meta_description = $request->meta_description;
            if ($request->file('meta_image')) {
                $product->meta_image = ImageManager::update('product/meta/', $product->meta_image, 'png', $request->file('meta_image'));
            }

            $product->save();
            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Model\Product',
                            'translationable_id' => $product->id,
                            'locale' => $key,
                            'key' => 'name'],
                        ['value' => $request->name[$index]]
                    );
                }
                if ($request->description[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Model\Product',
                            'translationable_id' => $product->id,
                            'locale' => $key,
                            'key' => 'description'],
                        ['value' => $request->description[$index]]
                    );
                }
            }

            $variation_images = [];
            if(isset($request->line_id))
            {
                $delete_lines = ProductVariationImage::where('product_id', $id)
                                        ->whereNotIn('id', $request->line_id)
                                        ->get();

                if($delete_lines->count())
                {
                    foreach($delete_lines as $key => $line)
                    {
                        ImageManager::delete('product/variation/'.$line->image);
                        $line->delete();
                    }
                }

                // else
                // {
                //     foreach($product->variation_images as $key => $line)
                //     {
                //         ImageManager::delete('product/variation/'.$line->image);
                //         $line->delete();
                //     }
                // }
            }

            if(isset($request->variation_image))
            {
                foreach($request->variation_image as $key => $image)
                {
                    if(isset($request->product_id[$key]))
                    {
                        $line = ProductVariationImage::find($request->product_id[$key]);
                        ImageManager::delete('product/variation/'.$line->image);
                        $image_name = ImageManager::upload('product/variation/', 'png', $image);
                        $line->update(['image'=>$image_name]);

                    }
                    else{
                        $image_name = ImageManager::upload('product/variation/', 'png', $image);
                        $variation_images[] = [
                            'variation' => $request->variation_code[$key],
                            'image' => $image_name
                        ];
                    }
                }

            }

            if(!empty($variation_images))
            {
                $product->variation_images()->createMany($variation_images);
            }

            Toastr::success('Product updated successfully.');
            return back();
        }
    }

    public function view($id)
    {
        $product = Product::with(['reviews'])->where(['id' => $id])->first();
        $reviews = Review::where(['product_id' => $id])->paginate(Helpers::pagination_limit());
        return view('seller-views.product.view', compact('product', 'reviews'));
    }

    public function remove_image(Request $request)
    {
        ImageManager::delete('/product/' . $request['image']);
        $product = Product::find($request['id']);
        $array = [];
        if (count(json_decode($product['images'])) < 2) {
            Toastr::warning('You cannot delete all images!');
            return back();
        }
        foreach (json_decode($product['images']) as $image) {
            if ($image != $request['name']) {
                array_push($array, $image);
            }
        }
        Product::where('id', $request['id'])->update([
            'images' => json_encode($array),
        ]);
        Toastr::success('Product image removed successfully!');
        return back();
    }

    public function delete($id)
    {
        $product = Product::with('variation_images')->find($id);
        Cart::where('product_id', $product->id)->delete();
        foreach (json_decode($product['images'], true) as $image) {
            ImageManager::delete('/product/' . $image);
        }
        ImageManager::delete('/product/thumbnail/' . $product['thumbnail']);

        foreach($product->variation_images as $key => $line)
        {
            ImageManager::delete('product/variation/'.$line->image);
            $line->delete();
        }

        $product->delete();
        FlashDealProduct::where(['product_id' => $id])->delete();
        DealOfTheDay::where(['product_id' => $id])->delete();
        Toastr::success('Product removed successfully!');
        return back();
    }

    public function bulk_import_index()
    {
        return view('seller-views.product.bulk-import');
    }

    public function bulk_import_data(Request $request)
    {
        try {
            $collections = (new FastExcel)->import($request->file('products_file'));
        } catch (\Exception $exception) {
            Toastr::error('You have uploaded a wrong format file, please upload the right file.');
            return back();
        }
        $data = [];
        $col_key = ['name', 'category_id', 'sub_category_id', 'sub_sub_category_id', 'brand_id', 'unit', 'min_qty', 'refundable', 'youtube_video_url', 'unit_price', 'purchase_price', 'tax', 'discount', 'discount_type', 'current_stock', 'details', 'thumbnail'];
        $skip = ['youtube_video_url', 'details', 'thumbnail'];
        foreach ($collections as $collection) {
            foreach ($collection as $key => $value) {
                if ($key!="" && !in_array($key, $col_key)) {
                    Toastr::error('Please upload the correct format file.');
                    return back();
                }

                if ($key!="" && $value === "" && !in_array($key, $skip)) {
                    Toastr::error('Please fill ' . $key . ' fields');
                    return back();
                }
            }

            $thumbnail = explode('/', $collection['thumbnail']);

            array_push($data, [
                'name' => $collection['name'],
                'slug' => Str::slug($collection['name'], '-') . '-' . Str::random(6),
                'category_ids' => json_encode([['id' => (string)$collection['category_id'], 'position' => 1], ['id' => (string)$collection['sub_category_id'], 'position' => 2], ['id' => (string)$collection['sub_sub_category_id'], 'position' => 3]]),
                'brand_id' => $collection['brand_id'],
                'unit' => $collection['unit'],
                'min_qty' => $collection['min_qty'],
                'refundable' => $collection['refundable'],
                'unit_price' => $collection['unit_price'],
                'purchase_price' => $collection['purchase_price'],
                'tax' => $collection['tax'],
                'discount' => $collection['discount'],
                'discount_type' => $collection['discount_type'],
                'current_stock' => $collection['current_stock'],
                'details' => $collection['details'],
                'video_provider' => 'youtube',
                'video_url' => $collection['youtube_video_url'],
                'images' => json_encode(['def.png']),
                'thumbnail' => $thumbnail[1] ?? $thumbnail[0],
                'status' => 0,
                'colors' => json_encode([]),
                'attributes' => json_encode([]),
                'choice_options' => json_encode([]),
                'variation' => json_encode([]),
                'featured_status' => 1,
                'added_by' => 'seller',
                'user_id' => auth('seller')->id(),
            ]);
        }
        DB::table('products')->insert($data);
        Toastr::success(count($data) . ' - Products imported successfully!');
        return back();
    }

    public function bulk_export_data()
    {
        $products = Product::where(['added_by' => 'seller', 'user_id' => \auth('seller')->id()])->get();
        //export from product
        $storage = [];
        foreach ($products as $item) {
            $category_id = 0;
            $sub_category_id = 0;
            $sub_sub_category_id = 0;
            foreach (json_decode($item->category_ids, true) as $category) {
                if ($category['position'] == 1) {
                    $category_id = $category['id'];
                } else if ($category['position'] == 2) {
                    $sub_category_id = $category['id'];
                } else if ($category['position'] == 3) {
                    $sub_sub_category_id = $category['id'];
                }
            }
            $storage[] = [
                'name' => $item->name,
                'category_id' => $category_id,
                'sub_category_id' => $sub_category_id,
                'sub_sub_category_id' => $sub_sub_category_id,
                'brand_id' => $item->brand_id,
                'unit' => $item->unit,
                'min_qty' => $item->min_qty,
                'refundable' => $item->refundable,
                'youtube_video_url' => $item->video_url,
                'unit_price' => $item->unit_price,
                'purchase_price' => $item->purchase_price,
                'tax' => $item->tax,
                'discount' => $item->discount,
                'discount_type' => $item->discount_type,
                'current_stock' => $item->current_stock,
                'details' => $item->details,
                'thumbnail' => 'thumbnail/' . $item->thumbnail

            ];
        }
        return (new FastExcel($storage))->download('products.xlsx');
    }

    public function barcode(Request $request, $id)
    {
        if ($request->limit > 270) {
            Toastr::warning(translate('You can not generate more than 270 barcode'));
             return back();
        }
        $product = Product::findOrFail($id);
        $limit =  $request->limit ?? 4;
        return view('seller-views.product.barcode', compact('product', 'limit'));
    }

    public function categoryByProduct(Request $request)
    {
        $query = $request->get('query');
        if($query != null) {
           $keywords=explode(" ", $query);

           $categories = Category::where(function ($query) use ($keywords) {
                            foreach ($keywords as $keyword) {
                               $query->orWhere('slug', 'LIKE', "%$keyword%");
                               $query->orWhere('keyword', 'LIKE', "%$keyword%");
                            }
                            })
                        ->get();

           if (count($categories) > 0) {
            $cat_id = [];
            $all_cats = Category::all();
            foreach($all_cats as $key=>$cat){
            $cat_id[]=$cat->id;
              }

            $sub_sub_cat = [];
            $sub_cat = [];
            $html = '';

        foreach($categories as $key => $value) {

            if($value->parent_id == 0) {

            } else {
                if(in_array($value->parent_id,$cat_id)) {
                $chk_cat = Category::find($value->parent_id);
                if (in_array($chk_cat->parent_id, $cat_id)) {
                    $sub_sub_cat[] = $value->id;
                } else {
                    $sub_cat[] = $value->id;
                }
            }
            }
        }

        if (count($sub_sub_cat) > 0) {
            $html = "";
            $html .= "<br/>";
            foreach($sub_sub_cat as $key => $category)
            {
                $sub_sub_cat_data = Category::find($category);
                $s_c = Category::where('id', $sub_sub_cat_data->parent_id)->first();
                $ct = Category::where('id', $s_c->parent_id)->first();
                $html .= "<label class='cat-title-label'><input class='cat-title' type='radio' name='category' value='".$sub_sub_cat_data->id."'> 
                              ".$ct->name." <- ".$s_c->name." <- ".$sub_sub_cat_data->name."
                          </label>
                          <br>";
            }
        } else {

        }

        if (count($sub_sub_cat) == 0) {
            if (count($sub_cat) > 0) {
                $html .= "Sub Category";
                $html .= "<br/>";
                foreach($sub_cat as $key => $categorys)
                {
                    $sub_cat_data = Category::find($categorys);
                    $html .= "<label class='cat-title-label'><input class='cat-title' type='radio' name='category' value='".$sub_cat_data->id."'>
                                  ".$sub_cat_data->name."
                              </label>
                              <br>";
                }
            }

            else {

            }
        }




        return response()->json([
            'success' => true,
            'html' => $html,
        ]);
           }    else {
            $html = '';
            $all_categories = Category::where('parent_id', 0)->get();
            foreach($all_categories as $key => $category)
        {
          $html .= '<option value="'. $category->id .'">'. $category->name .'</option>';
        }

          return response()->json([
                'html' =>$html,
                'success' => false,
            ]);
           }

        } else {
            $html = '';
            $all_categories = Category::where('parent_id', 0)->get();
            foreach($all_categories as $key => $category)
        {
          $html .= '<option value="'. $category->id .'">'. $category->name .'</option>';
        }

          return response()->json([
                'html' =>$html,
                'success' => false,
            ]);
        }
    }

     public function fileUpload(Request $request){

        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
            $request->file('upload')->move(public_path('ck-images'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('public/ck-images/'.$fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
    
    public function uploadVariationImage(Request $request)
        {
            
            $image = $request->file('variation_image');
            $image = Image::make($image)->resize(800, 800);
            
            if ($image != null) {
                $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
    
            if (!Storage::disk('public')->exists('product/variation')) {
                Storage::disk('public')->makeDirectory('product/variation/');
            }

            // Check if the image instance is valid
            // Save the image data directly, no need for file_get_contents
            Storage::disk('public')->put('product/variation/' . $imageName, (string) $image->encode('png'));
        
            } else {
                $imageName = 'def.png';
            }
        
            return response()->json(['image' => $imageName]);
        }

}
