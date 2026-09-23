 @if(!empty($replytMsg) && count($replytMsg)>0 )
 <table class="table table-striped table-bordered table-hover" id="">
	<thead>
	<tr>
	<th>Sr. No</th>
	<th>Message</th>
	<th>Replied Date</th>
	</tr>
	</thead>
	<tbody>
			@foreach($replytMsg as $key => $value)
			<tr>
			<td>Reply No {{ $key +1  }}</td>
			<td> {!! nl2br(e($value->reply)) !!} </td>
			<td> 9 June 2017 </td>
			</tr>
			@endforeach			
			</tbody>
			</table>

@endif



