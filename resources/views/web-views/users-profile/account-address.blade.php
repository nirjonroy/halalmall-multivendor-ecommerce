@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('My Address'))

@push('css_or_js')
    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.css"/>
    <link rel="stylesheet" href="{{ asset('public/assets/front-end/css/bootstrap-select.min.css') }}">

    <style>
        .cz-sidebar-body h3:hover + .divider-role {
            border-bottom: 3px solid {{$web_config['primary_color']}} !important;
        }
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: {{$web_config['primary_color']}};
        }

        .iconHad {
            color: {{$web_config['primary_color']}};
        }
        .namHad {
            padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 13px;
        }
        .modal-backdrop {
            z-index: 0 !important;
            display: none;
        }
        .donate-now li {
            margin: {{Session::get('direction') === "rtl" ? '0 0 0 5px' : '0 5px 0 0'}};
        }
        .donate-now input[type="radio"]:checked + label,
        .Checked + label {
            background: {{$web_config['primary_color']}};
        }
        .filter-option{
            display: block;
            width: 100%;
            height: calc(1.5em + 1.25rem + 2px);
            padding: 0.625rem 1rem;
            font-size: .9375rem;
            font-weight: 400;
            line-height: 1.5;
            color: #4b566b;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #dae1e7;
            border-radius: 0.3125rem;
            box-shadow: 0 0 0 0 transparent;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .btn-light + .dropdown-menu{
            transform: none !important;
            top: 41px !important;
        }
    </style>
@endpush

@section('content')
    <div class="__account-address">
        <div class="modal fade rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-name">{{\App\CPU\translate('add_new_address')}}</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('address-store')}}" method="post">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Nav pills -->
                                    <ul class="donate-now d-flex">
                                        <li>
                                            <input type="radio" id="a25" name="addressAs" value="permanent"/>
                                            <label for="a25" class="component">{{\App\CPU\translate('permanent')}}</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="a50" name="addressAs" value="home"/>
                                            <label for="a50" class="component">{{\App\CPU\translate('Home')}}</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="a75" name="addressAs" value="office" checked="checked"/>
                                            <label for="a75" class="component">{{\App\CPU\translate('Office')}}</label>
                                        </li>

                                    </ul>
                                </div>

                            <!--    <div class="col-md-6 d-flex">-->
                                    <!-- Nav pills -->

                            <!--    <ul class="donate-now">-->
                            <!--        <li>-->
                            <!--            <input type="radio" name="is_billing" id="b25" value="0" checked/>-->
                            <!--            <label for="b25" class="billing_component">{{\App\CPU\translate('shipping')}}</label>-->
                            <!--        </li>-->
                            <!--        <li>-->
                            <!--            <input type="radio" name="is_billing" id="b50" value="1"/>-->
                            <!--            <label for="b50" class="billing_component">{{\App\CPU\translate('billing')}}</label>-->
                            <!--        </li>-->
                            <!--    </ul>-->
                            <!--</div>-->
                        </div>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div id="home" class="container tab-pane active"><br>


                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="name">{{\App\CPU\translate('contact_person_name')}}</label>
                                            <input class="form-control" type="text" id="name" name="name" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="firstName">{{\App\CPU\translate('Phone')}}</label>
                                            <input class="form-control" type="text" id="phone" name="phone" required>
                                        </div>

                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="address-city">{{\App\CPU\translate('Country')}}</label>
                                        <select name="country" id="" class="form-control selectpicker" data-live-search="true">
                                            @foreach($data as $d)
                                                <option value="{{ $d['name'] }}">{{ $d['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="address-city">{{\App\CPU\translate('Division')}}</label>
                                        <select name="division" id="division" class="form-control" onchange="divisionsList();">
                                                                <option disabled selected>Select Division</option>
                                                                <option value="Barishal">Barishal</option>
                                                                <option value="Chattogram">Chattogram</option>
                                                                <option value="Dhaka">Dhaka</option>
                                                                <option value="Khulna">Khulna</option>
                                                                <option value="Mymensingh">Mymensingh</option>
                                                                <option value="Rajshahi">Rajshahi</option>
                                                                <option value="Rangpur">Rangpur</option>
                                                                <option value="Sylhet">Sylhet</option>
                                                              </select>
                                        <!--<input class="form-control" type="text" id="division" name="division" required>-->
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="address-city">{{\App\CPU\translate('City')}}</label>
                                        <select name="city" id="city" class="form-control" required onchange="thanaList();"></select>
                                        <!--<input class="form-control" type="text" id="address-city" name="city" required>-->
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="address-city">{{\App\CPU\translate('Thana')}}</label>
                                        <select name="thana" id="thana" class="form-control" required></select>
                                        <!--<input class="form-control" type="text" id="address-city" name="thana" required>-->
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="zip">{{\App\CPU\translate('Zip')}}</label>
                                            <input class="form-control" type="text" id="zip" name="zip" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="address">{{\App\CPU\translate('address')}}</label>

                                        <textarea class="form-control" id="address"
                                                            type="text"  name="address" required></textarea>
                                    </div>
                                    @php($default_location=\App\CPU\Helpers::get_business_settings('default_location'))
                                    <!--<div class="form-group col-md-12">-->
                                    <!--    <input id="pac-input" class="controls rounded __inline-46" title="{{\App\CPU\translate('search_your_location_here')}}" type="text" placeholder="{{\App\CPU\translate('search_here')}}"/>-->
                                    <!--    <div class="__h-200px" id="location_map_canvas"></div>-->
                                    <!--</div>-->
                                </div>
                            </div>
                            <input type="hidden" id="latitude"
                                name="latitude" class="form-control d-inline"
                                placeholder="Ex : -94.22213" value="{{$default_location?$default_location['lat']:0}}" required readonly>
                            <input type="hidden"
                                name="longitude" class="form-control"
                                placeholder="Ex : 103.344322" id="longitude" value="{{$default_location?$default_location['lng']:0}}" required readonly>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('close')}}</button>
                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Add')}} {{\App\CPU\translate('Informations')}}  </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            </div>
        </div>

        <!-- Page Content-->
        <div class="container pb-5 mb-2 rtl">
            <h3 class="py-3 text-center headerTitle">{{\App\CPU\translate('address')}}</h3>
            <div class="row">
                <!-- Sidebar-->
            @include('web-views.partials._profile-aside')
            <!-- Content  -->
                <section class="col-lg-9 col-md-9">

                    <!-- Addresses list-->
                    <div class="d-flex justify-content-end mb-3">
                        <button type="submit" class="btn btn--primary" data-toggle="modal"
                            data-target="#exampleModal" id="add_new_address">{{\App\CPU\translate('add_new_address')}}
                        </button>
                    </div>
                    <div class="row g-3">
                    @foreach($shippingAddresses as $shippingAddress)
                        <section class="col-lg-6 col-md-6">
                            <div class="card __shadow h-100">

                                    <div class="card-header d-flex justify-content-between d-flex align-items-center">
                                        <div>
                                            <i class="fa fa-thumb-tack fa-2x iconHad" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <span> {{\App\CPU\translate($shippingAddress['address_type'])}} {{\App\CPU\translate('address')}} ({{$shippingAddress['is_billing']==1?\App\CPU\translate('Billing_address'):\App\CPU\translate('shipping_address')}}) </span>
                                        </div>

                                        <div class="d-flex justify-content-between">


                                                <a class="" title="Edit Address" id="edit" href="{{route('address-edit',$shippingAddress->id)}}">
                                                    <i class="fa fa-edit fa-lg"></i>
                                                </a>

                                                <a class="" title="Delete Address" href="{{ route('address-delete',['id'=>$shippingAddress->id])}}" onclick="return confirm('{{\App\CPU\translate('Are you sure you want to Delete')}}?');" id="delete">
                                                    <i class="fa fa-trash fa-lg"></i>
                                                </a>

                                        </div>
                                    </div>


                                    {{-- Modal Address Edit --}}
                                    <div class="modal fade" id="editAddress_{{$shippingAddress->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog  modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <div class="row">
                                                    <div class="col-md-12"> <h5 class="modal-title font-name ">{{\App\CPU\translate('update')}} {{\App\CPU\translate('address')}}  </h5></div>
                                                </div>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="updateForm">
                                                        @csrf
                                                        <div class="row pb-1">
                                                            <div class="col-md-6 d-flex">
                                                                <!-- Nav pills -->
                                                                <input type="hidden" id="defaultValue" class="add_type" value="{{$shippingAddress->address_type}}">
                                                                <ul class="donate-now">
                                                                    <li class="address_type_li">
                                                                        <input type="radio" class="address_type" id="a25" name="addressAs" value="permanent"  {{ $shippingAddress->address_type == 'permanent' ? 'checked' : ''}} />
                                                                        <label for="a25" class="component">{{\App\CPU\translate('permanent')}}</label>
                                                                    </li>
                                                                    <li class="address_type_li">
                                                                        <input type="radio" class="address_type" id="a50" name="addressAs" value="home" {{ $shippingAddress->address_type == 'home' ? 'checked' : ''}} />
                                                                        <label for="a50" class="component">{{\App\CPU\translate('Home')}}</label>
                                                                    </li>
                                                                    <li class="address_type_li">
                                                                        <input type="radio" class="address_type" id="a75" name="addressAs" value="office" {{ $shippingAddress->address_type == 'office' ? 'checked' : ''}}/>
                                                                        <label for="a75" class="component">{{\App\CPU\translate('Office')}}</label>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                        </div>
                                                        <!-- Tab panes -->
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="person_name">{{\App\CPU\translate('contact_person_name')}}</label>
                                                                <input class="form-control" type="text" id="person_name"
                                                                    name="name"
                                                                    value="{{$shippingAddress->contact_person_name}}"
                                                                    required>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="own_phone">{{\App\CPU\translate('Phone')}}</label>
                                                                <input class="form-control" type="text" id="own_phone" name="phone" value="{{$shippingAddress->phone}}" required="required">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="city">{{\App\CPU\translate('City')}}</label>

                                                                    <input class="form-control" type="text" id="city" name="city" value="{{$shippingAddress->city}}" required>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="zip_code">{{\App\CPU\translate('zip_code')}}</label>
                                                                    <input class="form-control" type="text" id="zip_code" name="zip" value="{{$shippingAddress->zip}}" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                <label for="own_state">{{\App\CPU\translate('State')}}</label>
                                                                    <input type="text" class="form-control" name="state" value="{{ $shippingAddress->state }}" id="own_state"  placeholder="" required>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                <label for="own_country">{{\App\CPU\translate('Country')}}</label>
                                                                    <input type="text" class="form-control" id="own_country" name="country" value="{{ $shippingAddress->country }}" placeholder="" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">

                                                                <div class="form-group col-md-12">
                                                                    <label for="own_address">{{\App\CPU\translate('address')}}</label>
                                                                    <input class="form-control" type="text" id="own_address"
                                                                        name="address"
                                                                        value="{{$shippingAddress->address}}" required>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" id="latitude"
                                                                name="latitude" class="form-control d-inline"
                                                                placeholder="Ex : -94.22213" value="{{$default_location?$default_location['lat']:0}}" required readonly>
                                                            <input type="hidden"
                                                                name="longitude" class="form-control"
                                                                placeholder="Ex : 103.344322" id="longitude" value="{{$default_location?$default_location['lng']:0}}" required readonly>
                                                            <div class="modal-footer">
                                                                <button type="button" class="closeB btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('close')}}</button>
                                                                <button type="submit" class="btn btn--primary" id="addressUpdate" data-id="{{$shippingAddress->id}}">{{\App\CPU\translate('update')}}  </button>
                                                            </div>
                                                        </form>
                                                </div>
                                                </div>
                                            </div>
                                        </div>

                                    <div class="card-body">
                                        <div class="font-name"><span>{{$shippingAddress['contact_person_name']}}</span>
                                        </div>
                                        <div><span class="font-nameA"> <strong>{{\App\CPU\translate('Phone')}}  :</strong>  {{$shippingAddress['phone']}}</span>
                                        </div>
                                        <div><span class="font-nameA"> <strong>{{\App\CPU\translate('City')}}  :</strong>  {{$shippingAddress['city']}}</span>
                                        </div>
                                        <div><span class="font-nameA"> <strong> {{\App\CPU\translate('zip_code')}} :</strong> {{$shippingAddress['zip']}}</span>
                                        </div>
                                        <div><span class="font-nameA"> <strong>{{\App\CPU\translate('address')}} :</strong> {{$shippingAddress['address']}}</span>
                                        </div>
                                        <div><span class="font-nameA"> <strong>{{\App\CPU\translate('country')}} :</strong> {{$shippingAddress['country']}}</span>
                                        </div>

                                    </div>

                            </div>
                        </section>
                    @endforeach
                </div>
        </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('public/assets/front-end/js/bootstrap-select.min.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('.address_type_li').on('click', function (e) {
                // e.preventDefault();
                $('.address_type_li').find('.address_type').removeAttr('checked', false);
                $('.address_type_li').find('.component').removeClass('active_address_type');
                $(this).find('.address_type').attr('checked', true);
                $(this).find('.address_type').removeClass('add_type');
                $('#defaultValue').removeClass('add_type');
                $(this).find('.address_type').addClass('add_type');

                $(this).find('.component').addClass('active_address_type');
            });
        })

        $('#addressUpdate').on('click', function(e){
            e.preventDefault();
            let addressAs, address, name, zip, city, state, country, phone;

            addressAs = $('.add_type').val();

            address = $('#own_address').val();
            name = $('#person_name').val();
            zip = $('#zip_code').val();
            city = $('#city').val();
            state = $('#own_state').val();
            country = $('#own_country').val();
            phone = $('#own_phone').val();

            let id = $(this).attr('data-id');

            if (addressAs != '' && address != '' && name != '' && zip != '' && city != '' && state != '' && country != '' && phone != '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('address-update')}}",
                    method: 'POST',
                    data: {
                        id : id,
                        addressAs: addressAs,
                        address: address,
                        name: name,
                        zip: zip,
                        city: city,
                        state: state,
                        country: country,
                        phone: phone
                    },
                    success: function () {
                        toastr.success('{{\App\CPU\translate('Address Update Successfully')}}.');
                        location.reload();


                    }
                });
            }else{
                toastr.error('{{\App\CPU\translate('All input field required')}}.');
            }

        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{\App\CPU\Helpers::get_business_settings('map_api_key')}}&libraries=places&v=3.49"></script>
    <script>

        function initAutocomplete() {
            var myLatLng = { lat: {{$default_location?$default_location['lat']:'-33.8688'}}, lng: {{$default_location?$default_location['lng']:'151.2195'}} };

            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: { lat: {{$default_location?$default_location['lat']:'-33.8688'}}, lng: {{$default_location?$default_location['lng']:'151.2195'}} },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap( map );
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng( coordinates['lat'], coordinates['lng'] ) ;
                marker.setPosition( latlng );
                map.panTo( latlng );

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];

                geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').value = results[1].formatted_address;
                            console.log(results[1].formatted_address);
                        }
                    }
                });
            });

            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });

                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();

                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };
        $(document).on('ready', function () {
            initAutocomplete();

        });

        $(document).on("keydown", "input", function(e) {
          if (e.which==13) e.preventDefault();
        });
    </script>
    
    <script>
    // Division Section select
