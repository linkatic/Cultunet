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
 * This function needs to be here because, Joomla calls it
 **/ 
function submitbutton(action)
{
	if(action == 'newcategory')
	{
		azcommunity.editVideosCategory( 0 , '<?php echo JText::_('CC NEW CATEGORY'); ?>');
	}
	
	if(action == 'removecategory')
	{
		submitform(action);
	}
}
</script>
	<form action="index.php?option=com_community" method="post" name="adminForm">
	<table class="adminlist" cellspacing="1">
		<thead>
			<tr class="title">
				<th width="1%"><?php echo JText::_('CC NUM'); ?></th>
				<th width="1%"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->categories ); ?>);" /></th>
				<th width="15%" style="text-align: left;">
					<?php echo JText::_('CC NAME'); ?>
				</th>
				<th style="text-align: left;">
					<?php echo JText::_('CC CATEGORY DESCRIPTION'); ?>
				</th>
				<th width="5%">
					<?php echo JText::_('CC PUBLISHED');?>
				</th>
				<!--<th width="5%">
					<?php echo JText::_('CC VIDEOS S'); ?>
				</th>-->
			</tr>		
		</thead>
<?php
		$i		= 0;
		
		foreach($this->categories as $category)
		{
?>
			<tr>
				<td><?php echo $i + 1; ?></td>
				<td><?php echo JHTML::_('grid.id', $i++, $category->id); ?></td>
				<td>
					<?php echo JHTML::_('link', 'javascript:void(0);', $category->name, array('id' => 'videos-title-' . $category->id , 'onclick'=>'azcommunity.editVideosCategory(\'' . $category->id . '\',\'' . JText::_('CC EDIT CATEGORY') . '\');')); ?>
				</td>
				<td id="videos-description-<?php echo $category->id; ?>">
					<?php echo $category->description;?>
				</td>
				<td id="published<?php echo $category->id;?>" align="center">
					<?php echo $this->getPublish( $category , 'published' , 'videoscategories,ajaxTogglePublish' );?>
				</td>
				<!--<td align="center">
					<a href="index.php?option=com_community&view=videos&category=<?php echo $category->id;?>">
						<?php echo $category->videoscount; ?>
					</a>
				</td>-->
			</tr>
<?php
		}
?>
		<tfoot>
		<tr>
			<td colspan="6">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
	</table>
	<input type="hidden" name="view" value="videoscategories" />
	<input type="hidden" name="option" value="com_community" />
	<input type="hidden" name="task" value="videoscategories" />
	<input type="hidden" name="boxchecked" value="0" />
	</form>