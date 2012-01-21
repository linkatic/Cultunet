/**
 * CSV Improved JavaScript
 *
 * CSV Improved
 * Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 *
 * @package CSV Improved
 * @version $Id: csvi.js 903 2009-05-14 15:47:24Z Roland $
 */

/**
 * Change the display of a block
 */
function expand_layer(sid,show) {
	var sbox = document.getElementById(sid);
	var sbox_style = sbox.style;
	if (show===true) {
		sbox_style.display='block';
	}
	else if (show===false) {
		sbox_style.display='none';
	}
	else {
		if ((sbox_style.display=='none')||(sbox_style.display=='')) {
			sbox_style.display='block';
		}
		else {
			sbox_style.display='none';
		}
	}
	return false;
}
/**
 * Change the colour of a specified element
 */
function ChangeClass(id, newClass) {
	identity=document.getElementById(id);
	identity.className=newClass;
}

function CheckFieldOrder(from) {
	if (from.value == 0) {
		alert('Field value order should be greater than 0.');
		// fieldname = 'field[0>][_ordering]';
		// document.adminForm.fieldname.focus();
		document.adminForm.elements['field\[0\>\]\[_order\]'].focus();
		return false;
	}
	else return true;
}

function switchMenu(obj) {
	var el = document.getElementById(obj);
	if ( el.style.display == 'none' || el.style.display == '') {
		el.style.display = 'block';
	}
	else {
		el.style.display = 'none';
	}
}

function switchRow(obj) {
	if (obj == 'all') {
		jQuery('table#templates tbody tr').each(function() {
			jQuery(this).show();
		})
	}
	else {
		jQuery('table#templates tbody tr').each(function() {
			if (this.id != obj) {
				jQuery(this).hide();
			}
		})
	}
}

function showExportDiv(obj) {
	if (typeof(obj) == 'undefined') {
		jQuery("[id$='export']").each(function(i) {
			jQuery(this).remove();

		})
	}
	else if (jQuery("#"+obj).length == 0 ) {
		jQuery.ajax({
			url: "index.php?option=com_csvimproved&controller=export&task=export&format=ajax&choice="+obj,
			cache: false,
			success: function(html){
				jQuery("#extraoptions").append(html);
			}
		})
	}
}

var xmlHttp

function ShowTemplateDetails(templateid, rpc_call, target, csvi_type) {
	RpcCall("templateid="+templateid.value+"&rpc_call="+rpc_call+"&csvi_type="+csvi_type, target);
}

function AddTemplateField(target) {
	var fieldorder = document.getElementById('newfield_fieldorder');
	var fieldname = document.getElementById('newfieldname');
	var columnheader = document.getElementById('newfield_columnheader');
	var defaultvalue = document.getElementById('newfield_defaultvalue');
	var poststr = 'field_order='+fieldorder.firstChild.value;
	poststr += '&field_name='+fieldname.options[fieldname.selectedIndex].value;
	if (columnheader != null) poststr += '&column_header='+columnheader.firstChild.value;
	poststr += '&default_value='+defaultvalue.firstChild.value;
	poststr += '&templateid='+document.adminForm.templateid.value;
	poststr += '&limitstart='+document.adminForm.limitstart.value;
	poststr += '&task=saveconfigfield';
	poststr += '&setpublished=1';
	RpcCall(poststr+"&rpc_call=addtemplatefield", target);
}

function DeleteTemplateField(fieldid, trid, target) {
	poststr = 'field_id='+fieldid;
	poststr += '&templateid='+document.adminForm.templateid.value;
	poststr += '&limitstart='+document.adminForm.limitstart.value;
	poststr += '&task=configdelete';
	RpcCall(poststr+"&rpc_call=deletetemplatefield", target);
}

function TemplateConfig(type, template_id, state, target) {
	var url='controller=templates&task=getimexsettings&type='+type;
	url += '&template_id='+template_id;
	url += '&state='+state;
	RpcCall(url, target);
}

