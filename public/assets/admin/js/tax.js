$(function()
{
	
  
  
		$(".ajaxtaxclass").on("submit", function (event) {

				
				var id= $('.btnupdate').data('id');
				if(id){
					
					var mytaxurl = addtaxurl+'/'+id;
					
					}
					else{
						var mytaxurl = addtaxurl;
						
						}
				var formData =$(this).serialize();
				$('.submitbtn').html('Processing');
                 $('.submitbtn').attr('disabled','disabled') 

				$.ajax({

				type        : 'POST',
				url         : mytaxurl,
				data        : formData,
				dataType    : 'json',
				 beforeSend:function(){
		
					$('.formmessage').remove();
					
					}
					,
				success     : function(data) {

				
					 $('.submitbtn').show();
					 $('.update').hide();
				
				
				// log data to the console so we can see
				console.log(data);
			toastr[data.status]("Tax Class Add succesfully", "Notifications");
				
				
				
				 $('.submitbtn').html('Save');
                 $('.submitbtn').removeAttr('disabled');
				 $('.submitbtn').removeAttr('disabled');
				 $('.success').removeClass('success');
				 $( '.ajaxtaxclass' ).each(function(){
			this.reset();
			});
				
	
           var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
               bDestroy: true,
                ajax: datatbleurl,
                columns: [
                    { data: 'tax_class_id', name: 'tax_class_id' },
                    { data: 'tax_title', name: 'tax_title' },
                    { data: 'slug', name: 'slug' },
                    { data: 'add_date', name: 'created_at' },
                       { data: 'actions', name: 'actions', orderable: true, searchable: true }
                    
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
                      $('input[name="' + key + '"], select[name="' + key + '"]').addClass('inputTxtError').after(msg);
			  
			 });
			  
	        }

				});
                      
				return false;
				// stop the form from submitting and refreshing
				event.preventDefault();
		});  
		
		
		
		
		
       $('body').delegate('.deletetax','click', function (event){
               
               var id= $(this).data('id');
               
               var deleurl =taxdestroyurl+'/' + id;
               
             
               $.ajax(
        {
             url: deleurl,
            type: 'POST',
           dataType    : 'json',
            
             data: {
                
                 '_token': $('input[name=_token]').val(),
                
            
                },
            
          success     : function(data) {
			
			toastr[data.status]("Tax Class Deleted succesfully", "Notifications");
				 
				  var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
               bDestroy: true,
                ajax: datatbleurl,
                columns: [
                    { data: 'tax_class_id', name: 'tax_class_id' },
                    { data: 'tax_title', name: 'tax_title' },
                    { data: 'slug', name: 'slug' },
                    { data: 'add_date', name: 'created_at' },
                       { data: 'actions', name: 'actions', orderable: true, searchable: true }
                    
                ]
            });
          table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
				 
				 
				 
				 
				}
        });   
            
              return false;
				// stop the form from submitting and refreshing
				event.preventDefault(); 
		   });
  
  
  
  	$('body').delegate('.edittax','click', function (){
				   
				   var id= $(this).data('id');
				  var editurl =taxedityurl+'/' + id;
				
				  
					
					 $.ajax({
						 
						 url: editurl,
						 type: "post",
						  dataType    : 'json',
				data: {
					
					 '_token': $('input[name=_token]').val(),
					
				
					},
				  success: function(response){
					  console.log(response);
					 
					  var tax_class_id  = response.tax.tax_class_id;
				
					 $('#tax_title').val(response.tax.tax_title);
					
					 $('.submitbtn').hide();
					 $('.update').show();
					
					 $('#update').empty();
					 $('#update').append("<button id='updatetax' data-id="+tax_class_id+" class='btn btn-primary btn-block btn-md btn-responsive btnupdate' >Update</button>");		
							
							  
					 }
					
				  
				  });
				
				//~ 
				   
			});
			   
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
 });

