<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title" id="user_delete_confirm_title">{{$model}}</h4>
</div>
<div class="modal-body">
   
        @if($error)
        <div>{!! $error !!}</div>
    @else
    
    		<form class="form-horizontal ajaxform" method="post" action="{{ $confirm_route }}" >
	   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
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
            <th style="font-size:12px;padding:3px 9px;font-family:Verdana,Arial;font-weight:normal; text-align:right;" bgcolor="#EAEAEA" align="left">Action</th>
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
	  <td style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0;text-align:left" valign="top" align="center"> 
		<input type="radio" name="order_action[{{ $value->ord_item_id }}]" value="Item Ready to Ship" required> Item Ready to Deliver<br>
<input type="radio" name="order_action[{{ $value->ord_item_id }}]" value="Item not available">  Item not available </td>	
                    
   <?php   $total = $total + $value->row_total ?>
</tr>
@endforeach
</tbody>


<tbody >






</tbody>

</table>
<table style="border-collapse:collapse;padding:0;margin:0;width:100%" cellspacing="0" cellpadding="0" border="0">
<tbody>

</tbody></table>
</td>
    </tr>
    <tr>
<td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:0;margin:0">
	<br />
	                    <label for="password_login">Delivery Time</label>
                        <div class="clr"></div>
                        <div style="width:20%; float:left;">  <input type="text" name="delivery_time" class="form-control" style="height:38px;"></div>
                        
                        
                      
	
	<button  type="submit" class="btn btn-danger" style="margin: 2px 5px;">Confirm</button>
</td>

    </tr>

</tbody>
</table>

  </tbody>            
	
    
    
    
    </form>
    
    
      
    @endif
    
    

 
</div>

