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
			<h3 class="frontTitle"><?php echo JText::_('CC GROUP LATEST'); ?></h3>
			<div class="group-list">
				<ul class="avatar-list">
<?php
	foreach ( $groups as $group ) {
		
		if ( $count == 1 ) {
?>
					<li class="first pos-rel">
						<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id); ?>" class="avatar-link">
							<!--<span class="tag-new"></span>-->
							<img src="<?php echo JURI::root() . '/' . $group->avatar; ?>" alt="<?php echo $this->escape($group->name); ?>" class="profile-avatar" style="float: none;" />
						</a>
						
						<h3 class="title">
							<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id); ?>">
								<?php echo JText::_($this->escape($group->name)); ?>
							</a>
						</h3>
						<div class="desc-title">
							<?php echo JText::_('CC GROUP INFO DESCRIPTION'); ?>
						</div>
						<div class="desc-details">
							<?php echo JText::_( $this->escape($group->description) ); ?>
						</div>
					</li>
					<?php if ( count($groups) > 1 )  { ?>
					<li>
						<div class="desc-title">
							<?php echo JText::_('CC GROUP INFO OTHERS'); ?>
						</div>
					</li>
					<?php } ?>

		<?php
		}
		else {
		?>
					<li>
						<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id); ?>">
							<img src="<?php echo JURI::root() . '/' . $group->thumb; ?>" alt="<?php echo $this->escape($group->name); ?>" class="avatar jomTips" width="45" title="<?php echo htmlspecialchars(JText::_($this->escape($group->name))); ?>::<?php echo JText::_($this->escape($group->description)); ?>" />
						</a>
					</li>
		<?php
		}
		$count++;
	} // end foreach
?>
				</ul>
			</div>
<?php
} // end if
?>

			<div style="text-align: right;">
				        <a href="<?php echo JText::_('index.php?option=com_community&view=groups'); ?>"><?php echo JText::_('CC VIEW ALL GROUPS'); ?></a>
		    </div>