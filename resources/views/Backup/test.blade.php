<!doctype html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>User Data</title>
</head>
<body>
	<table>
		<thead>
			<tr>
				<th>Id</th>
				<th>Name</th>
				<th>Email</th>
				<th>Mobile No.</th>
				<th>DOB</th>
				<th>Gender</th>
				<th>User Type</th>
				<th>Dealer</th>
				<th>Dealer Mobile No.</th>
				<th>State</th>
				<th>District/City</th>
				<th>Pincode</th>
				<th>Address</th>
				<th>Added Date</th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $key=>$row)
			<tr>
				<td>{{$row->id}}</td>
				<td>{{$row->full_name}}</td>
				<td>{{$row->email}}</td>
				<td>{{$row->mobileno ?? '-NA-'}}</td>
				<td>{{$row->dob ?? '-NA-'}}</td>
				<td>{{$row->gender  ?? '-NA-'}}</td>
				<td>{{$row->user_type  ?? '-NA-'}}</td>
				<td>{{$row->dealer->full_name  ?? '-NA-'}}</td>
				<td>{{$row->dealer->mobileno  ?? '-NA-'}}</td>
				<td>{{$row->state  ?? '-NA-'}}</td>
				<td>{{$row->city  ?? '-NA-'}}</td>
				<td>{{$row->pincode  ?? '-NA-'}}</td>
				<td>{{$row->address  ?? '-NA-'}}</td>
				<td>{{date('d-m-Y h:i A',strtotime($row->created_at))}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>
