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
<!--
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	// do field validation
	if (form.title.value == ""){
		alert( "<?php echo JText::_( 'ADD CATEGORY NAME' ); ?>" );
	} else {
		submitform( pressbutton );
	}
}
//-->
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">

<table class="adminform admintable" style="border: 1px solid rgb(233, 233, 233);">
	<tr>
		<td class="key">
			<label for="title">
				<?php echo JText::_( 'CATEGORY NAME' ).':'; ?>
			</label>
		</td>
		<td>
			<input id="title" name="title" value="<?php echo $this->row->title; ?>" size="50" maxlength="100" />
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="slug">
				<?php echo JText::_( 'CATEGORY SLUG' ).':'; ?>
			</label>
		</td>
		<td>
			<input class="inputbox" type="text" name="slug" id="slug" size="50" maxlength="100" value="<?php echo $this->row->slug; ?>" />
		</td>
	</tr>
	<tr>		
		<td class="key">
			<label for="published">
				<?php echo JText::_( 'PUBLISHED' ).':'; ?>
			</label>
		</td>
		<td>
			<?php echo JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $this->row->published ); ?>
		</td>
	</tr>
	<tr>		
		<td class="key">
			<label for="default">
				<?php echo JText::_( 'DEFAULT' ).':'; ?>
			</label>
		</td>
		<td>
			<?php echo JHTML::_('select.booleanlist', 'default', 'class="inputbox"', $this->row->default ); ?>
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="published">
				<?php echo JText::_( 'DESCRIPTION' ).':'; ?>
			</label>
		</td>
		<td>
			<textarea name="text" id="text" rows="5" cols="50" style="width: 97%;"><?php echo $this->row->text; ?></textarea>
		</td>
	</tr>
</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_lyftenbloggie" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="controller" value="categories" />
<input type="hidden" name="view" value="category" />
<input type="hidden" name="task" value="" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>