

<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Item Detail <span class="pull-right">
				</span></h4>
</div>
		<div class="modal-body">
	

<div class="table-responsive">

<table style="border-collapse:collapse;padding:0;margin:0;width:100%" cellspacing="0" cellpadding="0" border="0">
<tbody>

 <tr>
 


<tr>
<td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:0;margin:0">
            
            <table style="border:1px solid #eaeaea;border-collapse:collapse;padding:0;margin:0;width:100%" width="650" cellspacing="0" cellpadding="0" border="0">
<thead><tr>
<th style="font-size:13px;padding:3px 9px;font-family:Verdana,Arial;font-weight:normal" bgcolor="#EAEAEA" align="left">Sr No.</th>
<th style="font-size:13px;padding:3px 9px;font-family:Verdana,Arial;font-weight:normal" bgcolor="#EAEAEA" align="left">Item Description</th>
<th style="font-size:13px;padding:3px 9px;font-family:Verdana,Arial;font-weight:normal" bgcolor="#EAEAEA" align="left">Seller Name</th>
            <th style="font-size:13px;padding:3px 9px;font-family:Verdana,Arial;font-weight:normal" bgcolor="#EAEAEA" align="left">Item Cost</th>
            <th style="font-size:13px;padding:3px 9px;font-family:Verdana,Arial;font-weight:normal; text-align:center;" bgcolor="#EAEAEA" align="center">Qty</th>
            <th style="font-size:12px;padding:3px 9px;font-family:Verdana,Arial;font-weight:normal; text-align:right;" bgcolor="#EAEAEA" align="right">Subtotal</th>
        </tr></thead>
<tbody bgcolor="#F6F6F6">
	
	<?php  $total = 0; ?>
	@foreach($items as $key=>$value)
	<tr>
<td style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" valign="top" align="left">
        <strong style="font-size:11px;font-family:Verdana,Arial;font-weight:normal">{{$key+1}}</strong>
</td> 
<td style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" valign="top" align="left">
        <strong style="font-size:11px;font-family:Verdana,Arial;font-weight:normal">{{$value->product_name}}
        ({{ $value->unit}})
        
        </strong>
</td>

<td style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" valign="top" align="left">
        <strong style="font-size:11px;font-family:Verdana,Arial;font-weight:normal">{{ucfirst($value->company_name)}}</strong>
</td> 

<td style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" valign="top" align="left">
	 {{ $order->order_currency_code }} {{number_format(($value->price),2)}}</td>
    <td style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" valign="top" align="center">{{ $value->quantity_order}}</td>
   
    <td style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0;text-align:right" valign="top" align="center"> 
		{{ $order->order_currency_code }} {{number_format(($value->row_total),2)}}</td>
                    
   <?php   $total = $total + $value->row_total ?>
</tr>
@endforeach
</tbody>


<tbody >

<tr>
	
	<td colspan="2" align="right">
		
		
		Delivery Time : {{ $order->delivery_time }}    | Delivery Address : {{ ($order->order_address) }}
	
	</td>
	
<td colspan="3" style="padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0; font-size:12px;" align="right">
                        Subtotal        </td>
       
       
       
       
        <td style=" font-size:12px; padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" align="right">
                        <span style="font-family:Helvetica Neue&quot;,Verdana,Arial,sans-serif">{{ $order->order_currency_code }}  {{number_format(($total),2)}}</span>                    </td>
    </tr>
    <td colspan="5" style=" font-size:12px; padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" align="right">
                    Discount            </td>
    <td style=" font-size:12px; padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" align="right"><span style="font-family:&quot;Helvetica Neue&quot;,Verdana,Arial,sans-serif">- {{ $order->order_currency_code }} {{$order->discount_amount}}</span></td>
</tr>





<tr >
<td colspan="5" style="padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" align="right">
                        <strong style="font-family:Verdana,Arial;font-weight:normal">Total Payable</strong>
                    </td>
        <td style="padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" align="right">
                        <strong style="font-family:Verdana,Arial;font-weight:normal"><span  style="font-family:&quot;Helvetica Neue&quot;,Verdana,Arial,sans-serif">{{ $order->order_currency_code }}  {{$total}}</span></strong>
                    </td>
    </tr>
</tbody>

</table>
<table style="border-collapse:collapse;padding:0;margin:0;width:100%" cellspacing="0" cellpadding="0" border="0">
<tbody>

</tbody></table>
</td>
    </tr>
    <tr>
<td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:0;margin:0">
</td>

    </tr>

</tbody>
</table>

  </tbody>            
			
		</div>


