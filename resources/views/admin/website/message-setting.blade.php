@extends('admin/layouts/default')
@section('title')
    General Settings::CRM
@stop
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
    <link href="{{ asset('assets/admin/css/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
@stop
@section('content')
    <section class="content-header">
        <h1>Edit Message Setting</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Admin</li>
            <li class="active">Edit Message Setting</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="pen" data-size="20" data-c="#fff" data-hc="#fff"
                                data-loop="true"></i>
                            Edit Message Setting
                        </h3>
                        {{-- <span class="pull-right ">
                            <button class="btn btn-danger" type="button" id="backbtn" data-url='{{ URL::previous()}}'>

                                <i class="glyphicon glyphicon-chevron-left"></i>

                                Back
                            </button>
                        </span> --}}
                    </div>

                    <div class="panel-body">

                        <div id="rootwizard">


                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">

                                    <form id="basic" action="{{ route('message-setting') }}" method="POST"
                                        class="form-horizontal ajax_form" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <h2 class="hidden">&nbsp;</h2>
                                        {{-- <div class="form-group">
                                            <label for="mode" class="col-sm-2 control-label">Mode</label>
                                            <div class="col-sm-10" style="margin-bottom: 10px;">
                                                @if($data)
                                                @php
                                                    $mode = explode(',',$data->mode);
                                                @endphp
                                                @endif
                                                <input style="margin-right: 5px;" type="checkbox" name="mode[]" @if(isset($data)) @if(in_array("Sms",$mode)) checked @endif @endif value="Sms">Sms
                                                <input style="margin-right: 5px;" type="checkbox" name="mode[]" @if(isset($data)) @if(in_array("WhatsApp",$mode)) checked @endif @endif value="WhatsApp"> WhatsApp
                                                <input style="margin-right: 5px;" type="checkbox" name="mode[]" @if(isset($data)) @if(in_array("AppNotification",$mode)) checked @endif @endif value="AppNotification"> AppNotification
                                            </div>
                                            <span class="text-danger" style="margin-left: 18%;">This condition will work only if no mode is set in the message manager, then these will be the default settings.</span>
                                        </div> --}}
                                        <h3>WhatsApp Settings</h3>
                                        <br>
                                        <div class="form-group">
                                            <label for="whatsaap_api_key" class="col-sm-2 control-label">API Key</label>
                                            <div class="col-sm-10">
                                                <input id="whatsaap_api_key" name="whatsaap_api_key" type="text"
                                                    placeholder="WhatsApp API Key" class="form-control"
                                                    value="{{ $data->whatsaap_api_key }}" />
                                                <span class="help-block">Celitix WhatsApp (WABA) API key.</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="waba_number" class="col-sm-2 control-label">Waba Number</label>
                                            <div class="col-sm-10">
                                                <input id="waba_number" name="waba_number" type="text"
                                                    placeholder="Enter Your Waba Number" class="form-control"
                                                    value="{{ $data->waba_number }}" />
                                            </div>
                                        </div>
                                        <br>
                                        <h3>SMS Settings</h3>
                                        <br>


                                        <div class="form-group">
                                            <label for="sms_api_key" class="col-sm-2 control-label">SMS API Key</label>
                                            <div class="col-sm-10">
                                                <input id="sms_api_key" name="sms_api_key" type="text"
                                                    placeholder="Celitix SMS API key" class="form-control"
                                                    value="{{ $data->sms_api_key }}" />
                                                <span class="help-block">Leave empty to use the WhatsApp API key.</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="sms_entity_id" class="col-sm-2 control-label">Entity ID</label>
                                            <div class="col-sm-10">
                                                <input id="sms_entity_id" name="sms_entity_id" type="text"
                                                    placeholder="DLT Principal Entity ID" class="form-control"
                                                    value="{{ $data->sms_entity_id }}" />
                                                <span class="help-block">Sender ID and Template ID are set on each message under Messages.</span>
                                            </div>
                                        </div>

                                        <br>
                                        <h3>Push Notification Settings</h3>
                                        <br>

                                        <div class="form-group">
                                            <label for="contact_no" class="col-sm-2 control-label">FCM API Key</label>
                                            <div class="col-sm-10">
                                                <input id="fcm_api_key" name="fcm_api_key" type="text"
                                                    placeholder="FCM API Key" class="form-control"
                                                    value="{{ $data->fcm_api_key }}" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="contact_no" class="col-sm-2 control-label">FCM Icon URL</label>
                                            <div class="col-sm-10">
                                                <input id="fcm_icon_url" name="fcm_icon_url" type="text"
                                                    placeholder="FCM Icon URL" class="form-control"
                                                    value="{{ $data->fcm_icon_url }}" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="fcm_service_account_json" class="col-sm-2 control-label">Firebase Service Account JSON</label>
                                            <div class="col-sm-10">
                                                <textarea id="fcm_service_account_json" name="fcm_service_account_json"
                                                    rows="8" class="form-control" style="font-family:monospace;font-size:12px;"
                                                    placeholder='{"type": "service_account", "project_id": "...", ...}'>{{ $data->fcm_service_account_json }}</textarea>
                                                <span class="help-block">Required for push notifications to actually
                                                    deliver. Firebase Console &rarr; Project Settings &rarr; Service
                                                    Accounts &rarr; Generate new private key, then paste the full
                                                    contents of the downloaded JSON file here.</span>
                                            </div>
                                        </div>

                                        <div class="pager wizard">
                                            <button type="submit" class="btn btn-primary submit">Submit</button>
                                        </div>

                                </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--row end-->
        </div>
    </section>
