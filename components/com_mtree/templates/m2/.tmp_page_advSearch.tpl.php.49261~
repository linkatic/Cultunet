<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		form.task.value=pressbutton;
		form.submit();
		}
</script>
 
<h2 class="contentheading"><?php echo JText::_( 'Advanced search' ) ?></h2>

<form action="<?php echo JRoute::_("index.php") ?>" method="post" name="adminForm" id="adminForm">
<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center">
	<!-- <tr>
		<td colspan="2"><?php printf(JText::_( 'Return results if x of the following conditions are met' ),$this->lists['searchcondition']); ?></td>
	</tr>
	<tr>
		<td colspan="2">
		<input type="button" value="<?php echo JText::_( 'Search' ) ?>" onclick="javascript:submitbutton('advsearch2')" class="button" />	<input type="reset" value="<?php echo JText::_( 'Reset' ) ?>" class="button" /></td>
	</tr> -->
	<?php if( isset($this->catlist) ) { ?>
	<tr>
		<td width="30%"><?php echo JText::_( 'Category' ) ?>:</td>
		<td width="70%"><?php echo $this->catlist; ?></td>
	</tr>
	<?php
	}

	$n = 0;
	while( $this->fields->hasNext() ) {
		$field = $this->fields->getField();
		if($field->hasSearchField()) {
			echo '<tr class="row'.$n.'">';
			echo '<td width="30%" align="left">' . $field->caption . ':' . '</td>';
			echo '<td width="70%" align="left">';
			echo $field->getSearchHTML();
			echo '</td>';
			echo '</tr>';
		}
		$this->fields->next();
		$n = $n+1;
	}
	?>
	<tr>
		<td colspan="2">
		<input type="submit" value="<?php echo JText::_( 'Search' ) ?>" onclick="javascript:submitbutton('advsearch2')" class="button" />	<input type="reset" value="<?php echo JText::_( 'Reset' ) ?>" class="button" /></td>
	</tr>
</table>
<input type="hidden" name="Itemid" value="<?php echo $this->Itemid ?>" />
<input type="hidden" name="option" value="com_mtree" />
<input type="hidden" name="task" value="advsearch2" />
</form>

<div class="clear">&nbsp;</div>