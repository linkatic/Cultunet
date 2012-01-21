<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	groups		Array	Array of groups object
 * @param	total		integer total number of groups
 * @param	user		CFactory User object 
 */
defined('_JEXEC') or die();
?>
<div class="appsBoxTitle"><?php echo JText::_('CC PROFILE GROUPS'); ?></div>
<div class="small"><?php echo JText::sprintf((CStringHelper::isPlural($total)) ? 'CC GROUPS COUNT MANY' : 'CC GROUPS COUNT', $total); ?></div>
<ul class="friend-right-info">
	<?php
	for($i = 0; ($i < 12) && ($i < count($groups)); $i++)
	{
		$row	=& $groups[$i];
	?>
	<li>
		<a href="<?php echo $row->link;?>">
			<img title="<?php echo $this->escape($row->name); ?>::<?php echo $this->escape($row->description); ?>" alt="<?php echo $this->escape($row->name); ?>" src="<?php echo $row->avatar; ?>" class="avatar jomTips"/>
		</a>
	</li>
	<?php
	}
	?>
</ul>
<div style="clear: both;"></div>
<div style="text-align:right;">
	<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&userid=' . $user->id ); ?>">
		<?php echo JText::_('CC SHOW ALL GROUPS'); ?>
	</a>
</div>
	
