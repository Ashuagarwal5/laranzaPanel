<!doctype html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Bulk History Data</title>
</head>
<body>
	<table>
		<thead>
			<tr>
				<th>Sr No.</th>
				<th>Reward Value</th>
				<th>QR Value</th>
				<!-- <th>QR Link</th> -->
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key=>$row)
			<tr>
				<td>{{ $row->id }}</td>
				<td>{{ $row->reward_points }}</td>
				<td>{{ $row->qr_value }}</td>
				<!-- <td></td> -->
				<td>{{ $row->add_date }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>
