/**
 * @author Fahrettin Kutyol - mad4media
 */
function m4j_submit(button)
	{
	 document.m4jForm.task.value=button;
	 try 
	 {
		 document.m4jForm.onsubmit();
	 }
	 catch(e){}
	 document.m4jForm.submit();
	}

function confirm_delete(text,url)
	{
		var x = window.confirm(text);
		if(x) window.location.href = url;
	}

function rowOut(object,even) 
	{
		if(even) object.className = 'even';
		else object.className = 'odd';	
	}

function rowOver(object) 
	{
		object.className = 'highlight';
	}

function jump(object,url)
	{
		url = url+object.value;	
		window.location.href = url;	
	}
	
function trim (string) 
	{

  return string.replace (/^\s+/, '').replace (/\s+$/, '');
	}
	
function append(id,value)
	{
		
		if(trim(document.getElementById(id).value) !="")  value = ",\n" + value;
		document.getElementById(id).value =  trim(document.getElementById(id).value) + value;
		
		
	}