@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Review Create'))

@section('content')

<!-- Page Content-->
<div class="container pb-5 mb-2 mb-md-4 mt-2">
    <div class="row">
        <!-- Sidebar-->
        <section class="col-lg-12  col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="">Create Fake Review</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.reviews.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">

                        <div class="form-group">
                                <label for="exampleInputEmail1">Customer Name</label>
                                <input type="text" class="form-control" name="customer" value="{{ old('customer') }}" required>
                            </div>                            
                            <div class="form-group">
                                <label for="exampleInputEmail1">Choose Product</label>
                                <select class="js-select2-custom form-control" name="product_id" required>
                                    <option value="">Please Select Product</option>
                                    @foreach($products as $key => $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">{{\App\CPU\translate('rating')}}</label>
                                <select class="js-example-basic-multiple form-control" name="rating">
                                    <option value="1">{{\App\CPU\translate('1')}}</option>
                                    <option value="2">{{\App\CPU\translate('2')}}</option>
                                    <option value="3">{{\App\CPU\translate('3')}}</option>
                                    <option value="4">{{\App\CPU\translate('4')}}</option>
                                    <option value="5">{{\App\CPU\translate('5')}}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">{{\App\CPU\translate('comment')}}</label>
                                <textarea class="form-control" name="comment"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">{{\App\CPU\translate('attachment')}}</label>
                                <div class="row coba"></div>
                                <div class="mt-1 text-info">{{\App\CPU\translate('File type: jpg, jpeg, png. Maximum size: 2MB')}}</div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <a href="{{ URL::previous() }}" class="btn btn-secondary">{{\App\CPU\translate('back')}}</a>
                            <input type="hidden" name="delivery_man_id" value="">
                            <button type="submit" class="btn btn--primary">{{\App\CPU\translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

</div>
@endsection

@push('script')
    <script src="{{asset('public/assets/front-end/js/spartan-multi-image-picker.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $(".coba").spartanMultiImagePicker({
                fieldName: 'fileUpload[]',
                maxCount: 5,
                rowHeight: '150px',
                groupClassName: 'col-md-4',
                placeholderImage: {
                    image: '{{asset('public/assets/front-end/img/image-place-holder.png')}}',
                    width: '100%'
                },
                dropFileLabel: "{{\App\CPU\translate('drop_here')}}",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{\App\CPU\translate('input_png_or_jpg')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{\App\CPU\translate('file_size_too_big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
@endpush
