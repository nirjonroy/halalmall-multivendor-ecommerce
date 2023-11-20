@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Order List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div>
            <!-- Page Title -->
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                <h2 class="h1 mb-0">
                    <img src="{{asset('/public/assets/back-end/img/all-orders.png')}}" class="mb-1 mr-1" alt="">
                    <span class="page-header-title">
                        @if($status =='processing')
                            {{\App\CPU\translate('packaging')}}
                        @elseif($status =='failed')
                            {{\App\CPU\translate('Failed_to_Deliver')}}
                        @elseif($status == 'all')
                            {{\App\CPU\translate('all')}}
                        @else
                            {{\App\CPU\translate(str_replace('_',' ',$status))}}
                        @endif
                    </span>
                    {{\App\CPU\translate('Orders')}}
                </h2>
                <span class="badge badge-soft-dark radius-50 fz-14">{{$orders->total()}}</span>
            </div>
            <!-- End Page Title -->

            <!-- Order States -->
            <div class="card">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url()->current() }}" id="form-data" method="GET">
                            <div class="row gy-3 gx-2">
                                <div class="col-12 pb-0">
                                    <h4>{{\App\CPU\translate('select')}} {{\App\CPU\translate('date')}} {{\App\CPU\translate('range')}}</h4>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <select name="filter" class="form-control">
                                        <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>{{\App\CPU\translate('all')}}</option>
                                        <option value="admin" {{ $filter == 'admin' ? 'selected' : '' }}>{{\App\CPU\translate('In_House')}}</option>
                                        <option value="seller" {{ $filter == 'seller' ? 'selected' : '' }}>{{\App\CPU\translate('Seller')}}</option>
                                        @if($status == 'all' || $status == 'delivered')
                                        <option value="POS" {{ $filter == 'POS' ? 'selected' : '' }}>POS</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-floating">
                                        <input type="date" name="from" value="{{$from}}" id="from_date"
                                            class="form-control">
                                        <label>{{\App\CPU\translate('Start_Date')}}</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3 mt-2 mt-sm-0">
                                    <div class="form-floating">
                                        <input type="date" value="{{$to}}" name="to" id="to_date"
                                            class="form-control">
                                        <label>{{\App\CPU\translate('End_Date')}}</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3 mt-2 mt-sm-0  ">
                                    <button type="submit" class="btn btn--primary btn-block" onclick="formUrlChange(this)" data-action="{{ url()->current() }}">
                                        {{\App\CPU\translate('show')}} {{\App\CPU\translate('data')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Order stats -->
                    @if($status == 'all' && $filter != 'POS')
                    <div class="row g-2 mb-20">
                        <div class="col-sm-6 col-lg-3">
                            <!-- Card -->
                            <a class="order-stats order-stats_pending" href="{{route('admin.orders.list',['pending'])}}">
                                <div class="order-stats__content">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/pending.png')}}" class="svg" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('pending')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $pending_count }}
                                </span>
                            </a>
                            <!-- End Card -->
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <!-- Card -->
                            <a class="order-stats order-stats_confirmed" href="{{route('admin.orders.list',['confirmed'])}}">
                                <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/confirmed.png')}}" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('confirmed')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $confirmed_count }}
                                </span>
                            </a>
                            <!-- End Card -->
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <!-- Card -->
                            <a class="order-stats order-stats_packaging" href="{{route('admin.orders.list',['processing'])}}">
                                <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/packaging.png')}}" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('Packaging')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $processing_count }}
                                </span>
                            </a>
                            <!-- End Card -->
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <!-- Card -->
                            <a class="order-stats order-stats_out-for-delivery" href="{{route('admin.orders.list',['out_for_delivery'])}}">
                                <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/out-of-delivery.png')}}" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('out_for_delivery')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $out_for_delivery_count }}
                                </span>
                            </a>
                            <!-- End Card -->
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <div class="order-stats order-stats_delivered cursor-pointer"
                                onclick="location.href='{{route('admin.orders.list',['delivered'])}}'">
                                <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/delivered.png')}}" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('delivered')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $delivered_count }}
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <div class="order-stats order-stats_canceled cursor-pointer"
                                onclick="location.href='{{route('admin.orders.list',['canceled'])}}'">
                                <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/canceled.png')}}" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('canceled')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $canceled_count }}
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <div class="order-stats order-stats_returned cursor-pointer"
                                onclick="location.href='{{route('admin.orders.list',['returned'])}}'">
                                <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/returned.png')}}" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('returned')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $returned_count }}
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <div class="order-stats order-stats_failed cursor-pointer"
                                onclick="location.href='{{route('admin.orders.list',['failed'])}}'">
                                <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/failed-to-deliver.png')}}" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('Failed_To_Delivery')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $failed_count }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- End Order stats -->

                    <!-- Data Table Top -->
                    <div class="px-3 py-4 light-bg">
                        <div class="row g-2 flex-grow-1">
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <form action="" method="GET">
                                    <!-- Search -->
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{\App\CPU\translate('Search by Order ID')}}" aria-label="Search by Order ID" value="{{ $search }}"
                                            required>
                                        <button type="submit" class="btn btn--primary input-group-text">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                    <!-- End Search -->
                                </form>
                            </div>
                            
                            
                            
                            <div class="col-sm-6 col-md-6 col-lg-8">
                                <div class="row">
                                    <div class="col-md-2">
                                        
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <a type="submit" class="btn btn-outline--primary" href="{{route('admin.pos.index')}}">
                                    {{\App\CPU\translate('Add New Order')}}
                                </a>
                                    </div>
                                    
                                    <div class="col-md-3" style="text-align: right;">
                                        <button type="button" class="btn btn-outline--primary " data-toggle="dropdown">
                                            <i class="tio-download-to"></i>
                                            {{\App\CPU\translate('Export Order')}}
                                            <i class="tio-chevron-down"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" style="text-align: center;">
                                            <li>
                                                <form action="{{ route('admin.orders.order-bulk-export', ['status' => $status, 'from' => $from, 'to' => $to, 'filter' => $filter, 'search' => $search]) }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="ord_id" value="" id="check">
                                                    <button type="submit" class="btn btn-primary">Excel Format</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4" style="">
                                        
                                        <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                            <i class="tio-download-to"></i>
                                            {{\App\CPU\translate('export_packing_data')}}
                                            <i class="tio-chevron-down"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" style="text-align: center;">
                                            <li>
                                                <form action="{{ route('admin.orders.export-packing-data', ['status' => $status, 'from' => $from, 'to' => $to, 'filter' => $filter, 'search' => $search]) }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="ord_id" value="" id="pack">
                                                    <button type="submit" class="btn btn-primary">Excel Format</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Data Table Top -->
                  
                
                <form id="generate-invoices-form" action="{{ route('admin.orders.generate-multiple-invoices') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <button id="generate-invoices-btn" type="submit" class="btn btn-outline--primary" style="margin-bottom: 8px;"><i class="tio-print mr-1"></i> Print Invoices</button>
                    <button type="button" class="btn btn-outline--primary  " id="download-selected-packing-pdf-btn" style="margin-top:-8px">
                    <i class="tio-print mr-1"></i> Print Packing Slips
                </button>
                    
               
                    <!-- Your form inputs and table here -->
<!--<button id="generate-invoices-btn" class=" btn btn-outline--success" type="submit"><i class="tio-print mr-1"></i> Print</button>-->



                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100"
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th><input type="checkbox" id="select-all" value="Select All"></th>
                                    <th class="">{{\App\CPU\translate('SL')}}</th>
                                    <th>{{\App\CPU\translate('Order')}} {{\App\CPU\translate('ID')}}</th>
                                    <th>{{\App\CPU\translate('Order')}} {{\App\CPU\translate('Date')}}</th>
                                    <th>{{\App\CPU\translate('customer')}} {{\App\CPU\translate('info')}}</th>
                                    <th>{{\App\CPU\translate('Store')}}</th>
                                    <th class="text-right">{{\App\CPU\translate('Total')}} {{\App\CPU\translate('Amount')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Order')}} {{\App\CPU\translate('Status')}} </th>
                                    <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach($orders as $key=>$order)

                                <tr class="status-{{$order['order_status']}} class-all">
                                    <td>
                                        <input type="checkbox" id="order_checkbox" class="order_checkbox" name="selected_records[]" value="{{ $order->id }}">
                                    </td>
                                    <td class="">
                                        
                                      {{$orders->firstItem()+$key}}
                                    </td>
                                    <td >
                                        <a class="title-color" href="{{route('admin.orders.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
                                    </td>
                                    <td>
                                        <div>{{date('d M Y',strtotime($order['created_at']))}},</div>
                                        <div>{{ date("h:i A",strtotime($order['created_at'])) }}</div>
                                    </td>
                                    <td>
                                        @if($order->customer_id == 0)
                                            <strong class="title-name">{{\App\CPU\translate('walking_customer')}}</strong>
                                        @else
                                            @if($order->customer)
                                                <a class="text-body text-capitalize" href="{{route('admin.orders.details',['id'=>$order['id']])}}">
                                                    <strong class="title-name">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</strong>
                                                </a>
                                                <a class="d-block title-color" href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                                            @else
                                                <label class="badge badge-danger fz-12">{{\App\CPU\translate('invalid_customer_data')}}</label>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <span class="store-name font-weight-medium">
                                            @if($order->seller_is == 'seller')
                                                {{ isset($order->seller->shop) ? $order->seller->shop->name : 'Store not found' }}
                                            @elseif($order->seller_is == 'admin')
                                                {{\App\CPU\translate('In-House')}}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div>
                                            @php($discount = 0)
                                            @if($order->coupon_discount_bearer == 'inhouse' && !in_array($order['coupon_code'], [0, NULL]))
                                                @php($discount = $order->discount_amount)
                                            @endif
                                            {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount+$discount))}}
                                        </div>

                                        @if($order->payment_status=='paid')
                                            <span class="badge text-success fz-12 px-0">
                                                {{\App\CPU\translate('paid')}}
                                            </span>
                                        @else
                                            <span class="badge text-danger fz-12 px-0">
                                                {{\App\CPU\translate('unpaid')}}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center text-capitalize">
                                        @if($order['order_status']=='pending')
                                            <span class="badge badge-soft-info fz-12">
                                                {{\App\CPU\translate($order['order_status'])}}
                                            </span>

                                        @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                            <span class="badge badge-soft-warning fz-12">
                                                {{str_replace('_',' ',$order['order_status'] == 'processing' ? \App\CPU\translate($order['packaging']):\App\CPU\translate($order['order_status']))}}
                                            </span>
                                        @elseif($order['order_status']=='confirmed')
                                            <span class="badge badge-soft-success fz-12">
                                                {{\App\CPU\translate($order['order_status'])}}
                                            </span>
                                        @elseif($order['order_status']=='failed')
                                            <span class="badge badge-danger fz-12">
                                                {{\App\CPU\translate('failed_to_deliver')}}
                                            </span>
                                        @elseif($order['order_status']=='delivered')
                                            <span class="badge badge-soft-success fz-12">
                                                {{\App\CPU\translate($order['order_status'])}}
                                            </span>
                                        @else
                                            <span class="badge badge-soft-danger fz-12">
                                                {{\App\CPU\translate($order['order_status'])}}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-outline--primary square-btn btn-sm mr-1" title="{{\App\CPU\translate('view_order')}}"
                                                href="{{route('admin.orders.details',['id'=>$order['id']])}}">
                                                <img src="{{asset('/public/assets/back-end/img/eye.svg')}}" class="svg" alt="">
                                            </a>
                                            <a class="btn btn-outline-success square-btn btn-sm mr-1" target="_blank" title="{{\App\CPU\translate('invoice')}}"
                                                href="{{route('admin.orders.generate-invoice',[$order['id']])}}">
                                                <i class="tio-download-to"></i>
                                            </a>
                                            <a class="btn btn-outline--primary square-btn btn-sm mr-1" title="{{\App\CPU\translate('view_packing_slip')}}"
                                                href="{{route('admin.orders.packing_details',['id'=>$order['id']])}}">
                                                <img src="{{asset('/public/assets/back-end/img/eye.svg')}}" class="svg" alt="">
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                          
                        </table>
                  		
                  </div>
                    <!-- End Table -->
                  
                  
                  </form>
                    <!-- Pagination -->
                    <div class="table-responsive mt-4">
                        <div class="d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {!! $orders->links() !!}
                        </div>
                    </div>
                    <!-- End Pagination -->
                </div>
            </div>
            <!-- End Order States -->

            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal d-none">
                <span class="hs-nav-scroller-arrow-prev d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-left"></i>
                    </a>
                </span>

                <span class="hs-nav-scroller-arrow-next d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-right"></i>
                    </a>
                </span>

                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">{{\App\CPU\translate('order_list')}}</a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
            <!-- End Nav Scroller -->
        </div>
        <!-- End Page Header -->
    </div>
