<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 **/
defined('_JEXEC') or die();
?>
<?php if( $isMine ): ?>
<script type="text/javascript" language="javascript">

joms.jQuery(document).ready(function(){
	
	var profileStatus = joms.jQuery('#profile-new-status');
	var statusText    = joms.jQuery('#statustext');
	var saveStatus    = joms.jQuery('#save-status');

	statusText.data('CC_PROFILE_STATUS_INSTRUCTION', '<?php echo addslashes(JText::_('CC PROFILE STATUS INSTRUCTION')); ?>')
	          .val(statusText.data('CC_PROFILE_STATUS_INSTRUCTION'));
	
	joms.utils.textAreaWidth(statusText);
	joms.utils.autogrow(statusText);

	statusText.focus(function()
	{
		profileStatus.removeClass('inactive');
		statusText.val('');
	}).blur(function()
	{
		if (statusText.val()=='')
		{
			setTimeout(function()
			{
				statusText.val(statusText.data('CC_PROFILE_STATUS_INSTRUCTION'));
				profileStatus.addClass('inactive');
			}, 200);
		}
	});

	saveStatus.click(function()
	{
		var newStatusText = statusText.val();
		jax.call('community', 'status,ajaxUpdate', statusText.val());
		
		/* Update page */
// 		joms.jQuery('#profile-status-message').html(newStatusText);
// 		joms.jQuery('title').val(newStatusText); // Note: This omits out the member name.

		statusText.val('').trigger('blur');
	});
	joms.profile.setStatusLimit( statusText );
});
</script>
<?php endif; ?>

<?php echo $adminControlHTML; ?>

<div id="profile-header">
	<div class="welcometext">
	    <?php if( $isMine ): ?>
	        <?php echo JText::sprintf('CC WELCOME BACK', $user->getDisplayName()); ?>
	    <?php else : ?>
	        <?php echo $user->getDisplayName(); ?>
	    <?php endif; ?>
	</div>

	<div id="profile-status">
		<span id="profile-status-message"><?php echo $user->getStatus(); ?></span>
	</div>
	
	<?php if( $isMine ): ?>
	<div id="profile-new-status" class="inactive clrfix">
		<label for="statustext"><?php echo JText::_('CC MY STATUS');?></label>
		<textarea name="statustext" id="statustext" class="status" rows="" cols="" maxlength="<?php echo $config->get('statusmaxchar');?>"></textarea>
		<button id="save-status" class="button"><?php echo JText::_('CC SAVE'); ?></button>
		<span id="profile-status-notice" class="inactive"><?php echo JText::sprintf('CC CHARACTERS LEFT' , $config->get('statusmaxchar') );?></span>
	</div>
	<?php endif; ?>	  


	<?php if( !$isMine ): ?>
    <div class="js-box-grey rounded5px">
		<?php if(!$isFriend && !$isMine) { ?>
		<a href="javascript:void(0)" class="icon-add-friend profile-action" onclick="joms.friends.connect('<?php echo $profile->id;?>')">
			<span><?php echo JText::_('CC ADD AS FRIEND'); ?></span>
		</a>
		<?php } ?>

		<?php if($config->get('enablephotos')): ?>
		<a class="icon-photos profile-action" href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=myphotos&userid='.$profile->id); ?>">
			<span><?php echo JText::_('CC PHOTO GALLERY'); ?></span>
		</a>
		<?php endif; ?>

		<?php if($showBlogLink): ?>
		<a class="icon-blog profile-action" href="<?php echo JRoute::_('index.php?option=com_myblog&blogger=' . $user->getDisplayName() . '&Itemid=' . $blogItemId ); ?>">
			<span><?php echo JText::_('CC BLOG'); ?></span>
		</a>
		<?php endif; ?>
						
		<?php if($config->get('enablevideos')): ?>
		<a class="icon-videos profile-action" href="<?php echo CRoute::_('index.php?option=com_community&view=videos&task=myvideos&userid='.$profile->id); ?>">
			<span><?php echo JText::_('CC VIDEOS GALLERY'); ?></span>
		</a>
		<?php endif; ?>

		<?php if( !$isMine && $config->get('enablepm') ): ?>
		<a class="icon-write profile-action" onclick="<?php echo $sendMsg; ?>" href="javascript:void(0);">
			<span><?php echo JText::_('CC WRITE MESSAGE'); ?></span>
		</a>
		<?php endif; ?>
	</div>
	<?php else : ?>

    <div id="my-profile-toolbar" class="js-box-grey rounded5px" style="margin: 10px 0 10px;">
		<ul class="actions">
			<li class="profile">
                <a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=edit'); ?>">
                    <span><?php echo JText::_('CC EDIT PROFILE'); ?></span>
                </a>
			</li>
			<li class="avatar">
                <a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=uploadAvatar'); ?>">
                    <span><?php echo JText::_('CC EDIT AVATAR'); ?></span>
                </a>
			</li>
			<li class="privacy">
                <a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=privacy'); ?>">
                    <span><?php echo JText::_('CC EDIT PRIVACY'); ?></span>
                </a>
			</li>
			<?php if($config->get('enablevideos')){ ?>
				<li class="video">
                    <a href="javascript:void(0);" onclick="joms.videos.addVideo();">
                        <span><?php echo JText::_('CC ADD VIDEO'); ?></span>
                    </a>
				</li>
			<?php } ?>
		</ul>
		<ul class="actions">
			<li class="apps">
                <a href="<?php echo CRoute::_('index.php?option=com_community&view=apps&task=browse'); ?>">
                    <span><?php echo JText::_('CC ADD APPLICATIONS'); ?></span>
                </a>
			</li>
			<li class="group">
                <a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=create'); ?>">
                    <span><?php echo JText::_('CC ADD GROUP'); ?></span>
                </a>
			</li>
			<li class="invite">
                <a href="<?php echo CRoute::_('index.php?option=com_community&view=friends&task=invite'); ?>">
                    <span><?php echo JText::_('CC INVITE FRIENDS'); ?></span>
                </a>
			</li>
			<?php if($config->get('createevents') && $config->get('enableevents')){ ?>
				<li class="events">
                    <a href="<?php echo CRoute::_('index.php?option=com_community&view=events&task=create'); ?>">
                        <span><?php echo JText::_('CC ADD EVENT'); ?></span>
                    </a>
				</li>
			<?php } ?>
		</ul>
        <ul class="actions">
        	<?php if( $config->get('enablepm') ){ ?>
			<li class="write">
                <a href="<?php echo CRoute::_('index.php?option=com_community&view=inbox&task=write'); ?>">
                    <span><?php echo JText::_('CC WRITE MESSAGE'); ?></span>
                </a>
			</li>
			<li class="inbox">
                <a href="<?php echo CRoute::_('index.php?option=com_community&view=inbox'); ?>">	                            
                    <span><?php echo JText::_('CC VIEW YOUR INBOX'); ?></span>
                </a>
			</li>
			<?php } ?>
			<?php if($config->get('enablephotos')){ ?>
				<li class="photo">
                    <a href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=uploader&userid='.$profile->id); ?>">
                        <span><?php echo JText::_('CC UPLOAD PHOTOS'); ?></span>
                    </a>
				</li>
			<?php } ?>
		</ul>
        <div class="clr"></div>
	</div>
	
<?php endif; ?>
</div>

<div class="clr"></div>