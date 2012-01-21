function showHiddenDiv (divname) {
	var div = document.getElementById(divname);
	var span = document.getElementById(divname+'_hidden');

	if (span.style.display == "none")	{
		span.style.display = "block";
		div.style.display = "none";
	} else {
		span.style.display = "none";
		div.style.display = "block";
	}
}