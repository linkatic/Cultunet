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
<form action="index.php" method="post" name="adminForm">
	<table class="adminform">
		<tr>
			<td width="100%">
			  	<?php echo JText::_( 'SEARCH' ); ?>
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onChange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'GO' ); ?></button>
				<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'RESET' ); ?></button>
			</td>
		</tr>
	</table>

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'ID', 'u.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'USER', 'u.name', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JText::_( 'ENABLED' ); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<td colspan="10">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>

	<tbody>
		<?php
		$k = 0;
		$i = 0;
		$n = count($this->rows);
		foreach ($this->rows as $row) {
			$img 		= $row->block ? 'publish_x.png' : 'tick.png';
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center"><?php echo $row->id; ?></td>
			<td align="left"><span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT AUTHOR' );?>::<?php echo $row->username; ?>"><a href="#" onclick="javascript:sendtomain(<?php echo $row->id.', \''.$row->name.'\''; ?>);"><?php echo $row->name; ?></a></span>
			</td>
			<td align="center">
				<img src="images/<?php echo $img;?>" width="16" height="16" border="0" />
			</td>
		</tr>
		<?php 
		$k = 1 - $k;
        $i++;
		}
		if($i == 0){
			echo '<tr><td colspan="4" align="center">'.JText::_( 'NO AUTHORS AVAILABLE' ).'</td></tr>';
		}
		?>
	</tbody>

	</table>

	<input type="hidden" name="tmpl" value="component" />
	<input type="hidden" name="option" value="com_lyftenbloggie" />
	<input type="hidden" name="view" value="userselement" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>