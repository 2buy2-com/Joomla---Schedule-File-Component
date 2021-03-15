jQuery(document).on('click', '.run-schedulefiles', function(e){
	e.preventDefault();
	jQuery.ajax(
		"index.php?option=com_schedulefiles&view=schedulefiles&extension=com_schedulefiles&format=json&task=run_file", 
		{
			data: {'file_id': jQuery(this).attr('data-file')},
		success: function(result, status, xhr){
			if(result.status_code == 200){
				location.reload();
			} else {
				alert('Error code: '+result.status_code);
			}
		},
		error: function() {console.log('AJAX call failed');}
	});
});

jQuery(document).on('change', 'input[type="checkbox"]', function(){
	if(jQuery('input[type="checkbox"]:checked').length > 0){
		jQuery('input[name="boxchecked"]').val('1');
	} else {
		jQuery('input[name="boxchecked"]').val('0');
	}
});
