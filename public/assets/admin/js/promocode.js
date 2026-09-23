  
        
        function discountType(data,type,row,meta)
        {
			if(row.apply_as=="percentage of product price discount")
			{
				return data+"%";
			}
			else
			{
				return currency+data;
			}
		}
		
		function deletePromocode(promo_code_id)
		{
			var result = confirm("Are Your sure Want to delete?");
			if (result) {
				$.ajax({
				url: delete_route+'/'+promo_code_id,
				type: "GET",
				
				dataType: 'json',	
					success: function(response) {
						
						toastr[response.status]("Successfully Delete Promocode", "Notifications");
						
						var table = $('#table1').DataTable({
						processing: true,
						serverSide: true,
						bDestroy: true,
						ajax: list_route,
					
						columns: [
						{ data: 'promo_code_id', name: 'promo_code_id' },
						{ data: 'title', name: 'title' },
						{ data: 'from_date', name: 'from_date' },     
						{ data: 'to_date', name: 'to_date' }, 					

						{ data: 'discount', name: 'discount',render:function(data,type,row,meta){ return discountType(data,type,row,meta)} },
						{ data: 'actions', name: 'actions', orderable: false, searchable: true }
						]
						
						});
						
						table.on( 'draw', function () {
						$('.livicon').each(function(){
						$(this).updateLivicon();
						});
						});
						
						

					}
				});

			}

		} 
		
		
		$( '#use_auto_generatd').on( "change", function() {
		
		if($(use_auto_generatd).is(":checked")==true)
		{
			$('#coupon_code').prop('required',null);
			$('#coupon_code').val("");
			$('#coupon_code_div').hide();
		}
		else
		{
			$('#coupon_code').prop('required',true);
			$('#coupon_code_div').show();
		}

	});
	
	
	
		$('#promo_form').on('submit',function(event){
	
		  if($('#apply_as').val()=="percentage of product price discount" && $('#discount').val()>100)
			{
				event.preventDefault();
				$('#discount_error').html("percentage should be less than 100");
				$('#discount_error').css("color",'red');
				$('#discount').focus();
			}
			 
		});
		
		$('#quantity_form').on('submit',function(event){
			 event.preventDefault();
		
			
			
			$.ajax({
			url: edit_route+'/'+promo_code_id,
			type: "POST",
			data: $('form').serialize(),
			dataType: 'json',	
			success: function(response) {
				$('#quantity').val(response.quantity);
				toastr[response.status]("Coupon Code Add Successfully", "Notifications");
			    	var table = $('#table1').DataTable({
					processing: true,
					serverSide: true,
					bDestroy: true,
					ajax: coupon_route+'/'+response.promo_code_id,
					columns: [
					{ data: 'coupon_code_id', name: 'coupon_code_id' },
					{ data: 'coupon_code', name: 'coupon_code' },
					{ data: 'coupon_used', name: 'coupon_used' }

					]
					});
					
					table.on( 'draw', function () {
					$('.livicon').each(function(){
					$(this).updateLivicon();
					});
					});


			}
			});

			 
		});
		
		$(document).ready(function () {


		var tabId = window.location.hash; 
		
		 $('.nav-tabs a[href="#' + tabId + '"]').tab('show');
				
		});
		
		function restorePromocode(promo_code_id)
		{
			var result = confirm("Are Your sure Want to restore?");
			if (result) {
				$.ajax({
				url: restore_route+'/'+promo_code_id,
				type: "GET",
				dataType: 'json',	
					success: function(response) {
						
						toastr[response.status]("Successfully Restore Promocode", "Notifications");
						
						var table = $('#table1').DataTable({
						processing: true,
						serverSide: true,
						bDestroy: true,
						ajax:deleteList,
					
						columns: [
						{ data: 'promo_code_id', name: 'promo_code_id' },
						{ data: 'title', name: 'title' },
						{ data: 'from_date', name: 'from_date' },     
						{ data: 'to_date', name: 'to_date' }, 					

						{ data: 'discount', name: 'discount',render:function(data,type,row,meta){ return discountType(data,type,row,meta)} },
						{ data: 'actions', name: 'actions', orderable: false, searchable: true }
						]
						
						});
						
						table.on( 'draw', function () {
						$('.livicon').each(function(){
						$(this).updateLivicon();
						});
						});
						
						

					}
				});

			}

		} 
		
