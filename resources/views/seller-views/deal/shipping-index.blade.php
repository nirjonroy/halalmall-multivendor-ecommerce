@extends('layouts.back-end.app-seller')

@section('title', 'Free Shipping')

@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/featured_deal.png')}}" alt="">
                Free Shipping
            </h2>
        </div>
        <!-- End Page Title -->

       
        <!--modal-->

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <h5 class="mb-0 text-capitalize align-items-center d-flex gap-2">
                                    Free Shipping Table
                                    <span class="badge badge-soft-dark radius-50 fz-12">{{ ($free_shipping->total()) }}</span>
                                </h5>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                               placeholder="{{\App\CPU\translate('Search by Title')}}" aria-label="Search orders" value="{{ $search }}" required>
                                        <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{ \App\CPU\translate('SL')}}</th>
                                <th>{{ \App\CPU\translate('Title')}}</th>
                                <th>{{ \App\CPU\translate('Start Date')}}</th>
                                <th>{{ \App\CPU\translate('End Date')}}</th>
                                <th>{{ \App\CPU\translate('active')}} / {{ \App\CPU\translate('expired')}}</th>
                                <th>{{ \App\CPU\translate('publish')}}</th>
                                <th class="text-center">{{ \App\CPU\translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($free_shipping as $k=>$deal)
                                <tr>
                                    <th>{{$k+1}}</th>
                                    <td>{{$deal['title']}}</td>
                                    <td>{{date('d-M-y',strtotime($deal['start_date']))}}</td>
                                    <td>{{date('d-M-y',strtotime($deal['end_date']))}}</td>
                                    <td>
                                        @if(\Carbon\Carbon::parse($deal['end_date'])->endOfDay()->isPast())
                                        <span class="badge badge-soft-danger"> {{ \App\CPU\translate('expired')}} </span>
                                        @else
                                        <span class="badge badge-soft-success"> {{ \App\CPU\translate('active')}} </span>
                                        @endif
                                    </td>
                                    <td>
                                    <label class="">
                                        @if($deal->status == 1)
                                        <span class="badge badge-soft-success">Yes </span>
                                        @else
                                        <span class="badge badge-soft-danger">No</span>
                                        @endif
                                    </label>
                                </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center gap-10">
                                            <a class="h-30 d-flex gap-2 align-items-center btn btn-soft-info btn-sm border-info" href="{{route('seller.deal.fship-product',[$deal['id']])}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 9 9" fill="none" class="svg replaced-svg">
                                                    <path d="M9 3.9375H5.0625V0H3.9375V3.9375H0V5.0625H3.9375V9H5.0625V5.0625H9V3.9375Z" fill="#00A3AD"></path>
                                                </svg>
                                                {{\App\CPU\translate('Add_Product')}}
                                            </a>

                                            <!--<a title="{{ trans ('Edit')}}" href="{{route('admin.deal.edit',[$deal['id']])}}" class="btn btn-outline--primary btn-sm edit">-->
                                            <!--    <i class="tio-edit"></i>-->
                                            <!--</a>-->
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>


                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{$free_shipping->links()}}
                        </div>
                    </div>

                    @if(count($free_shipping)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                            <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>

        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>

    <script>
        $(document).on('change', '.featured', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var featured = 1;
            } else if ($(this).prop("checked") == false) {
                var featured = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.deal.featured-update')}}",
                method: 'POST',
                data: {
                    id: id,
                    featured: featured
                },
                success: function () {
                    toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                    // location.reload();
                }
            });
        });
        $(document).on('change', '.status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") === true) {
                var status = 1;
            } else if ($(this).prop("checked") === false) {
                var status = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.deal.feature-status')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function () {
                    toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                    setTimeout(function (){
                        location.reload();
                    },1000);
                }
            });
        });

    </script>

    <!-- Page level custom scripts -->

    <script>
        $(document).ready(function () {
            // color select select2
            $('.color-var-select').select2({
                templateResult: colorCodeSelect,
                templateSelection: colorCodeSelect,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            function colorCodeSelect(state) {
                var colorCode = $(state.element).val();
                if (!colorCode) return state.text;
                return "<span class='color-preview' style='background-color:" + colorCode + ";'></span>" + state.text;
            }
        });
    </script>
@endpush
