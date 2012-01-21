<?php
/**
 * @package		JomSocial
 * @subpackage 	Template
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 */
defined('_JEXEC') or die();
?>

<?php
if ( $rows ) {
?>

<?php foreach( $rows as $row ) { ?>

<div class="mini-profile">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	    <tr>
			<td valign="top">
				<div class="mini-profile-avatar">
					<a href="<?php echo $row->user->profileLink; ?>"><img class="avatar" src="<?php echo $row->user->getThumbAvatar(); ?>" alt="<?php echo $row->user->getDisplayName(); ?>" /></a>
				</div>
				
				<div class="mini-profile-details">
			    <h3 class="name">
					<a href="<?php echo $row->user->profileLink; ?>"><strong><?php echo $row->user->getDisplayName(); ?></strong></a>
				</h3>
				<div class="mini-profile-details-status"><?php echo $this->escape( $row->user->getStatus() ) ;?></div>

				<div class="mini-profile-details-action">
				    <span class="icon-group">
				    	<?php echo JText::sprintf( (CStringHelper::isPlural($row->user->friendsCount)) ? 'CC FRIENDS COUNT MANY' : 'CC FRIENDS COUNT' , $row->user->friendsCount);?>
				    </span>

                    <?php if( $my->id != $row->user->id && $config->get('enablepm') ): ?>
		        	<span class="icon-write">
			            <a onclick="joms.messaging.loadComposeWindow(<?php echo $row->user->id; ?>)" href="javascript:void(0);">
			            <?php echo JText::_('CC WRITE MESSAGE'); ?>
			            </a>
			        </span>
		        	<?php endif; ?>
				</div>
				</div>
			</td>
			
			<td width="100">
		        <div style="padding: 2px 5px; margin: 0 0 5px 0;">
				<a class="icon-remove" onclick="joms.friends.cancelRequest('<?php echo $row->user->id; ?>');" href="javascript:void(0);">
					<?php echo JText::_('CC REMOVE'); ?>
				</a>
				</div>
			</td>
		</tr>
	</table>

	<?php if($row->user->isOnline()): ?>
	<span class="icon-online-overlay">
    	<?php echo JText::_('CC ONLINE'); ?>
    </span>
    <?php endif; ?>

</div>
<?php } ?>

<?php 
}
else { 
?>
<div class="community-empty-list">
	<?php echo JText::_('CC PENDING REQUEST EMPTY'); ?>
</div>
<?php } ?>