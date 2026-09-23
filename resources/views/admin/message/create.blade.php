@extends('admin/layouts/default')
@section('title')
{{ $manager_name }} Manager::CRM
@stop
{{-- page level styles --}}
@section('header_styles')
<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/admin/css/select2.min.css') }}" rel="stylesheet"/>
<style>
	.msg-tabs { margin-top: 5px; }
	.msg-tabs > li > a { font-weight: 600; }
	.channel-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #ccc; margin-left: 6px; vertical-align: middle; }
	.channel-dot.on { background: #5cb85c; }
	.msg-tab-body { border: 1px solid #ddd; border-top: 0; padding: 18px 15px 5px; }
	.channel-toggle { background: #f7f7f7; border: 1px solid #eee; border-radius: 4px; padding: 10px 12px; margin-bottom: 18px; }
	.channel-toggle label { margin: 0; font-weight: 600; cursor: pointer; }
	.channel-toggle input { margin-right: 6px; }
	.channel-fields.is-off { opacity: .55; }
	.var-token { background: #fff3cd; padding: 0 3px; border-radius: 3px; }
	.var-value { background: #dff0d8; padding: 0 3px; border-radius: 3px; }
	.sms-preview { background: #f5f5f5; border: 1px solid #e3e3e3; border-radius: 6px; padding: 12px 14px; white-space: pre-wrap; word-wrap: break-word; min-height: 80px; line-height: 1.5; }
	.wa-preview-wrap { background: #e5ddd5; border-radius: 6px; padding: 20px; min-height: 180px; }
	.wa-bubble { background: #fff; border-radius: 8px; padding: 12px 14px; box-shadow: 0 1px 1px rgba(0,0,0,.15); white-space: pre-wrap; word-wrap: break-word; font-size: 14px; line-height: 1.5; color: #303030; }
	.wa-bubble .wa-footer { display: block; margin-top: 8px; color: #8c8c8c; font-size: 12px; }
	.wa-bubble .wa-buttons { display: block; border-top: 1px solid #eee; margin-top: 10px; padding-top: 4px; }
	.wa-bubble .wa-buttons span { display: block; text-align: center; color: #00a5f4; padding: 6px 0; font-size: 13px; }
	.empty-note { color: #7a7a7a; font-style: italic; }
	#message_form .box-error { border-color: #a94442 !important; box-shadow: 0 0 0 1px #a94442; }
</style>
@stop
@section('content')
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
		<li class="active">@if($data) Edit @else Create @endif</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<h3 class="panel-title">
						<i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i>
						@if($data) Edit @else Create @endif {{ $manager_name }}
					</h3>
					<div class="pull-right">
						<a href="{{ $manager_url }}" class="btn btn-sm btn-danger"><span class="btn-label">
							<i class="glyphicon glyphicon-chevron-left"></i>
						</span><span style="margin-left:8px">Back</span></a>
					</div>
				</div>
				<div class="panel-body">
					@include('admin.message')
					<form method="post" id="message_form" class="ajaxformclass" action="" enctype="multipart/form-data">
						<div class="alert" style="margin-top:10px;display:none;">
							<a href="javascript:void(0)" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
						</div>
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />

						{{-- One event for all three channels --}}
						<div class="row">
							<div class="form-group col-sm-6">
								<label for="event">Event Trigger *</label>
								<select class="form-control input-sm" name="event" id="event">
									<option value="">Select Event</option>
									@if($data && !\App\Services\MessageEvents::exists($data->title))
									<optgroup label="Legacy">
										<option value="{{ $data->title }}" selected data-description="Nothing in the app fires this event, so this message never sends. Pick an event from the list.">{{ $data->title }} (not fired by app)</option>
									</optgroup>
									@endif
									@foreach($eventGroups as $group => $events)
									<optgroup label="{{ $group }}">
										@foreach($events as $event => $description)
										@php($allTaken = count(array_filter(array_keys($recipients), function ($r) use ($event, $taken) { return in_array($event.'|'.$r, $taken); })) == count($recipients))
										<option value="{{ $event }}" data-description="{{ $description }}" data-admin="{{ in_array($event, $adminEvents) ? 1 : 0 }}"
											@if($allTaken) disabled @endif
											@if($data && $data->title == $event) selected @endif>{{ $event }}@if($allTaken) &mdash; already configured @endif</option>
										@endforeach
									</optgroup>
									@endforeach
								</select>
								<span class="help-block" id="event_description">The moment in the app that sends this message. The same event drives SMS, WhatsApp and App Notification.</span>
							</div>
							<div class="form-group col-sm-3">
								<label for="recipient">Send To *</label>
								<select class="form-control input-sm" name="recipient" id="recipient">
									@foreach($recipients as $value => $label)
									<option value="{{ $value }}" @if(($data ? ($data->recipient ?: 'user') : 'user') == $value) selected @endif>{{ $label }}</option>
									@endforeach
								</select>
								<span class="help-block" id="recipient_help">One event can have a separate message for each recipient, each with its own templates.</span>
							</div>
						</div>

						<ul class="nav nav-tabs msg-tabs">
							<li class="active"><a href="#tab_sms" data-toggle="tab">SMS <span class="channel-dot" data-for="sms_enabled"></span></a></li>
							<li><a href="#tab_whatsapp" data-toggle="tab">WhatsApp <span class="channel-dot" data-for="whatsapp_enabled"></span></a></li>
							<li><a href="#tab_app" data-toggle="tab">App Notification <span class="channel-dot" data-for="app_enabled"></span></a></li>
						</ul>
						<div class="tab-content msg-tab-body">

							{{-- ============================== SMS ============================== --}}
							<div class="tab-pane active" id="tab_sms">
								<div class="channel-toggle">
									<label><input type="checkbox" name="sms_enabled" id="sms_enabled" value="1" class="channel-switch" @if($data && $data->hasMode('Sms')) checked @endif> Send SMS when this event fires</label>
								</div>
								<div class="row channel-fields">
									<div class="col-sm-7">
										<div class="row">
											<div class="form-group col-sm-6">
												<label for="sms_template_id">Template ID *</label>
												<input type="text" class="form-control input-sm" name="sms_template_id" id="sms_template_id"
													placeholder="DLT Template ID, e.g. 1407166927529999024" value="{{ $data ? $data->sms_template_id : '' }}">
											</div>
											<div class="form-group col-sm-6">
												<label for="sms_sender_id">Sender ID *</label>
												<input type="text" class="form-control input-sm" name="sms_sender_id" id="sms_sender_id"
													placeholder="e.g. PROATV" value="{{ $data ? $data->sms_sender_id : '' }}">
											</div>
										</div>
										<div class="form-group">
											<label for="sms_message">Message *</label>
											<textarea class="form-control input-sm" rows="5" name="sms_message" id="sms_message" placeholder="Type or paste the DLT approved message">{{ $data ? $data->sms_message : '' }}</textarea>
											<span class="help-block">
												Use the text exactly as approved on DLT, with each variable written as <code>{#var#}</code>.
												Every variable gets a row below to pick its value.
												<span id="sms_counter" class="pull-right text-muted"></span>
											</span>
										</div>
										<div class="form-group" id="sms_variables_wrap" style="display:none;">
											<label>Message Variables</label>
											<table class="table table-bordered table-condensed">
												<thead>
													<tr>
														<th style="width:170px;">Variable</th>
														<th>Replace With</th>
													</tr>
												</thead>
												<tbody id="sms_variables_body"></tbody>
											</table>
										</div>
									</div>
									<div class="col-sm-5">
										<div class="form-group">
											<label>SMS Preview</label>
											<div class="sms-preview" id="sms_preview"><span class="empty-note">Type or paste the message to see its preview.</span></div>
										</div>
									</div>
								</div>
							</div>

							{{-- ============================ WhatsApp ============================ --}}
							<div class="tab-pane" id="tab_whatsapp">
								<div class="channel-toggle">
									<label><input type="checkbox" name="whatsapp_enabled" id="whatsapp_enabled" value="1" class="channel-switch" @if($data && $data->hasMode('WhatsApp')) checked @endif> Send WhatsApp when this event fires</label>
								</div>
								<div class="row channel-fields">
									<div class="col-sm-7">
										<p class="text-muted">
											{{ $templateCount }} approved template(s) synced from Celitix{{ $lastSynced ? ' on '.date('d M Y H:i', strtotime($lastSynced)) : '' }}.
											<a href="{{ route('message.whatsapp.sync') }}" class="btn btn-xs btn-default" id="wa_sync"><span class="glyphicon glyphicon-refresh"></span> Sync Templates</a>
										</p>
										<div class="form-group">
											<label for="whatsapp_category">Template Category *</label>
											<select class="form-control input-sm" name="whatsapp_category" id="whatsapp_category">
												<option value="">Select Template Category</option>
												@foreach($categories as $key => $label)
												<option value="{{ $key }}" @if($whatsapp && $whatsapp->category == $key) selected @endif>{{ $label }}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group">
											<label for="whatsapp_template">Template *</label>
											<select class="form-control input-sm" name="whatsapp_template" id="whatsapp_template"
												data-selected="{{ $whatsappTemplate ? $whatsappTemplate->id : '' }}">
												<option value="">Select Template</option>
												@foreach($templates as $template)
												<option value="{{ $template->id }}" @if($whatsappTemplate && $whatsappTemplate->id == $template->id) selected @endif>{{ $template->template_name }} ({{ $template->language }})</option>
												@endforeach
											</select>
											<span class="help-block">Only Meta-approved templates synced from the Celitix panel are listed.</span>
										</div>
										<div class="form-group" id="wa_variables_wrap" style="display:none;">
											<label>Template Variables</label>
											<table class="table table-bordered table-condensed">
												<thead>
													<tr>
														<th style="width:170px;">Template Variable</th>
														<th>Replace With</th>
													</tr>
												</thead>
												<tbody id="wa_variables_body"></tbody>
											</table>
										</div>
									</div>
									<div class="col-sm-5">
										<div class="form-group">
											<label>Template Preview</label>
											<div class="wa-preview-wrap">
												<div class="wa-bubble" id="wa_preview"><span class="empty-note">Select a template to see its preview.</span></div>
											</div>
										</div>
									</div>
								</div>
							</div>

							{{-- ========================= App Notification ========================= --}}
							<div class="tab-pane" id="tab_app">
								<div class="channel-toggle">
									<label><input type="checkbox" name="app_enabled" id="app_enabled" value="1" class="channel-switch" @if($data && $data->hasMode('AppNotification')) checked @endif> Send an app notification when this event fires</label>
									<span class="help-block text-warning" id="app_admin_note" style="display:none;margin:6px 0 0;">This message goes to the admin, who has no app device &mdash; app notifications are skipped for it.</span>
								</div>
								<div class="row channel-fields">
									<div class="col-sm-7">
										<div class="form-group">
											<label for="app_message">Message *</label>
											<textarea class="form-control input-sm" rows="5" name="app_message" id="app_message" placeholder="e.g. Dear {user_name}, {points} points have been added to your wallet.">{{ $data ? $data->app_message : '' }}</textarea>
										</div>
										<div class="form-group">
											<label for="app_target_screen">Opens screen</label>
											<select class="form-control input-sm" name="app_target_screen" id="app_target_screen">
												<option value="">&mdash; just open the app &mdash;</option>
												@foreach($pushScreens as $screen => $label)
												<option value="{{ $screen }}" @if($data && $data->app_target_screen === $screen) selected @endif>{{ $label }}</option>
												@endforeach
											</select>
											<span class="help-block">Where tapping the push notification takes the customer.</span>
										</div>
										<div class="form-group">
											<label for="app_token">Insert Variable</label>
											<select class="form-control input-sm" id="app_token">
												<option value="">Select a variable to insert at the cursor</option>
												@foreach($tokenGroups as $group => $groupTokens)
												<optgroup label="{{ $group }}">
													@foreach($groupTokens as $token => $label)
													<option value="{{ $token }}">{{ $label }}</option>
													@endforeach
												</optgroup>
												@endforeach
											</select>
											<span class="help-block">Variables such as <code>{user_name}</code> are replaced with real values when sent. The event name is used as the notification title.</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<p class="text-right" style="margin-top:15px;">
							<button type="submit" class="btn btn-space btn-primary submit">Submit</button>
							<a href="{{ route('admin.message') }}" class="btn btn-default">Cancel</a>
						</p>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
@stop
@section('footer_scripts')
<script src="{{ asset('assets/admin/js/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(function () {
		var templatesUrl = "{{ route('message.whatsapp.templates') }}";
		var previewUrl   = "{{ route('message.whatsapp.preview') }}";
		var csrfToken    = "{{ csrf_token() }}";
		var tokenGroups  = {!! json_encode($tokenGroups) !!};
		var smsMapping   = {!! json_encode($data ? (object) $data->smsMapping() : (object) []) !!};
		var waMapping    = {!! json_encode($whatsapp ? (object) $whatsapp->mapping() : (object) []) !!};

		var tokenLabels = {};
		$.each(tokenGroups, function (group, tokens) {
			$.each(tokens, function (token, label) { tokenLabels[token] = label; });
		});

		// Blade would compile a literal double brace, so it is assembled here.
		var OPEN  = '{' + '{';
		var CLOSE = '}' + '}';
		// Same rule as CelitixSms::VARIABLE_PATTERN.
		var SMS_VAR_SOURCE = '\\{\\{?#\\s*[A-Za-z0-9_]*\\s*#?\\}\\}?';

		function escapeHtml(text) {
			return $('<div></div>').text(text === null || text === undefined ? '' : text).html();
		}

		// How a mapped value reads in a preview: a token shows its label, fixed
		// text shows as typed, and an unmapped placeholder stays highlighted.
		function displayValue(value, placeholder) {
			if (value === '' || value === undefined || value === null) {
				return '<span class="var-token">' + escapeHtml(placeholder) + '</span>';
			}
			if (tokenLabels[value] !== undefined) {
				return '<span class="var-value">[' + escapeHtml(tokenLabels[value]) + ']</span>';
			}
			return escapeHtml(value);
		}

		// One "Replace With" row: a token dropdown, or custom text when none is picked.
		function variableRow(label, name, position, value, onChange) {
			var isToken = tokenLabels[value] !== undefined;
			var $input = $('<input type="text" class="form-control input-sm">')
				.attr('name', name)
				.attr('data-position', position)
				.attr('placeholder', 'Enter fixed text')
				.val(value || '')
				.prop('readonly', isToken);

			var $select = $('<select class="form-control input-sm"></select>')
				.append('<option value="">-- Custom text --</option>');
			$.each(tokenGroups, function (groupName, groupTokens) {
				var $group = $('<optgroup></optgroup>').attr('label', groupName);
				$.each(groupTokens, function (key, text) {
					$group.append($('<option></option>').attr('value', key).text(text));
				});
				$select.append($group);
			});
			$select.val(isToken ? value : '');

			$select.on('change', function () {
				if ($(this).val() === '') {
					$input.prop('readonly', false).val('').focus();
				} else {
					$input.prop('readonly', true).val($(this).val());
				}
				onChange();
			});
			$input.on('keyup change', onChange);

			return $('<tr></tr>')
				.append($('<td></td>').append($('<code></code>').text(label)))
				.append($('<td></td>').append($select).append($('<div style="margin-top:5px;"></div>').append($input)));
		}

		function collectValues($body) {
			var values = {};
			$body.find('input[data-position]').each(function () {
				values[$(this).attr('data-position')] = $(this).val();
			});
			return values;
		}

		// ------------------------------------------------------------ SMS
		function renderSms() {
			var text = $('#sms_message').val();
			if (!$.trim(text)) {
				$('#sms_preview').html('<span class="empty-note">Type or paste the message to see its preview.</span>');
				$('#sms_counter').text('');
				return;
			}
			var values = collectValues($('#sms_variables_body'));
			var pattern = new RegExp(SMS_VAR_SOURCE, 'g');
			var html = '', last = 0, position = 0, match;
			while ((match = pattern.exec(text)) !== null) {
				position++;
				html += escapeHtml(text.slice(last, match.index)) + displayValue(values[position], match[0]);
				last = match.index + match[0].length;
			}
			html += escapeHtml(text.slice(last));
			$('#sms_preview').html(html);

			var unicode = /[^\x00-\x7F]/.test(text);
			var single = unicode ? 70 : 160, multi = unicode ? 67 : 153;
			var parts = text.length <= single ? 1 : Math.ceil(text.length / multi);
			$('#sms_counter').text(text.length + ' chars' + (unicode ? ' (Unicode)' : '') + ' · ~' + parts + ' SMS part' + (parts > 1 ? 's' : ''));
		}

		function buildSmsVariables() {
			var $body = $('#sms_variables_body');
			// Keep what was already picked when the text is edited.
			$.extend(smsMapping, collectValues($body));

			var matches = $('#sms_message').val().match(new RegExp(SMS_VAR_SOURCE, 'g')) || [];
			var signature = matches.join('|');
			if ($body.data('signature') !== signature) {
				$body.empty();
				$.each(matches, function (i, placeholder) {
					var position = i + 1;
					$body.append(variableRow('#' + position + '  ' + placeholder, 'sms_variables[' + position + ']', position, smsMapping[position], renderSms));
				});
				$body.data('signature', signature);
				$('#sms_variables_wrap').toggle(matches.length > 0);
			}
			renderSms();
		}
		$('#sms_message').on('input change', buildSmsVariables);

		// ------------------------------------------------------- WhatsApp
		var rawTemplate = '', footerText = '', buttons = [];

		function clearWa(message) {
			$('#wa_preview').html('<span class="empty-note">' + escapeHtml(message) + '</span>');
			$('#wa_variables_body').empty();
			$('#wa_variables_wrap').hide();
			rawTemplate = '';
			footerText = '';
			buttons = [];
		}

		function renderWa() {
			if (!rawTemplate) {
				return;
			}
			var values = collectValues($('#wa_variables_body'));
			var html = escapeHtml(rawTemplate);
			$.each(values, function (position, value) {
				html = html.split(OPEN + position + CLOSE).join(displayValue(value, OPEN + position + CLOSE));
			});
			if (footerText) {
				html += '<span class="wa-footer">' + escapeHtml(footerText) + '</span>';
			}
			if (buttons && buttons.length) {
				html += '<span class="wa-buttons">';
				$.each(buttons, function (i, button) {
					var label = (button && (button.text || button.label)) ? (button.text || button.label) : button;
					html += '<span>' + escapeHtml(label) + '</span>';
				});
				html += '</span>';
			}
			$('#wa_preview').html(html);
		}

		function loadTemplate() {
			var templateId = $('#whatsapp_template').val();
			if (!templateId) {
				clearWa('Select a template to see its preview.');
				return;
			}
			$.post(previewUrl, { _token: csrfToken, template_id: templateId }, function (response) {
				if (response.status !== 'success') {
					clearWa(response.msg);
					return;
				}
				rawTemplate = response.raw;
				footerText = response.footer;
				buttons = response.buttons;
				var $body = $('#wa_variables_body').empty();
				$.each(response.positions || [], function (i, position) {
					$body.append(variableRow(OPEN + position + CLOSE, 'whatsapp_variables[' + position + ']', position, waMapping[position], renderWa));
				});
				$('#wa_variables_wrap').toggle(!!(response.positions && response.positions.length));
				renderWa();
			}, 'json');
		}

		$('#whatsapp_category').on('change', function () {
			var category = $(this).val();
			var $template = $('#whatsapp_template');
			$template.html('<option value="">Select Template</option>');
			if (!category) {
				$template.trigger('change.select2');
				clearWa('Select a template to see its preview.');
				return;
			}
			$.get(templatesUrl, { category: category }, function (response) {
				$.each(response.templates, function (i, template) {
					$template.append($('<option></option>').attr('value', template.id).text(template.label));
				});
				var selected = $template.data('selected');
				if (selected && $template.find('option[value="' + selected + '"]').length) {
					$template.val(String(selected));
				}
				$template.trigger('change.select2');
				loadTemplate();
			}, 'json');
		});

		$('#whatsapp_template').on('change', function () {
			// A different template declares different variables, so start clean.
			waMapping = {};
			$(this).data('selected', $(this).val());
			loadTemplate();
		});

		$('#wa_sync').on('click', function () {
			return confirm('Syncing reloads this page, so unsaved changes will be lost. Sync now?');
		});

		// -------------------------------------------------- App Notification
		$('#app_token').on('change', function () {
			var token = $(this).val();
			if (!token) {
				return;
			}
			var el = document.getElementById('app_message');
			var start = el.selectionStart || 0, end = el.selectionEnd || 0;
			el.value = el.value.slice(0, start) + token + el.value.slice(end);
			el.focus();
			el.selectionStart = el.selectionEnd = start + token.length;
			$(this).val('');
		});

		// ------------------------------------------------------------ Shared
		function syncChannels() {
			$('.channel-switch').each(function () {
				var on = $(this).is(':checked');
				var $fields = $(this).closest('.tab-pane').find('.channel-fields');
				$('.channel-dot[data-for="' + this.id + '"]').toggleClass('on', on);
				$fields.toggleClass('is-off', !on);
				// Disabled while the switch is off, so the tab can't be filled
				// in (or submitted) before it is actually turned on.
				$fields.find('input, textarea, select, button').not('.channel-switch').prop('disabled', !on);
				var $waTemplate = $fields.find('#whatsapp_template');
				if ($waTemplate.length && $waTemplate.data('select2')) {
					$waTemplate.prop('disabled', !on).trigger('change.select2');
				}
			});
		}
		$('.channel-switch').on('change', syncChannels);

		// "event|recipient" pairs that already have a message.
		var takenPairs = @json($taken);
		var isEdit = {{ $data ? 'true' : 'false' }};

		function syncRecipient() {
			var event = $('#event').val();
			$('#recipient option').each(function () {
				var taken = event && takenPairs.indexOf(event + '|' + this.value) !== -1;
				$(this).prop('disabled', taken)
					.text($(this).text().replace(/ — already configured$/, '') + (taken ? ' — already configured' : ''));
			});
			if ($('#recipient option:selected').is(':disabled')) {
				$('#recipient').val($('#recipient option:not(:disabled)').first().val());
			}
			$('#app_admin_note').toggle($('#recipient').val() === 'admin');
		}
		$('#recipient').on('change', syncRecipient);

		function syncEvent(fromUser) {
			var $option = $('#event option:selected');
			$('#event_description').text($option.data('description') || 'The moment in the app that sends this message. The same event drives SMS, WhatsApp and App Notification.');
			// Suggest the admin for events the admin acts on; still changeable.
			if (fromUser === true && !isEdit) {
				$('#recipient').val($option.data('admin') == 1 ? 'admin' : 'user');
			}
			syncRecipient();
		}
		$('#event').on('change', function () { syncEvent(true); });

		// formClass.js marks invalid fields with .box-error; bring the tab that
		// holds the first one into view, since it may not be the open tab.
		$(document).ajaxComplete(function (event, xhr, settings) {
			if (settings.url && settings.url.indexOf('/message/whatsapp/') !== -1) {
				return;
			}
			setTimeout(function () {
				var $error = $('#message_form .box-error').first();
				var $pane = $error.closest('.tab-pane');
				if ($pane.length && !$pane.hasClass('active')) {
					$('.msg-tabs a[href="#' + $pane.attr('id') + '"]').tab('show');
				}
			}, 100);
		});

		if ($.fn.select2) {
			$('#event, #whatsapp_template').select2({ width: '100%' });
		}
		syncEvent();
		syncChannels();
		buildSmsVariables();
		if ($('#whatsapp_template').val()) {
			loadTemplate();
		}
	});
</script>
@stop
