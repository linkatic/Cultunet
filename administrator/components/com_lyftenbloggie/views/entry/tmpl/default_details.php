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
<table class="adminform admintable" style="border: 1px solid rgb(233, 233, 233);">
	<tr>
		<td class="key">
			<label for="title">
				<?php echo JText::_( 'TITLE' ); ?>
			</label>
		</td>
		<td style="border-right: 1px solid rgb(233, 233, 233);">
			<input class="inputbox" type="text" name="title" id="title" size="40" maxlength="255" value="<?php echo $this->row->title; ?>" />
		</td>
		<td class="key">
			<label>
				<?php echo JText::_( 'PUBLISHED' ); ?>
			</label>
		</td>
		<td>
			<?php echo $this->lists['state']; ?>
		</td>
	</tr>
	<tr>
		<td class="key">
			<label for="alias">
				<?php echo JText::_( 'ALIAS' ); ?>
			</label>
		</td>
		<td style="border-right: 1px solid rgb(233, 233, 233);">
			<input class="inputbox" type="text" name="alias" id="alias" size="40" maxlength="255" value="<?php echo $this->row->alias; ?>" />
		</td>
		<td class="key">
			<label for="catid">
				<?php echo JText::_( 'CATEGORY' ); ?>
			</label>
		</td>
		<td>
			<?php echo $this->lists['catid']; ?>
		</td>
	</tr>
</table>
<table class="adminform">
	<tr>
		<td>
		<?php
			// parameters : areaname, content, hidden field, width, height, rows, cols
			echo $this->editor->display( 'text',  $this->row->text, '100%;', '350', '75', '20', array('pagebreak') ) ;
		?>
		</td>
	</tr>
</table>

<fieldset class="adminform">
	<legend><?php echo JText::_( 'TRACKBACKS' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
		<tr>
			<td width="185" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'TRACKBACKS' ); ?>::<?php echo JText::_( 'DETAILTRACKBACK' ); ?>">
						<?php echo JText::_( 'TRACKBACKS' ); ?>
					</span>
			</td>
			<td>
				<input class="text_area" type="text" name="trackbacks" size="70" value="" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
			</td>
		</tr>
		<tr>
			<td class="key" style="valign:top;"><?php echo JText::_( 'ALREADY PINGED' ); ?></td>
			<td>
			<?php if(!empty($this->row->pinged)) {
				echo '<ul>';
					foreach($this->row->pinged as $ping) {
						echo ($ping) ? '<li>'.$ping.'</li>' : '';
					}
				echo '</ul>';
				}
			 ?>
			</td>
		</tr>
		</tbody>
	</table>
</fieldset>