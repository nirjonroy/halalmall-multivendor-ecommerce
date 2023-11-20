@extends('layouts.back-end.app-seller')
@section('title', \App\CPU\translate('Shop Settings'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/shop-info.png')}}" alt="">
                {{\App\CPU\translate('Shop_Info')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-md-12">
                <div class="card" style="overflow: hidden;">
                    <div class="card-header">
                        <h4 class="mb-0">{{\App\CPU\translate('my_shop')}} {{\App\CPU\translate('Info')}} </h4>
                    </div>
                    <div class="row mt-5" style="padding: 0px 40px;">
                        
                        <div class="col-md-3" style="box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15)">
                            <h3 style="text-align: center;margin:20px 0px;">Shop Logo</h3>
                        @if($shop->image=='def.png')
                                <div class="text-{{Session::get('direction') === "rtl" ? 'right' : 'center'}}">
                                    <img height="200" width="200" class=""
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset('public/assets/back-end')}}/img/shop.png" style="border-radius: 5%;">
                                </div>
                            @else
                                <div class="text-{{Session::get('direction') === "rtl" ? 'right' : 'center'}}">
                                    <img src="{{asset('storage/app/public/shop/'.$shop->image)}}"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         class=""
                                         height="200" width="200" style="border-radius: 5%;" alt="">
                                </div>
                            @endif
                    </div>
                    <div class="col-md-3" style="box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15)">
                        <h3 style="text-align: center;margin:20px 0px;">Bank Cheque</h3>
                                    <div style="text-align: center;padding-bottom: 30px;">
                                        <img src="{{asset('storage/app/public/shop/bank_cheque/'.$shop->bank_cheque)}}"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         class="border"
                                         height="200" width="200" style="border-radius: 5%;" alt="">
                                    </div>
                    </div>
                    <div class="col-md-3" style="box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15)">
                        <h3 style="text-align: center;margin:20px 0px;">Trade License Image</h3>
                                    <div style="text-align: center;padding-bottom: 30px;">
                                        <img src="{{asset('storage/app/public/shop/trade_license/'.$shop->trade_license)}}"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         class="border"
                                         height="200" width="200" style="border-radius: 5%;" alt="">
                                    </div>
                    </div>
                    <div class="col-md-3" style="padding-bottom: 30px;box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15)">
                        <h3 style="text-align: center;margin: 20px 0px;">Top Banner</h3>
                         <div class="text-center">
                                    <img class="" id="viewerBanner"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset('storage/app/public/shop/banner/'.$shop->banner)}}" 
                                         height="200" width="200" style="border-radius: 5%;" alt="Product thumbnail"/>
                                </div>
                    </div>
                     <div class="col-md-3" style="box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15)">
                         <h3 style="text-align: center;margin: 20px 0px;">Side Banner</h3>
                         <div class="text-center">
                         <p>{{$shop_cat->cat_name}}</p>
                                    <img class="" id="viewerSideBanner"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset('storage/app/public/shop/banner/'.$shop->side_banner)}}"
                                         height="200" width="200" style="border-radius: 5%;" alt="Product thumbnail"/>
                                    
                                             
                                </div>
                    </div>
                    <div class="col-md-3" style="padding-bottom: 30px;box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15)">
                         <h3 style="text-align: center;margin: 20px 0px;">Side Banner2</h3>
                         <div class="text-center">
                                    <p>{{$shop_cat2->cat_name2}}</p>
                                    <img class="" id="viewerSideBanner"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset('storage/app/public/shop/banner/'.$shop->side_banner2)}}"
                                         height="200" width="200" style="border-radius: 5%;"
                                         alt="Product thumbnail"/>
                                    
                                             
                                </div>

                    </div>

                    <div class="col-md-3" style="padding-bottom: 30px;box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15)">
                         <h3 style="text-align: center;margin: 20px 0px;">Center Category Banner 1</h3>
                         <div class="text-center">
                         <p>{{$shop_cat3->cat_name3}}</p>
                                    <img class="" id="viewerSideBanner"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset('storage/app/public/shop/banner/'.$shop->side_banner3)}}" 
                                         height="200" width="200" style="border-radius: 5%;"
                                         alt="Product thumbnail"/>
                                    
                                             
                                </div>

                    </div>
                    <div class="col-md-3" style="padding-bottom: 30px;box-shadow: 0px 0px 5px rgba(0, 113, 220, 0.15)">
                         <h3 style="text-align: center;margin: 20px 0px;">Center Category Banner 2</h3>
                         <div class="text-center">
                         <p>{{$shop_cat4->cat_name4}}</p>
                                    <img class="" id="viewerSideBanner"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset('storage/app/public/shop/banner/'.$shop->side_banner4)}}" 
                                         height="200" width="200" style="border-radius: 5%;"
                                         alt="Product thumbnail"/>
                                    
                                             
                                </div>
                    </div> 
                    
                    
                    <div class="card-body">
                        <div class="align-items-center flex-wrap gap-5">
                            <!--@if($shop->image=='def.png')-->
                            <!--    <div class="text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}">-->
                            <!--        <img height="200" width="200" class="rounded-circle border"-->
                            <!--             onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"-->
                            <!--             src="{{asset('public/assets/back-end')}}/img/shop.png">-->
                            <!--    </div>-->
                            <!--@else-->
                            <!--    <div class="text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}">-->
                            <!--        <img src="{{asset('storage/app/public/shop/'.$shop->image)}}"-->
                            <!--             onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"-->
                            <!--             class="rounded-circle border"-->
                            <!--             height="200" width="200" alt="">-->
                            <!--    </div>-->
                            <!--@endif-->


                            <div class="">
                            <div class="flex-start">
                                    <h4>ID : </h4>
                                    <h4 class="mx-1">{{$shop->id}}</h4>
                                </div>                                
                                <div class="flex-start">
                                    <h4>{{\App\CPU\translate('Name')}} : </h4>
                                    <h4 class="mx-1">{{$shop->name}}</h4>
                                </div>
                                <div class="flex-start">
                                    <h6>{{\App\CPU\translate('Phone')}} : </h6>
                                    <h6 class="mx-1">{{$shop->contact}}</h6>
                                </div>                                
                                <div class="flex-start">
                                    <h6>NID : </h6>
                                    <h6 class="mx-1">{{$shop->nid}}</h6>
                                </div> 
                                <div class="flex-start">
                                    <h6>{{\App\CPU\translate('address')}} : </h6>
                                    <h6 class="mx-1">{{$shop->address}}</h6>
                                </div> 
                                <!--<div class="">-->
                                <!--    <h6>Trade License : </h6><br>-->
                                <!--    <div>-->
                                <!--        <img src="{{asset('storage/app/public/shop/trade_license/'.$shop->trade_license)}}"-->
                                <!--         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"-->
                                <!--         class="border"-->
                                <!--         height="300" width="300" alt="">-->
                                <!--    </div>-->
                                <!--</div><br>-->
                                
                                <!--<div class="text-center">-->
                                <!--    <img class="upload-img-view" id="viewerBanner"-->
                                <!--         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"-->
                                <!--         src="{{asset('storage/app/public/shop/banner/'.$shop->banner)}}" alt="Product thumbnail"/>-->
                                <!--</div>-->
                                <!--<div class="text-center">-->
                                <!--    <img class="upload-img-view" id="viewerSideBanner"-->
                                <!--         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"-->
                                <!--         src="{{asset('storage/app/public/shop/banner/'.$shop->side_banner)}}" alt="Product thumbnail"/>-->
                                <!--</div>-->
                                <div class="flex-start">
                                    <a class="btn btn--primary px-4" href="{{route('seller.shop.edit',[$shop->id])}}">{{\App\CPU\translate('edit')}}</a>
                                </div>
                            </div>
                            <div class=""></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/my-bank-info.png')}}" alt="">
                {{\App\CPU\translate('my_bank_info')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <!-- Card Header -->
                    <div class="border-bottom d-flex gap-3 flex-wrap justify-content-between align-items-center px-4 py-3">
                        <div class="d-flex gap-2 align-items-center">
                            <img width="20" src="{{asset('/public/assets/back-end/img/bank.png')}}" alt="" />
                            <h3 class="mb-0">{{\App\CPU\translate('Bank Information')}}</h3>
                        </div>

                        <div class="d-flex gap-2 align-items-center">
                            <a href="{{route('seller.profile.bankInfo',[$data->id])}}" class="btn btn--primary">
                                {{\App\CPU\translate('Edit')}}
                            </a>
                        </div>
                    </div>
                    <!-- End Card Header -->

                    <!-- Card Body -->
                    <div class="card-body p-30">
                        <div class="row justify-content-center">
                            <div class="col-sm-6 col-md-8 col-lg-6 col-xl-5">
                                <!-- Bank Info Card -->
                                <div class="card bank-info-card bg-bottom bg-contain bg-img" style="background-image: url({{asset('/public/assets/back-end/img/bank-info-card-bg.png')}});">
                                    <div class="border-bottom p-3">
                                        <h4 class="mb-0 fw-semibold">{{\App\CPU\translate('Holder_Name')}} : <strong>{{$data->holder_name ?? 'No Data found'}}</strong></h4>
                                    </div>

                                    <div class="card-body position-relative">
                                        <img class="bank-card-img" width="78" src="{{asset('/public/assets/back-end/img/bank-card.png')}}" alt="">

                                        <ul class="list-unstyled d-flex flex-column gap-4">
                                            <li>
                                                <h3 class="mb-2">{{\App\CPU\translate('Bank_Name')}} :</h3>
                                                <div>{{$data->bank_name ?? 'No Data found'}}</div>
                                            </li>
                                            <li>
                                                <h3 class="mb-2">{{\App\CPU\translate('Branch_Name')}} :</h3>
                                                <div>{{$data->branch ?? 'No Data found'}}</div>
                                            </li>
                                            <li>
                                                <h3 class="mb-2">{{\App\CPU\translate('Account_Number')}} : </h3>
                                                <div>{{$data->account_no ?? 'No Data found'}}</div>
                                            </li>                                            
                                        </ul>
                                    </div>
                                </div>
                                <!-- End Bank Info Card -->
                            </div>
                        </div>
                    </div>
                    <!-- End Card Body -->
                </div>
            </div>
        </div>
    </div>
    
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/my-bank-info.png')}}" alt="">
                {{\App\CPU\translate('Pixel_Analytics')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <!-- Card Header -->
                    <div class="border-bottom d-flex gap-3 flex-wrap justify-content-between align-items-center px-4 py-3">
                        <div class="d-flex gap-2 align-items-center">
                            <img width="20" src="{{asset('/public/assets/back-end/img/bank.png')}}" alt="" />
                            <h3 class="mb-0">{{\App\CPU\translate('Update Your Pixel Analytics')}}</h3>
                        </div>

                        <div class="d-flex gap-2 align-items-center">
                            <a href="{{route('seller.profile.bankInfo',[$data->id])}}" class="btn btn--primary">
                                {{\App\CPU\translate('Edit')}}
                            </a>
                        </div>
                    </div>
                    <!-- End Card Header -->

                    <!-- Card Body -->
                    
                    <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @php($pixel_analytics=\App\CPU\Helpers::get_business_settings('pixel_analytics'))
                            <div class="col-12 mb-3">
                                <form action="{{env('APP_MODE')!='demo'?route('seller.analytics-update'):'javascript:'}}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label class="title-color d-flex">{{\App\CPU\translate('pixel_analytics_your_pixel_id')}}</label>
                                        <textarea type="text" rows="10" placeholder="{{\App\CPU\translate('pixel_analytics_your_pixel_id_from_facebook')}}" class="form-control" name="seller_pixel_analytics">{{env('APP_MODE')!='demo'?$pixel_analytics??'':''}}</textarea>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary px-4">{{\App\CPU\translate('save')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    
                    <!-- End Card Body -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
@endpush
