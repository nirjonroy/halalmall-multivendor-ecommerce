
@extends('layouts.back-end.app-seller')
@section('title', \App\CPU\translate('Shop Edit'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
     <!-- Custom styles for this page -->
     <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
     <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')
    <!-- Content Row -->
    <div class="content container-fluid">

    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img width="20" src="{{asset('/public/assets/back-end/img/shop-info.png')}}" alt="">
            {{\App\CPU\translate('Edit_Shop_Info')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 ">{{\App\CPU\translate('Edit_Shop_Info')}}</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('seller.shop.update',[$shop->id])}}" method="post"
                          style="text-align: {{Session::get('direction') === 'rtl' ? 'right' : 'left'}};"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label for="name" class="title-color">{{\App\CPU\translate('Shop Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{$shop->name}}" class="form-control" id="name"
                                            required>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="title-color">{{\App\CPU\translate('Contact')}} <span class="text-info">( * {{\App\CPU\translate('country_code_is_must')}} {{\App\CPU\translate('like_for_BD_880')}} )</span></label>
                                    <input type="number" name="contact" value="{{$shop->contact}}" class="form-control" id="name"
                                            required>
                                </div>                                
                                <div class="form-group">
                                    <label for="nid" class="title-color">Shop NID : <span class="text-danger">*</span></label>
                                    <input type="number" name="nid" value="{{$shop->nid}}" class="form-control" id="nid"
                                            required>
                                </div>
                                <div class="form-group">
                                    <label for="address" class="title-color">{{\App\CPU\translate('Address')}} <span class="text-danger">*</span></label>
                                    <textarea type="text" rows="4" name="address" value="" class="form-control" id="address"
                                            required>{{$shop->address}}</textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-6" style="box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15); padding: 20px;">
                                <div class="text-center">
                                    <img class="upload-img-view" id="viewer"
                                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                    src="{{asset('storage/app/public/shop/'.$shop->image)}}" alt="Product thumbnail"/>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="title-color">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('Logo')}}</label>
                                    <div class="custom-file text-left">
                                        <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="customFileUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                    </div>
                                </div>
                            </div>
                            

                            <div class="col-md-6" style="box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15); padding: 20px;">
                                <div class="text-center" style="margin-bottom: 15px;">
                                    <img class="upload-img-view" id="viewerBanner"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset('storage/app/public/shop/banner/'.$shop->banner)}}" alt="Product thumbnail"/>
                                </div>
                                    <div class="flex-start">
                                        <label for="name" class="title-color">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('Top Banner')}} </label>
                                    </div>
                                <div class="custom-file text-left">
                                    <input type="file" name="banner" id="BannerUpload" class="custom-file-input"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label" for="BannerUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                </div>
                            </div> 
                            
                            <div class="col-md-6" style="box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15); padding: 20px;">
                                <div class="text-center">
                                    <img class="upload-img-view" id="viewer"
                                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                    src="{{asset('storage/app/public/shop/bank_cheque/'.$shop->bank_cheque)}}" alt="Product thumbnail"/>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="title-color">{{\App\CPU\translate('Bank')}} {{\App\CPU\translate('Cheque')}}</label>
                                    <div class="custom-file text-left">
                                        <input type="file" name="bank_cheque" id="customFileUpload" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="customFileUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('bank cheque')}}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" style="box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15); padding: 20px;">
                                <div class="form-group">
                                    <!--<div class="flex-start">-->
                                    <!--    <label for="name" class="title-color">{{\App\CPU\translate('Upload')}} Trade License </label>-->
                                    <!--    <div class="mx-1" for="ratio">-->
                                    <!--        <span class="text-info">{{\App\CPU\translate('Ratio')}} : ( 6:1 )</span>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    
                                </div>
                                <div class="text-center" style="margin-bottom: 15px;">
                                    <img class="upload-img-view" id="viewerTradeLicense"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset('storage/app/public/shop/trade_license/'.$shop->trade_license)}}" alt="Product thumbnail"/>
                                </div>
                                <div class="custom-file text-left">
                                        <input type="file" name="trade_license" id="TradeLicenseUpload" class="custom-file-input"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="TradeLicenseUpload">{{\App\CPU\translate('choose')}} Trade License</label>
                                    </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6" style="box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15); padding: 20px;">
                                <div class="form-group ">
                                    <!--<div class="flex-start">-->
                                    <!--    <label for="name" class="title-color">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('Side Banner')}} </label>-->
                                    <!--    <div class="mx-1" for="ratio">-->
                                    <!--        <span class="text-info">{{\App\CPU\translate('Ratio')}} : ( 6:1 )</span>-->
                                    <!--    </div>-->
                                    <!--</div>-->

                                    <div class="text-center" style="margin-bottom: 15px;">
                                        <img class="upload-img-view" id="viewerSideBanner"
                                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                            src="{{asset('storage/app/public/shop/banner/'.$shop->side_banner)}}" alt="Product thumbnail"/>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="flex-start">      
                                                <label for="name" class="title-color">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('Side Banner 1')}} </label>
                                        
                                    </div>
                                            <div class="custom-file text-left">
                                            <input type="file" name="side_banner" id="SideBannerUpload" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                
                                            <label class="custom-file-label" for="BannerUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                    </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="flex-start">      
                                                <label for="name" class="title-color">{{\App\CPU\translate('Select Side')}} {{\App\CPU\translate('Banner 1 Category')}} </label>
                                        
                                    </div>
                                            <select class="browser-default custom-select" name="category_id">
                                        <option selected>Select Slider Banner Category1</option>
                                        @foreach($categories as $key=>$cat)
                                        <option value="{{$cat->id}}" {{ old($cat->id) == $key ? "selected" : "" }}>{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                            </div>
                            <div class="col-md-6" style="box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15); padding: 20px;">
                                <div class="form-group ">
                                <!--<div class="flex-start">-->
                                <!--        <label for="name" class="title-color">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('Side Banner')}} </label>-->
                                <!--        <div class="mx-1" for="ratio">-->
                                <!--            <span class="text-info">{{\App\CPU\translate('Ratio')}} : ( 6:1 )</span>-->
                                <!--        </div>-->
                                <!--    </div>-->

                                    <div class="text-center" style="margin-bottom: 15px;">
                                        <img class="upload-img-view" id="viewerSideBanner"
                                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                            src="{{asset('storage/app/public/shop/banner/'.$shop->side_banner2)}}" alt="Product thumbnail"/>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="flex-start">      
                                                <label for="name" class="title-color">{{\App\CPU\translate('Upload Side')}} {{\App\CPU\translate('Side Banner 2')}} </label>
                                        
                                    </div>
                                            <div class="custom-file text-left" >
                                        
                                            <input type="file" name="side_banner2" id="SideBannerUpload" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="BannerUpload">Choose File</label>
                                    </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="flex-start">      
                                                <label for="name" class="title-color">{{\App\CPU\translate('Select')}} {{\App\CPU\translate('Side Banner 2 Category')}} </label>
                                        
                                    </div>
                                            <select class="browser-default custom-select" name="category_id2">
                                        <option selected>Select Slider Banner Category</option>
                                        @foreach($categories as $key=>$cat)
                                        <option value="{{$cat->id}}" {{ old($cat->id) == $key ? "selected" : "" }}>{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>


                        <!--Bottom Part-->

                        <div class="row">
                            <div class="col-md-6" style="box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15); padding: 20px;">
                                <div class="form-group ">

                                    <div class="text-center" style="margin-bottom: 15px;">
                                        <img class="upload-img-view" id="viewerSideBanner"
                                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                            src="{{asset('storage/app/public/shop/banner/'.$shop->side_banner3)}}" alt="Product thumbnail"/>
                                    </div>
                                    <div class="row">
                                        
                                        <div class="col-md-6">
                                            <div class="flex-start">
                                        <label for="name" class="title-color">{{\App\CPU\translate('Upload Center')}} {{\App\CPU\translate('Category Banner 1')}} </label>
                                        
                                    </div>
                                            <div class="custom-file text-left" >
                                        <br>
                                            <input type="file" name="side_banner3" id="SideBannerUpload" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="BannerUpload">Choose File</label>
                                    </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="flex-start">
                                        <label for="name" class="title-color">{{\App\CPU\translate('Select Center')}} {{\App\CPU\translate('Banner Category 1')}} </label>
                                        
                                    </div>
                                            <select class="browser-default custom-select " name="category_id3">
                                    
                                    @foreach($categories as $cat)
                                    <option value="{{$cat->id}}" {{ old($cat->id) == $key ? "selected" : "" }}>{{$cat->name}}</option>
                                    @endforeach
                                    </select>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-md-6" style="box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15); padding: 20px;">
                                <div class="form-group ">
                                        <!--<div class="flex-start">-->
                                            <!--<label for="name" class="title-color">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('Side Banner')}} </label>-->
                                            <!--<div class="mx-1" for="ratio">
                                        <!--        <span class="text-info">{{\App\CPU\translate('Ratio')}} : ( 6:1 )</span>-->
                                        <!--    </div>-->
                                        <!--</div>-->
    
                                    <div class="text-center" style="margin-bottom: 15px;">
                                            <img class="upload-img-view" id="viewerSideBanner"
                                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                src="{{asset('storage/app/public/shop/banner/'.$shop->side_banner4)}}" alt="Product thumbnail"/>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                 <div class="flex-start">
                                        <label for="name" class="title-color">{{\App\CPU\translate('Upload Center')}} {{\App\CPU\translate('Category Banner 2')}} </label>
                                        
                                    </div>
                                                <div class="custom-file text-left" >
                                            <br>
                                                <input type="file" name="side_banner4" id="SideBannerUpload" class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label" for="BannerUpload">Choose File</label>
                                        </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="flex-start">
                                        <label for="name" class="title-color">{{\App\CPU\translate('Select Center')}} {{\App\CPU\translate('Banner Category 2')}} </label>
                                        
                                    </div>
                                                <select class="browser-default custom-select " name="category_id4">
                                        <label> Fourth Banner Category</label>
                                        @foreach($categories as $cat)
                                        <option value="{{$cat->id}}" {{ old($cat->id) == $key ? "selected" : "" }}>{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                            </div>
                                        </div>
                                        
                                        
                                </div>
                                
                            </div>
                        </div>
                        <!--Bottom Part-->
                        
                            
                        </div>

                 <!--Deleted This part-->

                        <div class="d-flex justify-content-end gap-2">
                            <a class="btn btn-danger" href="{{route('seller.shop.view')}}">{{\App\CPU\translate('Cancel')}}</a>
                            <button type="submit" class="btn btn--primary" id="btn_update">{{\App\CPU\translate('Update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/products.png')}}" alt="">
                Popular Category
                <span class="badge badge-soft-dark radius-50 fz-14 ml-1">Popular Category</span>
            </h2>
        </div>

        <div class="container-fluid">
                        <form action="{{ route('seller.store-popular-category') }}" method="POST">
                            @csrf
                                  <input type="hidden" value="{{ auth('seller')->id() }}" name="seller_id" id="">  
                            <div class="form-group">
                                <label for="">Category</label>
                                <select name="category_id" id="" class="form-control">
                                    <option value="">select</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button class="btn btn-primary" type="submit">Save</button>
                        </form>
                    </div>
</div>       


<div class="container-fluid">
         <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Popular Category name</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    @foreach($popularCategories as $pcats)
      <tr>
        <td>{{$pcats->id}}</td>
        <td>{{$pcats->category->name}}</td>
        <td><a href="{{ route('seller.delete-popular-category', $pcats->id) }}" class="btn btn-success">Delete</a></td>
      </tr>
    @endforeach  
    </tbody>
  </table>
    </div>
@endsection

@push('script')



   <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readBannerURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewerBanner').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }   
     
     function readSideBannerURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewerSideBanner').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }   
     
     
       
        
        function readTradeLicenseURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewerTradeLicense').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function () {
            readURL(this);
        });

        $("#BannerUpload").change(function () {
            readBannerURL(this);
        });        
        
        $("#TradeLicenseUpload").change(function () {
            readTradeLicenseURL(this);
        });
     
        $("#SideBannerUpload").change(function () {
        readSideBannerURL(this);
    	});

   </script>

@endpush
