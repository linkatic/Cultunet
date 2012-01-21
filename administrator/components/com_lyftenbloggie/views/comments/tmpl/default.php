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
			  echo $this->lists['state'];
				?>
			</td>
		</tr>
	</table>

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'NUM' ); ?></th>
			<th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->rows ); ?>);" /></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'COMMENT', 'c.content', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="20%"><?php echo JHTML::_('grid.sort', 'COMMENTED ON', 'entryname', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="15%"><?php echo JHTML::_('grid.sort', 'COMMENTER', 'commenter', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="25"><?php echo JHTML::_('grid.sort', 'KARMA', 'c.karma', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JText::_( 'STATE' ); ?></th>
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
		foreach ($this->rows as $row) {

			$link 		= 'index.php?option=com_lyftenbloggie&amp;controller=comments&amp;task=edit&amp;id='. $row->id;

			if($this->_reportCount($row->id)) $row->state = 2;
			$alt = '';
			if ( $row->state == 1 ) {
				$img = BLOGGIE_ASSETS_URL.'/images/published.png';
				$alt = JText::_( 'APPROVED' );
			} else if ( $row->state == -1 ) {
				$img = BLOGGIE_ASSETS_URL.'/images/unpublished.png';
				$alt = JText::_( 'UNAPPROVED' );
			} else if ( $row->state == 2 ) {
				$img = BLOGGIE_ASSETS_URL.'/images/flagged.png';
				$alt = JText::_( 'FLAGGED' );
			} else if ( $row->state == 0 ) {
				$img = BLOGGIE_ASSETS_URL.'/images/spam.png';
				$alt = JText::_( 'SPAM' );
			}	
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
			<td width="7"><input id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" type="checkbox"></td>
			<td align="left"><a href="<?php echo $link; ?>"><?php echo substr($row->content, 0, 150); ?></a></td>
			<td><?php echo $row->entryname; ?></td>
			<td align="center"><?php 
			if($row->type == '2'){
				echo JText::_( 'TRACKBACK' );
			}else{
				echo $row->commenter;
				echo (!$row->user_id) ? ' <small>['.JText::_('ANONYMOUS').']</small>' : '';
			}
			?></td>
			<td align="center"><?php echo $row->karma; ?></td>
			<td align="center"><img src="<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" title="<?php echo $alt; ?>" /></td>
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
	<input type="hidden" name="controller" value="comments" />
	<input type="hidden" name="view" value="comments" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>