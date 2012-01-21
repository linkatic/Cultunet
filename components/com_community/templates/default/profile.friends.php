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

<div class="cModule">
	<h3><span><?php echo JText::_('CC PROFILE FRIENDS'); ?></span></h3>
	<ul class="cThumbList clrfix">
	<?php
	if( $friends )
	{
	?>
		<?php
		for($i = 0; ($i < 12) && ($i < count($friends)); $i++) {
			$friend =& $friends[$i];
		?>
		<li>
			<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$friend->id); ?>"><img alt="<?php echo $friend->getDisplayName();?>" title="<?php echo $friend->getTooltip(); ?>" src="<?php echo $friend->getThumbAvatar(); ?>" class="avatar jomTips" width="45" height="45" /></a>
		</li>
		<?php } ?>
	<?php
	}
	else
	{
	?>
		<li><?php echo JText::_('CC NO FRIENDS YET');?></li>
	<?php
	}
	?>
	</ul>
	<div style="clear: both;"></div>
	<div class="app-box-footer">
		<a href="<?php echo CRoute::_('index.php?option=com_community&view=friends&userid=' . $user->id ); ?>">
			<span><?php echo JText::_('CC SHOW ALL FRIENDS'); ?></span>
			<span>(<?php echo $total;?>)</span>
		</a>
	</div>
	
</div>
