<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm">

	<table class="adminform">
		<tr>
			<td width="100%">
			  	<?php echo JText::_( 'SEARCH' ); ?>
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onChange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'GO' ); ?></button>
				<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'RESET' ); ?></button>
			</td>
			<td nowrap="nowrap">
			  <?php
			  echo $this->lists['type'];
				?>
			</td>
		</tr>
	</table>

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'NUM' ); ?></th>
			<th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->rows ); ?>);" /></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'USER', 'u.name', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="10%"><?php echo JText::_( 'TYPE' ); ?></th>
			<th width="15"><?php echo JHTML::_('grid.sort', 'POSTS', 'npost', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'ID', 'u.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<td colspan="6">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>

	<tbody>
		<?php
		$k = 0;
		$i = 0;
		$n = count($this->rows);
		foreach ($this->rows as $row)
		{
			$link 		= 'index.php?option=com_lyftenbloggie&amp;controller=profiles&amp;task=edit&amp;id='. $row->id;
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
			<td width="7"><?php echo JHTML::_('grid.id', $i, $row->id ); ?></td>
			<td align="left"><span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT PROFILE' );?>::<?php echo $row->username; ?>"><a href="<?php echo $link; ?>"><?php echo $row->name; ?></a></span>
			</td>
			<td align="center"><?php echo $row->type ?></td>
			<td align="center"><?php echo $row->npost?></td>
			<td align="center"><?php echo $row->id; ?></td>
		</tr>
		<?php 
		$k = 1 - $k;
        $i++;
		}
		if($i == 0){
			echo '<tr><td colspan="6" align="center">'.JText::_( 'NO PROFILES AVAILABLE' ).'</td></tr>';
		}
		?>
	</tbody>

	</table>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_lyftenbloggie" />
	<input type="hidden" name="controller" value="profiles" />
	<input type="hidden" name="view" value="profiles" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>