<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<?php if ($this->params->show_add) { ?>
<script type="text/javascript">
function submitbutton()
{
	var table = window.parent.document.getElementById('<?php echo $this->function; ?>');
	if (typeof(table)=='undefined' || table===null)
		return false;
		
	for (var i=<?php echo $this->start; ?>; i<<?php echo $this->count; ?>; i++)
	{
		var cb = document.getElementById('cb'+i);
		
		<?php if ($this->task == 'addfolder') { ?>
		var item = cb.value + '<?php echo DS == '\\' ? DS.DS : DS; ?>';
		<?php } elseif ($this->task == 'addfile') { ?>
		var item = cb.value;
		<?php } ?>
		
		if (cb.checked)
		{
			if (table.innerHTML.indexOf('<td align="center">' + item + '</td>') != -1)
				alert('<?php echo JText::_('RSM_MEMBERSHIP_PATH_ALREADY'); ?>' + ' ' + item);
		}
	}
	
	<?php if ($this->params->show_add) { ?>
	$('folder_add_button').disabled = true;
	<?php } ?>
	
	rsmembership_submit_form_ajax('<?php echo $this->function; ?>');
	
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
	
	<?php if (!empty($this->membership_id)) { ?>
	var url = 'index.php?option=com_rsmembership&task=ajax_<?php echo $this->function; ?>&cid=<?php echo $this->membership_id; ?>&tmpl=component&format=raw&sid=' + Math.random();
	<?php } elseif (!empty($this->extra_value_id)) { ?>
	var url = 'index.php?option=com_rsmembership&task=ajax_<?php echo $this->function; ?>&cid=<?php echo $this->extra_value_id; ?>&tmpl=component&format=raw&sid=' + Math.random();
	<?php } ?>
	xmlHttp.onreadystatechange = function() {//Call a function when the state changes.
		if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		{
			window.parent.document.getElementById('<?php echo $this->function ?>_ajax').innerHTML = xmlHttp.responseText;
			// window.top.setTimeout('window.parent.document.getElementById(\'sbox-window\').close()', 500);
			window.parent.document.getElementById('sbox-window').close();
		}
	}
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function rsmembership_check_state_changed() 
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{
		document.getElementById('<?php echo $this->function ?>_ajax').innerHTML = xmlHttp.responseText;
		// window.top.setTimeout('window.parent.document.getElementById(\'sbox-window\').close()', 500);
		window.parent.document.getElementById('sbox-window').close();
	}
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
<?php } ?>

<div id="rsmembership_explorer">

<form action="<?php echo JRoute::_($this->link); ?>" method="post" name="adminForm" enctype="multipart/form-data">
<?php if (!empty($this->function) && $this->function == 'addmembershipfolders') { ?>
<button type="button" onclick="document.location = '<?php echo JRoute::_('index.php?option=com_rsmembership&view=share&membership_id='.$this->membership_id.'&tmpl=component'); ?>'"><?php echo JText::_('Back'); ?></button>
<?php } ?>
<?php if ($this->params->show_add) { ?>
<button id="folder_add_button" type="button" onclick="if(document.adminForm.boxchecked.value==0){alert('<?php echo JText::sprintf('Please make a selection from the list to', 'add'); ?>');}else{submitbutton()}"><?php echo JText::_('RSM_ADD_SELECTED_ITEMS'); ?></button>
<?php } ?>
	<?php if ($this->params->show_upload) { ?>
	<?php if ($this->canUpload) { ?>
	<table class="adminform">
	<tr>
		<th colspan="2"><?php echo JText::_('Upload File'); ?></th>
	</tr>
	<tr>
		<td width="120">
			<label for="upload"><?php echo JText::_('File'); ?>:</label>
		</td>
		<td>
			<input class="input_box" id="upload" name="upload" type="file" size="57" />
			<input class="button" type="button" value="<?php echo JText::_('Upload File'); ?>" onclick="submitbutton('upload')" />
		</td>
	</tr>
	</table>
	<?php } else { ?>
	<?php echo JText::_('RSM_CANT_UPLOAD'); ?>
	<?php } ?>
	<?php } ?>
	
	<div id="editcell1">
		<table class="adminlist">
			<thead>
			<tr>
				<th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $this->count; ?>);"/></th>
				<th><strong><?php echo JText::_('RSM_CURRENT_LOCATION'); ?></strong>
	<?php foreach ($this->elements as $element) { ?>
	<a href="<?php echo JRoute::_($this->link.'&folder='.urlencode($element->fullpath)); ?>"><?php echo $element->name; ?></a> <?php echo DS; ?>
	<?php } ?>
	<?php if ($this->params->show_new_dir) { ?>
	<input type="text" name="dirname" value="" />
	<button type="button" onclick="if (document.adminForm.dirname.value.length > 0) submitbutton('newdir'); else alert('<?php echo JText::_('RSM_DIRECTORY_NAME_ERROR'); ?>');"><?php echo JText::_('RSM_NEW_DIRECTORY'); ?></button>
	<?php } ?>
	</th>
			</tr>
			</thead>
			<tr>
				<td>&nbsp;</td>
				<td><a class="folder" href="<?php echo JRoute::_($this->link.'&folder='.urlencode($this->previous)); ?>">..<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/up.gif', JText::_('RSM_BACK')); ?></a></td>
			</tr>
	<?php
	$j = 0;
	foreach ($this->folders as $folder)
	{
	?>
		<tr>
			<?php if ($this->params->show_folders) { ?>
			<td><?php echo JHTML::_('grid.id', $j, $folder->fullpath); ?></td>
			<?php } else { ?>
			<td>&nbsp;</td>
			<?php } ?>
			<td><a class="folder" href="<?php echo JRoute::_($this->link.'&folder='.urlencode($folder->fullpath)); ?>"><?php echo $folder->name; ?></a> <a href="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=files&task=edit&cid='.urlencode($folder->fullpath.DS)); ?>"><?php if ($this->params->show_edit) { ?>[<?php echo JText::_('Edit'); ?>]</a><?php } ?></td>
		</tr>
	<?php
		$j++;
	}
	
	if ($this->params->show_files) {
		$i = $j;
		foreach ($this->files as $file)
		{
		?>
			<tr>
				<td><?php echo JHTML::_('grid.id', $i, $file->fullpath); ?></td>
				<td><a class="file" href="<?php echo $this->params->show_edit ? JRoute::_('index.php?option=com_rsmembership&controller=files&task=edit&cid='.urlencode($file->fullpath)) : 'javascript: void(0)'; ?>"><?php echo $file->name; ?></a> <a href="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=files&task=edit&cid='.urlencode($file->fullpath)); ?>"><?php if ($this->params->show_edit) { ?>[<?php echo JText::_('Edit'); ?>]</a><?php } ?></td>
			</tr>
		<?php
			$i++;
		}
	}
	?>
		</table>
	</div>
	
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_rsmembership" />
	<input type="hidden" name="view" value="files" />
	<input type="hidden" name="controller" value="files" />
	<input type="hidden" name="folder" value="<?php echo $this->current; ?>" />
	<?php if (!empty($this->membership_id)) { ?>
	<input type="hidden" name="membership_id" value="<?php echo $this->membership_id; ?>" />
	<?php } ?>
	<?php if (!empty($this->extra_value_id)) { ?>
	<input type="hidden" name="extra_value_id" value="<?php echo $this->extra_value_id; ?>" />
	<?php } ?>
	<input type="hidden" name="function" value="<?php echo $this->function; ?>" />
	<input type="hidden" name="task" value="" />
</form>

</div>