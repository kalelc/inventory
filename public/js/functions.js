$(document).ready(function() {
	$("#viewchars").click(function() {
		if ($("#viewchars").is(':checked')) {
			$("#password").prop('type', 'text');
			$("#password").attr('value', $('#password').val());
		}
		else {
			$("#password").prop('type', 'password');
		}
	});

	$( "#sortable" ).sortable({	stop: function( event, ui ) {} });
	$( "#sortable" ).disableSelection();

	$(".number_format").blur(function() {
		var value = $(this).val();
		NumberFormat.setValue(value);
		NumberFormat.numericFormat();
		$(this).val(NumberFormat.getValue());
	});
});


function showModal(){
	$('#modal').modal('show');
}

function showModalImage(image,title){
	$("#modalBody img").attr('src', image);
	$("#modalImageLabel").html(title);
	$("#modalImage").modal('show');
}
function showModalList(content,title){
	var json = JSON.parse(content);
	var result = "" ;
	$.each(json, function(index, value) {
		result += (index===0) ? "<a class='list-group-item active'>"+value+"</a>" : "<a class='list-group-item'>"+value+"</a>" ;
	});
	$("#modalBody > .list-group").html(result);
	$("#modalImageLabel").html(title);
	$("#modalList").modal('show');
}

