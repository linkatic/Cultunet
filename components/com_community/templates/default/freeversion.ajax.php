<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
?>
<style type="text/css">
#community-free {
	width: 478px;
	margin: 0 auto;
	overflow: hidden;
}
#community-free .notice {
	width: 90%;
	margin: 0 auto 10px;
	text-align: center;
}
#community-free .notice span {
	font-size: 18px;
	color: #960;
	padding-left: 25px;
	background: transparent url(<?php echo JURI::root(); ?>components/com_community/templates/default/images/free/warning.jpg) no-repeat 0 2px;
	height: 17px;
	line-height: 17px;
	margin: 0 auto;
}
#community-free h2 {
	padding: 8px 10px;
	background: #cdd1c3;
	color: #333;
	margin: 0;
	font-weight: 700;
	font-size: 14px;
}
#community-free .feature {
	margin-left: 20px;
	border-bottom: solid 1px #ddd;
	display: block;
	padding: 10px 4px 10px 0;
	font-size: 11px;
}
#community-free .feature.last {
	margin-right: 0;
	border-right: 0;
	padding-right: 0;
}

#community-free .feature .screenshot {
	margin-bottom: 10px;
}
#community-free .feature h3 {
	font-size: 16px;
	font-weight: normal;
	color: #660;
	margin: 0;
}
#community-free .actions {
	text-align: center;
}
#community-free .actions .buy {
	display: inline;
	margin: 0 auto;
}
#community-free .actions .buy a {
	width: 300px;
	height: 39.5px;
	display: block;
	font-size: 14px;
	line-height: 38px;
	font-weight: 700;
	text-decoration: none;
	margin: 20px auto 10px;
	background: transparent url(<?php echo JURI::root(); ?>components/com_community/templates/default/images/free/buy_s.jpg) no-repeat 0 0;
	color: #454636;
}
#community-free .actions .buy a:hover {
	background-position: 0 -39.5px;
}
#community-free .actions .buy a span {

}
</style>
<div id="community-free">
	
	<div class="notice">
		<span><?php echo JText::_('CC FEATURE NOT AVAILABLE');?></span>
	</div>
	
	<h2><?php echo JText::_('What do you get with Professional version'); ?></h2>
	
	<div class="feature">
		<div style="float: left; width: 130px;">
			<h3>User Groups</h3>
			<div class="screenshot">
				<img src="<?php echo JURI::root(); ?>components/com_community/templates/default/images/free/group_s.jpg" alt="Group" />
			</div>
		</div>
		
		<div style="margin-left: 140px;">
			<div>Unlimited no. of groups</div>
			<div>Unlimited no. group bulletins</div>
			<div>Upload photos & videos</div>
			<div>Configurable privacy settings</div>
		</div>
		<div class="clr"></div>
	</div>
	
	<div class="feature">
		<div style="float: left; width: 130px;">
			<h3>Photo Galleries</h3>
			<div class="screenshot">
				<img src="<?php echo JURI::root(); ?>components/com_community/templates/default/images/free/photo_s.jpg" alt="Photo Galleries" />
			</div>
		</div>
		
		<div style="margin-left: 140px;">
			<div>Unlimited no. of photo galleries</div>
			<div>Unlimited no. of photo uploads</div>
			<div>Powered by Ajax slideshow</div>
			<div>Set who can view your photos</div>
		</div>
		<div class="clr"></div>
	</div>
	
	<div class="feature">
		<div style="float: left; width: 130px;">
			<h3>Video Galleries</h3>
			<div class="screenshot">
				<img src="<?php echo JURI::root(); ?>components/com_community/templates/default/images/free/video_s.jpg" alt="Video Galleries" />
			</div>
		</div>
		
		<div style="margin-left: 140px;">
			<div>Unlimited no. of video galleries</div>
			<div>Unlimited no. of uploads</div>
			<div>Link videos from YouTube or others</div>
			<div>Comments on user videos</div>
		</div>
		<div class="clr"></div>
	</div>

	
	<div class="actions">
		<div class="buy">
			<a href="http://www.jomsocial.com/buy-now.html" target="_blank">
				<span>Yes, I want to buy Professional version</span>
			</a>
		</div>
		
		<div class="more">
			<a href="http://www.jomsocial.com/overview.html" target="_blank">
				I would like to see complete list of JomSocial’s features
			</a>
		</div>
	</div>
	
</div>