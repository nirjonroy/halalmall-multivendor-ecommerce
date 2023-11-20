@if(count($combinations[0]) > 0)
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
	@php
		$sku = '';
		foreach (explode(' ', $product_name) as $key => $value) {
			$sku .= substr($value, 0, 1);
		}

		$str = '';
		foreach ($combination as $key => $item){
			if($key > 0 ){
				$str .= '-'.str_replace(' ', '', $item);
				$sku .='-'.str_replace(' ', '', $item);
			}
			else{
				if($colors_active == 1){
					$color_name = \App\Model\Color::where('code', $item)->first()->name;
					$str .= $color_name;
					$sku .='-'.$color_name;
				}
				else{
					$str .= str_replace(' ', '', $item);
					$sku .='-'.str_replace(' ', '', $item);
				}
			}
		}
	@endphp
	@if(strlen($str) > 0)
			<tr>
				<td>
					<label for="" class="control-label">{{ $str }}</label>
				</td>
				<td>
					<input type="number" name="price_{{ $str }}" value="{{ $unit_price }}" min="0" step="0.01" class="form-control" required>
				</td>
				<td>
					<input type="text" name="sku_{{ $str }}" value="{{ $sku }}" class="form-control" required>
				</td>
				<td>
					<input type="number" name="qty_{{ $str }}" value="1" min="0" max="100000" step="1" class="form-control" required>
				</td>
			</tr>
	@endif
@endforeach
	</tbody>
</table>

@if(count($colors) > 0)
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
		@foreach($colors as $key => $code)
		 <tr>
			<td>
				{{ \App\Model\Color::where('code', $code)->first()->name }}
			</td>
			<td>
				<input type="file" id="variation_image" name="variation_image[]" class="form-control">
				<input type="hidden" name="variation_code[]" value="{{ $code }}">
				<input id="products_variation_image_name" type="hidden" name="products_variation_image_name[]">
			</td>
			<td>
				<img src="{{ asset('public/assets/back-end/img/400x400/img2.jpg') }}" height="100" width="200">
			</td>
		 </tr>
		@endforeach
	</tbody>
</table>
@endif

<script>

    const product_variation_array = [];
    $(document).on('change', '#variation_image', function() {
    const fileInput = this;
    if (fileInput.files && fileInput.files[0]) {
        const formData = new FormData();
        formData.append('variation_image', fileInput.files[0]);
        
        $.ajax({
            url: '{{ route('seller.product.upload_variation_image') }}', // Replace with your upload route
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                product_variation_array.push(response.image);
                $('#products_variation_image_name').val(product_variation_array);
                // Handle success, response will contain the uploaded image name or URL
                console.log('Image uploaded:', response);

                // You can update your UI or take further actions here
            },
            error: function(error) {
                // Handle error
                console.error('Error uploading image:', error);
            }
        });
    }
});

	update_qty();
	function update_qty()
	{
		var total_qty = 0;
		var qty_elements = $('input[name^="qty_"]');
		for(var i=0; i<qty_elements.length; i++)
		{
			total_qty += parseInt(qty_elements.eq(i).val());
		}
		if(qty_elements.length > 0)
		{
			$('input[name="current_stock"]').attr("readonly", true);
			$('input[name="current_stock"]').val(total_qty);
		}
		else{
			$('input[name="current_stock"]').attr("readonly", false);
		}
	}
	$('input[name^="qty_"]').on('keyup', function () {
		var total_qty = 0;
		var qty_elements = $('input[name^="qty_"]');
		for(var i=0; i<qty_elements.length; i++)
		{
			total_qty += parseInt(qty_elements.eq(i).val());
		}
		$('input[name="current_stock"]').val(total_qty);
	});

</script>
