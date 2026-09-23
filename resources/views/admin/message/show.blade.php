@extends('admin/layouts/default')
@section('title')
{{ $manager_name }} Manager::CRM
@stop
@section('header_styles')
<style>
	.msg-view dt {
		color: #777;
		font-weight: 600;
	}

	.msg-view dd {
		margin-bottom: 8px;
	}

	.msg-text {
		background: #f5f5f5;
		border: 1px solid #e3e3e3;
		border-radius: 6px;
		padding: 10px 12px;
		white-space: pre-wrap;
		word-wrap: break-word;
	}

	.channel-off {
		color: #999;
	}
</style>
@stop
@section('content')
@php
// How a saved mapping value reads: a token shows its label, anything else is fixed text.
$describe = function ($value) use ($tokens) {
if ($value === '' || $value === null) {
return '<span class="text-danger">not set</span>';
}
return isset($tokens[$value])
? e($tokens[$value]).' <code>'.e($value).'</code>'
: 'Fixed text: &ldquo;'.e($value).'&rdquo;';
};
$smsMapping = $data->smsMapping();
$waMapping = $whatsapp ? $whatsapp->mapping() : [];
$eventDescription = \App\Services\MessageEvents::description($data->title);
@endphp
<section class="content-header">
	<h1>{{ $manager_name }} Manager</h1>
	<ol class="breadcrumb">
		<li>
			<a href="{{ route('admin.dashboard') }}">
				<i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
				Dashboard
			</a>
		</li>
		<li>{{ $manager_name }} Manager</li>
		<li class="active">View</li>
	</ol>
</section>
<section class="content msg-view">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<h3 class="panel-title">
						<i class="livicon" data-name="eye-open" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i>
						{{ $data->title }}
					</h3>
					<div class="pull-right">
						<a href="{{ url('cpmin/message/edit/'.$data->id) }}" class="btn btn-sm btn-default"><i class="fa fa-edit"></i> Edit</a>
						<a href="{{ $manager_url }}" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>
					</div>
				</div>
				<div class="panel-body">
					<dl class="dl-horizontal">
						<dt>Event</dt>
						<dd>{{ $data->title }}
							@if($eventDescription) <span class="text-muted">&mdash; {{ $eventDescription }}</span>
							@else <span class="label label-warning">not fired by app</span>
							@endif
						</dd>
						<dt>Send To</dt>
						<dd>{{ \App\Message::recipientLabel($data->recipient) }}</dd>
						<dt>Mode</dt>
						<dd>
							@forelse($data->modes() as $mode) {!! \App\Message::modeBadge($mode) !!} @empty <span class="text-muted">None</span> @endforelse
						</dd>
						<dt>Last Updated</dt>
						<dd>{{ $data->updated_at ? date('d M Y H:i', strtotime($data->updated_at)) : '-' }}</dd>
					</dl>

					<div class="row">
						{{-- SMS --}}
						<div class="col-md-4">
							<div class="panel panel-default">
								<div class="panel-heading"><strong>SMS</strong> @if($data->hasMode('Sms')) <span class="label label-success pull-right">Enabled</span> @else <span class="label label-default pull-right">Disabled</span> @endif</div>
								<div class="panel-body @if(!$data->hasMode('Sms')) channel-off @endif">
									@if($data->sms_message)
									<p><strong>Template ID:</strong> {{ $data->sms_template_id ?: '-' }}<br><strong>Sender ID:</strong> {{ $data->sms_sender_id ?: '-' }}</p>
									<div class="msg-text">{{ $data->sms_message }}</div>
									@if(count($smsVariables))
									<table class="table table-condensed" style="margin-top:10px;">
										@foreach($smsVariables as $position => $placeholder)
										<tr>
											<td style="width:90px;"><code>#{{ $position }}</code></td>
											<td>{!! $describe(isset($smsMapping[$position]) ? $smsMapping[$position] : '') !!}</td>
										</tr>
										@endforeach
									</table>
									@endif
									@else
									<p class="text-muted">Not configured.</p>
									@endif
								</div>
							</div>
						</div>

						{{-- WhatsApp --}}
						<div class="col-md-4">
							<div class="panel panel-default">
								<div class="panel-heading"><strong>WhatsApp</strong> @if($data->hasMode('WhatsApp')) <span class="label label-success pull-right">Enabled</span> @else <span class="label label-default pull-right">Disabled</span> @endif</div>
								<div class="panel-body @if(!$data->hasMode('WhatsApp')) channel-off @endif">
									@if($whatsapp)
									<p><strong>Template:</strong> {{ $whatsapp->template_name }} ({{ $whatsapp->language }})<br><strong>Category:</strong> {{ $whatsapp->category }}</p>
									@if($whatsappTemplate)
									<div class="msg-text">{{ $whatsappTemplate->preview() }}</div>
									@else
									<p class="text-danger">This template is no longer in the synced list. Sync templates and pick it again.</p>
									@endif
									@if(count($waMapping))
									<table class="table table-condensed" style="margin-top:10px;">
										@foreach($waMapping as $placeholder => $value)
										<tr>
											<td style="width:90px;"><code>{{ '{'.'{'.$placeholder.'}'.'}' }}</code></td>
											<td>{!! $describe($value) !!}</td>
										</tr>
										@endforeach
									</table>
									@endif
									@else
									<p class="text-muted">Not configured.</p>
									@endif
								</div>
							</div>
						</div>

						{{-- App Notification --}}
						<div class="col-md-4">
							<div class="panel panel-default">
								<div class="panel-heading"><strong>App Notification</strong> @if($data->hasMode('AppNotification')) <span class="label label-success pull-right">Enabled</span> @else <span class="label label-default pull-right">Disabled</span> @endif</div>
								<div class="panel-body @if(!$data->hasMode('AppNotification')) channel-off @endif">
									@if($data->app_message)
									<p><strong>Title:</strong> {{ $data->title }}</p>
									<div class="msg-text">{{ $data->app_message }}</div>
									@else
									<p class="text-muted">Not configured.</p>
									@endif
								</div>
							</div>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading"><strong>Send Test Message</strong></div>
						<div class="panel-body">
							<p class="text-muted">Sends the configured SMS or WhatsApp to the number below. There is no customer behind a test, so variables go out as their token names.</p>
							<div class="row">
								<div class="col-sm-3">
									<select class="form-control input-sm" id="test_channel">
										<option value="Sms">SMS</option>
										<option value="WhatsApp">WhatsApp</option>
									</select>
								</div>
								<div class="col-sm-4">
									<input type="text" class="form-control input-sm" id="test_mobileno" placeholder="91XXXXXXXXXX">
								</div>
								<div class="col-sm-2">
									<button type="button" class="btn btn-sm btn-primary" id="test_send_btn"><i class="fa fa-paper-plane"></i> Send</button>
								</div>
							</div>
							<div id="test_send_result" style="margin-top:10px;"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@stop
@section('footer_scripts')
<script type="text/javascript">
	$(function() {
		$('#test_send_btn').on('click', function() {
			var $btn = $(this).prop('disabled', true);
			$('#test_send_result').empty();
			$.post("{{ route('message.test-send', $data->id) }}", {
				_token: "{{ csrf_token() }}",
				channel: $('#test_channel').val(),
				mobileno: $('#test_mobileno').val()
			}, function(response) {
				var css = response.status === 'success' ? 'alert-success' : 'alert-danger';
				$('#test_send_result').html('<div class="alert ' + css + '">' + $('<div></div>').text(response.msg).html() + '</div>');
			}, 'json').always(function() {
				$btn.prop('disabled', false);
			});
		});
	});
</script>
@stop