<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	profile			A Profile object that contains profile fields for this specific user
 * @param	profile->
 * @params	isMine		boolean is this profile belongs to me?
 */
defined('_JEXEC') or die();
?>
<div class="profile-info-iphone activity-stream-profile">
	<div class="appsBoxTitle"><?php echo JText::_('CC ABOUT ME');?></div>
	<?php if( $isMine ): ?>
	<!--
	<span>
		<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=edit');?>">[ <?php echo JText::_('CC EDIT PROFILE');?> ]</a>
	</span>
	-->
	<?php endif; ?>

	<?php foreach( $profile['fields'] as $groupName => $items ): ?>
	<ul class="profile-right-info">
		<?php if( $groupName != 'ungrouped' ): ?>
			<li class="infoGroupTitle"><?php echo ($groupName != 'ungrouped') ? JText::_( $groupName ) : ''; ?></li>
		<?php endif; ?>

		<?php foreach( $items as $item ): ?>
			<li class="infoTitle"><?php echo JText::_( $item['name'] ); ?></li>
	    	<li class="infoDesc">
	    		<?php if(!empty($item['searchLink'])) :?>
					<a href="<?php echo $item['searchLink']; ?>"> 
				<?php endif; ?>
				
				<?php echo CProfileLibrary::getFieldData( $item['type'] , $item['value'] ); ?>
				
				<?php if(!empty($item['searchLink'])) :?>
					</a> 
				<?php endif; ?>
			</li>
	    <?php endforeach; ?>
	</ul>
	<?php endforeach; ?>
	<div class="profile-info-iphone-overlay"></div>
</div>