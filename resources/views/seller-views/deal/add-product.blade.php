@extends('layouts.back-end.app-seller')
@section('title', \App\CPU\translate('Deal Product'))
@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('public/assets/back-end/css/custom.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize">
            <img src="{{asset('/public/assets/back-end/img/inhouse-product-list.png')}}" class="mb-1 mr-1" alt="">
            {{\App\CPU\translate('Add_new_product')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 text-capitalize">{{$deal['title']}}</h3>
                    <input type="hidden" class="minimum_discount_amount" value="{{ $deal['minimum_discount_amount'] }}">
                </div>
                <div class="card-body">
                    <form action="{{route('seller.deal.add-product',[$deal['id']])}}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="name" class="title-color text-capitalize">{{ \App\CPU\translate('Add new product')}}</label>
                                    <select
                                        class="js-example-basic-multiple js-states js-example-responsive form-control"
                                        name="product_id">
                                        <option disabled selected>
                                            {{ \App\CPU\translate('Select product')}}
                                        </option>
                                        @foreach ($seller_products as $key => $product)
                                            <option value="{{ $product->id }}">
                                                {{$product['name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn--primary px-4">{{ \App\CPU\translate('Add')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="px-3 py-4">
                    <h5 class="mb-0 text-capitalize">
                        {{ \App\CPU\translate('Product')}} {{ \App\CPU\translate('Table')}}
                        <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $products->total() }}</span>
                    </h5>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100" cellspacing="0">
                        <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{ \App\CPU\translate('SL')}}</th>
                                <th>{{ \App\CPU\translate('image')}}</th>
                                <th>{{ \App\CPU\translate('name')}}</th>
                                <th>{{ \App\CPU\translate('price')}}</th>
                                <th>{{ \App\CPU\translate('Discount Price')}}<br/> <span style="color: red;">(Minimum Discount {{ $deal['minimum_discount_amount'] }}%)</span></th>
                                <th>{{ \App\CPU\translate('After Discount Price')}}</th>
                                <th class="text-center">{{ \App\CPU\translate('action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $k=>$de_p)
                            <tr>
                                <td>{{$products->firstitem()+$k}}</td>
                                <td><img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$de_p['thumbnail']}}"
                                    onerror="this.src='{{asset('/public/assets/back-end/img/brand-logo.png')}}'"class="avatar border" alt=""></td>
                                    <td><a href="#" target="_blank" class="font-weight-semibold title-color hover-c1">{{$de_p['name']}}</a></td>
                                    <td>{{\App\CPU\BackEndHelper::usd_to_currency($de_p['unit_price'])}}</td>
                                <input type="hidden" name="product_price" class="product_price" value="{{ $de_p['unit_price'] }}">
                                <input type="hidden" class="product_id" value="{{ $de_p['id'] }}">
                                <input type="hidden" class="minimum_discount_amount" value="{{ $de_p['id'] }}">
                                <input type="hidden" value="percent" class="discount_type">
                                <td style="width: 15%;">
                                    
                                    <input class="discount_value form-control" value="{{ $de_p['discount'] }}" type="number" name="discount">
                                    
                                </td>
                                <td>
                                    <?php
                                     $unit_price = $de_p['unit_price'];
                                     $discount = $de_p['discount'];
                                     $total_discount = ($unit_price / 100) * $discount;
                                     $after_discount = $unit_price - $total_discount;
                                     echo $after_discount;
                                    ?>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        @if($de_p->user_id == auth('seller')->id())
                                        <a  title="{{ trans ('Delete')}}"
                                            class="btn btn-outline-danger btn-sm delete"
                                            id="{{$de_p['id']}}">
                                            <i class="tio-delete"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <table>
                        <tfoot>
                            {!! $products->links() !!}
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });

        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });

        $(document).on('blur', '.discount_value', function () {
            let mdp = $('.minimum_discount_amount').val();
            let tr =$(this).closest('tr');
            var discount_type = Number(tr.find('.discount_type').val());
            var discount_value = Number(tr.find('.discount_value').val());
            var product_price = tr.find('.product_price').val();
            var product_id = tr.find('.product_id').val();
            
            if(discount_value == 0)
            {
                toastr.error('{{\App\CPU\translate('Please Increase Your Discount Price')}}');
                return false; 
            } else {
                if(mdp > discount_value)
                {
                   toastr.error('{{\App\CPU\translate('Please Increase Your Discount Price')}}');
                   return false;
                }
            }
            

            $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
            });

            $.ajax({
                url: "{{route('seller.deal.discount-update')}}",
                method: 'GET',
                data: {
                    discount_type : discount_type,
                    discount_value: discount_value,
                    product_price : product_price,
                    product_id    : product_id,
                    mdpp          : mdp
                },
                success: function (res) {
                    if(res.success)
                    {
                        toastr.success('{{\App\CPU\translate('Discount updated successfully')}}');
                    }

                    else if(res.success == false) {
                        alert('hi');
                    } else {

                    }
                    window.location.reload();

                }
            });

        });

        $(document).on('change', '.status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('seller.deal.status-update')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function () {
                    toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{\App\CPU\translate('Are_you_sure_remove_this_product')}}?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{\App\CPU\translate('Yes')}}, {{\App\CPU\translate('delete_it')}}!',
                type: 'warning',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('seller.deal.delete-product')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function (data) {
                            toastr.success('{{\App\CPU\translate('product_removed_successfully')}}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
