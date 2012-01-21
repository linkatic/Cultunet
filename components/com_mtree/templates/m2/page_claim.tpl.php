<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( 'viewlink' );
			return;
		} else {
			form.submit();
		}
	}
</script>
 
<h2 class="contentheading"><?php echo JText::_( 'Claim listing' ) . ' - ' . $this->link->link_name; ?></h2>

<div id="listing">

<form action="<?php echo JRoute::_("index.php") ?>" method="post" name="adminForm" id="adminForm">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr><td><b><?php echo JText::_( 'Message' ) ?>:</b></td></tr>
	<tr><td><textarea name="message" rows="8" cols="69" class="inputbox"></textarea></td></tr>
	<tr>
		<td align="left">
			<input type="hidden" name="option" value="<?php echo $this->option ?>" />
			<input type="hidden" name="task" value="send_claim" />
			<input type="hidden" name="Itemid" value="<?php echo $this->Itemid ?>" />
			<input type="hidden" name="link_id" value="<?php echo $this->link->link_id ?>" />
			<?php echo JHTML::_( 'form.token' ); ?>
			<input type="button" value="<?php echo JText::_( 'Claim listing' ) ?>" onclick="javascript:submitbutton('send_claim')" class="button" /> <input type="button" value="<?php echo JText::_( 'Cancel' ) ?>" onclick="javascript:submitbutton('cancel')" class="button" />
		</td>
	</tr>
</table>
</form>

</div>