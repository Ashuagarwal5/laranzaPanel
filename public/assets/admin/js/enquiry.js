
  $(document).on('submit', '#message_form', function(event){
		event.preventDefault();

		$('.btnmessage').html('Processing');
		$('.btnmessage').attr('disabled','disabled')
		$.ajax({
		url: message_route+'/'+ureqId,
		type: "POST",
		data: $(this).serialize(),
		dataType: 'json',
		beforeSend:function(){

			$('.formmessage').remove();

			}
        ,
		success: function(response) {
			toastr[response.status]("Sucessfully Send", "Notifications");
			  $('#messagetxt').val("");
			        $('.btnmessage').html('Send');
			        $('.btnmessage').removeAttr('disabled');
			        $('#message_chat').html(response.content);


		},
		error: function(response){
			        $('.btnmessage').html('Send');
			        $('.btnmessage').removeAttr('disabled');
		}

		});



});

		$(document).on('submit', '#quo_form', function(event){
		event.preventDefault();

		$('.btnsbmt').html('Processing');
		$('.btnsbmt').attr('disabled','disabled')
		$.ajax({
		url: quotation_route+'/'+ureqId,
		type: "POST",
		data: $(this).serialize(),
		dataType: 'json',
		beforeSend:function(){

			$('.formmessage').remove();

			}
        ,
		success: function(response) {
			$('.btnsbmt').html('Send To client');
			 $('.btnsbmt').removeAttr('disabled');
			toastr[response.status]("Sucessfully Send", "Notifications");
			$('#quo_form')[0].reset();

			$('[href="#faq-cat-4"]').hide();
			$("#faq-cat-4").hide();
			$('[href="#faq-cat-3"]').show();
			$('[href="#faq-cat-3"]').trigger('click');
			$('#accordion-cat-3').html(response.content);

		},
		error: function(response){
			        $('.btnsbmt').html('Submit');
			        $('.btnsbmt').removeAttr('disabled');
		}

		});



});
$(document).on('submit', '#update_quo_form', function(event){
		event.preventDefault();
		$('.btnsbmt').html('Processing');
		$('.btnsbmt').attr('disabled','disabled')
		var quoId = $('#update_quo_form').attr('data-quoId');


		$.ajax({
		url: quotation_route+'/'+ureqId+'/'+quoId,
		type: "POST",
		data: $(this).serialize(),
		dataType: 'json',
		beforeSend:function(){

			$('.formmessage').remove();

			}
        ,
		success: function(response) {
			 $('.btnsbmt').html('Send To client');
			 $('.btnsbmt').removeAttr('disabled');
			toastr[response.status]("Sucessfully Send", "Notifications");


			$('[href="#faq-cat-5"]').hide();
			$("#faq-cat-5").hide();
			$('[href="#faq-cat-3"]').show();
			$('[href="#faq-cat-3"]').trigger('click');
			$('#accordion-cat-3').html(response.content);

		},
		error: function(response){
			        $('.btnsbmt').html('Submit');
			        $('.btnsbmt').removeAttr('disabled');
		}

		});



});


		$(document).on('click', '#send_new', function(){
			$('.btnsendnew').html('Processing');
			$('.btnsendnew').removeAttr('disabled');
		$.ajax({
		url: newquotation_route+'/'+ureqId,
		type: "get",
		dataType: 'json',
		success: function(response) {
			 $('.btnsendnew').html('Send New Quotation');
			 $('.btnsendnew').removeAttr('disabled');
			$('[href="#faq-cat-4"]').show();
			$('[href="#faq-cat-4"]').trigger('click');
			$('#accordion-cat-4').html(response.content);

		},

		});

		});



    $(document).ready(function(){

		  $(document).on('click', '.add-row', function(){
			var tab  = $('.nav-tabs .active a').attr('href');


			var markup ="<tr><td>#</td><td><input type='text' name='item_description[]'  required /></td>"+
                "<td><input type='number' name='item_qty[]' class='qty' style='width: 50px;' min='1' required /></td>"+
                "<td><input type='number' name='item_unitprice[]' class='unit' style='width: 100px;' min='1' required /></td>"+
                "<td class='price'></td> <td>  <button type='button' class='delete-row'>Delete Row</button></td></tr>";

            $(tab+" "+"#quo_table tbody .rowclass").before(markup);
        });

        $(document).on('click', '.delete-row', function(){
                    $(this).parents("tr").remove();
					var total=0;
					$('.qty').each(function(key, val) {
						var price = $(this).val();
						var unit  = $('.unit').eq(key).val();
						if(price!="" && unit!="")
						{
							$('.price').eq(key).html("<td>"+price*unit+"</td>");
							total = total+(price*unit);
						}

					});
					$('.net_total').html(total);
					$('.total').html(total);


        });
    });

	$(document).on('change', '.qty,.unit', function(e){
		var total=0;
		$('.qty').each(function(key, val) {
			var price = $(this).val();
			var unit  = $('.unit').eq(key).val();
			if(price!="" && unit!="")
			{
				$('.price').eq(key).html("<td>"+price*unit+"</td>");
				total = total+(price*unit);
			}

		});
		$('.net_total').html(total);
		$('.total').html(total);
	 });


   $(document).on('click', '.upd_quo', function(event){

		var quoId = $(this).attr('data-quoId');

		event.preventDefault();
		$.ajax({
		url: quotationedit_route+'/'+quoId,
		type: "get",
		dataType: 'json',
		beforeSend:function(){

			$('.formmessage').remove();

			}
        ,
		success: function(response) {

			$('[href="#faq-cat-5"]').show();
			$('[href="#faq-cat-5"]').trigger('click');
			$('#accordion-cat-5').html(response.content);

		},

		});

	});

	 $(document).on('click', '[href="#faq-cat-1"],[href="#faq-cat-2"],[href="#faq-cat-3"]', function(){
			$("#faq-cat-4").css("display", "none");
			$("#faq-cat-5").css("display", "none");


	 });

	  $(document).on('click', '[href="#faq-cat-4"]', function(){
			$("#faq-cat-5").css("display", "none");
			$("#faq-cat-4").css("display", "block");


	 });

	  $(document).on('click', '[href="#faq-cat-5"]', function(){
		  $("#faq-cat-4").css("display", "none");
			$("#faq-cat-5").css("display", "block");


	 });

	 $(document).on('change', '#term_setting', function(){
		 var tab  = $('.nav-tabs .active a').attr('href');
		  var terms = $('option:selected', this);
		  terms.remove();

		  $(tab+" "+".terms_list").append("<li >"+terms.text()+"<a class='btn btn-danger fa fa-minus term_list_value' data-termid='"+terms.val()+"' href='javascript:'></a></li>");

			var name = terms.val();
			$(tab+" "+".term").after(
			"<input type='hidden' name='term[]' value="+name+" />"
			);

	 });

	  $(document).on('click', '.term_list_value', function(){
		  var tab  = $('.nav-tabs .active a').attr('href');
		 	var value = $(this).attr('data-termid');
			 $(tab+" "+"#term_setting").append("<option value='"+value+"'>"+$(this).parent().text()+"</option>");
				$(this).parent().remove();

			$(tab+" "+'input[type="hidden"][value="'+value+'"]').remove();
	 });

