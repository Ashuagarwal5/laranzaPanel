$(function()
{
	$('#backbtn').click(function(){
	var url=$(this).attr('data-url');
	
	window.location.href=url;
	
});

$(function()
{
	var stateval = $('#state').val();
    
	$.get(stateurl+'/'+ stateval , function(states)
	{

				var $el = $("#ch_user1");
				$el.empty(); 
				$el.append($("<option></option>")
				.attr("value", '').text('Please Select'));
				data = $.parseJSON(states);
				$.each(data, function(k, v) {
					$el.append($("<option></option>")
				.attr("value", v).text(v));
		});	

	  });
	  
	
});


$('#state').change(function()
{
    var stateval = $('#state').val();

	$.get(stateurl+'/'+ stateval , function(states)
	{

			var $el = $("#ch_user1");
			$el.empty(); 
			$el.append($("<option></option>")
			.attr("value", '').text('Please Select'));
			data = $.parseJSON(states);
			$.each(data, function(k, v) {

			$el.append($("<option></option>")
			.attr("value", v).text(v));
			});	

	});
});	

	
	
$('.tabbable-line li a').click(function(){

	var tabpage=$(this).attr('href');

			if(tabpage=='#discussion')
			{

			$('.discussioncomment').html('<center><img src='+gifimageurl+' ></center>');
			$.ajax({
					type: "GET",
					url: discussionurl,
					error: function(data){
					alert("There was a problem");
					},
					success: function(data){

					$('.discussioncomment').html(data);
					}
			});

	}
	
	 if(tabpage=="#status")
	    {
		    $.ajax({
		      type: "GET",
		      url: statusurl,
		      error: function(data){
		        alert("There was a problem");
		      },
		      success: function(data){
		       
		        $('.statusdata').html(data);
		      }
		  });

	    }
	

});
  

$("#updatestatus").on("submit", function (event) {

		
		    var  current_state = $("#state").val();
		//~ 
			var formData =$(this).serialize();
			$('.submitbtn').html('Processing');
			
			$.ajax({
			type        : 'POST',
			url         : $(this).attr('action'),
			data        : formData,
			dataType    : 'json',
		    beforeSend:function(){
		
			$('.formmessage').remove();
			
			}
			,
			success     : function(data) {

			if(data.success==true)
			
			{
				console.log(data);
				toastr[data.status]("status updated succesfully", "Notifications");
			
			 $('.submitbtn').html('Save');
			
			$.ajax({
				  type: "GET",
				  url: discussionurl,
				  error: function(data){
					alert("There was a problem");
				  },
				  success: function(data){
					
					$('.discussioncomment').html(data);
				  }
		  });
		  
			$( '#updatestatus' ).each(function(){
			this.reset();
			});
		
		    $( '.backbtn' ).click(); 
		
		   $.get(remainstateurl+'/'+ current_state , function(statess)
		    {
						var $ell = $("#state");
						$ell.empty(); // remove old options
						data = statess;
						$.each(data, function(k, v) {
				
						$ell.append($("<option></option>")
									.attr("value", k).text(k));
							   });	
			
			});
			
			setTimeout(function(){// wait for 5 secs(2)
								location.reload(); // then reload the page.(3)
									}, 3000); 	
		  
		    }
			}
			,error: function(data){
				
		
			 $('.submitbtn').html('Save');
				 var data = data.responseJSON;
				  
				   $.each(data, function( key, value ) {
						 
				  console.log(key + " => " + value); // view in console for error messages
				  var msg = '<label class="error formmessage" for="'+key+'"  style="color:red">'+value+'</label>';
				  $('input[name="' + key + '"], select[name="' + key + '"]').addClass('inputTxtError').after(msg);
			  
			 });
			  
	        }
	  
			});

			return false;
			//~ // stop the form from submitting and refreshing
			event.preventDefault();
	});  


  
  
$('body').delegate('.invoicebtn','click', function (){	   
	   
	   var order_id= $(this).data('id');
	   	   
	   	$.ajax({
						url: generateinvoiceurl,
						type: "POST",
						dataType: 'JSON',
						data: {
                       
						'_token': $('input[name=_token]').val(),

				                },
				        success: function(response){
                             if(response.success==true)
							{
								
							toastr[response.status]("Successfully Generate Invoice", "Notifications");	
							
							 setTimeout(function(){// wait for 5 secs(2)
								location.reload(); // then reload the page.(3)
									}, 3000); 	
							}
							
			
					}
		     });
	    });  
	   
  
		$(".ajaxformshipment").on("submit", function (event) {

				var formData =$(this).serialize();
				$('.submitbtn').html('Processing');
                 $('.submitbtn').attr('disabled','disabled') 

				$.ajax({

				type        : 'POST',
				url         : shipmeturl,
				data        : formData,
				dataType    : 'json',
				 beforeSend:function(){
		
					$('.formmessage').remove();
					
					}
					,
				success     : function(data) {

				// log data to the console so we can see
				console.log(data);
			toastr[data.status]("Shipment updated succesfully", "Notifications");
				 $('.submitbtn').html('Save');
                 $('.submitbtn').removeAttr('disabled');
				 $('.submitbtn').removeAttr('disabled');
				 $('.success').removeClass('success');
				 $( '.ajaxformshipment' ).each(function(){
			this.reset();
			});
				
	
             var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
               
                ajax: shipmenttrack,
                columns: [
                    { data: 'ord_shp_id', name: 'ord_shp_id' },
                    { data: 'courier_name', name: 'courier_name' },
                   { data: 'tracking_detail', name: 'tracking_detail' },
                    { data: 'add_date', name: 'created_at' },

                   	
                ]
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
	
                 
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

  $('body').delegate('.EmailInvoice','click', function (){	   
            $('.EmailInvoice').html('Processing');
                 $('.EmailInvoice').attr('disabled','disabled') 
	   	$.ajax({
						url: emailinvoiceurl,
						type: "get",
						dataType: 'JSON',
						data: {
                       
						'_token': $('input[name=_token]').val(),

				                },
				        success: function(response){
                           
                           $('.EmailInvoice').html('Email Invoice');
                           $('.EmailInvoice').removeAttr('disabled');
                           
                             if(response.success==true)
							{
							toastr[response.status]("Invoice Send Successfully", "Notifications");	
							
							}
							
			
					}
		     });
	    });  
	   
  
  
  
 });

