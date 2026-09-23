
	

			 $.each(activity, function( key, value ) {
				
				
				 var date = new Date(value.event_date);
				var d = date.getDate();
                m = date.getMonth();
                y = date.getFullYear();

				item = {}
				item ["title"] = value.event;
				item ["start"] =  new Date(y, m, d);
				
				if(value.event_type=="primary")
				item ["backgroundColor"] = ('#0275d8')
				else if(value.event_type=="success")
				item ["backgroundColor"] = ('#5cb85c')
				else if(value.event_type=="info")
				item ["backgroundColor"] = ('#5bc0de')
				else if(value.event_type=="warning")
				item ["backgroundColor"] = ('#f0ad4e')
				else if(value.event_type=="danger")
				item ["backgroundColor"] = ('#d9534f')
				else if(value.event_type=="default")
				item ["backgroundColor"] = ('#a9b6bc')
				
				
				jsonObj.push(item);
			});

			

$('#activity_cal_form').submit(function( event ) {
  
	 event.preventDefault();
		$.ajax({
		url: activity_route,
		type: "POST",
		data: $(this).serialize(),
		dataType: 'json',
		beforeSend:function(){
			
			$('.formmessage').remove();
			
			}
        ,	
		success: function(response) {
			$('#myModal').modal('hide');
			$('#activity_cal_form')[0].reset(); 
			
			
			 var jsonObj=[];
			 
				   $.each(response.content, function( key, value ) {
					   
						var date = new Date(value.event_date);
						
						var d = date.getDate();
						m = date.getMonth();
						y = date.getFullYear();

						item = {}
						item ["title"] = value.event;
						item ["start"] =  new Date(y, m, d);
						
						if(value.event_type=="primary")
							item ["backgroundColor"] = ('#0275d8')
						else if(value.event_type=="success")
							item ["backgroundColor"] = ('#5cb85c')
						else if(value.event_type=="info")
							item ["backgroundColor"] = ('#5bc0de')
						else if(value.event_type=="warning")
							item ["backgroundColor"] = ('#f0ad4e')
						else if(value.event_type=="danger")
							item ["backgroundColor"] = ('#d9534f')
						else if(value.event_type=="default")
							item ["backgroundColor"] = ('#a9b6bc')
						
						jsonObj.push(item);
					});
					
					$('#calendar').fullCalendar('destroy');
			
				    	 $('#calendar').fullCalendar({
							header: {
								left: 'prev,next today',
								center: 'title',
								right: 'month'
							},
						
						   events: jsonObj,
						     
							eventClick: function(event) {
							if (event.title) {
							$('#event_value').html(event.title);
							$('#event').modal();


							}
						},
						   
						   editable: true,
						   droppable: false,
						   height:450
						});
				},
				
          
		});

	

});

