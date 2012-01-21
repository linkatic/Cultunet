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

<?php if ( $my->id == 0 ) : ?>
<div class="black-button" id="back-toolbar">
	<a class="btn-blue btn-prev" href="<?php echo CRoute::_('index.php?option=com_community'); ?>">
		<span><?php echo JText::_('CC GO TO LOGIN PAGE'); ?></span>
	</a>
	<div class="clr"></div>
</div>
<?php endif; ?>

<div class="profile-box">

	<!-- Avatar -->
	<div class="profile-avatar">
	    <?php if( $isMine ): ?>
		<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=edit'); ?>">
		<?php endif; ?>
		<img src="<?php echo $user->getThumbAvatar(); ?>" alt="<?php echo $user->getDisplayName(); ?>" />
		<?php if( $isMine ): ?>
		</a>
		<?php endif; ?>
	</div>

	<!-- Short Profile info -->
	<div class="profile-info">
		<div class="contentheading">
			<?php echo $user->getDisplayName(); ?>
		</div>

		<div id="profile-status">
			<span id="profile-status-message"><?php echo $user->getStatus() ;?></span>
		</div>
	</div>
	<div class="clr">&nbsp;</div>
	<?php if( $isMine ): ?>
	<a name="edit-link" id="edit-link" href="javascript:void(0);" onclick="iphone.editStatus();">&nbsp;</a>
	<?php endif; ?>
</div>
	