$(document).on('click', '#generate_invoice', function(event){
		
		var quoId = $(this).attr('data-quoId');


		$.ajax({
		url: invoice_route+'/'+quoId,
		type: "GET",
		
		dataType: 'json',
	
		success: function(response) {

			$('[href="#faq-cat-7"]').show();
			$('[href="#faq-cat-7"]').trigger('click');
			$('#faq-cat-7-invoice').html(response.content);

		},

		});



});

	  

  $(document).on('click', '.approve', function(){
	  var ref=$(this);
	    BootstrapDialog.show({
            title: 'Confirm Box',
            message: 'Are You Sure Want To Approve This Quotation',
            buttons: [{
                      label: 'Close',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }, {
                label: 'Approve',
                action: function(dialogItself) {
						approve(ref);
						dialogItself.close();
                }
            }]
        });
        
	
	
	});
	
	function approve(ref)
	{
		var quo_ref_no=$(ref).attr('data-approve-quoid');

						var enurefno=$(ref).attr('data-enurefno');

						

						$(this).html('Processing');
						$(this).attr('disabled','disabled')
						$.ajax({
						url: quotation_route+'/'+quo_ref_no,
						type: "POST",
						dataType: 'json',
						data: {'_token': $('input[name=_token]').val()},

						success: function(response) {
						toastr[response.status]("Sucessfully Approve", "Notifications");

						ref.html('Approved');
						ref.removeAttr('disabled');
						ref.removeClass('approve');
						$('table tbody tr'+'.'+enurefno).find('td a.approve').remove();
						$('table tbody tr'+'.'+enurefno).find('td.actions a').remove();



						},


						});
	}
	  $(document).on('click', '.approvequo', function(){
	
			var ref=$(this);
			  BootstrapDialog.show({
            title: 'Confirm Box',
            message: 'Are You Sure Want To Approve This Quotation',
            buttons: [{
                      label: 'Close',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }, {
                label: 'Approve',
                action: function(dialogItself) {
						approvequo(ref);
						dialogItself.close();
                }
            }]
        });
        
	  	
	});
	
	function approvequo(ref)
	{
			 var quo_ref_no=$(ref).attr('data-approve-quoid');
	 
	 var enurefno=$(ref).attr('data-enurefno');
	
	  var ref=$(ref);
	
		$(ref).html('Processing');
		$(ref).attr('disabled','disabled')
		$.ajax({
		url: quotation_route+'/'+quo_ref_no,
		type: "POST",
		dataType: 'json',
		data: {'_token': $('input[name=_token]').val()},
		
		success: function(response) {
			toastr[response.status]("Sucessfully Approve", "Notifications");
			
					ref.html('Approved');
			        ref.removeAttr('disabled');
			        ref.removeClass('approvequo');
			        $('.allquo').find('a.approvequo').remove();
			       
					$(document).find('table tbody tr'+'.'+enurefno+' td a.approve').remove();
					$(document).find('table tbody tr'+'.'+enurefno+' td.actions a').remove();
					$(document).find('table tbody tr'+'.'+enurefno+' td a#'+quo_ref_no).show();
			     
		},


		});
	}
	 
		$(document).on('submit', '#invoice_form', function(event){
				event.preventDefault();
				var dataref_no=$(this).attr('data-ref_no');
	 
				$('.btnsbmt').html('Processing');
				$('.btnsbmt').attr('disabled','disabled')
				$.ajax({
				url: invoiceform_route+'/'+dataref_no,
				type: "POST",
				data: $(this).serialize(),
				dataType: 'json',
				beforeSend:function(){

					$('.formmessage').remove();

					}
				,
				success: function(response) {
						toastr[response.status]("Sucessfully Send", "Notifications");
						$('#generate_invoice').hide();
					$('#generate_invoice').html('Send To client');
					 $('#generate_invoice').removeAttr('disabled');
					
			
					$('[href="#faq-cat-7"]').hide();
					$("#faq-cat-7").hide();
					$('[href="#faq-cat-8"]').show();
					$('[href="#faq-cat-8"]').trigger('click');
					$('#faq-cat-8-invoice').html(response.content);

				},
				error: function(response){
							$('#generate_invoice').html('Submit');
							$('#generate_invoice').removeAttr('disabled');
				}

		});



});  
 


  
