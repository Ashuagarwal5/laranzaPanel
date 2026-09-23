<!doctype html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>CustomerPoints Data</title>
	</head>
	<body>
		<table>
			<thead>
				<tr>
					<th>Customer Name</th>
					<th>Mobile No.</th>
					<th>Coupon No.</th>
					<th>QR Value</th>
					<th>Reward Points</th>
					<th>Transaction Type</th>
					<th>Lang.</th>
					<th>Lat.</th>
					<th>Product Group Code</th>
					<th>Density</th>
					<th>Length</th>
					<th>Width</th>
					<th>Thickness</th>

					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				@foreach($csp as $key=>$row)
				<tr>
					<td>{{$row->full_name ?? '-NA-'}}</td>
					<td>{{$row->mobileno ?? '-NA-'}}</td>
					<td>{{$row->coupon_no ?? '-NA-'}}</td>
					<td>{{$row->qr_value ?? '-NA-'}}</td>
					<td>{{$row->point ?? '-NA-'}}</td>
					<td>{{$row->transaction_type  ?? '-NA-'}}</td>
					<td>{{$row->lg  ?? '-NA-'}}</td>
					<td>{{$row->lt  ?? '-NA-'}}</td>
					<td>{{$row->product_group_code  ?? '-NA-'}}</td>
					<td>{{$row->density  ?? '-NA-'}}</td>
					<td>{{$row->length  ?? '-NA-'}}</td>
					<td>{{$row->width  ?? '-NA-'}}</td>
					<td>{{$row->thickness  ?? '-NA-'}}</td>
					<td>{{$row->add_date  ?? '-NA-'}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</body>
	</html>
