$(document).ready(function() {

	$('#product_serial').keyup(function() {
		var serial = $('#product_serial').val();
		$.fn.searchSerial(serial);
	});
});

function searchProductSerial(serialName) {
	$.fn.searchProductSerial(serialName);
	$("#serials").html("");
}

$.fn.searchSerial = function(serial) {
	var serial = serial;

	if (serial != "") {
		$.ajax({
			type : "POST",
			url : "/process/receive-inventory/add/details/search-serial",
			scriptCharset: "utf-8" ,
			data : {
				serial : serial,
			}
		})
		.done(
			function(result) {

				$("#serials").html("<li><a href='javascript:void(0);'>Buscando..</a></li>");

				if (typeof result === 'object') {
					var productList = Array();
					$("#serials").html("");

					if(result.serials.length == "") {
						console.log("es cero");
					}

					$.each(result.serials, function(key, item) {
						
						var serialName = "" ;
						var serialsArray = $.parseJSON(item);
						
						for(var i = 0; i < serialsArray[0].length;i++) {
							serialName += serialsArray[0][i];
						}
						$("#serials").append("<li><a href='javascript:void(0);' onclick='searchProductSerial(\""+serialName+"\")'>"+serialName+"</a></li>");
					});
				} else {
					$("#serials").html("");
				}
			}
			);
	}
	else {
		$("#serials").html("");
	}
}

$.fn.searchProductSerial = function(product) {
	var product = product;

	if (product != "") {
		$.ajax({
			type : "POST",
			url : "/admin/product/search-serial",
			data : {
				product : product,
			}
		})
		.done(
			function(result) {

				$("#product").html("<option value=''>Buscando..</option>")

				if (typeof result === 'object') {
					var productList = Array();
					$("#product").html("")
					$.each(result.products, function(key, item) {
						$("#product").append("<option value='"+key+"'>"+item+"</option>")
						productList[key] = item ;
					});
				} else {
					console.log("debe seleccionar una categoria");
				}
			});
	}
	else
		console.log("error");
}