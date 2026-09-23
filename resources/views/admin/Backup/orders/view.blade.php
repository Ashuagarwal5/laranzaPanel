@php ($siteSettingList=App\WebsiteSetting::getWebsiteSettingAdmin())


<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title">Completed Order Detail <span class="pull-right"><a href="{{route('invoice-print',$order->order_id)}}" class="btn btn-warning" style="margin-right:5px;" alt="Print Invoice" target="_blank"><i class="fa fa-print"></i></a></span></h4>
</div>
<div class="modal-body">

<div style="font-family:Verdana,Arial;font-weight:normal;margin:0;padding:0;text-align:left;color:#333333;background-color:#fff;font-size:12px">
<div class="table-responsive">
<table style="border-collapse:collapse;padding:0;margin:0 auto;font-size:12px" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr>
<td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:0;margin:0;width:100%" valign="top" align="center">
<table style="border-collapse:collapse;padding:0;margin:0 auto;" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
<tbody>
<tr>
<td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:5px;margin:0;border:1px solid #ebebeb;background:#fff" valign="top">




<table style="border-collapse:collapse;padding:0;margin:0;width:100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
    <tr>
        <td style="background:#fafafa; text-align:center; padding:20px 0; border-bottom:solid 1px #ddd;">
            <center>
                <img alt="Arist Health Care" style="outline:none;text-decoration:none"  border="0" src="{{URL::to(App\Helpers\Thumbnail::image("logo/$siteSettingList->logo","200","50","f")) }}">

            </center>
        </td>
    </tr>
    <tr>
        <td  style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:5px 15px;margin:0;text-align:center">
            <h3 style="font-family:Verdana,Arial;font-weight:normal;font-size:17px;margin-bottom:10px;margin-top:15px">Your order <span>#{{$order->increment_id}}</span>
            </h3>
            <p style="font-family:Verdana,Arial;font-weight:normal;font-size:11px;margin:1em 0 15px">Placed on {{date('F d,Y h:i:s A',strtotime($order->created_at))}} IST</p>
        </td>
    </tr>
    <tr>

     <td><table style="border-collapse:collapse;padding:0;margin:0;width:100%" cellspacing="0" cellpadding="0" border="0">
        <tbody><tr>
            <td  style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:10px 15px 0;margin:0;padding-top:10px;text-align:left">
                <h6 style="font-family:Verdana,Arial;font-weight:700;font-size:12px;margin-bottom:0px;margin-top:5px;text-transform:uppercase">Ship To : </h6>
                <p style="font-family:Verdana,Arial;font-weight:normal;font-size:12px;line-height:18px;margin-bottom:15px;margin-top:2px"><span>
                    {{$order->first_name .' '. $order->last_name}}<br>
                    {{$order->ship_address}},<br>
                    {{$order->ship_city}}, {{$order->state}},
                    {{$order->pincode}}

                    {{ $order->ship_country}}<br>
                    Contact no : {{$order->phone}}<br>
                    E-mail :  {{$order->email}}


                </span></p>
            </td>

            @if($order->delivery_option != 'download' )

            @endif
        </tr>

    </tbody></table></td></tr>
    <tr>
        <td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:0;margin:0">

            <table style="border:1px solid #eaeaea;border-collapse:collapse;padding:0;margin:0;width:100%" width="650" cellspacing="0" cellpadding="0" border="0">
                <thead><tr>
                    <th style="font-size:13px;padding:3px 9px;font-family:Verdana,Arial;font-weight:normal" bgcolor="#EAEAEA" align="left">Sr No.</th>
                    <th style="font-size:13px;padding:3px 9px;font-family:Verdana,Arial;font-weight:normal" bgcolor="#EAEAEA" align="left">Item Description</th>
                    <th style="font-size:13px;padding:3px 9px;font-family:Verdana,Arial;font-weight:normal" bgcolor="#EAEAEA" align="left">Item Size/Color</th>
                    <th style="font-size:13px;padding:3px 9px;font-family:Verdana,Arial;font-weight:normal" bgcolor="#EAEAEA" align="left">Item Cost</th>
                    <th style="font-size:13px;padding:3px 9px;font-family:Verdana,Arial;font-weight:normal; text-align:center;" bgcolor="#EAEAEA" align="center">Qty</th>
                    <th style="font-size:12px;padding:3px 9px;font-family:Verdana,Arial;font-weight:normal; text-align:right;" bgcolor="#EAEAEA" align="right">Subtotal</th>
                </tr></thead>
                <tbody bgcolor="#F6F6F6">

                 @foreach($items as $key=>$value)
                 <tr>
                    <td style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" valign="top" align="left">
                        <strong style="font-size:11px;font-family:Verdana,Arial;font-weight:normal">{{$key+1}}</strong>
                    </td>
                    <td style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" valign="top" align="left">
                        <strong style="font-size:11px;font-family:Verdana,Arial;font-weight:normal">{{$value->product_name}}</strong>
                    </td>
                    <td style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" valign="top" align="left">
                        <strong style="font-size:11px;font-family:Verdana,Arial;font-weight:normal">
                            @if($value->product_id == 3)
                            {{$value->product_size}}/{{$value->product_color}}
                            @else
                            NA
                            @endif
                        </strong>
                    </td>
                    <td style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" valign="top" align="left">
                     {{$value->currency_symbol}} {{number_format(($value->price),2)}}</td>
                     <td style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" valign="top" align="center">
                      {{$value->quantity_order}}
                  </td>

                  <td style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0;text-align:right" valign="top" align="center">
                      {{$value->currency_symbol}} {{number_format(($value->row_total),2)}}</td>



                  </tr>
                  @endforeach
              </tbody>


              <tbody >
               @if($siteSettingList->enable_gst_setting == 'Yes')

               <tr>

                <td colspan="5" style=" font-size:12px; padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" align="right">
                Base Price   </td>
                <td style=" font-size:12px; padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" align="right"><span style="font-family:&quot;Helvetica Neue&quot;,Verdana,Arial,sans-serif">{{$order->currency_symbol}} {{number_format(($order->sub_total * 100/118),2)}}</span></td>
            </tr>
            <tr>
                <td colspan="5" style=" font-size:12px; padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" align="right">
                Tax  (GST 18%)    </td>
                <td style=" font-size:12px; padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" align="right"><span style="font-family:&quot;Helvetica Neue&quot;,Verdana,Arial,sans-serif">{{$order->currency_symbol}} {{number_format(($order->sub_total - $order->sub_total * 100/118),2)}}</span></td>
            </tr>
            @endif
            <tr>
                <td colspan="5" style="padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0; font-size:12px;" align="right">
                Subtotal        </td>
                <td style=" font-size:12px; padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" align="right">
                    <span style="font-family:Helvetica Neue&quot;,Verdana,Arial,sans-serif">{{$order->currency_symbol}} {{number_format(($order->sub_total),2)}}</span>                    </td>
                </tr>
                <tr>
                    <td colspan="5" style=" font-size:12px; padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" align="right">
                    Discount            </td>
                    <td style=" font-size:12px; padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" align="right"><span style="font-family:&quot;Helvetica Neue&quot;,Verdana,Arial,sans-serif">- {{$order->currency_symbol}} {{$order->discount_amount}}</span></td>
                </tr>





                <tr >
                    <td colspan="5" style="padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" align="right">
                        <strong style="font-family:Verdana,Arial;font-weight:normal">Total Payable</strong>
                    </td>
                    <td style="padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" align="right">
                        <strong style="font-family:Verdana,Arial;font-weight:normal"><span  style="font-family:&quot;Helvetica Neue&quot;,Verdana,Arial,sans-serif">{{$order->currency_symbol}} {{$order->grand_total}}</span></strong>
                    </td>
                </tr>
            </tbody>

        </table>
        <table style="border-collapse:collapse;padding:0;margin:0;width:100%" cellspacing="0" cellpadding="0" border="0">
            <tbody>
                <tr>


                    <td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:10px 0;margin:0;text-align:left;padding-bottom:10px">
                        <h6 style="font-family:Verdana,Arial;font-weight:700;text-align:left;font-size:12px;margin-bottom:0px;margin-top:5px;text-transform:uppercase">Payment method:</h6>
                        <p style="font-family:Verdana,Arial;font-weight:normal;text-align:left;font-size:12px;margin-top:2px;margin-bottom:30px;line-height:18px;padding:0"><strong style="font-family:Verdana,Arial;font-weight:normal;text-align:left">{{($order->payment_method=='cod')? 'Cash On Delivery' : $order->payment_method}}</strong></p>



                    </td>
                </tr>
            </tbody></table>
        </td>
    </tr>
    <tr>
        <td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:0;margin:0">
            <table style="border-collapse:collapse;padding:0;margin:0 ; width: 100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr>
                <td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:0 1%;margin:0;background:#e1f0f8;border-right:1px dashed #c3ced4;text-align:center;">
                    <h1 style="font-family:Verdana,Arial;font-weight:700;font-size:14px;margin:1em 0;line-height:20px;text-transform:uppercase;margin-top:25px">WELCOME TO THE Arist Health Care FAMILY.</h1>

                </td>
                <td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:2%;margin:0;background:#e1f0f8;width:40%">
                    <h4 style="font-family:Verdana,Arial;font-weight:bold;margin-bottom:5px;font-size:12px;margin-top:13px">Order Questions?</h4>
                    <p style="font-family:Verdana,Arial;font-weight:normal;font-size:11px;line-height:17px;margin:1em 0">

                        <b>Call Us:</b>
                        <a style="color:#3696c2;text-decoration:underline">{{$siteSettingList->contact_no}}</a><br><span>10 Am to 7 Pm</span><br><b>Email:</b> <a href="#" style="color:#3696c2;text-decoration:underline" target="_blank">{{$siteSettingList->customer_support_email}}</a>

                    </p>
                </td>
            </tr></tbody></table>
        </td>
    </tr>
</tbody></table>

</td>
</tr>
</tbody></table>
<h5  style="font-family:Verdana,Arial;font-weight:normal;text-align:center;font-size:22px;line-height:32px;margin-bottom:20px;margin-top:20px">Thank you, Arist Health Care!</h5>
</td>
</tr>
</tbody>
</table>
</div>
<div class="yj6qo">
</div>
<div class="adL">
</div>
</div>

</div>


