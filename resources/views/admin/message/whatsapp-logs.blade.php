@extends('admin.layouts.default')
{{-- Page title --}}
@section('title')
Logs::CRM
@parent
@stop
{{-- Page content --}}
@section('content')
<section class="content-header">
	<h1>Logs</h1>
	<ol class="breadcrumb">
		<li>
			<a href="{{ route('admin.dashboard') }}">
				<i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
				Dashboard
			</a>
		</li>
		<li>Message Manager</li>
		<li class="active">Logs</li>
	</ol>
</section>
<section class="content paddingleft_right15">
	<div class="row">
		<div class="panel panel-primary">
			<div class="panel-heading clearfix">
				<h4 class="panel-title pull-left">
					<i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i>
					Logs
				</h4>
				<div class="pull-right">
					<a href="{{ route('admin.message') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Back to Messages</a>
				</div>
			</div>
			<div class="panel-body">
				@if (session('log_message'))
				<div class="alert alert-success">{{ session('log_message') }}</div>
				@endif

				<ul class="nav nav-tabs" style="margin-bottom:15px;">
					<li @if($tab=='whatsapp') class="active" @endif>
						<a href="{{ route('admin.whatsapp.logs', ['tab' => 'whatsapp']) }}">WhatsApp History</a>
					</li>
					<li @if($tab=='sms') class="active" @endif>
						<a href="{{ route('admin.whatsapp.logs', ['tab' => 'sms']) }}">SMS History</a>
					</li>
				</ul>

				<div style="margin-bottom:12px;">
					<a href="{{ route('admin.whatsapp.logs', ['tab' => $tab]) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Refresh</a>
					<form method="post" action="{{ route('admin.whatsapp.logs.clear') }}" style="display:inline;" onsubmit="return confirm('Clear this history? This cannot be undone.');">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<input type="hidden" name="tab" value="{{ $tab }}" />
						<button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Clear this log</button>
					</form>
					<span class="text-muted" style="margin-left:10px;">Latest 200 attempts. Older entries are removed automatically.</span>
				</div>

				@if ($tab == 'sms')
				<div class="table-responsive">
					<table class="table table-bordered table-condensed">
						<thead>
							<tr><th>ID</th><th>Time</th><th>Mobile</th><th>Sender ID</th><th>Template ID</th><th>Event</th><th>Status</th><th>Error</th><th>API response</th></tr>
						</thead>
						<tbody>
							@forelse ($smsRows as $row)
							<tr>
								<td>{{ $row->id }}</td>
								<td>{{ $row->created_at }}</td>
								<td>{{ $row->mobileno }}</td>
								<td>{{ $row->sender_id }}</td>
								<td style="font-size:11px; word-break:break-all;">{{ $row->template_id }}</td>
								<td>{{ $row->event }}</td>
								<td><span class="label label-{{ in_array($row->status, ['delivered','sent','queued','submitted']) ? 'success' : ($row->status == 'failed' ? 'danger' : 'default') }}">{{ $row->status }}</span></td>
								<td style="max-width:260px; word-break:break-word;">{{ $row->error }}</td>
								<td style="font-size:11px; max-width:300px; word-break:break-all;">{{ mb_strimwidth((string) $row->response, 0, 300, '...') }}</td>
							</tr>
							@empty
							<tr><td colspan="9" class="text-center text-muted">No SMS have been attempted yet.</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
				@else
				<div class="table-responsive">
					<table class="table table-bordered table-condensed">
						<thead>
							<tr><th>ID</th><th>Time</th><th>Mobile</th><th>Template</th><th>Status</th><th>Error</th><th>wamid</th><th>API response</th></tr>
						</thead>
						<tbody>
							@forelse ($whatsappRows as $row)
							<tr>
								<td>{{ $row->id }}</td>
								<td>{{ $row->created_at }}</td>
								<td>{{ $row->mobileno }}</td>
								<td>{{ $row->template_name }}</td>
								<td>
									<span class="label label-{{ in_array($row->status, ['sent','delivered','read']) ? 'success' : ($row->status == 'failed' ? 'danger' : 'default') }}">{{ $row->status }}</span>
								</td>
								<td style="max-width:360px; word-break:break-word;">{{ $row->error }}</td>
								<td style="font-size:11px; word-break:break-all;">{{ $row->wamid }}</td>
								<td style="font-size:11px; max-width:320px; word-break:break-all;">{{ mb_strimwidth((string) $row->response, 0, 300, '...') }}</td>
							</tr>
							@empty
							<tr><td colspan="8" class="text-center text-muted">No WhatsApp messages have been attempted yet.</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
				@endif
			</div>
		</div>
	</div>
</section>
@stop
