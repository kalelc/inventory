$(document).ready(function() {
	$('#category').change(function() {
		var category = $("#category").val();
		$.fn.searchSpecifications(category);
	});

	$("#product").submit(function() {
		var select = $('select[name="measures[]"] option:selected');
		result = true;
		$(select).each(function(){
			if(!this.hasAttribute('value')) {
				result = false;
				$("#specification-error").html("<ul class='error'><li>debe seleccionar las especificaciones del producto<li></ul>");
			}
		});
		return result;
	});

	$("a.specificacionEvent").click(function() {
		var specification = $(this).attr("value");
		$(this).hide();
		$.fn.getMeasures(specification);

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
						select += "<option></option>" ;
						$.each(specification.measure, function( id, measure ) {
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

$.fn.getMeasures = function(specification) {
	if (specification>0) {
		$.ajax({
			type : "POST",
			url : "/admin/specification/measures",
			data : {
				specification : 			specification,
			}
		})
		.done(
			function(result) {

				$("#measure"+specification).html("");
				$("#measure"+specification).prop("disabled", false);

				if (typeof result === 'object') {
					$.each(result.measures, function( id, measure ) {
					var measureName = "" ;

					measureName += !measure.measureValue ? "" : measure.measureValue+" ";
					measureName += !measure.measureTypeName ? "" : measure.measureTypeName;

					$("#measure"+measure.specification).append("<option value='"+measure.id+"'>"+measureName+"</option>");
					});

				} else {
					console.log("debe seleccionar una specification");
				}
			});
	}
	else
		console.log("error");

}