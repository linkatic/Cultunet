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
$access_array = array();
?>

<form action="index.php" method="post" name="adminForm">

	<table class="adminform">
		<tr>
			<td width="100%">
			  	<?php echo JText::_( 'SEARCH' ); ?>
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onChange="document.adminForm.submit();" />
				<?php echo $this->lists['searchtype']; ?>
				<button onclick="this.form.submit();"><?php echo JText::_( 'GO' ); ?></button>
				<button onclick="this.form.getElementById('search').value='';this.form.getElementById('filter_type').value='';this.form.submit();"><?php echo JText::_( 'RESET' ); ?></button>
			</td>
			<td nowrap="nowrap">
			  <?php echo $this->lists['categories']; ?>
			</td>
			<td nowrap="nowrap">
			  <?php echo $this->lists['state']; ?>
			</td>
		</tr>
	</table>

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'NUM' ); ?></th>
			<th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->rows ); ?>);" /></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'ENTRY', 'c.title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'AUTHOR', 'author', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'CATEGORY', 'category', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'DATE POSTED', 'c.created', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="20%"><?php echo JHTML::_('grid.sort', 'VIEWS', 'c.hits', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JText::_( 'PUBLISHED' ); ?></th>
			<th width="7%"><?php echo JHTML::_('grid.sort', 'ACCESS', 'c.access', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'ID', 'c.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
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
		
		foreach ($this->rows as $row)
		{
			$link 		= 'index.php?option=com_lyftenbloggie&amp;controller=entries&amp;task=edit&amp;id='. $row->id;
			$access 	= ($row->access == 1) ? JText::_('GUEST'): $row->groupname;
			
			$checked 	= JHTML::_('grid.checkedout', $row, $i );
			if ( $row->state == 1 ) {
				$img = BLOGGIE_ASSETS_URL.'/images/published.png';
				$alt = JText::_( 'PUBLISHED' );
			} else if ( $row->state == -1 ) {
				$img = BLOGGIE_ASSETS_URL.'/images/unpublished.png';
				$alt = JText::_( 'UNPUBLISHED' );
			} else if ( $row->state == 2 ) {
				$img = BLOGGIE_ASSETS_URL.'/images/preview.png';
				$alt = JText::_( 'PENDING REVIEW' );
			} else if ( $row->state == 3 ) {
				$img = BLOGGIE_ASSETS_URL.'/images/delete.png';
				$alt = JText::_( 'PENDING DELETION' );
			}			
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
			<td width="7"><?php echo $checked; ?></td>
			<td align="left">
				<?php
				if ( $row->checked_out && ( $row->checked_out != $this->user->get('id') ) ) {
					echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8');
				} else {
				?>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT ENTRY' );?>::<?php echo $row->title; ?>">
					<a href="<?php echo $link; ?>">
					<?php echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8'); ?>
					</a></span>
				<?php
				}
				?>
			</td>
			<td align="center">
				<?php echo $row->author; ?>
			</td>
			<td align="center">
				<?php echo $row->category; ?>
			</td>
			<td align="center">
				<?php echo JHTML::_('date', $row->created, '%b %d, %Y'); ?>
			</td>
			<td align="center">
				<?php echo $row->hits; ?>
			</td>
			<td align="center">
				<?php echo (isset($img))?'<img src="'.$img.'" width="16" height="16" border="0" alt="'.$alt.'" title="'.$alt.'" />':''; ?>
			</td>
			<td align="center">
				<?php echo $access; ?>
			</td>
			<td align="center"><?php echo $row->id; ?></td>
		</tr>
		<?php 
		$k = 1 - $k;
        $i++;
		} 
		?>
	</tbody>

	</table>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_lyftenbloggie" />
	<input type="hidden" name="controller" value="entries" />
	<input type="hidden" name="view" value="entries" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>