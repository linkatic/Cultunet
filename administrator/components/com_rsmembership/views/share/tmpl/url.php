<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
?>

<script type="text/javascript">
function submitbutton()
{
	var table = window.parent.document.getElementById('<?php echo $this->function; ?>');
	if (typeof(table)=='undefined' || table===null)
		return false;
	
	var form = document.adminForm;
	// do field validation
	if (form.params.value.length == 0)
		alert('<?php echo JText::_('RSM_URL_ERROR', true); ?>');
	else
	{
		$('membership_save_button').disabled = true;
		$('membership_save_button2').disabled = true;
		
		<?php if ($this->what == 'membership_id') { ?>
		rsmembership_submit_form_ajax('addmembershipurl');
		<?php } else { ?>
		rsmembership_submit_form_ajax('addextravalueurl');
		<?php } ?>
	}
	
	return false;
}

// submit the form through ajax
function rsmembership_submit_form_ajax(pressbutton)
{
	if (pressbutton)
		document.adminForm.task.value=pressbutton;

	xmlHttp = rsmembership_get_xml_http_object();
	
	var url = document.adminForm.action;
	
	var params = new Array();
	for (i=0; i<document.adminForm.elements.length; i++)
	{
		// don't send an empty value
		if (document.adminForm.elements[i].name.length == 0) continue;
		// check if the checkbox is checked
		if (document.adminForm.elements[i].type == 'checkbox' && document.adminForm.elements[i].checked == false) continue;
		// check if the radio is selected
		if (document.adminForm.elements[i].type == 'radio' && document.adminForm.elements[i].checked == false) continue;
		
		params.push(document.adminForm.elements[i].name + '=' + escape(document.adminForm.elements[i].value));
	}
	
	params = params.join('&');
	
	xmlHttp.open("POST", url, true);

	//Send the proper header information along with the request
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.setRequestHeader("Content-length", params.length);
	xmlHttp.setRequestHeader("Connection", "close");

	xmlHttp.onreadystatechange = function() {//Call a function when the state changes.
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		rsmembership_refresh_results();
	}
	xmlHttp.send(params);
}

// refresh the results from the parent window
function rsmembership_refresh_results()
{
	xmlHttp = rsmembership_get_xml_http_object();
	if (xmlHttp==null)
	{
	  alert ("Browser does not support HTTP Request");
	  return;
	}
	
	var url = 'index.php?option=com_rsmembership&task=ajax_<?php echo $this->function; ?>&cid=<?php echo $this->id; ?>&tmpl=component&format=raw&sid=' + Math.random();
	
	xmlHttp.onreadystatechange = function() {//Call a function when the state changes.
		if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		{
			window.parent.document.getElementById('<?php echo $this->function; ?>_ajax').innerHTML = xmlHttp.responseText;
			window.parent.eval('SqueezeBox.initialize({});	$$(\'a.modal\').each(function(el) {	el.addEvent(\'click\', function(e) {new Event(e).stop();	SqueezeBox.fromElement(el);	});	});');
			window.parent.document.getElementById('sbox-window').close();
		}
	}
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

// XML HTTP Object
function rsmembership_get_xml_http_object()
{
	var xmlHttp=null;
	try
	{
		// Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest();
	}
	catch (e)
	{
		// Internet Explorer
		try
		{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return xmlHttp;
}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&view=share&layout=url&tmpl=component&'.$this->what.'='.$this->id); ?>" method="post" name="adminForm">	
	<button type="button" onclick="document.location = '<?php echo JRoute::_('index.php?option=com_rsmembership&view=share&'.$this->what.'='.$this->id.'&tmpl=component'); ?>'"><?php echo JText::_('Back'); ?></button>
	<button id="membership_save_button" type="button" onclick="submitbutton();"><?php echo $this->row->id > 0 ? JText::_('RSM_UPDATE_URL') : JText::_('RSM_ADD_URL'); ?></button>
	<p><?php echo JText::_('RSM_URL_PLEASE_USE_NON_SEF'); ?></p>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="100"><span class="hasTip" title="<?php echo JText::_('RSM_URL_WHERE_DESC'); ?>"><label for="where"><?php echo JText::_('RSM_URL_WHERE'); ?></label></span></td>
			<td><?php echo $this->lists['where']; ?></td>
		</tr>
		<tr>
			<td width="100"><span class="hasTip" title="<?php echo JText::_('RSM_URL_ADDRESS_DESC'); ?>"><label for="params"><?php echo JText::_('RSM_URL_ADDRESS'); ?></label></span></td>
			<td>index.php?option=<input type="text" name="params" value="<?php echo $this->escape($this->row->params); ?>" id="params" size="90" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="100"><span class="hasTip" title="<?php echo JText::_('FLIPPING_BOOK_ID_DESC'); ?>"><label for="params"><?php echo JText::_('FLIPPING_BOOK_ID'); ?></label></span></td>
			<td><input type="text" name="book_id" value="<?php echo $this->escape($this->row->book_id); ?>" id="book_id" size="90" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="100"><span class="hasTip" title="<?php echo JText::_('PUBLISHED_DESC'); ?>"><label for="published"><?php echo JText::_('PUBLISHED'); ?></label></span></td>
			<td><?php echo $this->lists['published']; ?></td>
		</tr>
	</table>
	
	<button id="membership_save_button2" type="button" onclick="submitbutton();"><?php echo $this->row->id > 0 ? JText::_('RSM_UPDATE_URL') : JText::_('RSM_ADD_URL'); ?></button>
	
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_rsmembership" />
	<input type="hidden" name="controller" value="share" />
	<input type="hidden" name="view" value="share" />
	<input type="hidden" name="layout" value="url" />
	<input type="hidden" name="<?php echo $this->what; ?>" value="<?php echo $this->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="cid" value="<?php echo $this->row->id; ?>" />
	
	<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortOrder; ?>" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>