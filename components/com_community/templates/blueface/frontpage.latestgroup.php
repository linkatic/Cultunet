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
<div class="app-box latest-groups" id="latest-groups">
	<div class="app-box-header">
    	<h2 class="app-box-title"><?php echo JText::_('CC GROUP LATEST'); ?></h2>
	</div>
    
    <div class="app-box-content">
        <ul class="cThumbList clrfix">

		<?php foreach ( $groups as $group ) { ?>
		
		<?php if ( $count == 1 ) { ?>
		<li class="featured">
			<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id); ?>"><img src="<?php echo $group->getAvatar('avatar'); ?>" alt="<?php echo $this->escape($group->name); ?>" class="avatar" /></a>
			<h3 class="title">   
				<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id); ?>"><?php echo $this->escape( $group->name ); ?></a>
			</h3> 
			<div class="desc-details">
				<?php echo JText::_( $this->escape($group->description) ); ?>
			</div>			
		</li>
		<?php } elseif ( $count > 1 ) { ?>
			<li>
				<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id); ?>"><img src="<?php echo $group->getAvatar(); ?>" alt="<?php echo $this->escape($group->name); ?>" class="avatar jomTips" width="45" title="<?php echo $this->escape( JText::_( $group->name ) ); ?>::<?php echo $this->escape( JText::_( $group->description) ); ?>" /></a>
			</li>
		<?php } ?>
		<?php $count++; ?>
		
		<?php } ?>
		
		</ul>
	</div>

	<div class="app-box-footer" style="clear: left;">
	<a href="<?php echo JText::_('index.php?option=com_community&view=groups'); ?>">
		<?php echo JText::_('CC VIEW ALL GROUPS'); ?>
	</a>
	</div>
        
</div>
<?php
} // end if
?>