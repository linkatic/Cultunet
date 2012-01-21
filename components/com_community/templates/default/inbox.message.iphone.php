<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
?>
<script type="text/javascript">


</script>
<div class="inbox iphone <?php echo isset($isMine) && $isMine ? 'isMine' : ''; ?>">
	<div class="" style="margin: 0 0 10px;" id="message-<?php echo $msg->id; ?>" >
	    <div class="avatar avatar-message-view">
	    	<a href="<?php echo $authorLink;?>">
				<img width="45" src="<?php echo $user->getThumbAvatar(); ?>" alt="<?php echo $user->getDisplayName(); ?>" />
			</a>
		</div>
	
		<div class="message-body">
			<div class="bubble-arrow">
	            <div class="bubble-content">
					<?php echo $msg->body; ?>

					<div class="message-info" style="margin: 10px 0 0;">
					    <div>						
							<?php
								$postdate =  CTimeHelper::getDate($msg->posted_on);
								echo $postdate->toFormat('%d %b %Y, %I:%M %p'); 
							?>
						</div>
						<!--
						<div>
							<a href="<?php echo $authorLink;?>">
						    <?php echo JText::sprintf('CC INBOX FROM', $user->getDisplayName()); ?>
						    </a>
						</div>
						-->
						<div>
						    <a href="javascript:jax.call('community', 'inbox,ajaxRemoveMessage', <?php echo $msg->id; ?>);" title="<?php echo JText::_('CC INBOX REMOVE MESSAGE'); ?>">
							<?php echo JText::_('CC INBOX REMOVE MESSAGE'); ?>
							</a>
						</div>
					</div>
					<div class="clr"></div>
				</div>			
			</div>
		</div>
		<div class="clr"></div>
	</div>
</div>