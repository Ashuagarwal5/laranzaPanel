(function($) { $(document).ready(function(){
		$('.isa_success').append('<section onclick="close_div()" class="close" style="cursor:pointer">×</section>');})})(jQuery)
	function deletePhoto(pid,imgid)
	{
		BootstrapDialog.confirm(deletealert+' ?', function(r){
		if (r==true)
		{
			if(imgid!=cvr_imgid)
			{
					var posturl=siteurl+'lol/photo/delete/'+pid+'/'+imgid;
					$.ajax({
					url: posturl,
					dataType: 'json',
					type: "GET",
					beforeSend: function(){
							 $('#wait-div').show();
							},
				  success: function(data){
				  $('#wait-div').hide();
					 if(data.success)
					  {  
							$("#image_"+imgid).hide('slow').remove();
							var count = $("#countp").text();
							$("#countp").text(count-1);
							if(count-1==0)
							$("#nophoto").slideDown(1000);
					  }else{
						  
						  alert(data.error_message);
						  
						}
				  },
				  error: function (data) {
					 alert(servererror);
					 return false;
				  }
				
				});
				}
			else
			{
			BootstrapDialog.alert(nodelerecover);
			}
		}
		});
		return false;
		
	}
	function editPhotoPath(prf_img_id)
	{
	window.location.href = siteurl+'lol/editPicture/'+prf_img_id;
	}
	function set_cover(prf_img_id,pid)
	{
			var posturl=siteurl+'lol/photo/setCoverImage/'+pid+'/'+prf_img_id;
			$.ajax({
			url: posturl,
			dataType: 'json',
			type: "GET",
			beforeSend: function(){
					 $('#wait-div').show();
					},
			success: function(data){
			$('#wait-div').hide();
				if(data.success)
				{  
					
					$(".set_p").show('slow');
					$("#set_cover"+prf_img_id).hide('slow').remove();
				}
			},
			error: function (data) {
				alert(servererror);
				return false;
			}

		});
	}
	
	
	
	$(function() {
	$("#uploader").plupload({
		// General settings
		runtimes : 'html5,flash,silverlight,html4',
		url : siteurl+'admin/media/uploadhandlerPhoto',
		max_file_count: 50,
		chunk_size: '1mb',
		// Resize images on clientside if we can
		filters : {
			// Maximum file size
			max_file_size : '1000mb',
			// Specify what files to browse for
			mime_types: [
				{title : "Image files", extensions : "jpg,gif,png,jpeg"},
				{title : "Zip files", extensions : "zip"}
			]
		},

			// Rename files by clicking on their titles
			rename: true,
			// Sort files
			sortable: true,
			// Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
			dragdrop: true,
			// Views to activate
			views: {
				list: true,
				thumbs: true, // Show thumbs
				active: 'thumbs'
			},
			// Flash settings
			flash_swf_url : templateassets+'plupload/Moxie.swf',
			// Silverlight settings
			silverlight_xap_url : templateassets+'plupload/js/Moxie.xap'
		});
		$('#uploader').on('complete', function() {
					 $("#button_frm").removeAttr("disabled");
					  $('#button_frm').pulsate({
						color: "#399bc3",
						repeat: true
					});
		});
	});
