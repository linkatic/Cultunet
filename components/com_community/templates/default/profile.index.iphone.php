<script type="text/javascript" language="javascript">
var wall = {
	toggle: 	function(id) {
		if ( !joms.jQuery('#' + id).hasClass('open') ) {
			joms.jQuery('#' + id).slideDown().addClass('open');
		}
		else {
			joms.jQuery('#'+id).slideUp().removeClass('open');
		}
	}
}; 

<?php if( $isMine ): ?>
var curStatus = '';
var iphone = {
	editStatus:		function() {
		joms.jQuery('#edit-status-overlay').fadeIn();
		joms.jQuery('#status-text').focus();
	},
	cancelUpdate: 	function() {
		joms.jQuery('#edit-status-overlay').fadeOut();
	},
	saveUpdate: 	function() {
		if ( curStatus != joms.jQuery('#status-text').val() ) {
			
			// show loading?
			joms.jQuery('#loading').show();
			
			curStatus = escape(joms.jQuery('#status-text').val());
			jax.call('community', 'status,ajaxUpdate', curStatus);
			
			joms.jQuery('#profile-status-message').html(unescape(curStatus));
			joms.jQuery('#status-text').val(unescape(curStatus));
		}
		joms.jQuery('#loading').hide();
		joms.jQuery('#edit-status-overlay').hide();
	},
	clearField: 	function() {
		joms.jQuery('#status-text').val('').focus();
	}
};
joms.jQuery(document).ready( function() {
	joms.jQuery('#status-text').val('<?php echo addslashes(JText::_($user->_status)); ?>');
});
<?php endif; ?>

function tabToggle(id)
{
	if ( !joms.jQuery('#section-' + id).hasClass('current') )
	{
		var destination = joms.jQuery('#profile-tab').offset().top;
		joms.jQuery("html:not(:animated),body:not(:animated)").animate({ 
			scrollTop: destination
		}, 500, '');
			
		joms.jQuery('.tab-item').removeClass('active');
		joms.jQuery('.section-item').removeClass('current');
		joms.jQuery('.section-item').hide();
		
		joms.jQuery('#section-' + id).addClass('current');
		joms.jQuery('#section-' + id).show();
		joms.jQuery('#' + id).addClass('active');
	}
	return false;
}
joms.jQuery(document).ready( function() {
	joms.jQuery('#section-wall .app-box-header').remove();
});
</script>

<div class="profile-main">
	
	<?php if ( !$isMine ): ?>
	<div class="black-button" id="back-toolbar">
		<a class="btn-blue btn-prev" href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $my->id); ?>">
			<span><?php echo JText::_('CC BACK TO PROFILE'); ?></span>
		</a>
		<div class="clr"></div>
	</div>
	<?php endif; ?>
	
	
	<?php echo @$header; ?>
	<a name="ctab" id="ctab"></a>
	<ul id="profile-tab">
		<li id="activity" class="tab-item active"><a href="javascript:void(0);" onclick="tabToggle('activity');"><?php echo JText::_('CC ACTIVITY'); ?></a></li>
		<li id="wall" class="tab-item"><a href="javascript:void(0);" onclick="tabToggle('wall');"><?php echo JText::_('CC WALL'); ?></a></li>
		<li id="info" class="tab-item"><a href="javascript:void(0);" onclick="tabToggle('info');"><?php echo JText::_('CC INFO'); ?></a></li>
		<li id="friends" class="tab-item"><a href="javascript:void(0);" onclick="tabToggle('friends');"><?php echo JText::_('CC FRIENDS'); ?></a></li>
	</ul>
	<div class="clr">&nbsp;</div>

	<div id="section-activity" style="" class="section-item current">
		<?php echo $newsfeed; ?>
	</div>

	<div id="section-wall" style="display: none;" class="section-item">
		<?php echo $content; ?>
	</div>
	
	<div id="section-info" style="display: none;" class="section-item">
		<?php echo $about; ?>
	</div>
	
	<div id="section-friends" style="display: none;" class="section-item">
		<?php echo $friends; ?>
	</div>
	
</div>

<div class="clr">&nbsp;</div>