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

						selects += "<div class='col-sm-6'>" ;

						$.each(result.serialList, function(key, item) {
							selects += "<div class='list-group'>" ;

							if(key==1)
								selects += "<a href='javascript:void(0);' class='list-group-item active'>" ;
							else
								selects += "<a href='javascript:void(0);' class='list-group-item'>" ;
							
							selects += "<h5 class='list-group-item-heading'>"+item+"</h5>" ;
							selects += "<p><input type='text' name='serialValues[]' class='form-control'></p>";
							selects += "</a>" ;
							
							selects += "</div>" ;

							
						});
						selects += "</div>" ;
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