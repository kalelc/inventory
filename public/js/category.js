$(document).ready(function() {
	$(".specifications_name").click(function() {
		if($(this).is(':checked')) {  
			$("#specification"+this.value).prop('checked', true);
		} else { 
			$("#specification"+this.value).prop('checked', false);
		}  
	});
});