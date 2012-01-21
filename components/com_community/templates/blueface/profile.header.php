<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 **/
defined('_JEXEC') or die();
?>
<?php echo $adminControlHTML; ?>

<div id="profile-header">
    <div class="welcometext">
        <?php if( $isMine ): ?>
            <?php echo JText::sprintf('CC WELCOME BACK', $this->escape( $user->getDisplayName()) ); ?>
        <?php else : ?>
            <?php echo $this->escape( $user->getDisplayName() ); ?>
        <?php endif; ?>
    </div>
        
	<!-- TODO: Message that shows that message is updated -->
	<div id="message" class="notice" style="display: none;"><?php echo JText::_('CC STATUS UPDATED'); ?></div>

    <?php if ( $isMine ) : ?>
    <!-- begin: #profile-new-status -->
    <script type="text/javascript" language="javascript">
    joms.jQuery(document).ready(function(){
        
        var profileStatus = joms.jQuery('#profile-new-status');
        var statusText    = joms.jQuery('#statustext');
        var saveStatus    = joms.jQuery('#save-status');
        var editStatus    = joms.jQuery('#edit-status');

        statusText.data('CC_PROFILE_STATUS_INSTRUCTION', '<?php echo addslashes(JText::_('CC PROFILE STATUS INSTRUCTION')); ?>')

        function setBlankMode() {
            if (joms.jQuery.trim(statusText.val())=='')
            {
                profileStatus.removeClass('editMode')
                             .addClass('blankMode');
                statusText.val(statusText.data('CC_PROFILE_STATUS_INSTRUCTION'));
                updateTextarea();
            }
        }

        function setEditMode()
        {
            if (profileStatus.hasClass('blankMode'))
            {
                statusText.val('');
            } else {
                statusText.select();
            }
            
            profileStatus.removeClass('blankMode')
                         .addClass('editMode');
                         
            statusText.data('oldStatusText', statusText.val());

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
            setTimeout(function(){setBlankMode();}, 200);
        });
    
        saveStatus.click(function()
        {
            var newStatusText = statusText.val();

            if (newStatusText!=statusText.data('oldStatusText'))
            {
                jax.call('community', 'status,ajaxUpdate', statusText.val());
//                 joms.jQuery('#profile-status-message').html(newStatusText);
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

    <div id="profile-new-status">
        <textarea name="statustext" id="statustext" class="status" rows="" cols="" maxlength="<?php echo $config->get('statusmaxchar');?>"><?php echo empty( $user->_status ) ? '' : $user->_status; ?></textarea>
        <span id="profile-status-notice"><?php echo JText::sprintf('CC CHARACTERS LEFT' , $config->get('statusmaxchar') );?></span>
        <a id="save-status" class="button"><?php echo JText::_('Save'); ?></a>
        <a id="edit-status" class="button"><?php echo JText::_('Edit'); ?></a>
    </div>
    <?php else : ?>
        <?php if ( !empty( $user->_status ) ) : ?>
        <div id="profile-status-message" class="statustext"><?php echo $user->getStatus(); ?></div>
        <?php endif; ?>
    <?php endif; ?>

	<?php if( !$isMine ): ?>
	<ul class="profile-actions">
		<!-- Add Friend -->
		<?php if(!$isFriend && !$isMine) { ?>
	    <li class="profile-action add-friend">
	        <a class="icon-add-friend" href="javascript:void(0)" onclick="joms.friends.connect('<?php echo $profile->id;?>')">
	            <span><?php echo JText::_('CC ADD AS FRIEND'); ?></span>
	        </a>
	    </li>
		<?php } ?>
	    
		<!-- Gallery -->
		<?php if($config->get('enablephotos')): ?>
	    <li class="profile-action gallery">
	        <a class="icon-photos" href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=myphotos&userid='.$profile->id); ?>">
	            <span><?php echo JText::_('CC PHOTO GALLERY'); ?></span>
	        </a>
	    </li>
		<?php endif; ?>
	
		<!-- Blog -->
		<?php if($showBlogLink): ?>
	    <li class="profile-action blog">
			<a class="icon-blog" href="<?php echo JRoute::_('index.php?option=com_myblog&blogger=' . $user->getDisplayName() . '&Itemid=' . $blogItemId ); ?>">
				<span><?php echo JText::_('CC BLOG'); ?></span>
			</a>
		</li>
		<?php endif; ?>
					
		<!-- Videos -->                                	
		<?php if($config->get('enablevideos')): ?>
	    <li class="profile-action videos">
			<a class="icon-videos" href="<?php echo CRoute::_('index.php?option=com_community&view=videos&task=myvideos&userid='.$profile->id); ?>">
				<span><?php echo JText::_('CC VIDEOS GALLERY'); ?></span>
			</a>
		</li>
		<?php endif; ?>
	
		<!-- Write Message -->
		<?php if( !$isMine && $config->get('enablepm') ): ?>
	    <li class="profile-action write-message">
	        <a class="icon-write" onclick="<?php echo $sendMsg; ?>" href="javascript:void(0);">
	            <span><?php echo JText::_('CC WRITE MESSAGE'); ?></span>
	        </a>
	    </li>
		<?php endif; ?>

		<div style="clear: left;"></div>		
	</ul>
	<?php else : ?>
	<div id="profile-toolbar">
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
		
		<div style="clear: left;"></div>
	</div>
<?php endif; ?>
</div>