function divisionsList() {

// get value from division lists
var diviList = document.getElementById('division').value;

// set barishal division districts
if(diviList == 'Barishal'){
    var disctList = '<option disabled selected>Select District</option><option value="Barguna">Barguna</option><option value="Barishal">Barishal</option><option value="Bhola">Bhola</option><option value="Jhalokhathi">Jhalokhathi</option><option value="Patuakhali">Patuakhali</option><option value="Pirojpur">Pirojpur</option>';
}
// set Chattogram division districts
else if(diviList == 'Chattogram') {
    var disctList = '<option disabled selected>Select District</option><option value="Bandarban">Bandarban</option><option value="Chandpur">Chandpur</option><option value="Chattogram">Chattogram</option><option value="Cumilla">Cumilla</option><option value="Cox\'s Bazar">Cox\'s Bazar</option><option value="Feni">Feni</option><option value="Khagrachhari">Khagrachhari</option><option value="Noakhali">Noakhali</option><option value="Rangamati">Rangamati</option><option value="Lakshmipur">Lakshmipur</option>';
}
// set Dhaka division districts
else if(diviList == 'Dhaka') {
    var disctList = '<option disabled selected>Select District</option><option value="Dhaka">Dhaka</option><option value="Faridpur">Faridpur</option><option value="Gazipur">Gazipur</option><option value="Gopalganj">Gopalganj</option><option value="Kishoreganj">Kishoreganj</option><option value="Madaripur">Madaripur</option><option value="Manikganj">Manikganj</option><option value="Munshiganj">Munshiganj</option><option value="Narayanganj">Narayanganj</option><option value="Narsingdi">Narsingdi</option><option value="Rajbari">Rajbari</option><option value="Shariatpur">Shariatpur</option><option value="Tangail">Tangail</option>';
}

else if(diviList == 'Khulna') {
    var disctList = '<option disabled selected>Select District</option><option value="Bagerhat">Bagerhat</option><option value="Chuadanga">Chuadanga</option><option value="Jashore">Jashore</option><option value="Jhenaidah">Jhenaidah</option><option value="Khulna">Khulna</option><option value="Kushtia">Kushtia</option><option value="Magura">Magura</option><option value="Meharpur">Meharpur</option><option value="Narail">Narail</option><option value="Satkhira">Satkhira</option>';
}

else if(diviList == 'Mymensingh') {
    var disctList = '<option disabled selected>Select District</option><option value="Jamalpur">Jamalpur</option><option value="Mymensingh">Mymensingh</option><option value="Netrokona">Netrokona</option><option value="Sherpur">Sherpur</option>';
}
else if(diviList == 'Rajshahi') {
    var disctList = '<option disabled selected>Select District</option><option value="Bogura">Bogura</option><option value="Chapai Nawabganj">Chapai Nawabganj</option><option value="Jaipurhat">Jaipurhat</option><option value="Naogaon">Naogaon</option><option value="Natore">Natore</option><option value="Pabna">Pabna</option><option value="Rajshahi">Rajshahi</option><option value="Sirajganj">Sirajganj</option>';
}
else if(diviList == 'Rangpur') {
    var disctList = '<option disabled selected>Select District</option><option value="Dinajpur">Dinajpur</option><option value="Gaibandha">Gaibandha</option><option value="Lalmonirhat">Lalmonirhat</option><option value="Nilphamari">Nilphamari</option><option value="Panchagarh">Panchagarh</option><option value="Rangpur">Rangpur</option><option value="Thakurgaon">Thakurgaon</option>';
}
else if(diviList == 'Sylhet') {
    var disctList = '<option disabled selected>Select District</option><option value="Habiganj">Habiganj</option><option value="Mauluvibazar">Mauluvibazar</option><option value="Sunamganj">Sunamganj</option><option value="Sylhet">Sylhet</option>';
}

//  set/send districts name to District lists from division
document.getElementById("city").innerHTML= disctList;
}

