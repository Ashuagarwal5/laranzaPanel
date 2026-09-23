$(document).ready(function() {

	$(document).on("submit", ".ajaxformclass", function (event) {
		//alert('ok');
		//return false;
	var posturl=$(this).attr('action');
	var callbackFunction=$(this).attr('data-callback_function');
	if(callbackFunction)
	{
		if(callbackForm(callbackFunction) == false)
		{
			return false;
		}
	}
	var btn_txt;
	var formid = $(this).attr('id');
	if(formid)
	var formid = '#'+formid;
	else
	var formid = ".ajaxformclass";


$(".submit").attr("disabled", true);

	$(this).ajaxSubmit({
			url: posturl,
			dataType: 'json',
			beforeSend: function(){

				$('.formmessage').remove();
			$(formid).find('.box-error').attr("placeholder", "")
			$(formid).find('.box-error').removeClass('box-error');
			$(formid).find('.alert').removeClass('alert-info').removeClass('alert-success').removeClass('alert-danger').fadeIn(200);
			$(formid).find('.alert').addClass('alert-info');
			$(formid).find('.alert').show();
			$(formid).find('.ajax_message').html('<strong>Please Wait ! <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></strong>');
			},
			success: function(response){
				//alert('success');
				if(response.notLoggedIn)
				{
					window.location.href = response.route;
				}
				$(".submit").removeAttr("disabled", 'disabled');

				$(formid).find('.alert').removeClass('alert-info').removeClass('alert-success').removeClass('alert-danger').fadeOut(200);
				$(formid).find('.form-group').removeClass('has-error');
				$(formid).find('.display-error').removeClass('box-error');

				$(formid).find('.alert').removeClass('alert-info').removeClass('alert-success').removeClass('alert-danger').fadeOut(200);

               
                if (response.status == "success") {
					//alert("ok");
					toastr[response.msgType](response.msg, response.msgHead);
                    $(formid).find('.alert').fadeIn();
                    $(formid).find('.alert').addClass('alert-success').children('.ajax_message').html(response.success_msg);
					hide_alert_message();
					
                } 
                else {					
					
                    $(formid).find('.alert').fadeIn();
                    $(formid).find('.alert').addClass('alert-danger').children('.ajax_message').html(response.error_msg);
                    
                   
                   
                    $.each(response.errorArray, function(key, value) {
						
						if (!/\brequired\b/.test(value)){
					  		$(formid).find('.ajax_message').append('<br>'+value);
						} 
					
                        console.log(key + " => " + value);
                    	
						var placeH	=value;						
						$(formid).find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').attr("placeholder", placeH);
						$(formid).find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').addClass('box-error');
						
						if ( $(this).is('select') )
						$(formid).find('.selectOption').addClass('state-error');
						
                    });
                }
          
			if(response.resetform)
			{
				$(formid).resetForm();
			}
			
			   
			if(response.loginstatus=='success'){
                window.location.href = response.url;
			}    
            
            if (response.slideToTop) {
                $('html, body').animate({
					
                    scrollTop: $(formid).offset().top - 290
                }, 800);
            }
            if (response.url)
            {
                window.location.href = response.url;
			}
            if (response.selfReload)
            {
                window.location.reload();
			}
			if (response.closeModal)
			{
				$(response.closeModal).modal('toggle');
			}
            if (response.status == 'success') {
                //$(formid)[0].reset();
            }
			
            if (response.redirect == 'yes') {
                window.location.href = response.redirectUrl;
            }
           if (response.video == "yes")
            {
				alert("done");
               $("#thisdiv2").load(" #thisdiv2 > *");
                //window.location.reload();
			}
            
            if(response.ajaxPageCallBack)
			{
				
				response.formid = formid;				
				ajaxPageCallBack(response);

			}
			if(response.hide_class){
				var product = response.hide_class;
				$('#panel_body').hide();
				$('#show_body').css({"display": "block", "padding": "15px", "min-height": "266px"});
				$('#show_body').append('<div class="alert alert-success" role="alert"><h4 class="alert-heading">QR Code Generate Successfully</h4><p>You Are successfully generated QR code. Now you can download or copy Qrcode </p></div><div class="col-md-12 mar-10" id="download_button"><div class="col-xs-4 col-md-4"></div><div class="col-xs-4 col-md-2"><a class="btn btn-success btn-block btn-md" target="_blank"  href="'+product+'">Download</a></div><div class="col-xs-4 col-md-2"><a class="btn btn-danger btn-block btn-md copy_link" data_value="'+product+'">Copy Link</a><input type="hidden" name="copy_code" id="copy_code" class="copy_code" value="'+product+'"></div></div>');
			}
			if (response.open_modal == 'success') {
				$('#modal-message').modal('show');
            }
			},
			error: function(response) {
		//	alert('server error');          
               
			}
		});
	return false;
	});

	$(document).on("click", ".alert .close", function (event) {
		$(this).closest(".ajax_report").hide();
		$(this).closest(".alert").hide();
	});
});

function slideToElement(element,position)
{
	var target = $(element);

	$('html, body').animate({
		scrollTop: target.offset().top-100
	}, 500);
}

function slideToDiv(element)
{
	$("html, body").animate({scrollTop: $(element).offset().top-50 }, 1000);
}
function slideToTop()
{
	$("html, body").animate({scrollTop: 50}, 1000);
}

function isset(variable)
{
	if(typeof(variable) != "undefined" && variable !== null)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function hide_alert_message(){
	setTimeout(function() {
		$('.alert.alert-dismissable').fadeOut(1000);
	}, 5000);
}