function RpcCall(extra_params, target, async) {
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) {
	  alert ("Your browser does not support AJAX!");
	  return;
	}
	
	/* POST */
	var url="index.php";
	var params = "option=com_csvimproved&format=raw&"+extra_params+"&sid="+Math.random();
	
	/**
	 * Asynchronous request or Synchronous request
	 * true = asynchronous
	 * false = synchronous
	*/
	if (async == null) var async = true;
	xmlHttp.open("POST",url,async);
	
	/* Send the proper header information along with the request */
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.setRequestHeader("Content-length", params.length);
	xmlHttp.setRequestHeader("Connection", "close");
	xmlHttp.onreadystatechange = function() {
		if (xmlHttp.readyState == 4) {
			document.getElementById(target).innerHTML=xmlHttp.responseText;
		}
	}
	xmlHttp.send(params);
}

/* Create an XmlHttpObject */
function GetXmlHttpObject() {
	var xmlHttp=null;
	try {
	  // Firefox, Opera 8.0+, Safari
	  xmlHttp=new XMLHttpRequest();
	}
	catch (e) {
	  // Internet Explorer
	  try {
		xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
	  }
	  catch (e) {
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	}
	return xmlHttp;
}

function getFormValues(fobj){
	var str = "";
	for(var i = 0;i < fobj.elements.length;i++) {
		switch(fobj.elements[i].type){
			case "text":
			case "textarea":
			case "password":
				if (!fobj.elements[i].disabled) str += fobj.elements[i].name + "=" + encodeURIComponent(fobj.elements[i].value) + "&";
				break;
			case "hidden":
				//hidden cannot be disabled
				str += fobj.elements[i].name + "=" + encodeURIComponent(fobj.elements[i].value) + "&";
				break;
			case "checkbox":
			case "radio":
				if(fobj.elements[i].checked && !fobj.elements[i].disabled) str += fobj.elements[i].name + "=" + encodeURIComponent(fobj.elements[i].value) + "&";
				break;
			case "select-one":
				if (!fobj.elements[i].disabled) str += fobj.elements[i].name + "=" + encodeURIComponent(fobj.elements[i].options[fobj.elements[i].selectedIndex].value) + "&";
				break;
			case "select-multiple":
				if (!fobj.elements[i].disabled){
					for (var j = 0; j < fobj.elements[i].length; j++){
						var optElem = fobj.elements[i].options[j];
							if (optElem.selected == true){
								str += fobj.elements[i].name + "[]" + "=" + encodeURIComponent(optElem.value) + "&";
							}
						}
					}
				break;
		}
	}
	//Strip final &amp;
	str = str.substr(0,(str.length - 1));
	return str;
}

var _timeout = null;
var notallowedkeys = [9, 10, 16, 17, 18, 20, 27, 37, 38, 39, 40, 92, 93];
jQuery("#searchuser").live('keyup', function(e) {
	if (jQuery.inArray(e.keyCode, notallowedkeys) >= 0) {
		return;
	}
	else {
		if(_timeout != null) { 
			clearTimeout(_timeout); _timeout = null; 
		} 
		_timeout = setTimeout('searchUser()', 1000);
	}
})

function searchUser() {
	_timeout = null;
	jQuery("#selectuserid tbody").remove();
	jQuery("#selectuserid").append('<tbody><tr><td colspan="2"><div id="ajaxuserloading"><img src="/administrator/components/com_csvimproved/assets/images/ajax-loading.gif" /></div></td></tr></tbody>');
	var searchfilter = jQuery("input[name='searchuserbox']").val();
	jQuery.getJSON("index.php",{option: 'com_csvimproved', controller: 'export', task: 'getuser',  format: 'json', filter: searchfilter}, function(data){
		jQuery("#ajaxuserloading").remove();
		jQuery("#selectuserid tbody").remove();
		var options = [];
		var r = 0;
		options[++r] = '<tbody>';
		if (data.length > 0) {
			for (var i = 0; i < data.length; i++) {
				options[++r] = '<tr><td class="user_id">';
				options[++r] = data[i].user_id;
				options[++r] = '</td><td class="user_name">';
				options[++r] = data[i].user_name;
				options[++r] = '</td></tr>';
			}
		}
		options[++r] = '</tbody>';
		jQuery("#selectuserid").append(options.join(''));
		jQuery("table#selectuserid tr:even").addClass("row0");
		jQuery("table#selectuserid tr:odd").addClass("row1");
		jQuery('table#selectuserid tbody tr').click(function() {
			var user_id = jQuery(this).find('td.user_id').html();
			/* Check if the user ID is already in the select box */
			var existingvals = jQuery("select#orderuser").val();
			if (jQuery.inArray(user_id, existingvals) >= 0) {
				return;
			}
			else {
				var options = '<option value="'+user_id+'" selected="selected">'+jQuery(this).find('td.user_name').html()+'</option>';
				jQuery("select#orderuser").append(options);
				jQuery("select#orderuser option:eq(0)").attr("selected", "");
			}
		});
	})
}

jQuery("#searchproduct").live('keyup', function(e) {
	if (jQuery.inArray(e.keyCode, notallowedkeys) >= 0) {
		return;
	}
	else {
		if(_timeout != null) { 
			clearTimeout(_timeout); _timeout = null; 
		} 
		_timeout = setTimeout('searchProduct()', 1000);
	}
})

function searchProduct() {
	_timeout = null;
	jQuery("#selectproductsku tbody").remove();
	jQuery("#selectproductsku").append('<tbody><tr><td colspan="2"><div id="ajaxproductloading"><img src="/administrator/components/com_csvimproved/assets/images/ajax-loading.gif" /></div></td></tr></tbody>');
	var searchfilter = jQuery("input[name='searchproductbox']").val();
	jQuery.getJSON("index.php",{option: 'com_csvimproved', controller: 'export', task: 'getproduct',  format: 'json', filter: searchfilter}, function(data){
		jQuery("#ajaxproductloading").remove();
		jQuery("#selectproductsku tbody").remove();
		var options = [];
		var r = 0;
		options[++r] = '<tbody>';
		if (data.length > 0) {
			for (var i = 0; i < data.length; i++) {
				options[++r] = '<tr><td class="product_sku">';
				options[++r] = data[i].product_sku;
				options[++r] = '</td><td class="product_name">';
				options[++r] = data[i].product_name;
				options[++r] = '</td></tr>';
			}
		}
		options[++r] = '</tbody>';
		jQuery("#selectproductsku").append(options.join(''));
		jQuery("table#selectproductsku tr:even").addClass("row0");
		jQuery("table#selectproductsku tr:odd").addClass("row1");
		jQuery('table#selectproductsku tbody tr').click(function() {
			var product_sku = jQuery(this).find('td.product_sku').html();
			/* Check if the product ID is already in the select box */
			var existingvals = jQuery("select#orderproduct").val();
			if (jQuery.inArray(product_sku, existingvals) >= 0) {
				return;
			}
			else {
				var options = '<option value="'+product_sku+'" selected="selected">'+jQuery(this).find('td.product_name').html()+'</option>';
				jQuery("select#orderproduct").append(options);
				jQuery("select#orderproduct option:eq(0)").attr("selected", "");
			}
		});
	})
}

function AddProductNameField(text, textmax, cursize) {
	if (jQuery("#productnamefieldlength").size() == 0) {
		jQuery("#resizeproducttitle").after('<tr><td>'+text+'&nbsp;<input type="text" id="productnamefieldlength" name="productnamefieldlength" value="" />&nbsp;<span id="productnamemsg"></div><br />'+cursize+'</td></tr>');
		jQuery('#productnamefieldlength').numeric();
		jQuery("#productnamefieldlength").live('keyup', function() {
			if (jQuery("#productnamefieldlength").val() > 255) {
				jQuery("#productnamefieldlength").val('255');
				jQuery("#productnamemsg").html(textmax);
			}
			else {
				jQuery("#productnamemsg").html('');
			}
		});
	}
}
