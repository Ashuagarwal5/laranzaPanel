<!doctype html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Pending Point Redemption Data</title>
</head>
<body>
	<table>
		<thead>
			<tr>
				<th>Id</th>
				<th>Name</th>
				<th>Mobile No.</th>
				<th>City</th>
				<th>State</th>
				<th>Pincode</th>												
				<th>Reward Points</th>
				<th>Request Date</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key=>$row)
			<tr>
				<td>{{$row->id}}</td>
				<td>{{$row->user_name}}</td>
				<td>{{$row->user_mobile}}</td>
				<td>{{$row->user_city}}</td>
				<td>{{$row->user_state}}</td>
				<td>{{$row->user_pincode}}</td>												
				<td>{{$row->points}}</td>
				<td>{{$row->add_date}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>
