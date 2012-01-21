<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	my		User object
 **/
defined('_JEXEC') or die();
?>

<!--COMMUNITY FORM-->
<div class="community-form">
	
	<div class="community-form-instruction">
		<?php echo JText::_('CC INVITE TEXT'); ?>
	</div>
	
	<form name="jsform-friends-invite" action="<?php echo CRoute::getURI(); ?>" method="post">
		<?php echo $beforeFormDisplay;?>
		<div class="community-form-row">
			<label>
				<?php echo JText::_('CC INVITE FROM'); ?>:
			</label>
			<?php echo $my->email; ?>
		</div>
		<div class="community-form-row">
			<label>
				*<?php echo JText::_('CC INVITE TO'); ?>:
			</label>
			<textarea class="required inputbox" name="emails"><?php echo (! empty($post['emails'])) ? $post['emails'] : '' ; ?></textarea>
			<div class="small"><?php echo JText::_('CC SEPARATE BY COMMA'); ?></div>
		</div>
		
		<div class="community-form-row">
			<label>
			<?php echo JText::_('CC INVITE MESSAGE'); ?>:
			</label>
			<textarea class="inputbox" name="message"><?php echo (! empty($post['message'])) ? $post['message'] : '' ; ?></textarea>
			<div class="small"><?php echo JText::_('CC OPTIONAL');?></div>
		</div>
		
		<div class="community-form-row">
			<span class="hints"><?php echo JText::_( 'CC_REG_REQUIRED_FILEDS' ); ?></span>
		</div>
		<?php echo $afterFormDisplay;?>
		<div class="community-form-submit">
			<input type="hidden" name="action" value="invite" />
			<input type="submit" class="button" value="<?php echo JText::_('CC INVITE BUTTON'); ?>">
		</div>
	</form>
	
</div>
<!--end: COMMUNITY FORM-->


<?php if( !empty( $friends ) ) : ?>
<div class="suggest-friends">
	<h3><?php echo JText::_('CC FRIEND SUGGESTION'); ?></h3>
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
			<div class="icons">
					<span class="btn-add-friend">
						<a href="javascript:void(0)" onclick="joms.friends.connect('<?php echo $user->id;?>')"><span><?php echo JText::_('CC ADD AS FRIEND'); ?></span></a>
					</span>
			    <span class="icon-group"><a href="<?php echo CRoute::_('index.php?option=com_community&view=friends&userid=' . $user->id );?>"><?php echo JText::sprintf( (CStringHelper::isPlural($user->friendsCount)) ? 'CC FRIENDS COUNT MANY' : 'CC FRIENDS COUNT' , $user->friendsCount);?></a></span>
				<?php if( $my->id != $user->id && $config->get('enablepm') ): ?>
		        <span class="icon-write"><a onclick="joms.messaging.loadComposeWindow(<?php echo $user->id; ?>)" href="javascript:void(0);"><?php echo JText::_('CC WRITE MESSAGE'); ?></a></span>
		        <?php endif; ?>
				<!-- new online icon -->
				<?php if($user->isOnline()): ?>
				<span class="icon-online-overlay"><?php echo JText::_('CC ONLINE'); ?></span>
				<?php endif; ?>	        
			</div>
			<div class="clr"></div>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>