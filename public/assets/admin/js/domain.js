	$(document).ready(function (){
		var table = $('#tabledomaincontact').DataTable({
		processing: true,
		serverSide: true,
		
		ajax: doamin_contact,
		columns: [
		
			{ data: 'location', name: 'location' },
			{ data: 'name', name: 'contact_type_settings.name' },
			{ data: 'description', name: 'description' },
			{ data: 'value', name: 'value' },
			{ data: 'actions', name: 'actions', orderable: true, searchable: true }



		]
		});
		table.on( 'draw', function () {
			$('.livicon').each(function(){
			$(this).updateLivicon();
		});
	} );
	});
	$(document).ready(function (){


            var table = $('#tabledomainkeyword').DataTable({
                processing: true,
                serverSide: true,
               
                ajax: doamin_keyword,
                columns: [
					
					{ data: 'keyword_type', name: 'keyword_type' },
					{ data: 'label_description', name: 'label_description' },
					{ data: 'value', name: 'value',render:function(data,type,row,meta){ return displaykeyword(data,type,row,meta)} },
					{ data: 'actions', name: 'actions', orderable: true, searchable: true }



                ]
            });
	}); 
	
   function displaykeyword(data,type,row,meta)
	{
		var obj = jQuery.parseJSON(row.value);
		var str = "";
		$.each( obj, function( key, value ) {
			str = str + " " + value;
		});
		return str;
	}



	
	function deleteDoaminkeyword(keyword_id)
{
    var del_url = domain_keyword_del+'/'+domain_slug+'/'+keyword_id;
  $.ajax({
		url: del_url,
		 type: "post",
         dataType: 'json',
         data: {
             '_token': $('input[name=_token]').val(),
         },

		success: function(response) {
                  getDomainkeyword();


					toastr[response.status]("Sucessfully Deleted ", "Notifications");


		},
		error: function(response){

		}

		});

	}
	 
function deleteDoaminContact(contact_id)
{

    var domain_del_url = domain_conatct_Del+'/'+domain_slug+'/'+contact_id;


  $.ajax({
		url: domain_del_url,
		 type: "post",
         dataType: 'json',
         data: {
             '_token': $('input[name=_token]').val(),
         },

		success: function(response) {
                   getDomainConatct();


					toastr[response.status]("Sucessfully Deleted ", "Notifications");


		},
		error: function(response){

		}

		});

	}

	
function getDomainConatct(){


            var table = $('#tabledomaincontact').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                ajax: doamin_contact,
                columns: [
                     
                      { data: 'location', name: 'location' },
                       { data: 'name', name: 'contact_type_settings.name' },
                        { data: 'description', name: 'description' },
                         { data: 'value', name: 'value' },
                          { data: 'actions', name: 'actions', orderable: true, searchable: true }



                ]
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        };


  function getDomainkeyword(){


            var table = $('#tabledomainkeyword').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                ajax: doamin_keyword,
                columns: [
                    
                      { data: 'keyword_type', name: 'keyword_type' },
                       { data: 'label_description', name: 'label_description' },
                     { data: 'value', name: 'value',render:function(data,type,row,meta){ return displaykeyword(data,type,row,meta)} },


                          { data: 'actions', name: 'actions', orderable: true, searchable: true }



                ]
            });

        };
      function getdomainimages(){

	 $.ajax({
		url: domain_image_data,
		type: "GET",
		data: $(this).serialize(),
		dataType: 'html',
				success: function(response) {
				if(response!="")	
					$('.images-domain').html(response);
				else
					$('.images-domain').html("<br><center>No Domain Image Found</center>");

		},
		error: function(response){

		}


        });





  };



  function getdomainbanner(){

	 $.ajax({
		url: domain_banner_data,
		type: "GET",
		data: $(this).serialize(),
		dataType: 'html',
				success: function(response) {
				if(response!="")	
					$('.bannerimages-domain').html(response);
				else
						$('.bannerimages-domain').html("<br><center>No Banner Found</center>");

		},
		error: function(response){

		}

        });

  };

function deleteDomainImage(img)
{

 var url = page+"/"+img;

$.ajax({
		url: url,
		 type: "post",
         dataType: 'json',
         data: {
             '_token': $('input[name=_token]').val(),
         },

		success: function(response) {

					toastr[response.status]("Sucessfully Deleted ", "Notifications");

                   getdomainimages();
		},
		error: function(response){

		}

		});



}

function deleteDomainBanner(img){



 var url = page_url+"/bannerdel"+'/'+img;

$.ajax({
		url: url,
		 type: "post",
         dataType: 'json',
         data: {
             '_token': $('input[name=_token]').val(),
         },

		success: function(response) {

					toastr[response.status]("Sucessfully Deleted ", "Notifications");

                     getdomainbanner();

		},
		error: function(response){

		}

		});




	}
