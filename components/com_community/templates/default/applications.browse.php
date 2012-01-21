<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	applications	An array of applications object
 * @param	pagination		JPagination object 
 */
defined('_JEXEC') or die();
?>

<?php
if( $applications )
{
	foreach( $applications as $application )
	{
?>
		
<div class="app-item <?php echo $this->escape($application->name); ?>">

	<div class="app-avatar">
		<img src="<?php echo $application->appFavicon; ?>" alt="<?php echo $this->escape($application->title); ?>" />
	</div>

	<h3><?php echo $application->title; ?></h3>
	
	<div class="app-item-description">
		<?php echo $this->escape($application->description); ?>
	</div>
	
	<div class="app-item-details">
		<span style="margin-right: 10px;"><?php echo JText::_('CC APPLICATION COLUMN DATE'); ?>: <strong><?php echo $application->creationDate; ?></strong></span>
		<span style="margin-right: 10px;"><?php echo JText::_('CC APPLICATION COLUMN VERSION'); ?>: <strong><?php echo $application->version; ?></strong></span>
		<?php if($this->params->get('appsShowAuthor')) { ?>
			<span><?php echo JText::_('CC APPLICATION COLUMN AUTHOR'); ?>: <strong><?php echo $application->author; ?></strong></span>
		<?php } ?>	
	</div>
	
	<?php if( !$application->added && !$application->coreapp ) { ?>
		
		<?php if( !COMMUNITY_FREE_VERSION || ( COMMUNITY_FREE_VERSION && $addedAppCount < COMMUNITY_FREE_VERSION_APPS_LIMIT ) ){ ?>
			<a class="added-button" href="javascript:void(0);" onclick="joms.apps.add('<?php echo $this->escape($application->name); ?>')" title="<?php echo JText::_('CC APPLICATION BTN ADD'); ?>">
			   	<?php echo JText::_('CC APPLICATION LIST ADD'); ?>
		   	</a>
		<?php } ?>
	
	<?php } else { ?>
	
	<span class="added-ribbon">
	   	<?php echo JText::_('CC APPLICATION LIST ADDED'); ?>
	</span>	
	
	<?php } ?>
</div>	

<?php 
	}
}
else
{
?>
<div class="app-item">
	<div class="app-item-description"><?php echo JText::_('CC NO APPLICATIONS INSTALLED');?></div>
</div>
<?php
}
?>
<div class="pagination-container">
	<?php echo $pagination->getPagesLinks(); ?>
</div>