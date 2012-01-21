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
<ul class ="cDetailList clrfix">
	<li class="avatarWrap">
		<img style="width:64px" src="<?php echo $event->getThumbAvatar();?>" />
	</li>
	<li class="detailWrap">
	<?php echo JString::substr(strip_tags($event->description) , 0, $config->getInt('streamcontentlength'));?> ...
	</li>
</ul>