$(document).ready(function() {
	$('#addEmailField').click(function() {
		var emailIndex = $('input[name="emails[]"]').length;
		var regEmail = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

		if(emailIndex>=0) {
			if(emailIndex===0)
				$( "#list-emails" ).append( "<li class='list-group-item'><input type='text' name='emails[]' class='form-control'></li>" );

			var email = $('input[name="emails[]"]:eq('+(emailIndex-1)+')').val();

			if (regEmail.test(email)){
				console.log("email es valido");
				$( "#list-emails" ).append( "<li class='list-group-item'><input type='text' name='emails[]' class='form-control'></li>" );
			}
			else
				console.log("email no es valido !!");
		}
	});
	$('#addAddressField').click(function() {
		var addressIndex = $('input[name="addresses[]"]').length;

		if(addressIndex>=0) {
			var address = $('input[name="addresses[]"]:eq('+(addressIndex-1)+')').val();
			if(addressIndex===0)
				$( "#list-addresses" ).append( "<li class='list-group-item'><input type='text' name='addresses[]' class='form-control'></li>" );
			else {
				if(address!=="") {
					$( "#list-addresses" ).append( "<li class='list-group-item'><input type='text' name='addresses[]' class='form-control'></li>" );
				}
			}
		}
	});
	$('#addPhoneField').click(function() {
		var addressIndex = $('input[name="phones[]"]').length;

		if(addressIndex>=0) {
			var address = $('input[name="phones[]"]:eq('+(addressIndex-1)+')').val();
			if(addressIndex===0)
				$( "#list-phones" ).append( "<li class='list-group-item'><input type='text' name='phones[]' class='form-control'></li>" );
			else {
				if(address!=="") {
					$( "#list-phones" ).append( "<li class='list-group-item'><input type='text' name='phones[]' class='form-control'></li>" );
				}
			}
		}
	});
	$('#birthday').datepicker();
});
