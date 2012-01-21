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

<div id="group-discussion-topic">
	<!--Discussion : Avatar-->
	<div class="author-avatar">
		<a href="<?php echo CUrlHelper::userLink($creator->id); ?>"><img class="avatar" src="<?php echo $creator->getThumbAvatar(); ?>" border="0" alt="" /></a>
	</div>
    <!--Discussion : Avatar-->
    
    <!--Discussion : Detail-->
	<div class="discussion-detail">
		<!--Discussion : Author & Date-->
        <div class="discussion-created">
			<?php echo JText::sprintf('CC DISCUSSION STARTED BY ON' , $creatorLink , $creator->getDisplayName() , $discussion->created->toFormat('%d %B %I:%M %p') ); ?>
        </div>
        <!--Discussion : Author & Date-->
        	
        <!--Discussion : Entry-->
        <div class="discussion-entry">
			<?php echo $discussion->message; ?>
        </div>
        <!--Discussion : Entry-->
	</div>
    <!--Discussion : Detail-->
        
	<div style="clear: both;"></div>
</div>

<div class="app-box">
	<div class="wall-tittle"><?php echo JText::_('CC REPLIES'); ?></div>
	<div id="wallForm"><?php echo $wallForm; ?></div>
	<div id="wallContent"><?php echo $wallContent; ?></div>
</div>