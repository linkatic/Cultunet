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
<script language="javascript" type="text/javascript">
<!--
	function submitbutton1(pressbutton) {
		var form = document.adminForm;

		// do field validation
		if (form.install_package.value == ""){
			alert( "PLEASE SELECT A PLUGIN FROM YOUR COMPUTER TO INSTALL" );
		} else {
			form.task.value = 'doinstall';
			form.submit();
		}
	}
//-->
</script>
<h2 class="settingstitle"><?php echo JText::_('PLUGINS'); ?></h2>
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
	<table class="adminform">
		<tr>

			<td width="140">
				<label for="install_package"><?php echo JText::_('UPLOAD PLUGIN PACKAGE FILE'); ?>:</label>
			</td>
			<td>
				<input class="input_box" id="install_package" name="install_package" size="57" type="file">
				<input class="button" value="Upload File &amp; Install" onclick="submitbutton1()" type="button">
			</td>
			<td nowrap="nowrap" style="text-align:right;"><?php echo JText::_('Filter:').' '.$this->lists['types'].' '.$this->lists['state']; ?></td>
		</tr>
	</table>

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'NUM' ); ?></th>
			<th width="5"></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'TITLE', 'p.title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="15%"><?php echo Jtext::_('VERSION'); ?></th>
			<th width="15%"><?php echo JHTML::_('grid.sort', 'DATE', 'p.create_date', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="15%"><?php echo JHTML::_('grid.sort', 'AUTHOR', 'p.author', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="15%"><?php echo JHTML::_('grid.sort', 'TYPE', 'p.type', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="8%"><?php echo JHTML::_('grid.sort', 'PUBLISHED', 'p.published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="5%"><?php echo JText::_('CORE'); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'ID', 'p.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
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
			$link 		= 'index.php?option=com_lyftenbloggie&amp;controller=plugins&amp;task=edit&amp;cid[]='. $row->id;
			$published 	= JHTML::_('grid.published', $row, $i );
			$type = JText::_('TYPE_'.strtoupper($row->type));
			$type = ($type == 'TYPE_'.strtoupper($row->type)) ? $row->type : $type;
			
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
			<td width="7"><input id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" type="checkbox"></td>
			<td align="left"><a href="<?php echo $link; ?>"><?php echo $row->title; ?></a></td>
			<td align="center"><?php echo $row->version; ?></td>
			<td align="center"><?php echo $row->create_date; ?></td>
			<td align="center"><?php echo $row->author; ?></td>
			<td align="center"><?php echo $type; ?></td>
			<td align="center"><?php echo $published; ?></td>
			<td align="center"><?php echo ($row->iscore) ? '<img src="'.BLOGGIE_ASSETS_URL.'/images/core.png" title="'.JText::_('CORE').'" alt="'.JText::_('CORE').'" />' : '<img src="'.BLOGGIE_ASSETS_URL.'/images/noncore.png" title="'.JText::_('NOT CORE').'" alt="'.JText::_('NOT CORE').'" />'; ?></td>
			<td align="center"><?php echo $row->id; ?></td>
		</tr>
		<?php 
		$k = 1 - $k;
        $i++;
		}
		if($i == 0) {
		?>
		<tr>
			<td align="center" colspan="10"><?php echo JText::_( 'THERE ARE NO PLUGINS INSTALLED' ); ?></td>
		</tr>
		<?php 		
		}
		?>
	</tbody>

	</table>
	
	<input type="hidden" name="boxchecked" value="0" />	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_lyftenbloggie" />
	<input type="hidden" name="controller" value="plugins" />
	<input type="hidden" name="view" value="plugins" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
	</td>
</tr>
</table>