@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('POS'))
@section('content')
<!-- Content -->
	<!-- ========================= SECTION CONTENT ========================= -->
	<section class="section-content pt-5">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="card">
                        <h5 class="p-3 m-0 bg-light">{{\App\CPU\translate('Product_Section')}}</h5>
                        <div class="px-3 py-4">
                            <div class="row gy-1">
                                <div class="col-sm-6">
                                    <div class="input-group d-flex justify-content-end" >
                                        <select name="category" id="category" class="form-control js-select2-custom w-100" title="select category" onchange="set_category_filter(this.value)">
                                            <option value="">{{\App\CPU\translate('All Categories')}}</option>
                                            @foreach ($categories as $item)
                                            <option value="{{$item->id}}" {{$category==$item->id?'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <form class="">
                                        <!-- Search -->
                                        <div class="input-group-overlay input-group-merge input-group-custom">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="search" autocomplete="off" type="text" value="{{$keyword?$keyword:''}}"
                                                    name="search" class="form-control search-bar-input" placeholder="{{\App\CPU\translate('Search by Name')}}"
                                                    aria-label="Search here">
                                            <diV class="card pos-search-card w-4 position-absolute z-index-1 w-100">
                                                <div id="pos-search-box" class="card-body search-result-box d--none"></div>
                                            </diV>
                                        </div>
                                        <!-- End Search -->
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-2" id="items">
                            <div class="pos-item-wrap">
                                @foreach($products as $product)
                                    @include('admin-views.pos._single_product',['product'=>$product])
                                @endforeach
                            </div>
                        </div>

                        <div class="table-responsive mt-4">
                            <div class="px-4 d-flex justify-content-lg-end">
                                <!-- Pagination -->
                                {!!$products->withQueryString()->links()!!}
                            </div>
                        </div>
                    </div>
				</div>
				<div class="col-lg-5 mb-5">
                    <div class="card billing-section-wrap">
                        <h5 class="p-3 m-0 bg-light">{{\App\CPU\translate('Billing_Section')}}</h5>
                        <div class="card-body">
                            <div class="form-group d-flex gap-2">
                                <select onchange="customer_change(this.value);" id='customer' name="customer_id" data-placeholder="Walk In Customer" class="js-data-example-ajax form-control form-ellipsis">
                                    <option value="0">{{\App\CPU\translate('walking_customer')}}</option>
                                </select>
                                <button class="btn btn-success rounded text-nowrap" id="add_new_customer" type="button" data-toggle="modal" data-target="#add-customer" title="Add Customer">
                                    <i class="tio-add"></i>
                                    {{ \App\CPU\translate('customer')}}
                                </button>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize title-color d-flex align-items-center flex-wrap gap-1">
                                    {{\App\CPU\translate('current_customer')}} :
                                    <span class="mb-0" id="current_customer"></span>
                                </label>
                            </div>
                            <div class="d-flex gap-2 flex-wrap flex-sm-nowrap mb-3">
                                <select id='cart_id' name="cart_id" class=" form-control js-select2-custom" onchange="cart_change(this.value);">
                                </select>
                                <a class="btn btn-secondary rounded text-nowrap" onclick="clear_cart()">
                                    {{ \App\CPU\translate('clear_cart')}}
                                </a>
                                <a class="btn btn-info rounded text-nowrap" onclick="new_order()">
                                    {{ \App\CPU\translate('new_order')}}
                                </a>
                            </div>
                            <div id="cart" class="pb-5">
                                @include('admin-views.pos._cart',['cart_id'=>$cart_id])
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div><!-- container //  -->
	</section>

    <!-- End Content -->
    <div class="modal fade pt-5" id="quick-view" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="quick-view-modal">

            </div>
        </div>
    </div>

    @php($order=\App\Model\Order::find(session('last_order')))
    @if($order)
    @php(session(['last_order'=> false]))
    <div class="modal fade py-5" id="print-invoice" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{\App\CPU\translate('Print Invoice')}}</h5>
                    <button id="invoice_close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-12">
                        <center>
                            <input id="print_invoice" type="button" class="btn btn--primary non-printable" onclick="printDiv('printableArea')"
                                value="{{\App\CPU\translate('proceed')}}, {{\App\CPU\translate('if_thermal_printer_is_ready')}}"/>
                            <a href="{{url()->previous()}}" class="btn btn-danger non-printable">{{\App\CPU\translate('Back')}}</a>
                        </center>
                        <hr class="non-printable">
                    </div>
                    <div class="row m-auto" id="printableArea">
                        @include('admin-views.pos.order.invoice')
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="modal fade" id="add-customer" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{\App\CPU\translate('add_new_customer')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.pos.customer-store')}}" method="post" id="product_form"
                          >
                        @csrf
                            <div class="row pl-2" >
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label" >{{\App\CPU\translate('first_name')}} <span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="f_name" class="form-control" value="{{ old('f_name') }}"  placeholder="{{\App\CPU\translate('first_name')}}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label" >{{\App\CPU\translate('last_name')}} <span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="l_name" class="form-control" value="{{ old('l_name') }}"  placeholder="{{\App\CPU\translate('last_name')}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row pl-2" >
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label" >{{\App\CPU\translate('email')}}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"  placeholder="{{\App\CPU\translate('Ex')}}: ex@example.com" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label" >{{\App\CPU\translate('phone')}}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"  placeholder="{{\App\CPU\translate('phone')}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row pl-2" >
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{\App\CPU\translate('country')}} <span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="text"  name="country" class="form-control" value="{{ old('country') }}"  placeholder="{{\App\CPU\translate('country')}}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{\App\CPU\translate('division')}} <span
                                            class="input-label-secondary text-danger">*</span></label>
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
                                        <!--<input type="text"  name="city" class="form-control" value="{{ old('city') }}"  placeholder="{{\App\CPU\translate('city')}}" required>-->
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{\App\CPU\translate('city')}} <span
                                            class="input-label-secondary text-danger">*</span></label>
                                            <select name="city" id="city" class="form-control" required onchange="thanaList();"></select>
                                        <!--<input type="text"  name="city" class="form-control" value="{{ old('city') }}"  placeholder="{{\App\CPU\translate('city')}}" required>-->
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{\App\CPU\translate('thana')}} <span
                                            class="input-label-secondary text-danger">*</span></label>
                                           <select name="thana" id="thana" class="form-control" required></select>
                                        <!--<input type="text"  name="city" class="form-control" value="{{ old('city') }}"  placeholder="{{\App\CPU\translate('city')}}" required>-->
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{\App\CPU\translate('zip_code')}} <span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="text"  name="zip_code" class="form-control" value="{{ old('zip_code') }}"  placeholder="{{\App\CPU\translate('zip_code')}}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{\App\CPU\translate('address')}} <span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="text"  name="address" class="form-control" value="{{ old('address') }}"  placeholder="{{\App\CPU\translate('address')}}" required>
                                    </div>
                                </div>
                            </div>

                        <hr>
                        <button type="submit" id="submit_new_customer" class="btn btn--primary">{{\App\CPU\translate('submit')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')

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

<script>

        function delay(callback, ms) {
        var timer = 0;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
            callback.apply(context, args);
            }, ms || 0);
        };
        }

    $(document).on('ready', function () {
        $.ajax({
            url: '{{route('admin.pos.get-cart-ids')}}',
            type: 'GET',

            dataType: 'json', // added data type
            beforeSend: function () {
                $('#loading').removeClass('d-none');
                //console.log("loding");
            },
            success: function (data) {
                //console.log(data.cus);
                var output = '';
                    for(var i=0; i<data.cart_nam.length; i++) {
                        output += `<option value="${data.cart_nam[i]}" ${data.current_user==data.cart_nam[i]?'selected':''}>${data.cart_nam[i]}</option>`;
                    }
                    $('#cart_id').html(output);
                    $('#current_customer').text(data.current_customer);
                    $('#cart').empty().html(data.view);

            },
            complete: function () {
                $('#loading').addClass('d-none');
            },
        });
    });

    function form_submit(){
        Swal.fire({
            title: '{{\App\CPU\translate('Are you sure')}}?',
            type: 'warning',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No',
            confirmButtonText: 'Yes',
            reverseButtons: true
        }).then(function (result) {
            if(result.value){
                $('#order_place').submit();
            }
        });
    }
