$(document).ready(function() {
	$('#addEmailField').click(function() {
		var emailIndex = $('input[name="emails[]"]').length;
		var regEmail = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

		if(emailIndex>=0) {
			if(emailIndex===0)
				$( "#list-emails" ).append( "<a class='list-group-item'><input type='text' name='emails[]' class='form-control' placeholder='email'></a>" );

			var email = $('input[name="emails[]"]:eq('+(emailIndex-1)+')').val();

			if (regEmail.test(email)){
				$( "#list-emails" ).append( "<a class='list-group-item'><input type='text' name='emails[]' class='form-control' placeholder='email'></a>" );
			}
		}
	});
	$('#addAddressField').click(function() {
		var addressIndex = $('input[name="addresses[]"]').length;

		if(addressIndex>=0) {
			var address = $('input[name="addresses[]"]:eq('+(addressIndex-1)+')').val();
			if(addressIndex===0)
				$( "#list-addresses" ).append( "<a class='list-group-item'><input type='text' name='addresses[]' class='form-control' placeholder='dirección'></a>" );
			else {
				if(address!=="") {
					$( "#list-addresses" ).append( "<a class='list-group-item'><input type='text' name='addresses[]' class='form-control' placeholder='dirección'></a>" );
				}
			}
		}
	});
	$('#addPhoneField').click(function() {
		var addressIndex = $('input[name="phones[]"]').length;

		if(addressIndex>=0) {
			var address = $('input[name="phones[]"]:eq('+(addressIndex-1)+')').val();
			if(addressIndex===0)
				$( "#list-phones" ).append( "<a class='list-group-item'><input type='text' name='phones[]' class='form-control' placeholder='telefono'></a>" );
			else {
				if(address!=="") {
					$( "#list-phones" ).append( "<a class='list-group-item'><input type='text' name='phones[]' class='form-control' placeholder='telefono'></a>" );
				}
			}
		}
	});
	$('#birthday').datepicker();
});
