// bootstrap wizard//
$("#gender, #gender1").select2({
    theme:"bootstrap",
    placeholder:"",
    width: '100%'
});
$("#editForm").bootstrapValidator({
    fields: {
        first_name: {
            validators: {
                notEmpty: {
                    message: 'The full name is required'
                }
            },
            required: true,
            minlength: 3
        },
        last_name: {
            validators: {
                notEmpty: {
                    message: 'The full name is required'
                }
            },
            required: true,
            minlength: 3
        },
        password_confirm: {
            validators: {
                identical: {
                    field: 'password'
                }
            }
        },
        email: {
            validators: {
                notEmpty: {
                    message: 'The email address is required'
                },
                emailAddress: {
                    message: 'The input is not a valid email address'
                }
            }
        },

          mobileno: {
            validators: {
                notEmpty: {
                    message: 'Mobile No is required'
                }
            },
            required: true,
            minlength: 3
        },



    }
});


$('#rootwizard').bootstrapWizard({
    'tabClass': 'nav nav-pills',



    });




$('.saveedit').click(function () {
    var $validator = $('#editForm').data('bootstrapValidator').validate();
    if ($validator.isValid()) {
        document.getElementById("editForm").submit();
    }

});



        $(document).ready(function () {





  $('#change-password').on('submit',function(e){

   var error="";
    e.preventDefault(e);

       $('#change-password-btn').html('Processing');
	 $('#change-password-btn').attr('disabled','disabled')
        $.ajax({

        type:"POST",
        url: changeass_url,
        data:$(this).serialize(),
        dataType: 'json',
        beforeSend:function(){

			$('.formmessage').remove();

			}
        ,
        success: function(response){

            $('#change-password-btn').html('Submit');
			        $('#change-password-btn').removeAttr('disabled');
            if(response.success==true)
		           	{

					 $( '#change-password' ).each(function(){
			this.reset();
			});

			 window.location.href=user_url;


						toastr[response.status]("Password Change Suucessfully", "Notifications");


					}



           },error: function(data){
			    			   $('#change-password-btn').html('Submit');
			        $('#change-password-btn').removeAttr('disabled');
			var data = data.responseJSON;


                       $.each(data, function( key, value ) {


                      console.log(key + " => " + value); // view in console for error messages
                      var msg = '<label class="error formmessage" for="'+key+'"  style="color:red">'+value+'</label>';
                      $('input[name="' + key + '"], select[name="' + key + '"]').addClass('inputTxtError').after(msg);




                 });



          }

    });
 //~
 //~


 });





        });