@endsection

@push('script_2')




<script>
$(document).ready(function() {
    // Handle "Select All" functionality
    $('#select-all').click(function() {
        $('input[type="checkbox"]').prop('checked', this.checked);
    });

    // Trigger form submission for generating invoices
    $('#generate-invoices-btn').click(function() {
        console.log('Button clicked'); // Debugging statement
        $('form#report-form').submit();
    });
});


</script>

<script>
    
    
    $(document).on('click', 'input.order_checkbox', function(e){
        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();
        $('#check').val(order_ids);
        $('#pack').val(order_ids);
        
    });
    
</script>


    <script>
        function filter_order() {
            $.get({
                url: '{{route('admin.orders.inhouse-order-filter')}}',
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    toastr.success('{{\App\CPU\translate('order_filter_success')}}');
                    location.reload();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        };
    </script>
    <script>
        $('#from_date,#to_date').change(function () {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if(fr != ''){
                $('#to_date').attr('required','required');
            }
            if(to != ''){
                $('#from_date').attr('required','required');
            }
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('{{\App\CPU\translate('Invalid date range')}}!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })
    </script>
    
<script>

$(document).ready(function() {
    $('#download-selected-packing-pdf-btn').click(function() {
        var selectedRecords = $('input[name="selected_records[]"]:checked').map(function() {
            return this.value;
        }).get();

        if (selectedRecords.length > 0) {
            downloadSelectedPackingPDF(selectedRecords);
        } else {
            alert('Please select at least one record to generate PDF.');
        }
    });

    function downloadSelectedPackingPDF(selectedRecords) {
        $.ajax({
            type: 'POST',
            url: '{{ route('admin.orders.download-selected-packing-pdf') }}',
            data: {
                selected_records: selectedRecords
            },
            xhrFields: {
                responseType: 'blob' // Important for handling binary data
            },
            success: function(response) {
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'selected_packing.pdf';
                link.click();
            },
            error: function() {
                alert('Error generating or downloading the PDF.');
            }
        });
    }
});



</script>



@endpush
