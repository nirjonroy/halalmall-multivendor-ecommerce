@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Color'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0">
                <img src="{{asset('/public/assets/back-end/img/attribute.png')}}" class="mb-1 mr-1" alt="">
                {{\App\CPU\translate('Update')}} {{\App\CPU\translate('Color')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-md-12 mb-10">
                <div class="card">
                    <div class="card-body"
                         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <form action="{{route('admin.color.update',[$color['id']])}}" method="post">
                            @csrf
                            
                               
                                <div class="form-group"
                                     id="">
                                    <input type="hidden" id="id">
                                    <label class="title-color" for="name">{{ \App\CPU\translate('Color')}} {{ \App\CPU\translate('Name')}}
                                       </label>
                                    <input type="text" name="name"
                                           value="{{ $color['name'] }}"
                                           class="form-control" id="name"
                                           placeholder="{{\App\CPU\translate('Enter_Color_Name')}}">
                                </div>
                                
                                 <div class="form-group"
                                     id="">
                                    <input type="hidden" id="id">
                                    <label class="title-color" for="name">{{ \App\CPU\translate('Color')}} {{ \App\CPU\translate('Name')}}
                                       </label>
                                    <input type="text" name="code"
                                           value="{{ $color['code'] }}"
                                           class="form-control" id="name"
                                           placeholder="{{\App\CPU\translate('Enter_Color_Code')}}">
                                </div>
                                
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" class="btn px-4 btn-secondary">{{ \App\CPU\translate('reset')}}</button>
                                <button type="submit" class="btn px-4 btn--primary">{{ \App\CPU\translate('update')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div>
            @endsection

            @push('script')
                <script>

                    $(document).ready(function () {
                        $('#dataTable').DataTable();
                    });
                </script>
    @endpush
