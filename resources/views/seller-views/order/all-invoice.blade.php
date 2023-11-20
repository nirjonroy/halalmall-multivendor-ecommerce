<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{\App\CPU\translate('invoice')}}</title>
    <meta http-equiv="Content-Type" content="text/html;"/>
    <meta charset="UTF-8">
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
</head>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<body>
@foreach ($orders as $order)
<div style="page-break-after: always;"> 
<div class="invoice" >
    <table class="content-position mb-30">
        <tr>
            <th>
                <img height="50" src="{{ asset('storage/app/public/company/' . $company_web_logo) }}" alt="">
            </th>
        </tr>
    </table>

    <table class="bs-0 mb-30 px-10">
        <tr>
            <th class="content-position-y text-left">
                <h4 class="text-uppercase mb-1 fz-14">
                  Invoice #  {{ $order->id }}
                </h4>
               
            </th>
            <th class="content-position-y text-right">
                <h4 class="fz-14">{{$order->created_at}}</h4>
            </th>
        </tr>
    </table>
</div>
<div class="">
    <section>
        <table class="content-position-y fz-12">
            <tr>
                <td class="font-weight-bold p-1">
                    <table>
                        <tr>
                            <td>
                                
                                @php
                                $customerName = $order->customer ? $order->customer->f_name . ' ' . $order->customer->l_name : 'N/A';
                                $customerPhone = $order->customer ? $order->customer->phone : 'N/A';
                                $customerEmail = $order->customer ? $order->customer->email : 'N/A';
                                $customerShAddress = $order->customer ? $order->customer->address : 'N/A';
                                $customerCityAddress = $order->customer ? $order->customer->city : 'N/A';
                                @endphp
                                @if ($order->shippingAddress)
                                    <span class="h2" style="margin: 0px;">{{\App\CPU\translate('shipping_to')}} </span>
                                    <div class="h4 montserrat-normal-600">
                                        <p style=" margin-top: 6px; margin-bottom:0px;">@php echo $customerName; @endphp</p>
                                        <p style=" margin-top: 6px; margin-bottom:0px;">@php echo $customerPhone; @endphp</p>
                                        <p style=" margin-top: 6px; margin-bottom:0px;">@php echo $customerEmail; @endphp</p>
                                        <p style=" margin-top: 6px; margin-bottom:0px;">@php echo $customerShAddress; @endphp</p>
                                        <p style=" margin-top: 6px; margin-bottom:0px;">@php echo $customerCityAddress; @endphp</p>
                                        
                                    </div>
                                @else


                                    <span class="h2" style="margin: 0px;">{{\App\CPU\translate('customer_info')}} </span>
                                    <div class="h4 montserrat-normal-600">
                                        <p style=" margin-top: 6px; margin-bottom:0px;">@php echo $customerName; @endphp</p>
                                        @if (isset($order->customer) && $order->customer['id']!=0)
                                            <p style=" margin-top: 6px; margin-bottom:0px;">@php echo $customerEmail; @endphp</p>
                                            <p style=" margin-top: 6px; margin-bottom:0px;">@php echo $customerPhone; @endphp</p>
                                        @endif
                                    </div>
                                @endif
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
<td>
<div class="item style24" style="height: 235px;">            
            @if ($order->id !== null)
                <div class="barcode_image">
                <img height="90" width="90" src="data:image/png;base64,{{ DNS2D::getBarcodePNG($order->id.' '.$order->payment_status.' '.$customerEmail.' '.$customerPhone.' '.$order->order_amount,'QRCODE') }}" alt="barcode" />
               </div>
                <div class="barcode_code text-capitalize"></div>
            @else
                <p class="text-danger">{{ \App\CPU\translate('please_update_product_code') }}</p>
            @endif
            
        </div>
</td>
                <td>
                    
                </td>
            </tr>
        </table>


    </section>
</div>

<br>

<div class="">
    <div class="content-position-y">
        <table class="customers bs-0">
            <thead>
                <tr>
                    <th>{{\App\CPU\translate('SL')}}</th>
                    <th>{{\App\CPU\translate('item_description')}}</th>
                    <th>
                        {{\App\CPU\translate('unit_price')}}
                    </th>
                    <th>
                        {{\App\CPU\translate('qty')}}
                    </th>
                    <th>
                        {{\App\CPU\translate('total')}}
                    </th>
                </tr>
            </thead>
            @php
                $subtotal=0;
                $total=0;
                $sub_total=0;
                $total_tax=0;
                $total_shipping_cost=0;
                $total_discount_on_product=0;
                $ext_discount=0;
            @endphp
            <tbody>
            @foreach($order->details as $key => $details)
                @php $subtotal=($details['price'])*$details->qty @endphp
                <tr>
                    <td>{{$key+1}}</td>
                    <td>
                         @if ($details->product)
                                {{ $details->product->name }}
                            @else
                                {{ __('Product Not Found') }}
                            @endif
                        <br>
                        
                    </td>
                    <td>{{ $details->price }}</td>
                    <td>{{$details->qty}}</td>
                    <td>@php echo $subtotal; @endphp</td>
                </tr>

                @php
                    $sub_total+=$details['price']*$details['qty'];
                    $total_tax+=$details['tax'];
                    $total_shipping_cost+=$details->shipping ? $details->shipping->cost :0;
                    $total_discount_on_product+=$details['discount'];
                    $total+=$subtotal;
                @endphp
                
            @endforeach
            </tbody>
            <hr/>
            <tfoot style="font-size:14px">
                <tr>
                    <td colspan="4" style="text-align:right">Subtotal</td>
                    <td>@php echo $sub_total @endphp</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:right">Tax</td>
                    <td>@php echo $total_tax @endphp</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:right">Shipping Cost</td>
                    <td>@php echo $total_shipping_cost @endphp</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:right">Discount</td>
                    <td>@php echo $total_discount_on_product @endphp</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:right; ">Total</td>
                    <td>@php echo $total @endphp</td>
                </tr>
                
               
            </tfoot>
        </table>
    </div>
</div>
<?php
    if ($order['extra_discount_type'] == 'percent') {
        $ext_discount = ($sub_total / 100) * $order['extra_discount'];
    } else {
        $ext_discount = $order['extra_discount'];
    }
?>


<br>
<br><br><br>

<div class="row">
    <section>
        <table class="">
            <tr>
                <th class="fz-12 font-normal pb-3">
                    If_you_require_any_assistance_or_have_feedback_or_suggestions_about_our_site,_you <br /> can_email_us_at <a href="mail::to(Info@softItGlobal.com)">info@softitglobal.com</a>
                </th>
            </tr>
            <tr>
                <th class="content-position-y bg-light py-4">
                    <div class="d-flex justify-content-center gap-2">
                        <div class="mb-2">
                            <i class="fa fa-phone"></i>
                            {{\App\CPU\translate('phone')}}
                            : 01111
                        </div>
                        <div class="mb-2">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            {{\App\CPU\translate('email')}}
                            : r@ff
                        </div>
                    </div>
                    <div class="mb-2">
                        {{url('/')}}
                    </div>
                    <div>
                        {{\App\CPU\translate('All_copy_right_reserved_©_'.date('Y').'_').$company_name}}
                    </div>
                </th>
            </tr>
        </table>
    </section>
</div>
</div>
@endforeach
</body>
</html>