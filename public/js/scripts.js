$(document).ready(function() {
	//number format
	/*$(".number-format").blur(function() {
		var value = $(this).val();
		NumberFormat.setValue(value);
		NumberFormat.numericFormat();
		$(this).val(NumberFormat.getValue());
	});*/

$( "#sortable" ).sortable({
	stop: function( event, ui ) {
	}
});
$( "#sortable" ).disableSelection();

/*category*/
	$('#tab-category').show();
	$('#tab-specification').hide();

	$('#tabs-link-category').click(function(){
		$('#tab-category').show();
		$('#tab-specification').hide();
		$("ul.nav-tabs li").eq(0).addClass("active");
		$("ul.nav-tabs li").eq(1).removeClass("active");
	});
	$("#tabs-link-specification").click(function(){
		$('#tab-specification').show();
		$('#tab-category').hide();
		$("ul.nav-tabs li").eq(0).removeClass("active");
		$("ul.nav-tabs li").eq(1).addClass("active");
	});
	/*product*/
	$('#tab-product').show();
	$('#tab-product-files').hide();

	$('#tabs-link-product').click(function(){
		$('#tab-product').show();
		$('#tab-product-files').hide();
		$("ul.nav-tabs li").eq(0).addClass("active");
		$("ul.nav-tabs li").eq(1).removeClass("active");
	});
	$("#tabs-link-product-files").click(function(){
		$('#tab-product-files').show();
		$('#tab-product').hide();
		$("ul.nav-tabs li").eq(0).removeClass("active");
		$("ul.nav-tabs li").eq(1).addClass("active");
	});
		$("#tabs-link-product-specifications").click(function(){
		$('#tab-product-files').hide();
		$('#tab-product').hide();
		$("ul.nav-tabs li").eq(0).removeClass("active");
		$("ul.nav-tabs li").eq(1).removeClass("active");
	});
});