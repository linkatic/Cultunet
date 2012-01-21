<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @params	isAdmin		boolean is this group belong to me
 * @params	members		An array of member objects 
 * @params	title		A string that represents the title of the discussion 
 * @params	parentid	An integer value of the discussion parent. 
 * @params	groupid		An integer value of the discussion's group id. 
 */
defined('_JEXEC') or die();
?>
<div class="page-actions">
    <?php echo $reportHTML;?>
    <?php echo $bookmarksHTML;?>
    <div class="clr"></div>
</div>
<div id="discussTopic">
	<div style="float: left; width: 90px;">
		<img src="<?php echo $creator->getThumbAvatar(); ?>" alt=""/><br />
	</div>
	<div style="margin-left: 5px;">
		<div>
			<?php echo $discussion->message; ?>
		</div>
	</div>
	<div style="clear: both;"></div>
	<div style="border-top: 1px solid #EEE;margin-top: 15px; text-align: right; margin-bottom: 15px;">
		<div style="color: gray;">
			<?php echo JText::sprintf('CC DISCUSSION STARTED BY ON' , $creatorLink , $creator->getDisplayName() , $discussion->created->toFormat('%d %B %I:%M %p') ); ?>
		</div>
	</div>
</div>

<div class="app-box">
	<div class="ctitle"><span class="createdate"><?php echo JText::_('CC WALL'); ?></span></div>
	<div>
		<?php echo $wallForm; ?>
		<div id="wallContent">
			<?php echo $wallContent; ?>
		</div>
	</div>
</div>