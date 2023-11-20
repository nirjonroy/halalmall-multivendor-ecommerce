@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Flash Deal Products'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Deals of {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Deals of {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
    <style>
        .countdown-background{
            background: {{$web_config['primary_color']}};
        }
        .cz-countdown-days {
            border: .5px solid{{$web_config['primary_color']}};
        }

        .cz-countdown-hours {
            border: .5px solid{{$web_config['primary_color']}};
        }

        .cz-countdown-minutes {
            border: .5px solid{{$web_config['primary_color']}};
        }
        .cz-countdown-seconds {
            border: .5px solid{{$web_config['primary_color']}};
        }
        .flash_deal_product_details .flash-product-price {
            color: {{$web_config['primary_color']}};
        }
    </style>
@endpush

@section('content')
@php($decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'))
<div class="__inline-59">
    <!--
    <div class="for-banner container">

        <img class="d-block for-image"
             onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
             src=""
             alt="Shop Converse">

    </div>
    -->
    <div class="container md-4 mt-3 rtl"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row g-3 flex-center align-items-center">
            @php($free_shipping=\App\Model\FlashDeal::with(['products'=>function($query){
                $query->with('product')->whereHas('product',function($q){
                    $q->active();
                });
            }])->where(['status'=>1])->where(['deal_type'=>'free_shipping'])->get())
          
            <div class="col-sm-auto text-center {{Session::get('direction') === "rtl" ? 'text-sm-right' : 'text-sm-left'}}">
                <span class="flash_deal_title ">
                    {{ \App\CPU\translate('free_shipping')}}
                </span>
            </div>
            
        </div>
    </div>
    <!-- Toolbar-->

    <!-- Products grid-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <section class="col-lg-12">
                <div class="row mt-4">
                   
@foreach($free_shipping as $fDeals)
                          @foreach($fDeals->products as $key=>$deal)
                                @if( $deal->product)
                          <div class="col-md-3 col-sm-4 col-6" style="margin-bottom: 15px;">
                         @include('web-views.partials._category-single-product',['product'=>$deal->product,'decimal_point_settings'=>$decimal_point_settings])
                                @endif
                          </div>
                            @endforeach
                          @endforeach
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@push('script')

@endpush
