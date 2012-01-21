<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript" language="javascript">
/**
 * This function needs to be here because, Joomla toolbar calls it
 **/ 
function submitbutton( action )
{
	submitform( action );
}
</script>
<form action="index.php?option=com_community" method="post" name="adminForm">
<div>
	<?php echo JText::_('CC NOTE GROUP CREATION IS NOW ONLY AVAILABLE THROUGH THE FRONT END OF JOM SOCIAL'); ?>
</div>
<table class="adminform" cellpadding="3">
	<tr>
		<td width="95%">
			<?php echo JText::_('CC SEARCH');?>
			<input type="text" onchange="document.adminForm.submit();" class="text_area" value="<?php echo ($this->search) ? $this->search : '';?>" id="search" name="search"/>
			<button onclick="this.form.submit();"><?php echo JText::_('CC SEARCH');?></button>
		</td>
		<td nowrap="nowrap" align="right">
		<span style="font-weight: bold;"><?php echo JText::_('CC VIEWING GROUPS BY CATEGORY'); ?>:
		<?php echo $this->categories;?>
		</td>
	</tr>
</table>
	
<table class="adminlist" cellspacing="1">
	<thead>
		<tr class="title">
			<th width="1%">#</th>
			<th width="1%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->groups ); ?>);" />
			</th>
			<th width="15%" style="text-align: left;">
				<?php echo JHTML::_('grid.sort',   JText::_('CC NAME') , 'a.name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th style="text-align: left;">
				<?php echo JText::_('CC GROUP DESCRIPTION'); ?>
			</th>
			<th width="10%">
				<?php echo JHTML::_('grid.sort',   JText::_('CC GROUP ADMINISTRATOR'), 'c.name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="5%">
				<?php echo JHTML::_('grid.sort',   JText::_('CC PUBLISHED'), 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="5%">
				<?php echo JHTML::_('grid.sort',   JText::_('CC MEMBER S'), 'membercount', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
		</tr>
	</thead>
	<?php $i = 0; ?>
	<?php
		if( empty( $this->groups ) )
		{
	?>
	<tr>
		<td colspan="7" align="center"><?php echo JText::_('CC NO GROUPS CREATED YET');?></td>
	</tr>
	<?php
		} 
	?>
	<?php foreach( $this->groups as $row ): ?>
	<tr>
		<td align="center">
			<?php echo ( $i + 1 ); ?>
		</td>
		<td>
			<?php echo JHTML::_('grid.id', $i++, $row->id); ?>
		</td>
		<td>
			<a href="javascript:void(0);" onclick="azcommunity.editGroup('<?php echo $row->id;?>');">
				<?php echo $row->name; ?>
			</a>
		</td>
		<td>
			<?php echo $row->description; ?>
		</td>
		<td align="center">
			<?php echo $row->username; ?>
			<span>[ <a href="javascript:void(0);" onclick="azcommunity.changeGroupOwner('<?php echo $row->id; ?>');"><?php echo JText::_('CC CHANGE'); ?></a> ]</span>
		</td>
		<td id="published<?php echo $row->id;?>" align="center">
			<?php echo $this->getPublish( $row , 'published' , 'groups,ajaxTogglePublish' );?>
		</td>
		<td align="center">
			<?php echo $row->membercount; ?>
		</td>
	</tr>
	<?php endforeach; ?>
	<tfoot>
	<tr>
		<td colspan="15">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
	</tfoot>
</table>
<input type="hidden" name="view" value="groups" />
<input type="hidden" name="option" value="com_community" />
<input type="hidden" name="task" value="groups" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>