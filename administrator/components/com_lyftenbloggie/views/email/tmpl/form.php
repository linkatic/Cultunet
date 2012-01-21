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
<?php echo BlogSystemFun::getSideMenu('1', 'Settings'); ?>
<h2 class="settingstitle"><?php echo JText::_('EDIT EMAIL'); ?></h2>
<form action="index.php" method="post" name="adminForm">
	<table class="adminform">
	<tr>
		<th>
		<?php echo JText::_( 'File' )?>: <?php echo $this->file; ?>
		<?php echo ($this->writable) ? '<b><font color="green"> - '.JText::_( 'Writable' ).'</font></b>' : '<b><font color="red"> - '.JText::_( 'Unwritable' ).'</font></b>'; ?>
		</th>
	</tr>
	<tr>
		<td>
		<textarea style="width:99%" rows="25" name="filecontent" class="inputbox"><?php echo $this->content; ?></textarea>
		</td>
	</tr>
	</table>
	<?php if(!empty($this->used)) { ?>
	<div style="background:#FFFCDD;border:1px solid #ECE9CA;width:100%;">
	<h2 style="margin:5px 5px 7px 5px;color:#434343;width:98%;border-bottom:1px solid #ECE9CA;"><?php echo JText::_('VARIABLES AVAILABLE'); ?></h2>
	<table style="margin:5px;">
	<?php
	$i = 1;
	$t = 1;
	$total = count($this->used);
	foreach($this->used as $title=>$value)
	{
		echo ($i == 1) ? '<tr>' : '';
		echo '<td style="'.(($i == 1) ? 'padding-right:20px;' : 'padding:0 20px 0 70px;').'"><b>'.$title.'</b>:</td><td'.(($t == $total && $i == 1) ? ' colspan="3"' : '').'><i>'.$value.'</i></td>';
		if($i == 2)
		{
			echo '</tr>';
			$i = 0;
		}
		$i++;
		$t++;
	} ?>
	</table>
	</div>
	<?php } ?>
	<input type="hidden" name="file" value="<?php echo $this->filename;?>" />
	<input type="hidden" name="option" value="com_lyftenbloggie" />
	<input type="hidden" name="controller" value="email" />
	<input type="hidden" name="view" value="email" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
	</td>
</tr>
</table>