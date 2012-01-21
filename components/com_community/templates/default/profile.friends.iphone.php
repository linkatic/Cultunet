<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	friends		array or CUser (all user)
 * @param	total		integer total number of friends
 * @param	user		CFactory User object 
 */
defined('_JEXEC') or die();
?>
<div class="appsBoxTitle"><?php echo JText::_('CC PROFILE FRIENDS'); ?></div>
<div class="small" style="padding: 0 5px;"><?php echo JText::sprintf((CStringHelper::isPlural($total)) ? 'CC TOTAL FRIENDS COUNT MANY': 'CC TOTAL FRIENDS COUNT' , $total); ?></div>
<ul class="avatar-list">
	<?php
	for($i = 0; ($i < 12) && ($i < count($friends)); $i++) {
		$friend =& $friends[$i];
	?>
	<li class="avatar-list-item">
		<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$friend->id); ?>">
			<img width="45" alt="<?php echo $friend->getDisplayName(); ?>" src="<?php echo $friend->getThumbAvatar(); ?>" class="avatar"/>
		</a>
	</li>
	<?php } ?>
</ul>
<div style="clear: both;"></div>
<div class="app-box-footer">
	<a href="<?php echo CRoute::_('index.php?option=com_community&view=friends&userid=' . $user->id ); ?>">
		<?php echo JText::_('CC SHOW ALL FRIENDS'); ?>
	</a>
</div>
	
