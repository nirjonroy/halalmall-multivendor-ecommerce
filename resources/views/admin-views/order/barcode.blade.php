@extends('layouts.back-end.app')
@section('title', $order->payment_status . ' barcode ' . date('Y/m/d'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ asset('public/assets/back-end') }}/css/barcode.css" />
@endpush
@section('content')
    <div class="row m-2 show-div pt-3">
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <!-- Page Title -->
            <div class="mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
                    <img src="{{asset('/public/assets/back-end/img/inhouse-product-list.png')}}" alt="">
                    {{\App\CPU\translate('generate_barcode')}}
                </h2>
            </div>
            <!-- End Page Title -->

            <div class="card">
                <div class="py-4">
                    <div class="table-responsive">
                        <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                <th>{{\App\CPU\translate('Order')}} {{\App\CPU\translate('ID')}}</th>
                                    
                                    <th>{{ \App\CPU\translate('order_status') }}</th>                                    
                                    <th class="text-center">{{ \App\CPU\translate('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <form action="{{ url()->current() }}" method="GET">
                                    
                                        <th>
                                            @if ($order->id)
                                                <span>
                                                {{$order->id}}
                                                </span>

                                            @else

                                                <a class="title-color hover-c1" href="{{route('admin.product.edit',[$product['id']])}}">
                                                    {{ \App\CPU\translate('update_your_order_id') }}
                                                </a>

                                            @endif
                                            </th>
                                        <th>{{ $order->payment_status }}</th> 
                                        <th>
                                            <input id="limit" type="number" name="limit" min="1" class="form-control" value="{{ $limit }}">
                                            <span class="text-info mt-1 d-block">{{ \App\CPU\translate('maximum_quantity_270') }}</span>
                                        </th>                                       

                                        <th>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn btn-outline-info"
                                                    type="submit">{{ \App\CPU\translate('generate_barcode') }}</button>
                                                <a href="{{ route('admin.orders.barcode', [$order['id']]) }}"
                                                    class="btn btn-outline-danger">{{ \App\CPU\translate('reset') }}</a>
                                                <button type="button" id="print_bar" onclick="printDiv('printarea')"
                                                    class="btn btn-outline--primary ">{{ \App\CPU\translate('print') }}</button>
                                            </div>
                                        </th>
                                    </form>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mt-5 p-4">
            <h1 class="style-one-br show-div2">
                {{ \App\CPU\translate("This page is for A4 size page printer, so it won't be visible in smaller devices") }}.
            </h1>
        </div>
    </div>

    <div id="printarea" class="show-div pb-5">
        @if ($limit)
            <div class="barcodea4">
                @for ($i = 0; $i < $limit; $i++)
                    @if ($i % 27 == 0 && $i != 0)
            </div>
            <div class="barcodea4">
        @endif
        <div class="item style24" style="height: 235px;">
            <span
                class="barcode_site text-capitalize">{{ \App\Model\BusinessSetting::where('type', 'company_name')->first()->value }}</span>
            <span class="barcode_name text-capitalize">{{ $order->id }}</span>
            @if ($order->id !== null)
                <div class="barcode_image">
                <img height="115" width="115" src="data:image/png;base64,{{ DNS2D::getBarcodePNG($order->id.' '.$order->payment_status.' '.$order->customer->f_name.' '.$order->customer->phone.' '.$order->order_amount,'QRCODE') }}" alt="barcode" />
               </div>



                <div class="barcode_code text-capitalize">{{ \App\CPU\translate('code') }} : {{ $order->id }}</div>
            @else
                <p class="text-danger">{{ \App\CPU\translate('please_update_product_code') }}</p>
            @endif
            
        </div>
        @endfor
    </div>
    @endif
    </div>
@endsection
@push('script_2')
    <script src={{ asset('public/assets/admin/js/global.js') }}></script>
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
@endpush
