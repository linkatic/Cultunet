<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
$token = JHTML::_('form.token');
?>
<script type="text/javascript">
function submitbutton(pressbutton)
{
	var table = window.parent.document.getElementById('<?php echo $this->function; ?>');
	if (typeof(table)=='undefined' || table===null)
		return false;
	
	<?php echo $this->editor->save('notes'); ?>
	
	rsmembership_submit_form_ajax(pressbutton);
	$('membership_save_button').disabled = true;
	
	return false;
}

// submit the form through ajax
function rsmembership_submit_form_ajax(pressbutton)
{
	if (pressbutton)
		document.adminForm.task.value=pressbutton;

	xmlHttp = rsmembership_get_xml_http_object();
	
	var url = 'index.php';
	
	var params = new Array();
	for (i=0; i<document.adminForm.elements.length; i++)
	{
		// don't send an empty value
		if (document.adminForm.elements[i].name.length == 0) continue;
		// check if the checkbox is checked
		if (document.adminForm.elements[i].type == 'checkbox' && document.adminForm.elements[i].checked == false) continue;
		// check if the radio is selected
		if (document.adminForm.elements[i].type == 'radio' && document.adminForm.elements[i].checked == false) continue;
		
		// check if this is a dropdown with multiple selections
		if (document.adminForm.elements[i].type == 'select-multiple')
		{
			for (var j=0; j<document.adminForm.elements[i].options.length; j++)
				if (document.adminForm.elements[i].options[j].selected)
					params.push(document.adminForm.elements[i].name + '=' + document.adminForm.elements[i].options[j].value);
			
			continue;
		}
		
		params.push(document.adminForm.elements[i].name + '=' + document.adminForm.elements[i].value);
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
	
	var url = 'index.php?option=com_rsmembership&task=ajax_<?php echo $this->function; ?>&cid=<?php echo $this->row->user_id; ?>&tmpl=component&format=raw&sid=' + Math.random();
	xmlHttp.onreadystatechange = function() {//Call a function when the state changes.
		if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		{
			window.parent.document.getElementById('<?php echo $this->function ?>_ajax').innerHTML = xmlHttp.responseText;
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

function rsmembership_change_unlimited(what)
{
	if (what.checked)
	{
		$('membership_end').disabled = true;
		$('membership_end_img').style.display = 'none';
		$('membership_end_ajax').style.display = 'none';
	}
	else
	{
		$('membership_end').disabled = false;
		$('membership_end_img').style.display = '';
		$('membership_end_ajax').style.display = '';
	}
}

function rsmembership_change_noextra(what)
{
	if (what.checked)
		$('extras').disabled = true;
	else
		$('extras').disabled = false;
}

function rsmembership_date(time, container)
{
	xmlHttp = rsmembership_get_xml_http_object();
	if (xmlHttp==null)
	{
	  alert ("Browser does not support HTTP Request");
	  return;
	}
	
	var url = 'index.php?option=com_rsmembership&task=ajax_date&date=' + time + '&tmpl=component&format=raw&sid=' + Math.random();
	xmlHttp.onreadystatechange = function() {//Call a function when the state changes.
		if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
			$(container + '_ajax').innerHTML = xmlHttp.responseText;
	}
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
</script>

<button id="membership_save_button" type="button" onclick="submitbutton('membershipsave')"><?php echo JText::_('SAVE'); ?></button>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=users&task=editmembership&tmpl=component&cid='.$this->row->user_id); ?>" method="post" name="adminForm" id="adminForm">
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('Email'); ?>"><label for="user_id"><?php echo JText::_('Email'); ?></label></span></td>
			<td><a class="modal" id="email" href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=allusers&tmpl=component'); ?>" rel="{handler: 'iframe', size: {x: 560, y: 375}}"><?php echo $this->row->user->email; ?></a></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_DESC'); ?>"><label for="membership_id"><?php echo JText::_('RSM_MEMBERSHIP'); ?></label></span></td>
			<td><?php echo $this->lists['membership']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_EXTRA_DESC'); ?>"><label for="extras"><?php echo JText::_('RSM_EXTRA'); ?></label></span></td>
			<td><?php echo $this->lists['extras']; ?>
			<input type="checkbox" name="noextra" id="change_noextra" value="1" onchange="rsmembership_change_noextra(this);" <?php echo $this->row->noextra ? 'checked="checked"' : ''; ?> /> <?php echo JText::_('RSM_NO_EXTRA'); ?>
			</td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_STATUS_DESC'); ?>"><label for="rsm_status"><?php echo JText::_('RSM_MEMBERSHIP_STATUS'); ?></label></span></td>
			<td><?php echo $this->lists['status']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_START_DESC'); ?>"><label for="membership_start"><?php echo JText::_('RSM_MEMBERSHIP_START'); ?></label></span></td>
			<td><span id="membership_start_ajax"><?php echo date(RSMembershipHelper::getConfig('date_format'), $this->row->membership_start); ?></span> <?php echo JHTML::calendar($this->row->membership_start, 'membership_start', 'membership_start', '%s', 'onchange="rsmembership_date(this.value,\'membership_start\')" style="display: none"'); ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_END_DESC'); ?>"><label for="membership_end"><?php echo JText::_('RSM_MEMBERSHIP_END'); ?></label></span></td>
			<td><span id="membership_end_ajax"><?php echo date(RSMembershipHelper::getConfig('date_format'), $this->row->membership_end); ?></span> <?php echo JHTML::calendar($this->row->membership_end, 'membership_end', 'membership_end', '%s', 'onchange="rsmembership_date(this.value,\'membership_end\')" style="display: none"'); ?>
			<input type="checkbox" name="unlimited" id="change_unlimited" value="1" onchange="rsmembership_change_unlimited(this);" <?php echo $this->row->membership_end == 0 ? 'checked="checked"' : ''; ?> /> <?php echo JText::_('RSM_UNLIMITED'); ?>
			</td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_PRICE_DESC'); ?>"><label for="price"><?php echo JText::_('RSM_MEMBERSHIP_PRICE'); ?></label></span></td>
			<td><input type="text" name="price" value="<?php echo $this->row->price; ?>" id="price" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_CURRENCY_DESC'); ?>"><label for="currency"><?php echo JText::_('RSM_MEMBERSHIP_CURRENCY'); ?></label></span></td>
			<td><input type="text" name="currency" value="<?php echo $this->escape($this->currency); ?>" id="currency" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200" valign="top"><span class="hasTip" title="<?php echo JText::_('RSM_NOTES_DESC'); ?>"><label for="description"><?php echo JText::_('RSM_NOTES'); ?></label></span></td>
			<td><?php echo $this->editor->display('notes',$this->row->notes,500,250,70,10); ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('PUBLISHED_DESC'); ?>"><label for="published"><?php echo JText::_('PUBLISHED'); ?></label></span></td>
			<td><?php echo $this->lists['published']; ?></td>
		</tr>
	</table>

<?php echo $token; ?>
<input type="hidden" name="option" value="com_rsmembership" />
<input type="hidden" name="controller" value="users" />
<input type="hidden" name="task" value="editmembership" />
<input type="hidden" name="view" value="users" />
<input type="hidden" name="tmpl" value="component" />
<input type="hidden" name="format" value="raw" />

<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="user_id" value="<?php echo $this->row->user_id; ?>" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>

<script type="text/javascript">
rsmembership_change_unlimited($('change_unlimited'));
rsmembership_change_noextra($('change_noextra'));
</script>