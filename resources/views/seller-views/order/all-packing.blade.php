<!DOCTYPE html>
<html>
   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Packing PDF</title>
      <!-- Add any necessary meta tags, stylesheets, or fonts here -->
      <style media="all">
         * {
         margin: 0;
         padding: 0;
         line-height: 1.3;
         font-family: sans-serif;
         color: #333542;
         }
         /* IE 6 */
         * html .footer {
         position: absolute;
         top: expression((0-(footer.offsetHeight)+(document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight)+(ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop))+'px');
         }
         body {
         font-size: .75rem;
         }
         img {
         max-width: 100%;
         }
         .customers {
         font-family: Arial, Helvetica, sans-serif;
         border-collapse: collapse;
         width: 100%;
         }
         table {
         width: 100%;
         }
         table thead th {
         padding: 8px;
         font-size: 11px;
         text-align: left;
         }
         table tbody th,
         table tbody td {
         padding: 8px;
         font-size: 11px;
         }
         table.fz-12 thead th {
         font-size: 12px;
         }
         table.fz-12 tbody th,
         table.fz-12 tbody td {
         font-size: 12px;
         }
         table.customers thead th {
         background-color: #0177CD;
         color: #fff;
         }
         table.customers tbody th,
         table.customers tbody td {
         background-color: #FAFCFF;
         }
         table.calc-table th {
         text-align: left;
         }
         table.calc-table td {
         text-align: right;
         }
         table.calc-table td.text-left {
         text-align: left;
         }
         .table-total {
         font-family: Arial, Helvetica, sans-serif;
         }
         .text-left {
         text-align: left !important;
         }
         .pb-2 {
         padding-bottom: 8px !important;
         }
         .pb-3 {
         padding-bottom: 16px !important;
         }
         .text-right {
         text-align: right;
         }
         .content-position {
         padding: 15px 40px;
         }
         .content-position-y {
         padding: 0px 40px;
         }
         .text-white {
         color: white !important;
         }
         .bs-0 {
         border-spacing: 0;
         }
         .text-center {
         text-align: center;
         }
         .mb-1 {
         margin-bottom: 4px !important;
         }
         .mb-2 {
         margin-bottom: 8px !important;
         }
         .mb-4 {
         margin-bottom: 24px !important;
         }
         .mb-30 {
         margin-bottom: 30px !important;
         }
         .px-10 {
         padding-left: 10px;
         padding-right: 10px;
         }
         .fz-14 {
         font-size: 14px;
         }
         .fz-12 {
         font-size: 12px;
         }
         .fz-10 {
         font-size: 10px;
         }
         .font-normal {
         font-weight: 400;
         }
         .border-dashed-top {
         border-top: 1px dashed #ddd;
         }
         .font-weight-bold {
         font-weight: 700;
         }
         .bg-light {
         background-color: #F7F7F7;
         }
         .py-30 {
         padding-top: 30px;
         padding-bottom: 30px;
         }
         .py-4 {
         padding-top: 24px;
         padding-bottom: 24px;
         }
         .d-flex {
         display: flex;
         }
         .gap-2 {
         gap: 8px;
         }
         .flex-wrap {
         flex-wrap: wrap;
         }
         .align-items-center {
         align-items: center;
         }
         .justify-content-center {
         justify-content: center;
         }
         a {
         color: rgba(0, 128, 245, 1);
         }
         .p-1 {
         padding: 4px !important;
         }
         .h2 {
         font-size: 1.5em;
         margin-block-start: 0.83em;
         margin-block-end: 0.83em;
         margin-inline-start: 0px;
         margin-inline-end: 0px;
         font-weight: bold;
         }
         .h4 {
         margin-block-start: 1.33em;
         margin-block-end: 1.33em;
         margin-inline-start: 0px;
         margin-inline-end: 0px;
         font-weight: bold;
         }
      </style>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   </head>
   <body>
      <!--dsflkdsjfldsf j-->
      @foreach($orders as $key => $order)
      <div class="content container-fluid" style="page-break-after: always;">
         <!-- Page Title -->
         <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
               <img src="{{asset('/public/assets/back-end/img/all-orders.png')}}" alt="">
               {{\App\CPU\translate('order_details')}}
            </h2>
         </div>
         <!-- End Page Title -->
         <div class="row gy-3" id="printableArea">
            <div class="col-lg-8 col-xl-9">
               <!-- Card -->
               <div class="card h-100">
                  <!-- Body -->
                  <div class="card-body">
                     <!-- Order Note -->
                     <table class="table table-bordered" border="1">
                        <tbody>
                           <tr>
                              <td style="font-size:14px">MARCHANT</td>
                              <td  style="font-size:14px"> 
                                 {{ $order->seller->shop->name }}
                                 <br/>
                                 {{ $order->seller->shop->address }}
                                 <br/>
                                 {{ $order->seller->shop->contact }}
                              </td>
                           </tr>
                           <tr>
                              @php
                              $customerName = $order->customer ? $order->customer->f_name . ' ' . $order->customer->l_name : 'N/A';
                              $customerPhone = $order->customer ? $order->customer->phone : 'N/A';
                              $customerEmail = $order->customer ? $order->customer->email : 'N/A';
                              $customerShAddress = $order->customer ? $order->customer->address : 'N/A';
                              $customerCityAddress = $order->customer ? $order->customer->city : 'N/A';
                              @endphp
                              <td style="font-size:14px">Customer </td>
                              <td style="font-size:14px">
                                 <!--@php echo $customerName; @endphp-->
                                 <!--<br/>-->
                                 <!--@php echo $customerPhone; @endphp-->
                                 <!--<br/>-->
                                 
                                     @php
                                        $shippingAddress = json_decode($order->shipping_address_data);
                                    @endphp
                                
                                    @if ($shippingAddress)
                                        <p>Name: {{ $shippingAddress->contact_person_name }}</p>
                                        <p>Address Type: {{ $shippingAddress->address_type }}</p>
                                        <p>Address: {{ $shippingAddress->address }}</p>
                                        <p>City: {{ $shippingAddress->city }}</p>
                                        <p>Zip: {{ $shippingAddress->zip }}</p>
                                        <p>Phone: {{ $shippingAddress->phone }}</p>
                                        
                                    @else
                                        <p>No Customer.</p>
                                    @endif
                                 <br/>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row">
                                 <div class="item style24" style="">
                                    <span
                                       class="barcode_site text-capitalize"></span>
                                    <span class="barcode_name text-capitalize"></span>
                                    @if ($order->id !== null)
                                    <div class="barcode_image" style="text-align:left">
                                       @php
                                       $customerName = $order->customer ? $order->customer->f_name . ' ' . $order->customer->l_name : 'N/A';
                                       $customerPhone = $order->customer ? $order->customer->phone : 'N/A';
                                       $customerEmail = $order->customer ? $order->customer->email : 'N/A';
                                       $customerShAddress = $order->customer ? $order->customer->address : 'N/A';
                                       $customerCityAddress = $order->customer ? $order->customer->city : 'N/A';
                                       @endphp
                                       <img height="115" width="115" src="data:image/png;base64,{{ DNS2D::getBarcodePNG($order->id.' '.$order->payment_status.' '.$customerName.' '.$customerPhone.' '.$order->order_amount,'QRCODE') }}" alt="barcode" />
                                       <br> <br/>
                                       
                                    </div>
                                    <div class="barcode_code text-capitalize"></div>
                                    @else
                                    <p class="text-danger">{{ \App\CPU\translate('please_update_product_code') }}</p>
                                    @endif
                                 </div>
                              </th>
                              <th>
                              <div class="mb-3" style="text-align:left"> 
                                       <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($order->id, 'C39', true) }}" alt=""  align="left" />
                                       </div>
                                       <div class="barcode_code text-capitalize" style="margin-left: 15%;">
                                          {{$order->id}}
                                       </div>
                              </th>
                           </tr>
                        </tbody>
                     </table>
                     <hr />
                     <!-- End Row -->
                  </div>
                  <!-- End Body -->
               </div>
               <!-- End Card -->
            </div>
         </div>
         <!-- End Row -->
      </div>
      @endforeach  
   </body>
</html>