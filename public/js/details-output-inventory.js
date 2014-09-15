$(document).ready(function() {
	$('#product').blur(function() {
		$.fn.getSerialList();
	});
	$('#qty').blur(function() {
		$.fn.getSerialList();
	});
	$('#product_search').blur(function() {
		var product = $('#product_search').val();
		$.fn.searchProduct(product);
	});
});

$.fn.getSerialList = function() {}

$.fn.searchProduct = function(product) {

	var product = product;

	if (product != "") {
		$.ajax({
			type : "POST",
			url : "/admin/product/search",
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
							//console.log(productList);


				} else {
					console.log("debe seleccionar una categoria");
				}
			});
	}
	else
		console.log("error");
}