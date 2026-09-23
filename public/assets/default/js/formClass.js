$(document).ready(function() {

	$(document).on("submit", ".ajaxform", function (event) {
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
	var formid = ".ajaxform";




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
				
            $(".submit").removeAttr("disabled", 'disabled');
             
			$(formid).find('.alert').removeClass('alert-info').removeClass('alert-success').removeClass('alert-danger').fadeOut(200);
            $(formid).find('.form-group').removeClass('has-error');
            $(formid).find('.display-error').removeClass('box-error');
                 
              
                if (response.status == "success") 
                {
					// $(formid).find('.alert').show().addClass('alert-success');
					$(formid).find('.alert').fadeIn();
                    $(formid).find('.alert').addClass('alert-success').children('.ajax_message').html("Success ! "+response.msg);
					toastr[response.msgType](response.msg, response.msgHead);
                    
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
        
			if (response.selfReload)
			{
				setTimeout(function() 
				{
					window.location.reload();
				}, 1000);
				
			}
        
			if(response.resetform)
			$(formid).resetForm();
			   
			if(response.loginstatus=='success'){
				hide_alert_message();
                window.location.href = response.url;
			}    
            
            if (response.slideToTop) {
                $('html, body').animate({
					
                    scrollTop: $(formid).offset().top - 290
                }, 800);
            }
            if (response.url)
				//setTimeout(function() {
                window.location.href = response.url;
				//}, 3000);
           
            if (response.status == 'success') {
                //$(formid)[0].reset();
            }
            if (response.redirect == 'yes') {
				setTimeout(function() 
				{
					window.location.href = response.redirectUrl;
				}, 2000);
            }
              if (response.reloadCommentDiv)
				{
					alert(commented);
				   // $("#comment_div").load(" #comment_div > *");
					$("#comment_div").load(location.href + " #comment_div");
				}
              if (response.photo == "yes")
            {
			   $("#Photo").load(" #Photo > *");
            }
            if(response.ajaxPageCallBack)
			{
				
				response.formid = formid;				
				ajaxPageCallBack(response);

			}
			},
			error: function(response) {
			alert('server error');          
               
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
		$('.alert.alert-dismissable').fadeOut(5000);
	}, 8000);
}
