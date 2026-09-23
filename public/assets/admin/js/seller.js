$(function(){
  
  
  
  $('#backbtn').click(function(){
	var url=$(this).attr('data-url');
	
	window.location.href=url;
	
});
		$(".sellerstatus").on("submit", function (event) {
                
                
                
                var status = $('#status').val();
                
				var formData =$(this).serialize();
				$('.submitbtn').html('Processing');
                 $('.submitbtn').attr('disabled','disabled') 

				$.ajax({

				type        : 'POST',
				url         :  seller_status_url,
				data        : formData,
				dataType    : 'json',
				 beforeSend:function(){
		
					$('.formmessage').remove();
					
					}
					,
				success     : function(data) {

				// log data to the console so we can see
				console.log(data);
			toastr[data.status]("Save Succesfully", "Notifications");
				 $('.submitbtn').html('Save');
                 $('.submitbtn').removeAttr('disabled');
				 $('.submitbtn').removeAttr('disabled');
				 $('.success').removeClass('success');
				 $( '.sellerstatus' ).each(function(){
			this.reset();
			
			});
				 $("#status").val(status);	
	           	setTimeout(function(){// wait for 5 secs(2)
								location.reload(); // then reload the page.(3)
									}, 500); 
									
								
										
          
				}
                ,error: function(data){
				
		 
			     $('.submitbtn').html('Save');
			     $('.submitbtn').removeAttr('disabled');
				 $('.submitbtn').removeAttr('disabled');
				 var data = data.responseJSON;
				  
                
				   $.each(data, function( key, value ) {
						 
				  console.log(key + " => " + value); // view in console for error messages
				  var msg = '<label class="error formmessage" for="'+key+'"  style="color:red">'+value+'</label>';
				  $('.'+key).addClass('inputTxtError').after(msg);
			  
			 });
			  
	        }

				});
                      
				return false;
				// stop the form from submitting and refreshing
				event.preventDefault();
		}); 
   
});
