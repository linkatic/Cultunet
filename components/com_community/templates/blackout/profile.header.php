<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 **/
defined('_JEXEC') or die();
?>


<div id="cHeading">

<div class="cProfile">	
	<table cellpadding="0" cellspacing="0">
	<tr>
		<td class="profile-avatar">
		<?php if( $isMine ): ?><a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=edit'); ?>"><?php endif; ?><img src="<?php echo $profile->largeAvatar; ?>" alt="<?php echo $user->getDisplayName(); ?>" /><?php if( $isMine ): ?></a><?php endif; ?>
		<?php if( $config->get('enablevideos') ){ ?>
                <?php if( $config->get('enableprofilevideo') ){ ?>
		<div style="padding-top: 5px; text-align: center;"><a class="icon-videos" style="margin: 0;" onclick="joms.videos.playProfileVideo( <?php echo $profile->profilevideo; ?> , <?php echo $user->id; ?> )" href="javascript:void(0);"><?php echo JText::_('CC MY PROFILE VIDEO');?></a></div>
		<?php } ?>
                <?php } ?>
		</td>
		<td class="profile-summary">
			<h2 class="profileName"><?php echo $user->getDisplayName(); ?></h2>
			<?php echo $adminControlHTML; ?>
			<div id="profileStatus">
				<textarea name="" id="profileStatusText" rows="" cols="" maxlength="<?php echo $config->get('statusmaxchar');?>"><?php echo $user->getStatus(); ?></textarea>
				<button id="save-status" class="button"><?php echo JText::_('Save'); ?></button>
				<span id="profile-status-notice"><?php echo JText::sprintf('CC CHARACTERS LEFT' , $config->get('statusmaxchar') );?></span>
			</div>
		</td>
		<td class="profile-details">
			<table cellpadding="0" cellspacing="0">
				<?php if($config->get('enablekarma')){ ?>
				<tr class="profile-detail">
					<td class="profile-detail-title"><?php echo JText::_('CC KARMA'); ?></td>
					<td><img src="<?php echo $karmaImgUrl; ?>" alt="" /></td>
				</tr>
				<?php } ?>
		
				<tr class="profile-detail">
					<td class="profile-detail-title"><?php echo JText::_('CC PROFILE VIEW'); ?></td>
					<td><?php echo JText::sprintf('CC PROFILE VIEW RESULT', $user->getViewCount() ) ;?></td>
				</tr>

				<tr class="profile-detail">
				    <td class="profile-detail-title"><?php echo JText::_('CC MEMBER SINCE'); ?></td>
				    <td><?php echo JHTML::_('date', $registerDate , JText::_('DATE_FORMAT_LC2')); ?></td>
			    </tr>
			    
			    <tr class="profile-detail">
					<td class="profile-detail-title"><?php echo JText::_('CC LAST LOGIN'); ?></td>
					<td><?php echo $lastLogin; ?></td>
			    </tr>
			</table>
		</td>
	</tr>
	</table>
</div>

</div>


<div class="cToolbarBand profile-actions">
	<div class="bandContent">
	<?php if( !$isMine ): ?>
	<ul class="small-button">
		<?php if(!$isFriend && !$isMine) { ?>
	    <li class="btn-add-friend">
			<a href="javascript:void(0)" onclick="joms.friends.connect('<?php echo $profile->id;?>')"><span><?php echo JText::_('CC ADD AS FRIEND'); ?></span></a>
		</li>
		<?php } ?>
	
		<?php if($config->get('enablephotos')): ?>
	    <li class="btn-gallery">
			<a href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=myphotos&userid='.$profile->id); ?>">
				<span><?php echo JText::_('CC PHOTO GALLERY'); ?></span>
			</a>
		</li>
		<?php endif; ?>
	
		<?php if($showBlogLink): ?>
	    <li class="btn-blog">
			<a href="<?php echo CRoute::_('index.php?option=com_myblog&blogger=' . $user->getDisplayName() . '&Itemid=' . $blogItemId ); ?>">
				<span><?php echo JText::_('CC BLOG'); ?></span>
			</a>
		</li>
		<?php endif; ?>
						
		<?php if($config->get('enablevideos')): ?>
	    <li class="btn-videos">
			<a href="<?php echo CRoute::_('index.php?option=com_community&view=videos&task=myvideos&userid='.$profile->id); ?>">
				<span><?php echo JText::_('CC VIDEOS GALLERY'); ?></span>
			</a>
		</li>
		<?php endif; ?>
	
		<?php if( !$isMine && $config->get('enablepm')): ?>
	    <li class="btn-write-message">
			<a onclick="<?php echo $sendMsg; ?>" href="javascript:void(0);">
				<span><?php echo JText::_('CC WRITE MESSAGE'); ?></span>
			</a>
		</li>
		<?php endif; ?>
	</ul>
	<?php else : ?>
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
	    	<?php if( $config->get('enablepm')){ ?>
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
	
		<div style="clear: left; margin-bottom: 10px;"></div>
	<?php endif; ?>
	</div>
	<div class="bandFooter"><div class="bandFooter_inner"></div></div>
</div>





<?php if ( $isMine ) { ?>
<script type="text/javascript" language="javascript">
joms.jQuery(document).ready(function(){

	var profileStatus = joms.jQuery('#profileStatus');
	var statusText    = joms.jQuery('#profileStatusText');
	var saveStatus    = joms.jQuery('#save-status');
    var editStatus    = joms.jQuery('#edit-status');
    var setBlankModeTimer;

    statusText.data('CC_PROFILE_STATUS_INSTRUCTION', '<?php echo addslashes(JText::_('CC PROFILE STATUS INSTRUCTION')); ?>')

    function setBlankMode() {    	
        if (joms.jQuery.trim(statusText.val())=='')
        {
            profileStatus.removeClass('editMode')
                         .addClass('blankMode');
            statusText.val(statusText.data('CC_PROFILE_STATUS_INSTRUCTION'));
            updateTextarea();
        } else {
            profileStatus.removeClass('editMode'); 	
        	statusText.val(statusText.data('oldStatusText'));
            updateTextarea();
        }
    }

    function setEditMode()
    {
    	statusText.data('oldStatusText', statusText.val());
    	
        if (profileStatus.hasClass('blankMode'))
        {
            statusText.val('');
        } else {
            statusText.select();
        }

        profileStatus.removeClass('blankMode')
                     .addClass('editMode');

        updateTextarea();
    }
    
    function updateTextarea()
    {
        joms.utils.textAreaWidth(statusText);
        joms.utils.autogrow(statusText);   
    }

    // First time init
    setBlankMode();
    updateTextarea();

    statusText.focus(function()
    {
        setEditMode();
    }).blur(function()
    {
        setBlankModeTimer = setTimeout(function(){setBlankMode();}, 200);
    });

    saveStatus.click(function()
    {
    	clearTimeout(setBlankModeTimer);
    	
        var newStatusText = statusText.val();

        if (newStatusText!=statusText.data('oldStatusText'))
        {
            jax.call('community', 'status,ajaxUpdate', statusText.val());
            joms.jQuery('#profile-status-message').html(newStatusText);
        }
        
        profileStatus.removeClass('editMode');
    });
    
    editStatus.click(function()
    {
        statusText.trigger('focus');
    });
    
    joms.profile.setStatusLimit( statusText );
});
</script>
<?php } else { ?>
<script type="text/javascript" language="javascript">
joms.jQuery(document).ready(function(){
	var statusText = joms.jQuery('#profileStatusText');
	
	setTimeout(function(){
		joms.utils.textAreaWidth(statusText);
	}, 1000);
});
</script>
<?php } ?>