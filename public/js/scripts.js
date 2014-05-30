$(document).ready(function() {
	//number format
	/*$(".number-format").blur(function() {
		var value = $(this).val();
		NumberFormat.setValue(value);
		NumberFormat.numericFormat();
		$(this).val(NumberFormat.getValue());
	});*/

$( "#sortable" ).sortable({
	stop: function( event, ui ) {
	}
});
$( "#sortable" ).disableSelection();
});