@extends('layouts.back-end.app')
@section('title', 'Review Reply')
@push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Heading -->
        <div class="container">
            <!-- Page Title -->
            <div class="mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img width="20" src="{{asset('/public/assets/back-end/img/message.png')}}" alt="">
                    Review Details
                </h2>
            </div>
            <!-- End Page Title -->

            <!-- Content Row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex">
                                <i class="tio-user-big"></i>
                                Review Details
                            </h5>
                            <h5>Product : {{ $review->product->name }}</h5>
                            <h5>Customer : {{ $review->customer->f_name }}</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-user-information table-borderless mb-0">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Comment</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($replies as $key=>$reply)
                                    <tr>
                                        <td>{{ucFirst($reply->reply_type)}}</td>
                                        <td>{{$reply->comment}}</td>
                                        <td>{{$reply->updated_at->format('d F, Y')}}</td>
                                        <td>
                                            <a class="btn btn-outline-danger btn-sm square-btn" href="javascript:"
                                                    title="{{\App\CPU\translate('Delete')}}"
                                                    onclick="form_alert('product-{{$reply->id}}','Want to delete this review ?')">
                                                    <i class="tio-delete"></i>
                                            </a>
                                            <form action="{{route('admin.reviews.delete',[$reply->id])}}"
                                                method="post" id="product-{{$reply->id}}">
                                                @csrf 
                                                @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')

@endpush
