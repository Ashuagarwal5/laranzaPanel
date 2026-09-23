<!doctype html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Customer Transactions History</title>
</head>
<body>
	<table>
		<thead>
			<tr>
				<th>User Id</th>
				<th>Name</th>
				<th>Mobile No.</th>
				<th>City</th>
				<th>State</th>
				<th>Total Earned</th>												
				<th>Total Redeem</th>
				<th>Point Balance</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key=>$row)
			<tr>
				<td>{{ $row->id }}</td>
				<td>{{ $row->full_name }}</td>
				<td>{{ $row->mobileno }}</td>
				<td>{{ $row->city }}</td>
				<td>{{ $row->state }}</td>
				<td>{{ $row->total_earned }}</td>												
				<td>{{  (float) $row->total_redeemed }}</td>
				<td>{{ $row->total_earned - $row->total_redeemed }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>
