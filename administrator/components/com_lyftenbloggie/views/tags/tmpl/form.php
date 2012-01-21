<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>

<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	// do field validation
	if (form.name.value == ""){
		alert( "<?php echo JText::_( 'ENTER A TAG NAME' ); ?>" );
	} else {
		submitform( pressbutton );
	}
}
</script>


<form action="index.php" method="post" name="adminForm" id="adminForm">

<table class="adminform admintable" style="border: 1px solid rgb(233, 233, 233);">
	<tr>
		<td class="key">
			<label for="name"><?php echo JText::_( 'TAG NAME' ).':'; ?></label>
		</td>
		<td>
			<input name="name" value="<?php echo $this->row->name; ?>" size="50" maxlength="100" />
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="slug"><?php echo JText::_( 'SLUG NAME' ).':'; ?></label>
		</td>
		<td>
			<input name="slug" value="<?php echo $this->row->slug; ?>" size="50" maxlength="100" />
		</td>
	</tr>
</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_lyftenbloggie" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="controller" value="tags" />
<input type="hidden" name="view" value="tags" />
<input type="hidden" name="task" value="" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>