<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 */
defined('_JEXEC') or die();
?>
<div class="empty-message"><?php echo JText::_('CC EVENT CREATED DESCRIPTION');?></div>

<ul class="linklist">
	<li class="upload_avatar">
		<a href="<?php echo $linkUpload; ?>"><?php echo JText::_('CC EVENT UPLOAD NEW AVATAR');?></a>
	</li>
	<li class="event_invite">
		<a href="<?php echo $linkInvite; ?>"><?php echo JText::_('CC INVITE FRIENDS');?></a>
	</li>
	<li class="event_edit">
		<a href="<?php echo $linkEdit;?>">
			<?php echo JText::_('CC EDIT EVENT DETAILS');?>
		</a>
	</li>
	<li class="event_view">
		<a href="<?php echo $link; ?>">
			<?php echo JText::_('CC VIEW EVENT NOW');?>
		</a>
	</li>
</ul>