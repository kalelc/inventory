NumberFormat = (function(val)
{
	var val ;
	var separate ;

	function clearFormat() {

		var valSplit = this.getValue().split(this.getSeparate());
		var result = "";
		for(i = 0 ; i < valSplit.length ; i++) {
			result += valSplit[i] ;
		}

		this.setValue(result);
		return result ;
	}
	function numericFormat() {
		var result = String(this.getValue()).split("").reverse().join("")
		.replace(/(\d{3}\B)/g, "$1"+this.getSeparate()+"")
		.split("").reverse().join("");
		this.setValue(result);
		return result ;
	}
	function setValue(val) {

		this.val = val.replace(/\s+/g, '');
	}
	function getValue() {

		return this.val ;
	}
	function setSeparate(separate) {

		this.separate = separate ;
	}
	function getSeparate() {
		if(typeof(this.separate) === "undefined") 
			this.separate = '.' ;
		return this.separate ;
	}
	return {
		clearFormat : clearFormat ,
		numericFormat : numericFormat ,
		setSeparate : setSeparate ,
		getSeparate : getSeparate ,
		setValue : setValue,
		getValue : getValue
	} ;
}()) ;