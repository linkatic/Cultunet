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
global $mainframe;
?>
<?php echo BlogSystemFun::getSideMenu('1', 'Settings'); ?>
<h2 class="settingstitle"><?php echo JText::_('EMAILS'); ?></h2>
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'NUM' ); ?></th>
			<th width="5"></th>
			<th class="title"><?php echo JText::_('NAME'); ?></th>
			<th width="15%"><?php echo Jtext::_('MODIFIED'); ?></th>
		</tr>
	</thead>

	<tbody>
		<?php
		$k = 0;
		$i = 0;
		$n = count($this->rows);
		
		foreach ($this->rows as $row) {
			$link 	= 'index.php?option=com_lyftenbloggie&amp;controller=email&amp;task=edit&amp;file='. $row['file'];
			$title  = JText::_('FILE_'.strtoupper($row['file']));
			$title  = ($title == 'FILE_'.strtoupper($row['file'])) ? $row['file'] : $title;
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo ($i+1); ?></td>
			<td width="7"><input id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $row['file']; ?>" onclick="isChecked(this.checked);" type="radio"></td>
			<td align="left"><a href="<?php echo $link; ?>"><?php echo $title; ?></a></td>
			<td align="center"><?php echo $row['date']; ?></td>
		</tr>
		<?php 
		$k = 1 - $k;
        $i++;
		}
		if($i == 0) {
		?>
		<tr>
			<td align="center" colspan="4"><?php echo JText::_( 'THERE ARE NO EMAIL TEMPLATES' ); ?></td>
		</tr>
		<?php 		
		}
		?>
	</tbody>

	</table>
	
	<input type="hidden" name="boxchecked" value="0" />	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_lyftenbloggie" />
	<input type="hidden" name="controller" value="email" />
	<input type="hidden" name="view" value="email" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
	</td>
</tr>
</table>