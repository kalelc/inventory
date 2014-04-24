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
						$.each( result.specification, function( name, image ) {

							var image = !image || 0 === image.length ? "/img/icons/picture.png" : "/images/specification/"+image ;
							console.log(image)

							$("#tab-product-specifications ul").append("<li class='list-group-item'><img src='"+image+"' class='thumb_24'>&nbsp;"+name+"</li>");
						});
					} else {
						console.log("debe seleccionar una categoria");
					}
				});
		}
		else
			$("#tab-product-specifications ul").html("");
	}