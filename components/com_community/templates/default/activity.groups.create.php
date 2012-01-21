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
<div>
	<div style="float:left;padding-right:8px;position:relative" ><a href="<?php echo CRoute::_( 'index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $group->id );?>"><img class="avatar" alt="<?php echo $this->escape($group->name );?>" src="<?php echo $group->getThumbAvatar();?>" /></a></div>
	<?php echo JString::substr($group->description , 0, $config->getInt('streamcontentlength'));?> ...
	<div class="clr"></div>
</div>