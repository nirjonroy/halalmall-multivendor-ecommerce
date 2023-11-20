@if(count($combinations) > 0)
<table class="table table-bordered">
    <thead>
    <tr>
        <td class="text-center">
            <label for="" class="control-label">{{\App\CPU\translate('Variant')}}</label>
        </td>
        <td class="text-center">
            <label for="" class="control-label">{{\App\CPU\translate('Variant Price')}}</label>
        </td>
        <td class="text-center">
            <label for="" class="control-label">{{\App\CPU\translate('SKU')}}</label>
        </td>
        <td class="text-center">
            <label for="" class="control-label">{{\App\CPU\translate('Quantity')}}</label>
        </td>
    </tr>
    </thead>
    <tbody>
    @endif
    @foreach ($combinations as $key => $combination)
        <tr>
            <td>
                <label for="" class="control-label">{{ $combination['type'] }}</label>
                <input value="{{ $combination['type'] }}" name="type[]" class="d-none">
            </td>
            <td>
                <input type="number" name="price_{{ $combination['type'] }}"
                       value="{{ \App\CPU\Convert::default($combination['price']) }}" min="0"
                       step="0.01"
                       class="form-control" required>
            </td>
            <td>
                <input type="text" name="sku_{{ $combination['type'] }}" value="{{ $combination['sku'] }}"
                       class="form-control" >
            </td>
            <td>
                <input type="number" onkeyup="update_qty()" name="qty_{{ $combination['type'] }}" value="{{ $combination['qty'] }}" min="1" max="100000" step="1"
                       class="form-control"
                       required>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@if(count($items) > 0)
<hr>
<h4>Choose Variation Image</h4>
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Variation</th>
			<th>Choose Image</th>
			<th>Preview Image</th>
		</tr>
	</thead>
	<tbody>
		@foreach($items as $key => $item)
		 <tr>
			<td>
				{{ \App\Model\Color::where('code', $item->variation)->first()->name }}
			</td>
			<td>
				<input type="file" name="variation_image[]" class="form-control">
				<input type="hidden" name="line_id[]" value="{{ $item->id }}">
				<input type="hidden" name="variation_code[]" value="{{ $item->variation }}">
				<input type="hidden" name="product_id[]" value="{{ $item->id }}">
			</td>
			<td>
				<img src="{{asset('storage/app/public/product/variation')}}/{{$item->image}}" height="100" width="200">
			</td>
		 </tr>
		@endforeach
	</tbody>
</table>
@endif

