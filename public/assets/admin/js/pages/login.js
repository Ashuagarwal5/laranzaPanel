$(document).ready(function () {

    //Square blue color scheme for iCheck

    if ($.fn.iCheck) {
        $('input[type="checkbox"].square-blue').iCheck({
            checkboxClass: 'icheckbox_square-blue'
        });
    }

    

    

    

 $('#emailforgetform').submit(function( event ) {

    

	

	 event.preventDefault();

	 

	 	$('.emailbtn').html('Processing');

        $('.emailbtn').attr('disabled','disabled') 

		$.ajax({

		url : $(this).attr('action'),

		type: "POST",

		data: $('#emailforgetform').serialize(),

		dataType: 'json',

		 beforeSend:function(){

		

			$('.formmessage').remove();

			

			}

			,	

		success: function(response) {

			

		 $('.emailbtn').html('Submit');

                 $('.emailbtn').removeAttr('disabled');

		

		if(response.status== "error"){

			

			 $('#error_message').html(

				  "<div class='alert alert-danger alert-dismissable margin5'>"+

					"<button type='button' class='close' data-dismiss='alert' aria-hidden=true'>&times;</button>"+

					"<strong >Email incorrect</strong>"+ 

				   "</div>"

				   );

			

			}

		if(response.status== "success"){

				

				 $('#error_message').html(

				  "<div class='alert alert-success alert-dismissable margin5'>"+

					"<button type='button' class='close' data-dismiss='alert' aria-hidden=true'>&times;</button>"+

					"<strong >Your Password Reset Successfully . Please Check Your Mail</strong>"+ 

				   "</div>"

				   );

			}

		

		},

		error: function(response) {

			

			   $('.emailbtn').html('Submit');

			     $('.emailbtn').removeAttr('disabled');

				

			var data = response.responseJSON;

			

		

                       $.each(data, function( key, value ) {

                      console.log(key + " => " + value); 

                      var msg = '<label class="error formmessage" for="'+key+'"  style="color:red">'+value+'</label>';

                      $('input[id="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').addClass('inputTxtError').after(msg);

                               

                 });

                 

			

			

			

		}

		});

});

    

    

    

    

});

