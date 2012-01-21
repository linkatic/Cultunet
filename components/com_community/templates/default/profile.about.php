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
<div class="cModule">
	<h3><?php echo JText::_('CC ABOUT ME');?></h3>
	
	<?php if( $isMine ): ?>
	<a class="edit-this" href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=edit');?>" title="<?php echo JText::_('CC EDIT PROFILE'); ?>">[ <?php echo JText::_('CC EDIT PROFILE'); ?> ]</a>
	<?php endif; ?>

	<?php foreach( $profile['fields'] as $groupName => $items ): ?>

	<?php if( $groupName != 'ungrouped' ): ?>
	<h4><?php echo JText::_( $groupName ); ?></h4>
	<?php endif; ?>
	
	<dl class="profile-right-info">


		<?php foreach( $items as $item ): ?>
			<dt><?php echo JText::_( $item['name'] ); ?></dt>
	    	<dd>
	    		<?php if(!empty($item['searchLink'])) :?>
					<a href="<?php echo $item['searchLink']; ?>"> 
				<?php endif; ?>
				
				<?php echo CProfileLibrary::getFieldData( $item['type'] , $item['value'] ); ?>
				
				<?php if(!empty($item['searchLink'])) :?>
					</a> 
				<?php endif; ?>
			</dd>
	    <?php endforeach; ?>
	</dl>
	<?php endforeach; ?>
</div>