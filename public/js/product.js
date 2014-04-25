$(document).ready(function() {
	$('#category').change(function() {
		var category = $("#category").val();
		$.fn.searchSpecifications(category);
	});
});

$.fn.searchSpecifications = function(category) {
	if (category>0) {
		$.ajax({
			type : "POST",
			url : "search-specifications",
			data : {
				category : 			category,
			}
		})
		.done(
			function(result) {
				if (typeof result === 'object') {
					$("#tab-product-specifications").html("<ul class='list-group'>");

					$.each(result.specification, function( id, specification ) {

						var select = "<select name='measures[]'>" ;
						select += "<option name=''>---------</option>" ;
						$.each(specification.measure, function( id, measure ) {
							console.log(id);
							select += "<option value='"+id+"'>"+measure+"</option>" ;
						});
						select += "<select>" ;

						var image = !specification.image || 0 === specification.name.length ? "/img/icons/picture.png" : "/images/specification/"+specification.image ;
						$("#tab-product-specifications ul").append("<li class='list-group-item'><img src='"+image+"' class='thumb_24'>&nbsp;"+specification.name+"<div class='pull-right'>"+select+"</div></li>");
					});
				} else {
					console.log("debe seleccionar una categoria");
				}
			});
	}
	else
		$("#tab-product-specifications ul").html("");
}