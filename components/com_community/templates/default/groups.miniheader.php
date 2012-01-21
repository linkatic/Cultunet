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
<div class="profile-toolbox-bl" id="miniheader">
    <div class="profile-toolbox-br">
        <div class="profile-toolbox-tl">
            <div style="float: left; padding: 5px;">
				<div style="float: left; width: 40px;">
				    <a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id); ?>">
						<img width="32" src="<?php echo $group->getAvatar(); ?>" alt="<?php echo $this->escape($group->name); ?>" class="avatar" />
					</a>
				</div>
				<div style="margin: 0 0 0 50px;">
					<div><strong><span class="profile-toolbox-name"><?php echo $this->escape($group->name); ?></span></strong><br /></div>
					<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id); ?>">
						<?php echo JText::_('CC GO TO GROUP'); ?>
					</a>
				</div>
			</div>
			
			<div style="float: right;">
				<ul class="small-button" style="padding-top:5px;">

					<?php if($config->get('groupphotos')): ?>
				    <li class="btn-gallery">
						<a href="<?php echo CRoute::_('index.php?option=com_community&view=photos&groupid='.$group->id); ?>">
							<span><?php echo JText::_('CC PHOTO GALLERY'); ?></span>
						</a>
					</li>
					<?php endif; ?>

					<?php if($config->get('groupvideos')): ?>
				    <li class="btn-videos">
						<a href="<?php echo CRoute::_('index.php?option=com_community&view=videos&groupid='.$group->id); ?>">
							<span><?php echo JText::_('CC VIDEOS GALLERY'); ?></span>
						</a>
					</li>
					<?php endif; ?>

					<?php if($config->get('creatediscussion')): ?>
				    <li class="btn-discussions">
						<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewdiscussions&groupid='.$group->id); ?>">
							<span><?php echo JText::_('CC GROUP DISCUSSIONS'); ?></span>
						</a>
					</li>
					<?php endif; ?>

				    <li class="btn-members">
						<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewmembers&groupid='.$group->id); ?>">
							<span><?php echo JText::_('CC MEMBERS'); ?></span>
						</a>
					</li>

				</ul>
			</div>
			<div style="clear: both;"></div>
		</div>
	</div>
</div>
