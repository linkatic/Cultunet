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
<div class="empty-message"><?php echo JText::_('CC GROUP CREATED DESCRIPTION');?></div>

<ul class="linklist">
	<li class="upload_avatar">
		<a href="<?php echo $linkUpload; ?>"><?php echo JText::_('CC GROUP UPLOAD NEW AVATAR');?></a>
	</li>
	<li class="add_news">
		<a href="<?php echo $linkBulletin; ?>"><?php echo JText::_('CC GROUP POST NEW BULLETIN');?></a>
	</li>
	<li class="group_edit">
		<a href="<?php echo $linkEdit;?>">
			<?php echo JText::_('CC EDIT GROUP DETAILS');?>
		</a>
	</li>
	<li class="group_view">
		<a href="<?php echo $link; ?>">
			<?php echo JText::_('CC VIEW GROUP NOW');?>
		</a>
	</li>
</ul>