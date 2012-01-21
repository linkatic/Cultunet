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
	if (form.website.value == ""){
		alert( "<?php echo JText::_( 'ADD WEBSITE NAME' ); ?>" );
	} else if (form.type.value == ""){
				alert( "<?php echo JText::_( 'SELECT A BOOKMARK TYPE', true ); ?>" );
	} else {
		submitform( pressbutton );
	}
}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">

<table class="adminform admintable" style="border: 1px solid rgb(233, 233, 233);">
	<tr>
		<td class="key">
			<label for="website">
				<?php echo JText::_( 'WEBSITE NAME' ).':'; ?>
			</label>
		</td>
		<td>
			<input name="website" value="<?php echo $this->row->website; ?>" size="50" maxlength="100" />
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="type">
				<?php echo JText::_( 'BOOKMARK TYPE' ).':'; ?>
			</label>
		</td>
		<td>
			<?php echo $this->types; ?>
		</td>
	</tr>
	<tr>		
		<td class="key">
			<label for="published">
				<?php echo JText::_( 'PUBLISHED' ).':'; ?>
			</label>
		</td>
		<td>
			<?php
			$html = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $this->row->published );
			echo $html;
			?>
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="published">
				<?php echo JText::_( 'HTML' ).':'; ?>
			</label>
		</td>
		<td>
			<textarea name="html" id="html" rows="5" cols="50" style="width: 97%;"><?php echo $this->row->html; ?></textarea>
		</td>
	</tr>
</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_lyftenbloggie" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="controller" value="bookmarks" />
<input type="hidden" name="view" value="bookmarks" />
<input type="hidden" name="task" value="" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>