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

// used to hide "Reset Hits" when hits = 0
$hits = ($this->row->hits)?"  <span><input name=\"reset_hits\" type=\"button\" class=\"button\" value=\"".JText::_( 'RESET' )."\" onclick=\"resethit('resethits', '".$this->row->id."', 'hits')\" /></span>":"";
?>
<table width="100%" style="border: 1px dashed silver; padding: 5px; margin-bottom: 10px;">
<?php
if ( $this->row->id ) {
?>
	<tr>
		<td>
			<strong><?php echo JText::_( 'ENTRY ID' ); ?>:</strong>
		</td>
		<td>
			<?php echo $this->row->id; ?>
		</td>
	</tr>
<?php
}
?>
	<tr>
		<td>
			<strong><?php echo JText::_( 'State' ); ?></strong>
		</td>
		<td>
		<?php 
		if($this->row->state == 1) echo JText::_( 'PUBLISHED' );
		if($this->row->state == 2) echo JText::_( 'PENDING REVIEW' );
		if($this->row->state == -1) echo JText::_( 'UNPUBLISHED' );
		if($this->row->state == 3) echo JText::_( 'PENDING DELETION' );
		?>
		</td>
	</tr>
	<tr>
		<td>
			<strong><?php echo JText::_( 'HITS' ); ?></strong>
		</td>
		<td>
			<div id="hits"><?php echo $this->row->hits; ?><span><?php echo $hits; ?></span></div>
		</td>
	</tr>
	<tr>
		<td>
			<strong><?php echo JText::_( 'REVISED' ); ?></strong>
		</td>
		<td>
			<?php echo $this->row->version;?> <?php echo JText::_( 'times' ); ?>
		</td>
	</tr>
	<tr>
		<td>
			<strong><?php echo JText::_( 'CREATED' ); ?></strong>
		</td>
		<td>
			<?php
			if ( $this->row->created == $this->nullDate ) {
				echo JText::_( 'NEW ENTRY' );
			} else {
				echo JHTML::_('date',  $this->row->created,  JText::_('DATE_FORMAT_LC2') );
			}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<strong><?php echo JText::_( 'MODIFIED' ); ?></strong>
		</td>
		<td>
			<?php
				if ( $this->row->modified == $this->nullDate ) {
					echo JText::_( 'Not modified' );
				} else {
					echo JHTML::_('date',  $this->row->modified, JText::_('DATE_FORMAT_LC2'));
				}
			?>
		</td>
	</tr>
</table>
