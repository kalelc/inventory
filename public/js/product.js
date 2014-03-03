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
					console.log(result);
					if (result.response > 0) {
						$("#button_alert_delete").removeAttr('style');

						$('input[name=csrf]').val(result.csrf);
						$("#list_alerts").append("<li><input type='checkbox' name='tags[]' value='"+result.alertId+"'><label>"+result.tag+"</label></li>");
						$("#alert_search").val("");
						$("#alert_result").html("");

					} else {
						$("#num_alerts").html(result.total_tags);
					}
					$("#message_alert").html(result.message);

					$("input[type=checkbox]").click(function() {
						$.fn.checkTagSelect();
					});
				});
		}
	}