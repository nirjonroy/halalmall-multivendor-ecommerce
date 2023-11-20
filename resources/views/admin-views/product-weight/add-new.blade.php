@extends('layouts.back-end.app')
@section('title', 'Add Product Weight')

@push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
        <h2 class="h1 mb-0 d-flex align-items-center gap-2">
            <img width="20" src="{{asset('/public/assets/back-end/img/brand.png')}}" alt="">
            Add Product Weight
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <form action="{{route('admin.product-weight.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">

                            <div class="form-group">
                                    <label for="name" class="title-color">Weight Title<span class="text-danger">*</span></label>
                                    <div class="text-left">
                                        <input type="text" name="title" class="form-control"
                                           placeholder="Weight title here..." required>
                                    </div>
                                </div>                                
                                <div class="form-group">
                                    <label for="name" class="title-color">Weight Amount<span class="text-danger">*</span></label>
                                    <div class="text-left">
                                        <input type="number" name="amount" class="form-control"
                                           placeholder="Weight amount here..." required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-3 justify-content-end">
                            <button type="reset" id="reset" class="btn btn-secondary px-4">{{ \App\CPU\translate('reset')}}</button>
                            <button type="submit" class="btn btn--primary px-4">{{ \App\CPU\translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
       
    </script>
@endpush
