
	
	<?php if( $isMine ): ?>
	<!-- show edit status link -->
	<div id="edit-status-overlay" style="display: none;">
		
		<div class="overlay"></div>
		<div class="form-container">
			<div><textarea id="status-text"></textarea></div>

			<button onclick="iphone.saveUpdate();">
				<?php echo JText::_('CC IPHONE SAVE UPDATE'); ?>
			</button>
			
			<button onclick="iphone.cancelUpdate();">
				<?php echo JText::_('CC IPHONE CANCEL UPDATE'); ?>
			</button>
			
			<button onclick="iphone.clearField();">
				<?php echo JText::_('CC IPHONE BUTTON CLEAR'); ?>
			</button>		
		</div>
		<div id="loading" class="overlay loading" style="display: none;"></div>
	</div>
	<?php endif; ?>

	<?php //echo $adminControlHTML; ?>
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
				<span id="profile-status-message"><?php echo $this->escape($profile->status); ?></span>
			</div>
		</div>
		<div class="clr">&nbsp;</div>
		<?php if( $isMine ): ?>
		<a name="edit-link" id="edit-link" href="javascript:void(0);" onclick="iphone.editStatus();">&nbsp;</a>
		<?php endif; ?>
	</div>

	<div class="profile-details-box">
		<ul class="profile-details">
		    <li class="title"><?php echo JText::_('CC KARMA'); ?></li>
		    <li>&nbsp;<img src="<?php echo $karmaImgUrl; ?>" alt="" /></li>
	
		    <li class="title"><?php echo JText::_('CC MEMBER SINCE'); ?></li>
		    <li><?php echo JHTML::_('date', $registerDate , JText::_('DATE_FORMAT_LC2')); ?></li>
	
		    <li class="title"><?php echo JText::_('CC LAST LOGIN'); ?></li>
		    <li><?php echo $lastLogin; ?></li>
	
		    <li class="title"><?php echo JText::_('CC PROFILE VIEW'); ?></li>
		    <li><?php echo JText::sprintf('CC PROFILE VIEW RESULT', $user->getViewCount() ) ;?></li>
		</ul>
		<div class="clr">&nbsp;</div>
	</div>