</script>
<script>
    document.addEventListener("keydown", function(event) {
    "use strict";
    if (event.altKey && event.code === "KeyO")
    {
        $('#submit_order').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyZ")
    {
        $('#payment_close').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyS")
    {
        $('#order_complete').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyC")
    {
        emptyCart();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyA")
    {
        $('#add_new_customer').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyN")
    {
        $('#submit_new_customer').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyK")
    {
        $('#short-cut').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyP")
    {
        $('#print_invoice').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyQ")
    {
        $('#search').focus();
        $("#-pos-search-box").css("display", "none");
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyE")
    {
        $("#pos-search-box").css("display", "none");
        $('#extra_discount').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyD")
    {
        $("#pos-search-box").css("display", "none");
        $('#coupon_discount').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyB")
    {
        $('#invoice_close').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyX")
    {
        clear_cart();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyR")
    {
        new_order();
        event.preventDefault();
    }

});
</script>
<!-- JS Plugins Init. -->
<script>
    jQuery(".search-bar-input").on('keyup',function () {
        //$('#pos-search-box').removeClass('d-none');
        $(".pos-search-card").removeClass('d-none').show();
        let name = $(".search-bar-input").val();
        //console.log(name);
        if (name.length >0) {
            $('#pos-search-box').removeClass('d-none').show();
            $.get({
                url: '{{route('admin.pos.search-products')}}',
                dataType: 'json',
                data: {
                    name: name
                },
                beforeSend: function () {
                    $('#loading').removeClass('d-none');
                },
                success: function (data) {
                    //console.log(data.count);

                    $('.search-result-box').empty().html(data.result);
                    if(data.count==1)
                    {
                        $('.search-result-box').empty().hide();
                        $('#search').val('');
                        quickView(data.id);
                    }

                },
                complete: function () {
                    $('#loading').addClass('d-none');
                },
            });
        } else {
            $('.search-result-box').empty();
        }
    });
</script>
<script>
    "use strict";
    function customer_change(val) {
        //let  cart_id = $('#cart_id').val();
        $.post({
                url: '{{route('admin.pos.remove-discount')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    //cart_id:cart_id,
                    user_id:val
                },
                beforeSend: function () {
                    $('#loading').removeClass('d-none');
                },
                success: function (data) {
                    console.log(data);

                    var output = '';
                    for(var i=0; i<data.cart_nam.length; i++) {
                        output += `<option value="${data.cart_nam[i]}" ${data.current_user==data.cart_nam[i]?'selected':''}>${data.cart_nam[i]}</option>`;
                    }
                    $('#cart_id').html(output);
                    $('#current_customer').text(data.current_customer);
                    $('#cart').empty().html(data.view);
                },
                complete: function () {
                    $('#loading').addClass('d-none');
                }
            });
    }
</script>
<script>
    "use strict";
    function clear_cart()
    {
        let url = "{{route('admin.pos.clear-cart-ids')}}";
        document.location.href=url;
    }
</script>
<script>
    "use strict";
    function new_order()
    {
        let url = "{{route('admin.pos.new-cart-id')}}";
        document.location.href=url;
    }
</script>
<script>
    "use strict";
    function cart_change(val)
    {
        let  cart_id = val;
        let url = "{{route('admin.pos.change-cart')}}"+'/?cart_id='+val;
        document.location.href=url;
    }
</script>
<script>
    "use strict";
    function extra_discount()
    {
        //let  user_id = $('#customer').val();
        let discount = $('#dis_amount').val();
        let type = $('#type_ext_dis').val();
        //let  cart_id = $('#cart_id').val();
        if(discount > 0)
        {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.pos.discount')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    discount:discount,
                    type:type,
                    //cart_id:cart_id
                },
                beforeSend: function () {
                    $('#loading').removeClass('d-none');
                },
                success: function (data) {
                   // console.log(data);
                    if(data.extra_discount==='success')
                    {
                        toastr.success('{{ \App\CPU\translate('extra_discount_added_successfully') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }else if(data.extra_discount==='empty')
                    {
                        toastr.warning('{{ \App\CPU\translate('your_cart_is_empty') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });

                    }else{
                        toastr.warning('{{ \App\CPU\translate('this_discount_is_not_applied_for_this_amount') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }

                    $('.modal-backdrop').addClass('d-none');
                    $('#cart').empty().html(data.view);

                    $('#search').focus();
                },
                complete: function () {
                    $('.modal-backdrop').addClass('d-none');
                    $(".footer-offset").removeClass("modal-open");
                    $('#loading').addClass('d-none');
                }
            });
        }else{
            toastr.warning('{{ \App\CPU\translate('amount_can_not_be_negative_or_zero!') }}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    }
</script>
<script>
    "use strict";
    function coupon_discount()
    {

        let  coupon_code = $('#coupon_code').val();

        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.pos.coupon-discount')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    coupon_code:coupon_code,
                },
                beforeSend: function () {
                    $('#loading').removeClass('d-none');
                },
                success: function (data) {
                    console.log(data);
                    if(data.coupon === 'success')
                    {
                        toastr.success('{{ \App\CPU\translate('coupon_added_successfully') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }else if(data.coupon === 'amount_low')
                    {
                        toastr.warning('{{ \App\CPU\translate('this_discount_is_not_applied_for_this_amount') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }else if(data.coupon === 'cart_empty')
                    {
                        toastr.warning('{{ \App\CPU\translate('your_cart_is_empty') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                    else {
                        toastr.warning('{{ \App\CPU\translate('coupon_is_invalid') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }

                    $('#cart').empty().html(data.view);

                    $('#search').focus();
                },
                complete: function () {
                    $('.modal-backdrop').addClass('d-none');
                    $(".footer-offset").removeClass("modal-open");
                    $('#loading').addClass('d-none');
                }
            });

    }
</script>
<script>
    $(document).on('ready', function () {
        @if($order)
        $('#print-invoice').modal('show');
        @endif
    });
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        // location.reload();
    }

    function set_category_filter(id) {
        var nurl = new URL('{!!url()->full()!!}');
        nurl.searchParams.set('category_id', id);
        location.href = nurl;
    }


    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        var keyword= $('#datatableSearch').val();
        var nurl = new URL('{!!url()->full()!!}');
        nurl.searchParams.set('keyword', keyword);
        location.href = nurl;
    });

    function store_key(key, value) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            }
        });
        $.post({
            url: '{{route('admin.pos.store-keys')}}',
            data: {
                key:key,
                value:value,
            },
            success: function (data) {
                toastr.success(key+' '+'{{\App\CPU\translate('selected')}}!', {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
        });
    }

    function addon_quantity_input_toggle(e)
    {
        var cb = $(e.target);
        if(cb.is(":checked"))
        {
            cb.siblings('.addon-quantity-input').css({'visibility':'visible'});
        }
        else
        {
            cb.siblings('.addon-quantity-input').css({'visibility':'hidden'});
        }
    }
    function quickView(product_id) {
        $.ajax({
            url: '{{route('admin.pos.quick-view')}}',
            type: 'GET',
            data: {
                product_id: product_id
            },
            dataType: 'json',
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                $('#quick-view').modal('show');
                $('#quick-view-modal').empty().html(data.view);
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }

    function checkAddToCartValidity() {
        var names = {};
        $('#add-to-cart-form input:radio').each(function () { // find unique names
            names[$(this).attr('name')] = true;
        });
        var count = 0;
        $.each(names, function () { // then count them
            count++;
        });

        if (($('input:radio:checked').length - 1) == count) {
            return true;
        }
        return false;
    }

    function cartQuantityInitialize() {
        $('.btn-number').click(function (e) {
            e.preventDefault();

            var fieldName = $(this).attr('data-field');
            var type = $(this).attr('data-type');
            var input = $("input[name='" + fieldName + "']");
            var currentVal = parseInt(input.val());

            if (!isNaN(currentVal)) {
                if (type == 'minus') {

                    if (currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }

                } else if (type == 'plus') {

                    if (currentVal < input.attr('max')) {
                        input.val(currentVal + 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('max')) {
                        $(this).attr('disabled', true);
                    }

                }
            } else {
                input.val(0);
            }
        });

        $('.input-number').focusin(function () {
            $(this).data('oldValue', $(this).val());
        });

        $('.input-number').change(function () {

            minValue = parseInt($(this).attr('min'));
            maxValue = parseInt($(this).attr('max'));
            valueCurrent = parseInt($(this).val());

            var name = $(this).attr('name');
            if (valueCurrent >= minValue) {
                $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Cart',
                    text: 'Sorry, the minimum value was reached'
                });
                $(this).val($(this).data('oldValue'));
            }
            if (valueCurrent <= maxValue) {
                $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Cart',
                    text: 'Sorry, stock limit exceeded.'
                });
                $(this).val($(this).data('oldValue'));
            }
        });
        $(".input-number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }

    function getVariantPrice() {
        if ($('#add-to-cart-form input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{ route('admin.pos.variant_price') }}',
                data: $('#add-to-cart-form').serializeArray(),
                success: function (data) {

                    $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                    $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                    $('#set-discount-amount').html(data.discount);
                }
            });
        }
    }

    function addToCart(form_id = 'add-to-cart-form') {
        if (checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.pos.add-to-cart') }}',
                data: $('#' + form_id).serializeArray(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {

                    if (data.data == 1) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Cart',
                            text: '{{ \App\CPU\translate("Product already added in cart")}}'
                        });
                        return false;
                    } else if (data.data == 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cart',
                            text: '{{ \App\CPU\translate("Sorry, product is out of stock.")}}'
                        });
                        return false;
                    }
                    $('.call-when-done').click();

                    toastr.success('{{ \App\CPU\translate("Item has been added in your cart!")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $('#cart').empty().html(data.view);
                    //updateCart();
                    $('.search-result-box').empty().hide();
                    $('#search').val('');
                },
                complete: function () {
                    $('#loading').hide();
                }
            });
        } else {
            Swal.fire({
                type: 'info',
                title: 'Cart',
                text: '{{ \App\CPU\translate("Please choose all the options")}}'
            });
        }
    }

    function removeFromCart(key) {
        //console.log(key);
        $.post('{{ route('admin.pos.remove-from-cart') }}', {_token: '{{ csrf_token() }}', key: key}, function (data) {

            $('#cart').empty().html(data.view);
            if (data.errors) {
                for (var i = 0; i < data.errors.length; i++) {
                    toastr.error(data.errors[i].message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            } else {
                //updateCart();

                toastr.info('{{ \App\CPU\translate("Item has been removed from cart")}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }


        });
    }

    function emptyCart() {
        Swal.fire({
            title: '{{\App\CPU\translate('Are_you_sure?')}}',
            text: '{{\App\CPU\translate('You_want_to_remove_all_items_from_cart!!')}}',
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#161853',
            cancelButtonText: '{{\App\CPU\translate("No")}}',
            confirmButtonText: '{{\App\CPU\translate("Yes")}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.post('{{ route('admin.pos.emptyCart') }}', {_token: '{{ csrf_token() }}'}, function (data) {
                    $('#cart').empty().html(data.view);
                    toastr.info('{{ \App\CPU\translate("Item has been removed from cart")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                });
            }
        })
    }

    function updateCart() {
        $.post('<?php echo e(route('admin.pos.cart_items')); ?>', {_token: '<?php echo e(csrf_token()); ?>'}, function (data) {
            $('#cart').empty().html(data);
        });
    }

   $(function(){
        $(document).on('click','input[type=number]',function(){ this.select(); });
    });


    function updateQuantity(key,qty,e, variant=null){

        if(qty!==""){
            var element = $( e.target );
            var minValue = parseInt(element.attr('min'));
            // maxValue = parseInt(element.attr('max'));
            var valueCurrent = parseInt(element.val());

            //var key = element.data('key');

            $.post('{{ route('admin.pos.updateQuantity') }}', {_token: '{{ csrf_token() }}', key: key, quantity:qty, variant:variant}, function (data) {

                if(data.product_type==='physical' && data.qty<0)
                {
                    toastr.warning('{{\App\CPU\translate('product_quantity_is_not_enough!')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
                if(data.upQty==='zeroNegative')
                {
                    toastr.warning('{{\App\CPU\translate('Product_quantity_can_not_be_zero_or_less_than_zero_in_cart!')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
                if(data.qty_update==1){
                    toastr.success('{{\App\CPU\translate('Product_quantity_updated!')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
                $('#cart').empty().html(data.view);
            });
        }else{
            var element = $( e.target );
            var minValue = parseInt(element.attr('min'));
            var valueCurrent = parseInt(element.val());

            $.post('{{ route('admin.pos.updateQuantity') }}', {_token: '{{ csrf_token() }}', key: key, quantity:minValue, variant:variant}, function (data) {

                if(data.product_type==='physical' && data.qty<0)
                {
                    toastr.warning('{{\App\CPU\translate('product_quantity_is_not_enough!')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
                if(data.upQty==='zeroNegative')
                {
                    toastr.warning('{{\App\CPU\translate('Product_quantity_can_not_be_zero_or_less_than_zero_in_cart!')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
                if(data.qty_update==1){
                    toastr.success('{{\App\CPU\translate('Product_quantity_updated!')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
                $('#cart').empty().html(data.view);
            });
        }

        // Allow: backspace, delete, tab, escape, enter and .
        if(e.type == 'keydown')
        {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        }

    };

    // INITIALIZATION OF SELECT2
    // =======================================================
    // $('.js-select2-custom').each(function () {
    //     var select2 = $.HSCore.components.HSSelect2.init($(this));
    // });

    $('.js-data-example-ajax').select2({
        ajax: {
            url: '{{route('admin.pos.customers')}}',
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data) {
                return {
                results: data
                };
            },
            __port: function (params, success, failure) {
                var $request = $.ajax(params);

                $request.then(success);
                $request.fail(failure);

                return $request;
            }
        }
    });

    $('#order_place').submit(function(eventObj) {
        if($('#customer').val())
        {
            $(this).append('<input type="hidden" name="user_id" value="'+$('#customer').val()+'" /> ');
        }
        return true;
    });

</script>
<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>
@endpush
