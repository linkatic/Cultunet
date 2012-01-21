<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			form.task.value='viewlink';
			form.submit();
			return;
		}

	<?php if( $this->user_id <= 0 ) { ?>
		// do field validation
		if (form.your_name.value == ""){
			alert( "<?php echo JText::_( 'Please fill in the form' ) ?>" );
		} else {
	<?php } ?>
			form.task.value=pressbutton;
			try {
				form.onsubmit();
				}
			catch(e){}
			form.submit();
	<?php if( $this->user_id <= 0 ) { ?>
		}
	<?php } ?>
	}
</script>

 
<h2 class="contentheading"><?php echo JText::_( 'Report listing' ) . ' - ' . $this->link->link_name; ?></h2>

<div id="listing">
<form action="<?php echo JRoute::_("index.php") ?>" method="post" name="adminForm" id="adminForm">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<?php if( $this->user_id <= 0 ) { ?>
	<tr>
		<td width="20%"><?php echo JText::_( 'Your name' ) ?>:</td>
		<td width="80%"><input type="text" name="your_name" class="inputbox" size="40" /></td>
	</tr>
	<?php } ?>
	<tr>
		<td><?php echo JText::_( 'Report problem' ) ?>:</td>
		<td>
		  <select name="report_type">
				<option value="1"><?php echo JText::_( 'Report problem 1' ) ?></option>
				<option value="2"><?php echo JText::_( 'Report problem 2' ) ?></option>
				<option value="3"><?php echo JText::_( 'Report problem 3' ) ?></option>
				<option value="4"><?php echo JText::_( 'Report problem 4' ) ?></option>
		  </select>
		</td>
	</tr>
	<tr><td colspan="2"><b><?php echo JText::_( 'Message' ) ?>:</b></td></tr>
	<tr><td colspan="2"><textarea name="message" rows="8" cols="69" class="inputbox"></textarea></td></tr>
	<tr>
		<td colspan="2">
			<input type="hidden" name="option" value="<?php echo $this->option ?>" />
			<input type="hidden" name="task" value="send_report" />
			<input type="hidden" name="link_id" value="<?php echo $this->link->link_id ?>" />
			<input type="hidden" name="Itemid" value="<?php echo $this->Itemid ?>" />
			<input type="hidden" name="<?php echo JUtility::getToken(); ?>" value="1" />
			<input type="button" value="<?php echo JText::_( 'Send' ) ?>" onclick="javascript:submitbutton('send_report')" class="button" /> <input type="button" value="<?php echo JText::_( 'Cancel' ) ?>" onclick="javascript:submitbutton('cancel')" class="button" />
		</td>
	</tr>
</table>
</form>
</div>