// Thana Section select
function thanaList(){
var DisList = document.getElementById('city').value;
if(DisList == 'Bagerhat') {
    var thanaList = '<option value="">Select One</option><option value="Bagerhat Sadar">Bagerhat Sadar</option><option value="Chitalmari">Chitalmari</option><option value="Fakirhat">Fakirhat</option><option value="Kachua">Kachua</option><option value="Mollahat">Mollahat</option><option value="Mongla">Mongla</option><option value="Morrelganj">Morrelganj</option><option value="Rampal">Rampal</option><option value="Sarankhola">Sarankhola</option><option value="Others">Others</option>';
}
if(DisList == 'Bandarban') {
    var thanaList = '<option value="">Select One</option><option value="Alikadam">Alikadam</option><option value="Bandarban Sadar">Bandarban Sadar</option><option value="Lama">Lama</option><option value="Naikhongchhari">Naikhongchhari</option><option value="Rowangchhari">Rowangchhari</option><option value="Ruma">Ruma</option><option value="Thanchi">Thanchi</option><option value="Others">Others</option>';
}
if(DisList == 'Barguna') {
    var thanaList = '<option value="">Select One</option><option value="Amtali">Amtali</option><option value="Bamna">Bamna</option><option value="Barguna Sadar">Barguna Sadar</option><option value="Betagi">Betagi</option><option value="Patharghata">Patharghata</option><option value="Others">Others</option>';
}
if(DisList == 'Barishal') {
    var thanaList = '<option value="">Select One</option><option value="Agailjhara">Agailjhara</option><option value="Babuganj">Babuganj</option><option value="Bakerganj">Bakerganj</option><option value="Banari Para">Banari Para</option><option value="Barishal Sadar (Kotwali)">Barishal Sadar (Kotwali)</option><option value="Gaurnadi">Gaurnadi</option><option value="Hizla">Hizla</option><option value="Mehendiganj">Mehendiganj</option><option value="Muladi">Muladi</option><option value="Wazirpur">Wazirpur</option><option value="Others">Others</option>';
}
if(DisList == 'Bhola') {
    var thanaList = '<option value="">Select One</option><option value="Bhola Sadar">Bhola Sadar</option><option value="Burhanuddin">Burhanuddin</option><option value="Char Fasson">Char Fasson</option><option value="Daulatkhan">Daulatkhan</option><option value="Lalmohan">Lalmohan</option><option value="Manpura">Manpura</option><option value="Tazumuddin">Tazumuddin</option><option value="Others">Others</option>';
}
if(DisList == 'Bogura') {
    var thanaList = '<option value="">Select One</option><option value="Adamdighi">Adamdighi</option><option value="Bogura Sadar">Bogura Sadar</option><option value="Dhunat">Dhunat</option><option value="Dhupchanchia">Dhupchanchia</option><option value="Gabtali">Gabtali</option><option value="Kahaloo">Kahaloo</option><option value="Nandigram">Nandigram</option><option value="Sariakandi">Sariakandi</option><option value="Shajhanpur">Shajhanpur</option><option value="Sherpur">Sherpur</option><option value="Shibganj">Shibganj</option><option value="Sonatola">Sonatola</option><option value="Others">Others</option>';
}
if(DisList == 'Brahmanbaria') {
    var thanaList = '<option value="">Select One</option><option value="Akhaura">Akhaura</option><option value="Ashuganj">Ashuganj</option><option value="Banchharampur">Banchharampur</option><option value="Bijoynagar">Bijoynagar</option><option value="Brahmanbaria Sadar">Brahmanbaria Sadar</option><option value="Kasba">Kasba</option><option value="Nabinagar">Nabinagar</option><option value="Nasirnagar">Nasirnagar</option><option value="Sarail">Sarail</option><option value="Others">Others</option>';
}
if(DisList == 'Chandpur') {
    var thanaList = '<option value="">Select One</option><option value="Chandpur Sadar">Chandpur Sadar</option><option value="Faridganj">Faridganj</option><option value="Haim Char">Haim Char</option><option value="Hajiganj">Hajiganj</option><option value="Kachua">Kachua</option><option value="Matlab">Matlab</option><option value="Shahrasti">Shahrasti</option><option value="Uttar Matlab">Uttar Matlab</option><option value="Others">Others</option>';
}
if(DisList == 'Chapai Nawabganj') {
    var thanaList = '<option value="">Select One</option><option value="Bholahat">Bholahat</option><option value="Gomastapur">Gomastapur</option><option value="Nachole">Nachole</option><option value="Nawabganj Sadar">Nawabganj Sadar</option><option value="Shibganj">Shibganj</option><option value="Others">Others</option>';
}
if(DisList == 'Chattogram') {
    var thanaList = '<option value="">Select One</option><option value="Akbar Shah">Akbar Shah</option><option value="Anowara">Anowara</option><option value="Bakalia">Bakalia</option><option value="Bandar(Chitt. Port)">Bandar(Chitt. Port)</option><option value="Banshkhali">Banshkhali</option><option value="Bayejid Bostami">Bayejid Bostami</option><option value="Boalkhali">Boalkhali</option><option value="Chandanish">Chandanish</option><option value="Chandgaon">Chandgaon</option><option value="Chawkbazar">Chawkbazar</option><option value="Double Mooring">Double Mooring</option><option value="EPZ">EPZ</option><option value="Fatikchhari">Fatikchhari</option><option value="Halishahar">Halishahar</option><option value="Hathazari">Hathazari</option><option value="Karnafuli">Karnafuli</option><option value="Khulshi">Khulshi</option><option value="Kotwali">Kotwali</option><option value="Lohagara">Lohagara</option><option value="Mirsharai">Mirsharai</option><option value="Pahartali">Pahartali</option><option value="Panchlaish">Panchlaish</option><option value="Patenga">Patenga</option><option value="Patiya">Patiya</option><option value="Rangunia">Rangunia</option><option value="Raozan">Raozan</option><option value="Sadarghat">Sadarghat</option><option value="Sandwip">Sandwip</option><option value="Satkania">Satkania</option><option value="Sitakunda">Sitakunda</option><option value="Others">Others</option>'
}
if(DisList == 'Chuadanga') {
    var thanaList = '<option value="">Select One</option><option value="Alamdanga">Alamdanga</option><option value="Chuadanga Sadar">Chuadanga Sadar</option><option value="Damurhuda">Damurhuda</option><option value="Jiban Nagar">Jiban Nagar</option><option value="Others">Others</option>';
}
if(DisList == 'Cox\'s Bazar') {
    var thanaList = '<option value="">Select One</option><option value="Chakaria">Chakaria</option><option value="Cox\'s Bazar Sadar">Cox\'s Bazar Sadar</option><option value="Kutubdia">Kutubdia</option><option value="Maheshkhali">Maheshkhali</option><option value="Pekua">Pekua</option><option value="Ramu">Ramu</option><option value="Teknaf">Teknaf</option><option value="Ukhia">Ukhia</option><option value="Others">Others</option>';
}
if(DisList == 'Cumilla') {
    var thanaList = '<option value="">Select One</option><option value="Barura">Barura</option><option value="Brahaman Para">Brahaman Para</option><option value="Burichang">Burichang</option><option value="Chandina">Chandina</option><option value="Chauddagram">Chauddagram</option><option value="Cumilla Sadar">Cumilla Sadar</option><option value="Cumilla Sadar South">Cumilla Sadar South</option><option value="Daudkandi">Daudkandi</option><option value="Debidwar">Debidwar</option><option value="Homna">Homna</option><option value="Laksam">Laksam</option><option value="Langalkot">Langalkot</option><option value="Meghna">Meghna</option><option value="Monohorganj">Monohorganj</option><option value="Muradnagar">Muradnagar</option><option value="Titas">Titas</option><option value="Others">Others</option>';
}
if(DisList == 'Dhaka') {
    var thanaList = '<option value="">Select One</option><option value="Adabor">Adabor</option><option value="Airport">Airport</option><option value="Badda">Badda</option><option value="Banani">Banani</option><option value="Bangshal">Bangshal</option><option value="Bhashantek">Bhashantek</option><option value="Cantonment">Cantonment</option><option value="Chackbazar">Chackbazar</option><option value="Dakshin Khan">Dakshin Khan</option><option value="Darus-Salam">Darus-Salam</option><option value="Demra">Demra</option><option value="Dhamrai">Dhamrai</option><option value="Dhanmondi">Dhanmondi</option><option value="Dohar">Dohar</option><option value="Gandaria">Gandaria</option><option value="Gulshan">Gulshan</option><option value="Hatirjheel">Hatirjheel</option><option value="Hazaribhag">Hazaribhag</option><option value="Jattrabari">Jattrabari</option><option value="Kadamtoli">Kadamtoli</option><option value="Kafrul">Kafrul</option><option value="Kalabagan">Kalabagan</option><option value="Kamrangir Char">Kamrangir Char</option><option value="Keraniganj Model">Keraniganj Model</option><option value="Khilgaon">Khilgaon</option><option value="Khilkhet">Khilkhet</option><option value="Kotwali">Kotwali</option><option value="Lalbag">Lalbag</option><option value="Mirpur Model">Mirpur Model</option><option value="Mohammadpur">Mohammadpur</option><option value="Motijheel">Motijheel</option><option value="Mugda">Mugda</option><option value="Nawabganj">Nawabganj</option><option value="New Market">New Market</option><option value="Pallabi">Pallabi</option><option value="Paltan Model">Paltan Model</option><option value="Ramna Model">Ramna Model</option><option value="Rampura">Rampura</option><option value="Rupnagar">Rupnagar</option><option value="Sabujbhag">Sabujbhag</option><option value="Savar">Savar</option><option value="Shah Ali">Shah Ali</option><option value="Shahbag">Shahbag</option><option value="Shahjahanpur">Shahjahanpur</option><option value="Sher e Bangla Nagar">Sher e Bangla Nagar</option><option value="Shyampur">Shyampur</option><option value="South Keraniganj">South Keraniganj</option><option value="Sutrapur">Sutrapur</option><option value="Tejgaon">Tejgaon</option><option value="Tejgaon Industrial">Tejgaon Industrial</option><option value="Turag">Turag</option><option value="Uttar Khan">Uttar Khan</option><option value="Uttara East">Uttara East</option><option value="Uttara West">Uttara West</option><option value="Vatara">Vatara</option><option value="Wari">Wari</option><option value="Others">Others</option>';
}
if(DisList == 'Dinajpur') {
    var thanaList = '<option value="">Select One</option><option value="Biral">Biral</option><option value="Birampur">Birampur</option><option value="Birganj">Birganj</option><option value="Bochaganj">Bochaganj</option><option value="Chirirbandar">Chirirbandar</option><option value="Dinajpur Sadar">Dinajpur Sadar</option><option value="Fulbari">Fulbari</option><option value="Ghoraghat">Ghoraghat</option><option value="Hakimpur">Hakimpur</option><option value="Kaharole">Kaharole</option><option value="Khansama">Khansama</option><option value="Nawabganj">Nawabganj</option><option value="Parbatipur">Parbatipur</option><option value="Others">Others</option>';
}
if(DisList == 'Faridpur') {
    var thanaList = '<option value="">Select One</option><option value="Alfadanga">Alfadanga</option><option value="Bhanga">Bhanga</option><option value="Boalmari">Boalmari</option><option value="Char Bhadrasan">Char Bhadrasan</option><option value="Faridpur Sadar">Faridpur Sadar</option><option value="Madukhali">Madukhali</option><option value="Nagarkanda">Nagarkanda</option><option value="Sadarpur">Sadarpur</option><option value="Saltha">Saltha</option><option value="Others">Others</option>';
}
if(DisList == 'Feni') {
    var thanaList = '<option value="">Select One</option><option value="Chhagalnayian">Chhagalnayian</option><option value="Daganbhuyian">Daganbhuyian</option><option value="Feni Sadar">Feni Sadar</option><option value="Fulgazi">Fulgazi</option><option value="Parshuram">Parshuram</option><option value="Sonagazi">Sonagazi</option><option value="Others">Others</option>';
}
if(DisList == 'Gaibandha') {
    var thanaList = '<option value="">Select One</option><option value="Fulchhari">Fulchhari</option><option value="Gaibandha Sadar">Gaibandha Sadar</option><option value="Gobidaganj">Gobidaganj</option><option value="Palashbari">Palashbari</option><option value="Sadullapur">Sadullapur</option><option value="Saghatta">Saghatta</option><option value="Sundarganj">Sundarganj</option><option value="Others">Others</option>';
}
if(DisList == 'Gazipur') {
    var thanaList = '<option value="">Select One</option><option value="Gazipur Sadar">Gazipur Sadar</option><option value="Kaliakair">Kaliakair</option><option value="Kaliganj">Kaliganj</option><option value="Kapasia">Kapasia</option><option value="Sreepur">Sreepur</option><option value="Tongi">Tongi</option><option value="Others">Others</option>';
}
if(DisList == 'Gopalganj') {
    var thanaList = '<option value="">Select One</option><option value="Gopalganj Sadar">Gopalganj Sadar</option><option value="Kashiani">Kashiani</option><option value="Kotalipara">Kotalipara</option><option value="Muksudpur">Muksudpur</option><option value="Tungi Para">Tungi Para</option><option value="Others">Others</option>';
}
if(DisList == 'Habiganj') {
    var thanaList = '<option value="">Select One</option><option value="Ajmirganj">Ajmirganj</option><option value="Bahubal">Bahubal</option><option value="Baniachang">Baniachang</option><option value="Chunarughat">Chunarughat</option><option value="Habiganj Sadar">Habiganj Sadar</option><option value="Lakhai">Lakhai</option><option value="Madhabpur">Madhabpur</option><option value="Nabiganj">Nabiganj</option><option value="Shayestaganj">Shayestaganj</option><option value="Others">Others</option>';
}
if(DisList == 'Jaipurhat') {
    var thanaList = '<option value="">Select One</option><option value="Akkelpur">Akkelpur</option><option value="Joypurhat  Sadar">Joypurhat  Sadar</option><option value="Kalai">Kalai</option><option value="Khetlal">Khetlal</option><option value="Panchbibi">Panchbibi</option><option value="Others">Others</option>';
}
if(DisList == 'Jamalpur') {
    var thanaList = '<option value="">Select One</option><option value="Bakshiganj">Bakshiganj</option><option value="Dewanganj">Dewanganj</option><option value="Islampur">Islampur</option><option value="Jamalpur Sadar">Jamalpur Sadar</option><option value="Madarganj">Madarganj</option><option value="Melandaha">Melandaha</option><option value="Sarishabari">Sarishabari</option><option value="Others">Others</option>';
}
if(DisList == 'Jashore') {
    var thanaList = '<option value="">Select One</option><option value="Abhay Nagar">Abhay Nagar</option><option value="Bagherpara">Bagherpara</option><option value="Chowghacha">Chowghacha</option><option value="Jhikargacha">Jhikargacha</option><option value="Keshabpur">Keshabpur</option><option value="Kotwali">Kotwali</option><option value="Manirampur">Manirampur</option><option value="Sharsha">Sharsha</option><option value="Others">Others</option>';
}
if(DisList == 'Jhalokhathi') {
    var thanaList = '<option value="">Select One</option><option value="Jhalokhathi Sadar">Jhalokhathi Sadar</option><option value="Kanthalia">Kanthalia</option><option value="Nalchity">Nalchity</option><option value="Rajapur">Rajapur</option><option value="Others">Others</option>';
}
if(DisList == 'Jhenaidah') {
    var thanaList = '<option value="">Select One</option><option value="Harinakunda">Harinakunda</option><option value="Jhenaidah Sadar">Jhenaidah Sadar</option><option value="Kaliganj">Kaliganj</option><option value="Kotchandpur">Kotchandpur</option><option value="Mahespur">Mahespur</option><option value="Shailkupa">Shailkupa</option><option value="Others">Others</option>';
}
if(DisList == 'Khagrachhari') {
    var thanaList = '<option value="">Select One</option><option value="Dighinala">Dighinala</option><option value="Khagrachhari Sadar">Khagrachhari Sadar</option><option value="Lakshmichhari">Lakshmichhari</option><option value="Mahalchhari">Mahalchhari</option><option value="Manikchhari">Manikchhari</option><option value="Matiranga">Matiranga</option><option value="Panchhari">Panchhari</option><option value="Ramgarh">Ramgarh</option><option value="Others">Others</option>';
}
if(DisList == 'Khulna') {
    var thanaList = '<option value="">Select One</option><option value="Batiaghata">Batiaghata</option><option value="Dacope">Dacope</option><option value="Daulatpur">Daulatpur</option><option value="Dighala">Dighala</option><option value="Dumuria">Dumuria</option><option value="Khalishpur">Khalishpur</option><option value="Khan Jahan Ali">Khan Jahan Ali</option><option value="Khulna Sadar">Khulna Sadar</option><option value="Koyra">Koyra</option><option value="Paikgachha">Paikgachha</option><option value="Phultala">Phultala</option><option value="Rupsa">Rupsa</option><option value="Sonadanga">Sonadanga</option><option value="Terokhada">Terokhada</option><option value="Others">Others</option>';
}
if(DisList == 'Kishoreganj') {
    var thanaList = '<option value="">Select One</option><option value="Austagram">Austagram</option><option value="Bajitpur">Bajitpur</option><option value="Bhairab">Bhairab</option><option value="Hossenpur">Hossenpur</option><option value="Itna">Itna</option><option value="Karimganj">Karimganj</option><option value="Katiadi">Katiadi</option><option value="Kishoregonj SADAR">Kishoregonj SADAR</option><option value="Kuliar Char">Kuliar Char</option><option value="Mithamoin">Mithamoin</option><option value="Nikli">Nikli</option><option value="Pakundia">Pakundia</option><option value="Tarail">Tarail</option><option value="Others">Others</option>';
}
if(DisList == 'Kurigram') {
    var thanaList = '<option value="">Select One</option><option value="Bhurungamari">Bhurungamari</option><option value="Char Rajibpur">Char Rajibpur</option><option value="Chilmari">Chilmari</option><option value="Kurigram Sadar">Kurigram Sadar</option><option value="Nageshwari">Nageshwari</option><option value="Phulbari">Phulbari</option><option value="Rajarhat">Rajarhat</option><option value="Rajibpur">Rajibpur</option><option value="Rowmari">Rowmari</option><option value="Ulipur">Ulipur</option><option value="Others">Others</option>';
}
if(DisList == 'Kushtia') {
    var thanaList = '<option value="">Select One</option><option value="Bheramara">Bheramara</option><option value="Daulatpur">Daulatpur</option><option value="Khoksa">Khoksa</option><option value="Kumarkhali">Kumarkhali</option><option value="Kushtia Sadar">Kushtia Sadar</option><option value="Mirpur">Mirpur</option><option value="Others">Others</option>';
}
if(DisList == 'Lalmonirhat') {
    var thanaList = '<option value="">Select One</option><option value="Aditmari">Aditmari</option><option value="Hatibanda">Hatibanda</option><option value="Kaliganj">Kaliganj</option><option value="Lalmonirhat Sadar">Lalmonirhat Sadar</option><option value="Patgram">Patgram</option><option value="Others">Others</option>';
}
if(DisList == 'Lakshmipur') {
    var thanaList = '<option value="">Select One</option><option value="Komol Nogor">Komol Nogor</option><option value="Lakshmipur Sadar">Lakshmipur Sadar</option><option value="Raipur">Raipur</option><option value="Ramganj">Ramganj</option><option value="Ramgati">Ramgati</option><option value="Others">Others</option>';
}
if(DisList == 'Madaripur') {
    var thanaList = '<option value="">Select One</option><option value="Kalkini">Kalkini</option><option value="Madaripur Sadar">Madaripur Sadar</option><option value="Rajoir">Rajoir</option><option value="Shibchar">Shibchar</option><option value="Others">Others</option>';
}
if(DisList == 'Magura') {
    var thanaList = '<option value="">Select One</option><option value="Magura Sadar">Magura Sadar</option><option value="Mohammadpur">Mohammadpur</option><option value="Shalikha">Shalikha</option><option value="Sreepur">Sreepur</option><option value="Others">Others</option>';
}
if(DisList == 'Manikganj') {
    var thanaList = '<option value="">Select One</option><option value="Daulatpur">Daulatpur</option><option value="Ghior">Ghior</option><option value="Harirampur">Harirampur</option><option value="Manikganj Sadar">Manikganj Sadar</option><option value="Saturia">Saturia</option><option value="Shibalaya">Shibalaya</option><option value="Singair">Singair</option><option value="Others">Others</option>';
}
if(DisList == 'Meharpur') {
    var thanaList = '<option value="">Select One</option><option value="Gangni">Gangni</option><option value="Meherpur Sadar">Meherpur Sadar</option><option value="Mujib Nagar">Mujib Nagar</option><option value="Others">Others</option>';
}
if(DisList == 'Mauluvibazar') {
    var thanaList = '<option value="">Select One</option><option value="Barlekha">Barlekha</option><option value="Juri">Juri</option><option value="Kamalganj">Kamalganj</option><option value="Kulaura">Kulaura</option><option value="Mauluvi Bazar Sadar">Maulvi Bazar Sadar</option><option value="Rajnagar">Rajnagar</option><option value="Sreemangal">Sreemangal</option><option value="Others">Others</option>';
}
if(DisList == 'Munshiganj') {
    var thanaList = '<option value="">Select One</option><option value="Gazaria">Gazaria</option><option value="Louhajang">Louhajang</option><option value="Munshiganj Sadar">Munshiganj Sadar</option><option value="Serajdikhan">Serajdikhan</option><option value="Sreenagar">Sreenagar</option><option value="Tongibari">Tongibari</option><option value="Others">Others</option>';
}
if(DisList == 'Mymensingh') {
    var thanaList = '<option value="">Select One</option><option value="Bhalukha">Bhalukha</option><option value="Dhobaura">Dhobaura</option><option value="Fulbaria">Fulbaria</option><option value="Gaffargaon">Gaffargaon</option><option value="Gauripur">Gauripur</option><option value="Haluaghat">Haluaghat</option><option value="Ishwarganj">Ishwarganj</option><option value="Muktagachha">Muktagachha</option><option value="Mymensingh Sadar">Mymensingh Sadar</option><option value="Nandail">Nandail</option><option value="Phulpur">Phulpur</option><option value="Tarakanda">Tarakanda</option><option value="Trishal">Trishal</option><option value="Others">Others</option>';
}
if(DisList == 'Naogaon') {
    var thanaList = '<option value="">Select One</option><option value="Atrai">Atrai</option><option value="Badalgachhi">Badalgachhi</option><option value="Dhamoirhat">Dhamoirhat</option><option value="Mahadebpur">Mahadebpur</option><option value="Manda">Manda</option><option value="Naogaon Sadar">Naogaon Sadar</option><option value="Niamatpur">Niamatpur</option><option value="Patnitala">Patnitala</option><option value="Porsha">Porsha</option><option value="Raninagar">Raninagar</option><option value="Sapahar">Sapahar</option><option value="Others">Others</option>';
}
if(DisList == 'Narail') {
    var thanaList = '<option value="">Select One</option><option value="Kalia">Kalia</option><option value="Lohagara">Lohagara</option><option value="NarailSadar">NarailSadar</option><option value="Others">Others</option>';
}
if(DisList == 'Narayanganj') {
    var thanaList = '<option value="">Select One</option><option value="Araihazar">Araihazar</option><option value="Bandar">Bandar</option><option value="Narayanganj Sadar">Narayanganj Sadar</option><option value="Rupganj">Rupganj</option><option value="Siddirganj">Siddirganj</option><option value="Sonargaon">Sonargaon</option><option value="Others">Others</option>';
}
if(DisList == 'Narsingdi') {
    var thanaList = '<option value="">Select One</option><option value="Belabo">Belabo</option><option value="Manohardi">Manohardi</option><option value="Narsingdi Sadar">Narsingdi Sadar</option><option value="Palash">Palash</option><option value="Roypura">Roypura</option><option value="Shibpur">Shibpur</option><option value="Others">Others</option>';
}
if(DisList == 'Natore') {
    var thanaList = '<option value="">Select One</option><option value="Bagati Para">Bagati Para</option><option value="Baraigram">Baraigram</option><option value="Gurudaspur">Gurudaspur</option><option value="Lalpur">Lalpur</option><option value="Naldanga">Naldanga</option><option value="Natore Sadar">Natore Sadar</option><option value="Singra">Singra</option><option value="Others">Others</option>';
}
if(DisList == 'Netrokona') {
    var thanaList = '<option value="">Select One</option><option value="Atpara">Atpara</option><option value="Barhatta">Barhatta</option><option value="Durgapur">Durgapur</option><option value="Kalmakanda">Kalmakanda</option><option value="Kendua">Kendua</option><option value="Khaliajuri">Khaliajuri</option><option value="Madan">Madan</option><option value="Mohanganj">Mohanganj</option><option value="Netrokona Sadar">Netrokona Sadar</option><option value="Purbadhala">Purbadhala</option><option value="Others">Others</option>';
}
if(DisList == 'Nilphamari') {
    var thanaList = '<option value="">Select One</option><option value="Dimla">Dimla</option><option value="Domar">Domar</option><option value="Jaldhaka">Jaldhaka</option><option value="Kishoreganj ">Kishoreganj </option><option value="Nilphamari Sadar">Nilphamari Sadar</option><option value="Saidpur">Saidpur</option><option value="Others">Others</option>';
}
if(DisList == 'Noakhali') {
    var thanaList = '<option value="">Select One</option><option value="Begumganj">Begumganj</option><option value="Chatkhil">Chatkhil</option><option value="Companiganj">Companiganj</option><option value="Hatiya">Hatiya</option><option value="Kobirhat">Kobirhat</option><option value="Noakhali Sadar (Sudharam)">Noakhali Sadar (Sudharam)</option><option value="Senbagh">Senbagh</option><option value="Sonaimuri">Sonaimuri</option><option value="Subornachhar">Subornachhar</option><option value="Others">Others</option>';
}
if(DisList == 'Pabna') {
    var thanaList = '<option value="">Select One</option><option value="Atgharia">Atgharia</option><option value="Bera">Bera</option><option value="Bhangura">Bhangura</option><option value="Chatmohar">Chatmohar</option><option value="Faridpur">Faridpur</option><option value="Ishwardi">Ishwardi</option><option value="Pabna Sadar">Pabna Sadar</option><option value="Santhia">Santhia</option><option value="Sujanagar">Sujanagar</option><option value="Others">Others</option>';
}
if(DisList == 'Panchagarh') {
    var thanaList = '<option value="">Select One</option><option value="Atwari">Atwari</option><option value="Boda">Boda</option><option value="Debiganj">Debiganj</option><option value="Panchagarh Sadar">Panchagarh Sadar</option><option value="Tentulia">Tentulia</option><option value="Others">Others</option>';
}
if(DisList == 'Patuakhali') {
    var thanaList = '<option value="">Select One</option><option value="Bauphal">Bauphal</option><option value="Dashmina">Dashmina</option><option value="Dumki">Dumki</option><option value="Galachipa">Galachipa</option><option value="Kala Para">Kala Para</option><option value="Mirzaganj">Mirzaganj</option><option value="Patuakhali Sadar">Patuakhali Sadar</option><option value="Rangabali">Rangabali</option><option value="Others">Others</option>';
}
if(DisList == 'Pirojpur') {
    var thanaList = '<option value="">Select One</option><option value="Bhandaria">Bhandaria</option><option value="Indurkani">Indurkani</option><option value="Kawkhali">Kawkhali</option><option value="Mathbaria">Mathbaria</option><option value="Nazirpur">Nazirpur</option><option value="Nesarabad (Swarupkati)">Nesarabad (Swarupkati)</option><option value="Pirojpur Sadar">Pirojpur Sadar</option><option value="Others">Others</option>';
}
if(DisList == 'Rajbari') {
    var thanaList = '<option value="">Select One</option><option value="Balia Kandi">Balia Kandi</option><option value="Goalandaghat">Goalandaghat</option><option value="Kalukhali">Kalukhali</option><option value="Pangsha">Pangsha</option><option value="Rajbari Sadar">Rajbari Sadar</option><option value="Others">Others</option>';
}
if(DisList == 'Rajshahi') {
    var thanaList = '<option value="">Select One</option><option value="Balia Kandi">Balia Kandi</option><option value="Goalandaghat">Goalandaghat</option><option value="Kalukhali">Kalukhali</option><option value="Pangsha">Pangsha</option><option value="Rajbari Sadar">Rajbari Sadar</option><option value="Others">Others</option>';
}
if(DisList == 'Rangamati') {
    var thanaList = '<option value="">Select One</option><option value="Bagaichhari">Bagaichhari</option><option value="Barkal">Barkal</option><option value="Belaichhari">Belaichhari</option><option value="Juraichhari">Juraichhari</option><option value="Kaptai">Kaptai</option><option value="Kawkhali (Betbunia)">Kawkhali (Betbunia)</option><option value="Langadu">Langadu</option><option value="Naniarchar">Naniarchar</option><option value="Rajasthali">Rajasthali</option><option value="Rangamati Sadar">Rangamati Sadar</option><option value="Others">Others</option>';
}
if(DisList == 'Rangpur') {
    var thanaList = '<option value="">Select One</option><option value="Badarganj">Badarganj</option><option value="Gangachara">Gangachara</option><option value="Kaunia">Kaunia</option><option value="Mitha Pukur">Mitha Pukur</option><option value="Pirgachha">Pirgachha</option><option value="Pirganj">Pirganj</option><option value="Rangpur Sadar">Rangpur Sadar</option><option value="Taraganj">Taraganj</option><option value="Others">Others</option>';
}
if(DisList == 'Satkhira') {
    var thanaList = '<option value="">Select One</option><option value="Assasuni">Assasuni</option><option value="Debhata">Debhata</option><option value="Kalaroa">Kalaroa</option><option value="Kaliganj">Kaliganj</option><option value="Satkhira Sadar">Satkhira Sadar</option><option value="Shyamnagar">Shyamnagar</option><option value="Tala">Tala</option><option value="Others">Others</option>';
}
if(DisList == 'Shariatpur') {
    var thanaList = '<option value="">Select One</option><option value="Bhaderganj">Bhaderganj</option><option value="Damudya">Damudya</option><option value="Gosairhat">Gosairhat</option><option value="Naria">Naria</option><option value="Palong(Sadar)">Palong(Sadar)</option><option value="Zanjira">Zanjira</option><option value="Others">Others</option>';
}
if(DisList == 'Sherpur') {
    var thanaList = '<option value="">Select One</option><option value="Jhenaigati">Jhenaigati</option><option value="Nakla">Nakla</option><option value="Nalitabari">Nalitabari</option><option value="Sherpur Sadar">Sherpur Sadar</option><option value="Sreebardi">Sreebardi</option><option value="Others">Others</option>';
}
if(DisList == 'Sirajganj') {
    var thanaList = '<option value="">Select One</option><option value="Belkuchi">Belkuchi</option><option value="Chauhali">Chauhali</option><option value="Kamarkhanda">Kamarkhanda</option><option value="Kazipur">Kazipur</option><option value="Royganj">Royganj</option><option value="Shahjadpur">Shahjadpur</option><option value="Sirajganj Sadar">Sirajganj Sadar</option><option value="Tarash">Tarash</option><option value="Ullah Para">Ullah Para</option><option value="Others">Others</option>';
}
if(DisList == 'Sunamganj') {
    var thanaList = '<option value="">Select One</option><option value="Bishwambarpur">Bishwambarpur</option><option value="Chhatak">Chhatak</option><option value="Daxin Sunamganj">Daxin Sunamganj</option><option value="Derai">Derai</option><option value="Dharampasha">Dharampasha</option><option value="Dowarabazar">Dowarabazar</option><option value="Jagannatpur">Jagannatpur</option><option value="Jamalganj">Jamalganj</option><option value="Sulla">Sulla</option><option value="Sunamganj Sadar">Sunamganj Sadar</option><option value="Tahirpur">Tahirpur</option><option value="Others">Others</option>';
}
if(DisList == 'Sylhet') {
    var thanaList = '<option value="">Select One</option><option value="Balaganj">Balaganj</option><option value="Beani Bazar">Beani Bazar</option><option value="Bishwanath">Bishwanath</option><option value="Companiganj">Companiganj</option><option value="Fenchuganj">Fenchuganj</option><option value="Golabganj">Golabganj</option><option value="Gowainghat">Gowainghat</option><option value="Jaintiapur">Jaintiapur</option><option value="Kanaighat">Kanaighat</option><option value="Kowtali">Kowtali</option><option value="South Surma">South Surma</option><option value="Zakirganj">Zakirganj</option><option value="Others">Others</option>';
}
if(DisList == 'Tangail') {
    var thanaList = '<option value="">Select One</option><option value="Basail">Basail</option><option value="Bhuapur">Bhuapur</option><option value="Delduar">Delduar</option><option value="Dhonbari">Dhonbari</option><option value="Ghatail">Ghatail</option><option value="Gopalpur">Gopalpur</option><option value="Kalihati">Kalihati</option><option value="Madhupur">Madhupur</option><option value="Mirzapur">Mirzapur</option><option value="Nagarpur">Nagarpur</option><option value="Sakhipur">Sakhipur</option><option value="Tangail Sadar">Tangail Sadar</option><option value="Others">Others</option>';
}
if(DisList == 'Thakurgaon') {
    var thanaList = '<option value="">Select One</option><option value="Baliadangi">Baliadangi</option><option value="Haripur">Haripur</option><option value="Pirganj">Pirganj</option><option value="Ranisankail">Ranisankail</option><option value="Thakurgaon Sadar">Thakurgaon Sadar</option><option value="Others">Others</option>';
}
document.getElementById("thana").innerHTML= thanaList;
}
</script>
    
@endpush
