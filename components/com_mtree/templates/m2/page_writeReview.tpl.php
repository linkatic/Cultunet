<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( 'viewlink' );
			return;
		}

		// do field validation
		if (form.rev_text.value == ""){
			alert( "<?php echo JText::_( 'Please fill in review' ) ?>" );
		} else if (form.rev_title.value == ""){
			alert( "<?php echo JText::_( 'Please fill in title' ) ?>" );
		<?php
		if( 
			$this->config->get('require_rating_with_review')
			&& 
			$this->config->get('allow_rating_during_review') 
			&&
			(
				$this->config->get('user_rating') == '0'
				||
				($this->config->get('user_rating') == '1' && $this->my->id > 0)
				||
				($this->config->get('user_rating') == '2' && $this->my->id > 0 && $this->my->id != $this->link->user_id)
			)
		) {			
			echo '} else if (form.rating.value == ""){ alert("' . JText::_( 'Please fill in rating' ) . '"); ';
		}		
		?>} else {
			form.submit();
		}
	}
</script>
 
<h2 class="contentheading"><?php echo JText::_( 'Write review' ) . ' - ' . $this->link->link_name; ?></h2>

<div id="listing">

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<?php if ( !($this->my->id > 0) ) { ?>
	<tr>
		<td align="left">
			<?php echo JText::_( 'Your name' ) ?>:
		</td>
	</tr>
	<tr>
		<td align="left">
			<input type="text" name="guest_name" class="inputbox" size="20" />
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td align="left">
			<?php echo JText::_( 'Review title' ) ?>:
		</td>
	</tr>
	<tr>
		<td align="left">
			<input type="text" name="rev_title" class="inputbox" size="69" />
		</td>
	</tr>
	<tr>
		<td align="left">
			<?php
			if( 
				$this->config->get('allow_rating_during_review') 
				&&
				(
					$this->config->get('user_rating') == '0'
					||
					($this->config->get('user_rating') == '1' && $this->my->id > 0)
					||
					($this->config->get('user_rating') == '2' && $this->my->id > 0 && $this->my->id != $this->link->user_id)
				)
			) {
			?>
			<select name="rating" class="inputbox">
			<?php
			$options = array(""=>JText::_( 'Select your rating' ), "5"=>JText::_( 'Rating 5' ), "4"=>JText::_( 'Rating 4' ), "3"=>JText::_( 'Rating 3' ), "2"=>JText::_( 'Rating 2' ), "1"=>JText::_( 'Rating 1' ));
			echo $this->plugin( "options", $options, $this->user_rating ); 
			?>
			</select>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td align="left">
			<?php echo JText::_( 'Review' ) ?>:
		</td>
	</tr>
	<tr>
		<td align="left">
			<?php $this->plugin('textarea', 'rev_text', '', 8, 69, 'class="inputbox"'); ?>
			<br /><br />
			<input type="hidden" name="option" value="<?php echo $this->option ?>" />
			<input type="hidden" name="task" value="addreview" />
			<input type="hidden" name="Itemid" value="<?php echo $this->Itemid ?>" />
			<input type="hidden" name="link_id" value="<?php echo $this->link->link_id ?>" />
			<?php echo JHTML::_( 'form.token' ); ?>
			<input type="button" value="<?php echo JText::_( 'Add review' ) ?>" onclick="javascript:submitbutton('addreview')" class="button" /> <input type="button" value="<?php echo JText::_( 'Cancel' ) ?>" onclick="history.back();" class="button" />
		</td>
	</tr>
</table>
</form>

</div>