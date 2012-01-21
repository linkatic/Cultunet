<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			form.task.value='viewlink';
			form.submit();
			return;
		}

		// do field validation
		if (form.your_name.value == ""){
			alert( "<?php echo JText::_( 'Please fill in the form' ) ?>" );
		} else if (form.your_email.value == ""){
			alert( "<?php echo JText::_( 'Please fill in the form' ) ?>" );
		} else {
			form.task.value=pressbutton;
			try {
				form.onsubmit();
				}
			catch(e){}
			form.submit();
		}
	}
</script>
 
<h2 class="contentheading"><?php echo JText::_( 'Contact owner' ) . ' - ' . $this->link->link_name; ?></h2>

<div id="listing">

<form action="<?php echo JRoute::_("index.php") ?>" method="post" name="adminForm" id="adminForm">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr>
		<td colspan="2">
			<b><?php echo JText::_( 'From' ) ?>:</b>
		</td>
	</tr>
	<tr>
		<td width="20%"><?php echo JText::_( 'Your name' ) ?>:</td>
		<td width="80%"><input type="text" name="your_name" class="inputbox" size="40" value="<?php echo ($this->my->id) ? $this->my->name : ''; ?>" /></td>
	</tr>
	<tr>
		<td><?php echo JText::_( 'Your email' ) ?>:</td>
		<td><input type="text" name="your_email" class="inputbox" size="40" value="<?php echo ($this->my->id) ? $this->my->email : ''; ?>" /></td>
	</tr>
	<tr><td colspan="2"><b><?php echo JText::_( 'Message' ) ?>:</b></td></tr>
	<tr><td colspan="2"><textarea name="message" rows="8" cols="69" class="inputbox"></textarea></td></tr>
	<tr>
		<td colspan="2">
			<input type="hidden" name="option" value="<?php echo $this->option ?>" />
			<input type="hidden" name="task" value="send_contact" />
			<input type="hidden" name="link_id" value="<?php echo $this->link->link_id ?>" />
			<input type="hidden" name="Itemid" value="<?php echo $this->Itemid ?>" />
			<?php echo JHTML::_( 'form.token' ); ?>
			<input type="button" value="<?php echo JText::_( 'Send' ) ?>" onclick="javascript:submitbutton('send_contact')" class="button" /> <input type="button" value="<?php echo JText::_( 'Cancel' ) ?>" onclick="javascript:submitbutton('cancel')" class="button" />
		</td>
	</tr>
</table>
</form>
</div>