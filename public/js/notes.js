$(document).ready(function() {
	$("#layout_add_note").hide(); 
	$("#add_note").click(function() {
		$("#layout_add_note").toggle( function() {
			$("#add_note label span").html("");
			if($("#layout_add_note").is(":visible")) {
				$("#add_note span").removeClass("glyphicon-plus-sign");
				$("#add_note span").addClass("glyphicon-minus");
			}
			else {
				$("#add_note span").removeClass("glyphicon-minus");
				$("#add_note span").addClass("glyphicon-plus-sign");
			}
		}); 
	});

	$("#save_note").click(function() {
		$.fn.saveNote();
	});
	$(".delete_note").click(function() {
		$.fn.deleteNote($(this).attr('noteValue'));
	});
});

$.fn.saveNote = function() {
	
	var title = $("#title").val();
	var content = $("#content").val();

	var messageError = "<div class='alert alert-danger' role='alert'><button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>×</span><span class='sr-only'>Close</span></button>Los campos no pueden estar vacios.</div>";

	if (title !== '' && content !== '') {
		$.ajax({
			type : "POST",
			url : "/admin/note/add",
			data : {
				title : 			title,
				content : 			content,
			}
		})
		.done(
			function(result) {
				var contentHtml = "" ;
				
				$("#title").val("");
				$("#content").val("");

				if (typeof result === 'object') {
					noteId = result.result;

					contentHtml += "<div class='panel panel-default' id='panel_note_"+noteId+"'>";
					contentHtml += "<div class='panel-heading'>" ;
					contentHtml += "<h4 class='panel-title'>";
					contentHtml += "<a data-toggle='collapse' data-parent='#accordion' href='#note"+noteId+"'>"+title+"</a>" ;
					contentHtml += "<div class='pull-right'>" ;
					contentHtml += "</div>";
					contentHtml += "</h4>";
					contentHtml += "</div>";
					contentHtml += "<div id='note"+noteId+"' class='panel-collapse collapse'>";
					contentHtml += "<div class='panel-body'>"+content+"</div>";
					contentHtml += "</div>";
					contentHtml += "</div>";

					$("#accordion").append(contentHtml);
				} else {
					$(".message_note").html(messageError);
					
				}
			});
}
else
	$(".error_note").html(messageError);

}

$.fn.deleteNote = function(id) {
	
	var id = id;
	var messageSucccess = "<div class='alert alert-success' role='alert'><button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>×</span><span class='sr-only'>Close</span></button>La nota ha sido eliminada.</div>";
	var messageError = "<div class='alert alert-danger' role='alert'><button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>×</span><span class='sr-only'>Close</span></button>Error al eliminar la nota.</div>";

	if (title !== '' && content !== '') {
		$.ajax({
			type : "POST",
			url : "/admin/note/delete",
			data : {
				id : 			id,
			}
		})
		.done(
			function(result) {
				if (typeof result === 'object') {
					console.log(id);
					$("#panel_note_"+id).remove();
					$(".message_note").html(messageSucccess);
				} else {
					$(".message_note").html(messageError);
				}
			});
	}
	else
		$(".error_note").html(messageError);
}