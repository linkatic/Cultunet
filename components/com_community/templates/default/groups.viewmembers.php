<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @params	isMine		boolean is this group belong to me
 * @params	members		An array of member objects 
 */
defined('_JEXEC') or die();
?>
<?php
if( $type == '1' && !( $isMine || $isAdmin || $isSuperAdmin ) )
{
?>
	<div>
		<?php echo JText::_('CC PERMISSION DENIED'); ?>
	</div>
<?php
}
else
{
?>
	<?php if( $members ): ?>
		<div id="notice"></div>
	<?php
		foreach( $members as $member )
		{
			//$member->isAdmin
	?>
	<div class="mini-profile" id="member_<?php echo $member->id;?>">
		<div class="mini-profile-avatar">
			<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $member->id); ?>"><img class="avatar" src="<?php echo $member->getThumbAvatar(); ?>" alt="<?php echo $member->getDisplayName(); ?>" /></a>
		</div>
		<div class="mini-profile-details">
			<h3 class="name">
				<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $member->id); ?>"><strong><?php echo $member->getDisplayName(); ?></strong></a>
			</h3>

			<div style="padding:20px;float:right;z-index:9999;position:relative;">
				
				<?php if( $isMine || $isAdmin || $isSuperAdmin ): ?>
				<a class="remove" href="javascript:void(0);" onclick="joms.groups.removeMember('<?php echo $member->id; ?>','<?php echo $groupid;?>');">
					<?php echo JText::_('CC REMOVE MEMBER FROM GROUP');?>
				</a>
				<?php endif; ?>
			</div>
			<div class="mini-profile-details-status" style="margin-right:40px"><?php echo $member->getStatus() ;?></div>
			<div class="mini-profile-details-action">
				<span class="icon-group">
		    		<a href="<?php echo CRoute::_('index.php?option=com_community&view=friends&userid=' . $member->id );?>"><?php echo JText::sprintf( (CStringHelper::isPlural($member->friendsCount)) ? 'CC FRIENDS COUNT MANY' : 'CC FRIENDS COUNT' , $member->friendsCount);?></a>
		    	</span>


				<?php if( $my->id != $member->id && $config->get('enablepm') ): ?>
		        <span class="icon-write">
		            <a onclick="joms.messaging.loadComposeWindow(<?php echo $member->id; ?>)" href="javascript:void(0);">
		            <?php echo JText::_('CC WRITE MESSAGE'); ?>
		            </a>
		        </span>
		        <?php endif; ?>
		        
			    <?php if( !$member->approved && ($isMine || $isAdmin || $isSuperAdmin ) ): ?>
			    <span class="icon-approve" id="groups-approve-<?php echo $member->id;?>">
			    	<a href="javascript:void(0);" onclick="jax.call('community','groups,ajaxApproveMember', '<?php echo $member->id;?>' , '<?php echo $groupid;?>');">
						<?php echo JText::_('CC APPROVE'); ?>
					</a>
			    </span>
			    <?php endif; ?>
			    
			    <?php
					if( ($isMine && !$member->isMe && !$member->isAdmin && $member->approved) || ($isSuperAdmin && $isMember && !$member->isAdmin && $member->approved) )       
					{
				?>
				    <span class="icon-user">
				    	<a href="javascript:void(0);" onclick="jax.call('community','groups,ajaxAddAdmin','<?php echo $member->id;?>','<?php echo $groupid;?>');">
							<?php echo JText::_('CC SET AS GROUP ADMIN'); ?>
						</a>
				    </span>
			    <?php
			    	}
			    	else if( ($isMine && !$member->isMe && $member->isAdmin) || (!$member->isOwner && $member->isAdmin && $isSuperAdmin && $isMember) )
			    	{
			    ?>
				    <span class="icon-user">
				    	<a href="javascript:void(0);" onclick="jax.call('community','groups,ajaxRemoveAdmin','<?php echo $member->id;?>','<?php echo $groupid;?>');">
							<?php echo JText::_('CC REVERT GROUP ADMIN'); ?>
						</a>
				    </span>
			    <?php
					}
				?>
			</div>
			
			<?php if($member->isOnline()): ?>
			<span class="icon-online-overlay">
		    	<?php echo JText::_('CC ONLINE'); ?>
		    </span>
		    <?php endif; ?>			
			
		</div>
		<div class="clr"></div>
	</div>
	<?php
		}
	?>
	<div class="pagination-container">
		<?php echo $pagination->getPagesLinks(); ?>
	</div>
	<?php endif; ?>
<?php
}
?>