<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	$groupId	The current group id.
 */
defined('_JEXEC') or die();
?>
<div class="cModule">
	<p class="info"><?php echo JText::_('CC EVENT UPLOAD DESC');?></p>
	<form name="jsform-events-uploadavatar" action="<?php echo CRoute::_('index.php?option=com_community&view=events&task=uploadavatar');?>" method="post" enctype="multipart/form-data">
		<?php echo $beforeFormDisplay;?>
	    <input type="file" name="filedata" size="40" class="button" />
	    <?php echo $afterFormDisplay;?>
	    <input type="submit" value="<?php echo JText::_('CC BUTTON UPLOAD');?>" class="button" />
	    <input type="hidden" name="eventid" value="<?php echo $eventId; ?>" />
	    <input type="hidden" name="action" value="avatar"/>
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<p class="info"><?php echo JText::sprintf('CC MAX FILE SIZE FOR UPLOAD' , $uploadLimit ); ?></p>
</div>

<div class="cModule avatarPreview leftside">
	<h3><?php echo JText::_('CC EVENT LARGE AVATAR');?></h3>
	<p><?php echo JText::_('CC EVENT AVATAR NOTE LARGE');?></p>
	<img src="<?php echo $avatar;?>" alt="<?php echo JText::_('CC EVENT LARGE AVATAR');?>" border="0" />
</div>

<div class="cModule avatarPreview rightside">
	<h3><?php echo JText::_('CC EVENT THUMBNAIL AVATAR');?></h3>
	<p><?php echo JText::_('CC EVENT AVATAR NOTE SMALL');?></p>
	<img class="event-thumb" src="<?php echo $thumbnail;?>" alt="<?php echo JText::_('Thumbnail Avatar');?>" border="0" />
</div>