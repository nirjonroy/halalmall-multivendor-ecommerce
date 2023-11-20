@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Order Details'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/all-orders.png')}}" alt="">
                {{\App\CPU\translate('order_details')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row gy-3" id="printableArea">
            <div class="col-lg-8 col-xl-9">
                <!-- Card -->
                <div class="card h-100">
                    <!-- Body -->
                    <div class="card-body">

                        <!-- Order Note -->
                        
                        <table class="table table-bordered">
                          <tbody>
                            <tr>
                              
                              <td>MARCHANT</td>
                              <td> 
                              {{ $order->seller->shop->name }}
                                                <br/>
                                                {{ $order->seller->shop->address }}
                                                <br/>
                                                 {{ $order->seller->shop->contact }}
                            </td>
                             
                            </tr>
                            <tr>
                             
                              <td>CUSTOMER</td>
                                            <td>
                                                {{$order->customer['f_name'].' '.$order->customer['l_name']}}
                                                <br/>
                                                {{$order->customer['phone']}}
                                                <br/>
                                               {{$shipping_address ? $shipping_address->address  : ''}}
                                            </td>
                             
                            </tr>
                            <tr>
                              <th scope="row">
                                   <div class="item style24" style="">
            <span
                class="barcode_site text-capitalize"></span>
            <span class="barcode_name text-capitalize"></span>
            @if ($order->id !== null)
                <div class="barcode_image">
                <img height="115" width="115" src="data:image/png;base64,{{ DNS2D::getBarcodePNG($order->id.' '.$order->payment_status.' '.$order->customer->f_name.' '.$order->customer->phone.' '.$order->order_amount,'QRCODE') }}" alt="barcode" />
               </div>



                <div class="barcode_code text-capitalize"></div>
            @else
                <p class="text-danger">{{ \App\CPU\translate('please_update_product_code') }}</p>
            @endif
            
        </div>
                              </th>
                              <td colspan="2">
                                  <!--<div class="mb-3">{!! DNS1D::getBarcodeHTML('4445645656', 'PHARMA') !!}</div>-->
                                  <!--<div class="mb-3">{!! DNS1D::getBarcodeHTML($order->id, 'UPCA') !!}</div>-->
                                  <!--<div class="mb-3">{!! DNS1D::getBarcodeHTML($order->id, 'CODABAR') !!}</div>-->
                                 <div class="mb-3">{!! DNS1D::getBarcodeHTML($order->id, 'C39') !!}</div>
                                <div class="barcode_code text-capitalize" style="margin-left: 15%;">
                                    {{ $order->id }}
                                </div>
                              </td>
                              
                            </tr>
                          </tbody>
                        </table>

                       
                       
                        <hr />
                       
                        <!-- End Row -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>

          
        </div>
        <!-- End Row -->
    </div>

    <!--Show locations on map Modal -->
    <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"
                        id="locationModalLabel">{{\App\CPU\translate('location')}} {{\App\CPU\translate('data')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 modal_body_map">
                            <div class="location-map" id="location-map">
                                <div class="w-100 __h-400px" id="location_map_canvas"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!--Show delivery info Modal -->
    <div class="modal" id="shipping_chose" role="dialog" tabindex="-1" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{\App\CPU\translate('update_third_party_delivery_info')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{route('admin.orders.update-deliver-info')}}" method="POST">
                                @csrf
                                <input type="hidden" name="order_id" value="{{$order['id']}}">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="">{{\App\CPU\translate('delivery_service_name')}}</label>
                                        <input class="form-control" type="text" name="delivery_service_name" value="{{$order['delivery_service_name']}}" id="" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">{{\App\CPU\translate('tracking_id')}} ({{\App\CPU\translate('optional')}})</label>
                                        <input class="form-control" type="text" name="third_party_delivery_tracking_id" value="{{$order['third_party_delivery_tracking_id']}}" id="">
                                    </div>
                                    <button class="btn btn--primary" type="submit">{{\App\CPU\translate('update')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
@endsection

@push('script_2')
    <script>
        $(document).on('change', '.payment_status', function () {
            var id = $(this).attr("data-id");
            var value = $(this).val();
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure Change this')}}?',
                text: "{{\App\CPU\translate('You will not be able to revert this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{\App\CPU\translate('Yes, Change it')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.orders.payment-status')}}",
                        method: 'POST',
                        data: {
                            "id": id,
                            "payment_status": value
                        },
                        success: function (data) {
                            if(data.customer_status==0)
                            {
                                toastr.warning('{{\App\CPU\translate('Account has been deleted, you can not change the status!')}}!');
                                // location.reload();
                            }else
                            {
                                toastr.success('{{\App\CPU\translate('Status Change successfully')}}');
                                // location.reload();
                            }
                        }
                    });
                }
            })
        });

        function order_status(status) {
            @if($order['order_status']=='delivered')
            Swal.fire({
                title: '{{\App\CPU\translate('Order is already delivered, and transaction amount has been disbursed, changing status can be the reason of miscalculation')}}!',
                text: "{{\App\CPU\translate('Think before you proceed')}}.",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{\App\CPU\translate('Yes, Change it')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.orders.status')}}",
                        method: 'POST',
                        data: {
                            "id": '{{$order['id']}}',
                            "order_status": status
                        },
                        success: function (data) {

                            if (data.success == 0) {
                                toastr.success('{{\App\CPU\translate('Order is already delivered, You can not change it')}} !!');
                                // location.reload();
                            } else {

                                if(data.payment_status == 0){
                                    toastr.warning('{{\App\CPU\translate('Before delivered you need to make payment status paid!')}}!');
                                    // location.reload();
                                }else if(data.customer_status==0)
                                {
                                    toastr.warning('{{\App\CPU\translate('Account has been deleted, you can not change the status!')}}!');
                                    // location.reload();
                                }
                                else{
                                    toastr.success('{{\App\CPU\translate('Status Change successfully')}}!');
                                    // location.reload();
                                }
                            }

                        }
                    });
                }
            })
            @else
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure Change this')}}?',
                text: "{{\App\CPU\translate('You will not be able to revert this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{\App\CPU\translate('Yes, Change it')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.orders.status')}}",
                        method: 'POST',
                        data: {
                            "id": '{{$order['id']}}',
                            "order_status": status
                        },
                        success: function (data) {
                            if (data.success == 0) {
                                toastr.success('{{\App\CPU\translate('Order is already delivered, You can not change it')}} !!');
                                // location.reload();
                            } else {
                                if(data.payment_status == 0){
                                    toastr.warning('{{\App\CPU\translate('Before delivered you need to make payment status paid!')}}!');
                                    // location.reload();
                                }else if(data.customer_status==0)
                                {
                                    toastr.warning('{{\App\CPU\translate('Account has been deleted, you can not change the status!')}}!');
                                    // location.reload();
                                }else{
                                    toastr.success('{{\App\CPU\translate('Status Change successfully')}}!');
                                    // location.reload();
                                }
                            }

                        }
                    });
                }
            })
            @endif
        }
    </script>
    <script>
        $( document ).ready(function() {
            let delivery_type = '{{$order->delivery_type}}';


            if(delivery_type === 'self_delivery'){
                $('.choose_delivery_man').show();
                $('#by_third_party_delivery_service_info').hide();
            }else if(delivery_type === 'third_party_delivery')
            {
                $('.choose_delivery_man').hide();
                $('#by_third_party_delivery_service_info').show();
            }else{
                $('.choose_delivery_man').hide();
                $('#by_third_party_delivery_service_info').hide();
            }
        });
    </script>
    <script>
        function choose_delivery_type(val)
        {

            if(val==='self_delivery')
            {
                $('.choose_delivery_man').show();
                $('#by_third_party_delivery_service_info').hide();
            }else if(val==='third_party_delivery'){
                $('.choose_delivery_man').hide();
                $('#deliveryman_charge').val(null);
                $('#expected_delivery_date').val(null);
                $('#by_third_party_delivery_service_info').show();
                $('#shipping_chose').modal("show");
            }else{
                $('.choose_delivery_man').hide();
                $('#by_third_party_delivery_service_info').hide();
            }

        }
    </script>
    <script>
        function addDeliveryMan(id) {
            $.ajax({
                type: "GET",
                url: '{{url('/')}}/admin/orders/add-delivery-man/{{$order['id']}}/' + id,
                data: {
                    'order_id': '{{$order['id']}}',
                    'delivery_man_id': id
                },
                success: function (data) {
                    if (data.status == true) {
                        toastr.success('Delivery man successfully assigned/changed', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    } else {
                        toastr.error('Deliveryman man can not assign/change in that status', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function () {
                    toastr.error('Add valid data', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }

        function last_location_view() {
            toastr.warning('Only available when order is out for delivery!', {
                CloseButton: true,
                ProgressBar: true
            });
        }

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        function waiting_for_location() {
            toastr.warning('{{\App\CPU\translate('waiting_for_location')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }

        function amountDateUpdate(t, e){
            let field_name = $(t).attr('name');
            let field_val = $(t).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.orders.amount-date-update')}}",
                method: 'POST',
                data: {
                    'order_id': '{{$order['id']}}',
                    'field_name': field_name,
                    'field_val': field_val
                },
                success: function (data) {
                    if (data.status == true) {
                        toastr.success('Deliveryman charge add successfully', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    } else {
                        toastr.error('Failed to add deliveryman charge', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function () {
                    toastr.error('Add valid data', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{\App\CPU\Helpers::get_business_settings('map_api_key')}}&v=3.49"></script>
    <script>

        function initializegLocationMap() {
            var map = null;
            var myLatlng = new google.maps.LatLng({{$shipping_address->latitude ?? null}}, {{$shipping_address->longitude ?? null}});
            var dmbounds = new google.maps.LatLngBounds(null);
            var locationbounds = new google.maps.LatLngBounds(null);
            var dmMarkers = [];
            dmbounds.extend(myLatlng);
            locationbounds.extend(myLatlng);

            var myOptions = {
                center: myLatlng,
                zoom: 13,
                mapTypeId: google.maps.MapTypeId.ROADMAP,

                panControl: true,
                mapTypeControl: false,
                panControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.LARGE,
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                scaleControl: false,
                streetViewControl: false,
                streetViewControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                }
            };
            map = new google.maps.Map(document.getElementById("location_map_canvas"), myOptions);
            console.log(map);
            var infowindow = new google.maps.InfoWindow();

            @if($shipping_address && isset($shipping_address))
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng({{$shipping_address->latitude}}, {{$shipping_address->longitude}}),
                map: map,
                title: "{{$order->customer['f_name']??""}} {{$order->customer['l_name']??""}}",
                icon: "{{asset('public/assets/front-end/img/customer_location.png')}}"
            });

            google.maps.event.addListener(marker, 'click', (function (marker) {
                return function () {
                    infowindow.setContent("<div class='float-left'><img class='__inline-5' src='{{asset('storage/app/public/profile/')}}{{$order->customer->image??""}}'></div><div class='float-right __p-10'><b>{{$order->customer->f_name??""}} {{$order->customer->l_name??""}}</b><br/>{{$shipping_address->address??""}}</div>");
                    infowindow.open(map, marker);
                }
            })(marker));
            locationbounds.extend(marker.getPosition());
            @endif

            google.maps.event.addListenerOnce(map, 'idle', function () {
                map.fitBounds(locationbounds);
            });
        }

        // Re-init map before show modal
        $('#locationModal').on('shown.bs.modal', function (event) {
            initializegLocationMap();
        });
    </script>
@endpush