@stop
@section('footer_scripts')
    <script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
    <script>
        $('#backbtn').click(function() {
            var url = $(this).attr('data-url');

            window.location.href = url;

        });
        $(document).on("submit", ".ajax_form", function(event) {
            var posturl = $(this).attr('action');
            var callbackFunction = $(this).attr('data-callback_function');
            if (callbackFunction) {
                if (callbackForm() == false) {
                    return false;
                }
            }
            var formid = '#' + $(this).attr('id');

            $(this).ajaxSubmit({
                url: posturl,
                dataType: 'json',
                type: "POST",
                beforeSend: function() {
                    $(".submit").attr("disabled", 'disabled');
                    $('.formmessage').hide();
                    $('#wait-div').show();
                },
                success: function(response) {
                    $(".submit").removeAttr("disabled", 'disabled');
                    $(formid).find('.form-group').removeClass('has-error');
                    toastr[response.msgType](response.msg, response.msgHead);

                    $(formid).find('.alert').removeClass('alert-info').removeClass('alert-success')
                        .removeClass('alert-danger').fadeOut(200);
                    if (response.status == "success") {
                        $(formid).find('.alert').fadeIn();
                        $(formid).find('.alert').addClass('alert-success').children('.ajax_message')
                            .html(response.success_msg);
                    } else {
                        $(formid).find('.alert').fadeIn();
                        $(formid).find('.alert').addClass('alert-danger').children('.ajax_message')
                            .html(response.error_msg);
                        $.each(response.errorArray, function(key, value) {
                            console.log(key + " => " + value);
                            var msg = '<label class="error formmessage" for="' + key +
                                '"  style="color:ef6f6c">' + value + '</label>';

                            $(formid).find('input[name="' + key + '"], select[name="' + key +
                                    '"],textarea[name="' + key + '"]').closest('.form-group')
                                .addClass('has-error');

                            $(formid).find('input[name="' + key + '"], select[name="' + key +
                                    '"],textarea[name="' + key + '"]').addClass('inputTxtError')
                                .after(msg);
                        });



                    }
                    if (response.slideToTop) {
                        //$('html, body').animate({scrollTop: 0 }, 'slow');
                        $('html, body').animate({
                            scrollTop: $(formid).offset().top - 290
                        }, 800);
                    }
                    if (response.url)
                        window.location.href = response.url;
                    if (response.selfReload)
                        window.location.reload();
                    if (response.status == 'success') {
                        //$(formid)[0].reset();
                    }
                    if (response.redirect == 'yes') {
                        window.location.href = response.redirectUrl;
                    }
                },
                error: function(response) {
                    var data = response.responseJSON;
                    $(".submit").removeAttr("disabled", 'disabled');

                }
            });
            return false;
        });
    </script>
@stop
