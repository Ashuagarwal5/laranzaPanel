$(document).ready(function() {

	$(document).on("submit", ".ajaxform", function (event) {
		//alert('ajaxform');
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
	var formid = ".ajaxform";




	$(this).ajaxSubmit({
		
			url: posturl,
			dataType: 'json',
			beforeSend: function(){
					//alert('submit');
				$(".submit").attr("disabled", 'disabled');
				$('.formmessage').remove();
				$(formid).find('.box-error').removeClass('box-error');
				$(formid).find('.alert').removeClass('alert-success').removeClass('alert-danger').removeClass('alert-info');
				$(formid).find('.alert').addClass('alert-info').children('.ajax_message').html('<p><strong>Please wait! </strong>Your action is in proccess...</p>');
			},
			success: function(response){
				
             $(".submit").removeAttr("disabled", 'disabled');
				$(formid).find("input[type=submit]").removeAttr("disabled");
				$(formid).find("button[type=submit]").removeAttr("disabled");
				$(formid).find("input[type=submit]").html(btn_txt);
				$(formid).find("button[type=submit]").html(btn_txt);
				if(formid =='#save-my-canvas'){
					$(formid).resetForm();

				   $('#save-design-confirm').dialog('close'); 
				}
               
				$('#wait-div').hide();
				$(formid).find('.alert').removeClass('alert-success').removeClass('alert-danger').removeClass('alert-info');

				if(response.restore_error)
				{
					$(formid).find('.alert').show();
					$(formid).find('.alert').html(response.restore_error);
				}
				
					
					$(formid).find('.alert').fadeIn(200);

					if(response.success)
					{
						//alert('success');
						toastr[response.status](response.msg, "Notifications");
						
					}
					else
					{
						//alert('error');
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
							
						});
					}
					
				if(response.reload==true)
				 location.reload();

				if(response.resetform)
				$(formid).resetForm();

				if(response.url)

				window.location.href=response.url;

				if(response.parentUrl)
				window.top.location.href = response.parentUrl;

				if(response.selfReload)
				window.location.reload();

				if(response.slideToThisDiv)
				slideToDiv(response.divId);

				if(response.slideToTop)
				slideToTop();

				if(response.scrollToThisForm)
				slideToElement(formid);

				if(response.ajaxPageCallBack)
				{
					response.formid = formid;
					ajaxPageCallBack(response);

				}
				if(response.ajaxPageCallBackData)
				{
					response.formid = formid;
					ajaxPageCallBackData(response);
				}
				if(response.hideModel)
				{
					setTimeout(function()
					{
						$('.modal').modal('hide');
					},500);
				}
				setTimeout(function() {
					$(formid).find('.ajax_report').fadeOut(1000);
				}, 7000);
			},
			error:function(response){
					$(formid).find('.alert').fadeOut(100);
					$(formid).find("input[type=submit]").removeAttr("disabled");
					$(formid).find("button[type=submit]").removeAttr("disabled");
					$(formid).find("input[type=submit]").html(btn_txt);
					$(formid).find("button[type=submit]").html(btn_txt);

				var data = response.responseJSON;
                //alert(data);

                $.each(data, function( key, value ) {
					console.log(key + " => " + value);
					var msg = '<label class="error formmessage" for="'+key+'"  style="color:red">'+value+'</label>';
					$(formid).find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').last().addClass('inputTxtError').after(msg);

				});
				//alert( 'Connection error');
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
	}, 3000);
}
