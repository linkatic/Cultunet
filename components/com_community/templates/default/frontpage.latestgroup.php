<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 */
defined('_JEXEC') or die();

if ( !empty( $groups ) ) {
	$count = 1;
?>	
		<h3><span><?php echo JText::_('CC GROUP LATEST'); ?></span></h3>
		<ul class="cThumbList clrfix">

		<?php foreach ( $groups as $group ) { ?>
		
		<?php if ( $count == 1 ) { ?>
		<li class="featured">
			<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id); ?>"><img src="<?php echo $group->getAvatar('avatar'); ?>" alt="<?php echo $this->escape($group->name); ?>" /></a>
			<h3 class="title">   
				<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id); ?>"><?php echo $this->escape( $group->name ); ?></a>
			</h3> 
			<div class="desc-details">
				<?php echo substr(JText::_( $this->escape($group->description) ), 0, 150).'...'; ?>
			</div>			
		</li>
		<?php } elseif ( $count > 1 ) { ?>
			<li>
				<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id); ?>"><img src="<?php echo $group->getAvatar(); ?>" alt="<?php echo $this->escape($group->name); ?>" class="avatar jomTips" width="45" title="<?php echo htmlspecialchars( JText::_( $this->escape($group->name) )); ?>::<?php echo JText::_( $this->escape($group->description) ); ?>" /></a>
			</li>
		<?php } ?>
		<?php $count++; ?>
		
		<?php } ?>
		
		</ul>

		<div class="app-box-footer">
	        <a href="<?php echo JText::_('index.php?option=com_community&view=groups'); ?>"><?php echo JText::_('CC VIEW ALL GROUPS'); ?></a>
	    </div>

<?php
}
?>