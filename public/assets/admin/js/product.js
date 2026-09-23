function deleteImage(target,id)
{
	var route = $(target).attr('data-url');
	BootstrapDialog.show({
	title: 'Confirmation Box',
	message: 'Are you sure to delete this record.',
	buttons: [{
	label: 'Close',
		action: function(dialogItself){
			dialogItself.close();
		}
	}, {
	label: 'Delete',
	action: function(dialogItself) {
			
			$(target).attr('disabled','disabled');
				$.ajax(
				{
					url: route,
					type: 'get',
					dataType: "text",
					success:function(response){
					
						$(target).parent().remove();
						toastr['success']("Image Deleted Sucessfully.", "Notifications");
						
					}
				});   
				dialogItself.close();
	}
	}]
	});

}
