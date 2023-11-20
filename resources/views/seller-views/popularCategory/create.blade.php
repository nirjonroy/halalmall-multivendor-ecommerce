@extends('layouts.back-end.app-seller')

@section('title',\App\CPU\translate('Add Popular Category'))

@push('css_or_js')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/fontawesome.min.css" integrity="sha512-siarrzI1u3pCqFG2LEzi87McrBmq6Tp7juVsdmGY1Dr8Saw+ZBAzDzrGwX3vgxX1NkioYNCFOVC0GpDPss10zQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')

<div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/products.png')}}" alt="">
                Popular Category
                <span class="badge badge-soft-dark radius-50 fz-14 ml-1">Popular Category</span>
            </h2>
        </div>

        <div class="container-fluid">
                        <form action="{{ route('seller.store-popular-category') }}" method="POST">
                            @csrf
                                  <input type="hidden" value="{{ auth('seller')->id() }}" name="seller_id" id="">  
                            <div class="form-group">
                                <label for="">Category</label>
                                <select name="category_id" id="" class="form-control">
                                    <option value="">select</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button class="btn btn-primary" type="submit">Save</button>
                        </form>
                    </div>
</div>       


    <div class="container-fluid">
         <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Popular Category name</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    @foreach($popularCategories as $pcats)
      <tr>
        <td>{{$pcats->id}}</td>
        <td>{{$pcats->category->name}}</td>
        <td><a href="{{ route('seller.delete-popular-category', $pcats->id) }}" class="btn btn-success">Delete</a></td>
      </tr>
    @endforeach  
    </tbody>
  </table>
    </div>







@endsection