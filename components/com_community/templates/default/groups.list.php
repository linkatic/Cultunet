<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @params	groups		An array of group objects.
 */
defined('_JEXEC') or die();

if( $groups )
{
	for( $i = 0; $i < count( $groups ); $i++ )
	{
		$group	=& $groups[$i]; 
?>
	<div class="community-groups-results-item bg-container">
		<div class="community-groups-results-left">
			<a href="<?php echo CRoute::_( 'index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $group->id );?>"><img class="avatar" src="<?php echo $group->getThumbAvatar();?>" alt="<?php echo $this->escape($group->name); ?>"/></a>
		</div>
		<div class="community-groups-results-right">
			<h3 class="groupName">
				<a href="<?php echo CRoute::_( 'index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $group->id );?>">
					<?php echo $this->escape($group->name); ?>
				</a>
			</h3>
			<div class="groupDescription"><?php echo substr($this->escape($group->description), 0, 150).'...'; ?></div>
			<div class="groupCreated small"><?php echo JText::sprintf('CC GROUP CREATED ON' , JHTML::_('date', $group->created, JText::_('DATE_FORMAT_LC')) );?></div>            
            
			<div class="groupActions">
				<span class="icon-group" style="margin-right: 5px;">
					<a href="<?php echo CRoute::_( 'index.php?option=com_community&view=groups&task=viewmembers&groupid=' . $group->id ); ?>"><?php echo JText::sprintf((CStringHelper::isPlural($group->membercount)) ? 'CC MEMBERS COUNT MANY':'CC MEMBERS COUNT', $group->membercount);?></a>
				</span>
				<span class="icon-discuss" style="margin-right: 5px;">
					<?php echo JText::sprintf((CStringHelper::isPlural($group->discusscount)) ? 'CC GROUP DISCUSS COUNT MANY' :'CC GROUP DISCUSS COUNT', $group->discusscount);?>
				</span>
				<span class="icon-wall" style="margin-right: 5px;">
					<?php echo JText::sprintf((CStringHelper::isPlural($group->wallcount)) ? 'CC GROUP WALL COUNT MANY' : 'CC GROUP WALL COUNT', $group->wallcount);?>
				</span>
				<?php
				if( $isCommunityAdmin && $showFeatured )
				{
					if( !in_array($group->id, $featuredList) )
					{
				?>
					<span class="icon-addfeatured" style="margin-right: 5px;">	            
			            <a onclick="joms.featured.add('<?php echo $group->id;?>','groups');" href="javascript:void(0);">	            	            
			            <?php echo JText::_('CC MAKE FEATURED'); ?>
			            </a>
			        </span>
				<?php			
					}
				}
				?>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>
<?php
	}
}
else
{
?>
	<div class="group-not-found"><?php echo JText::_('CC NO GROUPS FOUND'); ?></div>
<?php
}
?>