@extends('layouts.back-end.app')

@section('title', 'Product Weight List')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/brand.png')}}" alt="">
                Product Weight List
                <span class="badge badge-soft-dark radius-50 fz-14">0 </span>
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <!-- Data Table Top -->
                    <div class="px-3 py-4">
                        <div class="row g-2 flex-grow-1">
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search_by_Name')}}" aria-label="Search by ID or name" value="0" required>
                                        <button type="submit" class="btn btn--primary input-group-text">{{ \App\CPU\translate('Search')}}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                            <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                                <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                    <i class="tio-download-to"></i>
                                    {{\App\CPU\translate('export')}}
                                    <i class="tio-chevron-down"></i>
                                </button>

                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.brand.export') }}">Excel</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Data Table Top -->

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>
                                        SL
                                    </th>
                                    <th>Weight Title</th>
                                    <th>Weight Amount</th>
                                    <th class="text-center">
                                        {{ \App\CPU\translate('action')}}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($product_weights as $k=>$b)
                                    <tr>
                                        <td>{{$k+1}}</td>
                                        <td>{{$b->title}}</td>
                                        <td>{{$b->amount}}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="btn btn-outline-info btn-sm square-btn" title="{{ \App\CPU\translate('Edit')}}"
                                                href="{{route('admin.product-weight.edit',[$b->id])}}">
                                                <i class="tio-edit"></i>
                                                </a>
                                                <form id="delete_weight" action="{{ route('admin.product-weight.destroy', [$b->id]) }}" method="POST">
                                                @csrf
                                                @method("DELETE")
                                                <button type="submit" class="btn btn-outline-danger btn-sm delete square-btn" title="{{ \App\CPU\translate('Delete')}}"
                                                >
                                                    <i class="tio-delete"></i>
                                                </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <div class="d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{$product_weights->links()}}
                        </div>
                    </div>
                    @if(count($product_weights)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                            <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).on('submit', 'form#delete_weight', function (e) {
            e.preventDefault();
            let url = $(this).attr("action");
            let method = $(this).attr("method");
            let data = $(this).serialize();
            Swal.fire({
                title: '{{ \App\CPU\translate('Are_you_sure_delete_this_brand')}}?',
                text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this')}}!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes')}}, {{ \App\CPU\translate('delete_it')}}!',
                cancelButtonText: "{{ \App\CPU\translate('cancel')}}",
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url,
                        method,
                        data,
                        success: function () {
                            toastr.success('Product weight deleted successfully');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
