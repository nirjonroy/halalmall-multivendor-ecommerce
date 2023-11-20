@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Withdraw information View'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">

@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/withdraw-icon.png')}}" alt="">
                {{\App\CPU\translate('withdraw')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Page Heading -->
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <div class="text-capitalize d-flex align-items-center justify-content-between gap-2 border-bottom pb-2 mb-4">
                            <h3 class="text-capitalize">
                                {{\App\CPU\translate('seller')}} {{\App\CPU\translate('Withdraw')}} {{\App\CPU\translate('information')}}
                            </h3>

                            <i class="tio-wallet-outlined fz-30"></i>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="flex-start flex-wrap">
                                    <div><h5 class="text-capitalize">{{\App\CPU\translate('amount')}} : </h5></div>
                                    <div class="mx-1"><h5>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\Convert::default($seller->amount))}}</h5></div>
                                </div>
                                <div class="flex-start flex-wrap">
                                    <div><h5>{{\App\CPU\translate('request_time')}} : </h5></div>
                                    <div class="mx-1">{{$seller->created_at}}</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="flex-start">
                                    <div class="title-color">{{\App\CPU\translate('Seller Claim')}} :</div>
                                    <div class="mx-1">{{$seller->transaction_note}}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @if ($seller->approved== 0)
                                    <button type="button" class="btn btn-success float-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}" data-toggle="modal"
                                            data-target="#exampleModal">{{\App\CPU\translate('proceed')}}
                                        <i class="tio-arrow-forward"></i>
                                    </button>
                                @else
                                    <div class="text-center float-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">
                                        @if($seller->approved==1)
                                            <label class="badge badge-success p-2 rounded-bottom">
                                                {{\App\CPU\translate('Approved')}}
                                            </label>
                                        @else
                                            <label class="badge badge-danger p-2 rounded-bottom">
                                                {{\App\CPU\translate('Denied')}}
                                            </label>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">

                        <div class="text-capitalize d-flex align-items-center justify-content-between gap-2 border-bottom pb-3 mb-4">
                            <h3 class="h3 mb-0">{{\App\CPU\translate('my_bank_info')}} </h3>
                            <i class="tio tio-dollar-outlined"></i>
                        </div>

                        <div class="mt-2">
                            <div class="flex-start">
                                <div><h4>{{\App\CPU\translate('bank_name')}} : </h4></div>
                                <div class="mx-1"><h4>{{$seller->seller->bank_name ? $seller->seller->bank_name : 'No Data found'}}</h4></div>
                            </div>
                            <div class="flex-start">
                                <div><h6>{{\App\CPU\translate('Branch')}} : </h6></div>
                                <div class="mx-1"><h6>{{$seller->seller->branch ? $seller->seller->branch : 'No Data found'}}</h6></div>
                            </div>
                            <div class="flex-start">
                                <div><h6>{{\App\CPU\translate('holder_name')}} : </h6></div>
                                <div class="mx-1"><h6>{{$seller->seller->holder_name ? $seller->seller->holder_name : 'No Data found'}}</h6></div>
                            </div>
                            <div class="flex-start">
                                <div><h6>{{\App\CPU\translate('account_no')}} : </h6></div>
                                <div class="mx-1"><h6>{{$seller->seller->account_no ? $seller->seller->account_no : 'No Data found'}}</h6></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($seller->seller->shop)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">

                            <div class="text-capitalize d-flex align-items-center justify-content-between gap-2 border-bottom pb-3 mb-4">
                                <h3 class="h3 mb-0">{{\App\CPU\translate('shop_info')}} </h3>
                                <i class="tio tio-shop-outlined"></i>
                            </div>

                            <div class="flex-start">
                                <div><h5>{{\App\CPU\translate('seller_b')}} : </h5></div>
                                <div class="mx-1"><h5>{{$seller->seller->shop->name}}</h5></div>
                            </div>
                            <div class="flex-start">
                                <div><h5>{{\App\CPU\translate('Phone')}} : </h5></div>
                                <div class="mx-1"><h5>{{$seller->seller->shop->contact}}</h5></div>
                            </div>
                            <div class="flex-start">
                                <div><h5>{{\App\CPU\translate('address')}} : </h5></div>
                                <div class="mx-1"><h5>{{$seller->seller->shop->address}}</h5></div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <div class="text-capitalize d-flex align-items-center justify-content-between gap-2 border-bottom pb-3 mb-4">
                            <h3 class="h3 mb-0">{{\App\CPU\translate('seller_info')}} </h3>
                            <i class="tio tio-user-big-outlined"></i>
                        </div>
                        <div class="flex-start">
                            <div><h5>{{\App\CPU\translate('name')}} : </h5></div>
                            <div class="mx-1"><h5>{{$seller->seller->f_name}} {{$seller->seller->l_name}}</h5></div>
                        </div>
                        <div class="flex-start">
                            <div><h5>{{\App\CPU\translate('Email')}} : </h5></div>
                            <div class="mx-1"><h5>{{$seller->seller->email}}</h5></div>
                        </div>
                        <div class="flex-start">
                            <div><h5>{{\App\CPU\translate('Phone')}} : </h5></div>
                            <div class="mx-1"><h5>{{$seller->seller->phone}}</h5></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{\App\CPU\translate('Withdraw request process')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{route('admin.sellers.withdraw_status',[$seller['id']])}}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">{{\App\CPU\translate('Request')}}:</label>
                                    <select name="approved" class="custom-select" id="inputGroupSelect02">
                                        <option value="1">{{\App\CPU\translate('Approve')}}</option>
                                        <option value="2">{{\App\CPU\translate('Deny')}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="col-form-label">{{\App\CPU\translate('Note about transaction or request')}}:</label>
                                    <textarea class="form-control" name="note" id="message-text"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('Close')}}</button>
                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('script')

@endpush
