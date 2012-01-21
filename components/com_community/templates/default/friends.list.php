<?php
/**
 * @package		JomSocial
 * @subpackage 	Template
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 * @param	author		string
 * @param	categories	An array of category objects.
 * @params	groups		An array of group objects.
 * @params	pagination	A JPagination object.
 * @params	isJoined	boolean	determines if the current browser is a member of the group
 * @params	isMine		boolean is this wall entry belong to me ?
 * @params	config		A CConfig object which holds the configurations for Jom Social
 */
defined('_JEXEC') or die();
?>
<?php echo $sortings; ?>
<?php if( !empty( $friends ) ) : ?>
	<?php foreach( $friends as $user ) : ?>

<div class="mini-profile">
	<div class="mini-profile-avatar">
		<a href="<?php echo $user->profileLink; ?>">
			<img class="avatar" src="<?php echo $user->getThumbAvatar(); ?>" alt="<?php echo $user->getDisplayName(); ?>" />
		</a>
	</div>
	<div class="mini-profile-details">
		<h3 class="name">
			<a href="<?php echo $user->profileLink; ?>"><strong><?php echo $user->getDisplayName(); ?></strong></a>
		</h3>
	
		<div class="mini-profile-details-status"><?php echo $user->getStatus() ;?></div>
		<div class="mini-profile-details-action">
			<?php
			if( $isMine )
			{
			?>
			<a class="remove jomTips" title="<?php echo JText::_('CC DELETE FRIEND'); ?>::<?php echo JText::_('CC DELETE FRIEND'); ?>"  onclick="if(!confirm('<?php echo JText::_('CC CONFIRM DELETE FRIEND'); ?>'))return false;" href="<?php echo CRoute::_('index.php?option=com_community&view=friends&task=remove&fid='.$user->id); ?>">
				<?php echo JText::_('CC REMOVE'); ?>
			</a>
			<?php
			}
			?>
		</div>

		<div class="icons">
		    <span class="icon-group">
		    	<a href="<?php echo CRoute::_('index.php?option=com_community&view=friends&userid=' . $user->id );?>"><?php echo JText::sprintf( (CStringHelper::isPlural($user->friendsCount)) ? 'CC FRIENDS COUNT MANY' : 'CC FRIENDS COUNT' , $user->friendsCount);?></a>
		    </span>

			<?php if( $my->id != $user->id && $config->get('enablepm') ): ?>
	        <span class="icon-write">
	            <a onclick="joms.messaging.loadComposeWindow(<?php echo $user->id; ?>)" href="javascript:void(0);">
	            <?php echo JText::_('CC WRITE MESSAGE'); ?>
	            </a>
	        </span>
	        <?php endif; ?>
	        
		</div>
		
		
		<!-- new online icon -->
	    <?php if($user->isOnline()): ?>
		<span class="icon-online-overlay">
	    	<?php echo JText::_('CC ONLINE'); ?>
	    </span>
	    <?php endif; ?>		
		<!-- new online icon -->
	</div>
	<div class="clr"></div>
</div>

	<?php endforeach; ?>
<?php endif; ?>