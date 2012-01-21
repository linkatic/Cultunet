<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @params	sortings	string	HTML code for the sorting
 * @params	groupsHTML	string HTML code for the group listings
 * @params	pagination	JPagination JPagination object 
 */
defined('_JEXEC') or die();
?>
<?php echo $sortings; ?>
<div class="cLayout clrfix">
	<!-- ALL MY GROUP LIST -->
	<div class="clrfix">
	<?php
	if( $groups )
	{
	?>
	<div>
		<?php echo JText::sprintf( CStringHelper::isPlural( $count ) ? 'CC GROUPS INVITATION COUNT MANY' : 'CC GROUPS INVITATION COUNT' , $count ); ?>
	</div>
	<?php
		for( $i = 0; $i < count( $groups ); $i++ )
		{
			$group	=& $groups[$i]; 
	?>
	<div class="community-groups-results-item" id="groups-invite-<?php echo $group->id;?>">
		<div class="community-groups-results-left">
			<a href="<?php echo CRoute::_( 'index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $group->id );?>"><img class="avatar" src="<?php echo $group->getThumbAvatar();?>" alt="<?php echo $this->escape($group->name); ?>"/></a>
		</div>
		<div class="community-groups-results-right">
			<h3 class="groupName">
				<a href="<?php echo CRoute::_( 'index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $group->id );?>"><?php echo $group->name; ?></a>
			</h3>
			<div class="groupDescription"><?php echo $this->escape($group->description); ?></div>
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
			</div>
			
			<div class="community-groups-pending-actions">
				<a class="icon-add-friend" href="javascript:void(0);" onclick="joms.groups.invitation.accept('<?php echo $group->id;?>');"><?php echo JText::_('CC ACCEPT INVITATION');?></a>
				<a class="icon-remove" href="javascript:void(0);" onclick="joms.groups.invitation.reject('<?php echo $group->id;?>');"><?php echo JText::_('CC REJECT INVITATION');?></a>
			</div>

			<div id="group-invite-notice"></div>
		</div>
		<div style="clear: both;"></div>
	</div>
	<?php
		}
	}
	else
	{
	?>
		<div class="group-not-found"><?php echo JText::_('CC NO GROUP INVITES'); ?></div>
	<?php
	}
	?>
		<div class="pagination-container">
			<?php echo $pagination->getPagesLinks(); ?>
		</div>
	</div>
	<div class="clr"></div>
</div>