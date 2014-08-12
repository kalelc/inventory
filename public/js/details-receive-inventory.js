$(document).ready(function() {
	$('#product').change(function() {
		$.fn.getSerialList();
	});
		$('#qty').blur(function() {
		$.fn.getSerialList();
	});
});

$.fn.getSerialList = function() {

	var product = $("#product").val();
	var qty = $("#qty").val();
	
	if (product>0 && qty > 0) {
		$.ajax({
			type : "POST",
			url : "/admin/product/get-serialList",
			data : {
				product : product,
			}
		})
		.done(
			function(result) {
				
				var selects = "" ;

				if (typeof result === 'object') {
					for (var i = 0; i < qty; i++) {
						selects += "<div class='panel panel-default'><div class='panel-body'>" ;
						$.each(result.serialList, function(key, item) {
							selects += "<div class='form-group'>" ;
							selects += "<label class='col-sm-2 control-label'>"+item+"</label>";
							selects += "<div class='col-sm-2'><input type='text' name='serialValues[]' class='form-control'></div>";
							selects += "</div>";
						});
						selects += "</div></div>";
					}
					$("#serial_values_layout").html(selects);

				} else {
					console.log("debe seleccionar una categoria");
				}
			});
	}
	else
		$("#serial_values_layout").html("<div class='alert alert-info'><p>Debe seleccionar una cantidad y un producto.</p></div>");
}