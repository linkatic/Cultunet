<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	applications	An array of applications object
 */
defined('_JEXEC') or die();

if( !empty( $apps ) )
{
	foreach( $apps as $app )
	{
?>
	<?php if ($itemType=='edit') { ?>
		<div class="app-item <?php if ($app->isCoreApp) echo 'app-core'; ?> app-item-edit" id="app-<?php echo $app->id; ?>">
			<?php if( !$app->isCoreApp ) { ?>
			<span class="app-actions joms_floatLeft joms_positionAbsolute">	
				<a class="app-action-remove" href="javascript:void(0);" onclick="joms.apps.windowTitle= '<?php echo JText::_('CC APPLICATION AJAX REMOVED');?>';joms.apps.remove('<?php echo $app->id; ?>');" title="<?php echo JText::_('CC APPLICATION BTN REMOVE'); ?>"><span><?php echo JText::_('CC APPLICATION LIST REMOVE'); ?></span></a>
			</span>
			<?php } ?>
		
			<span class="app-avatar"><img src="<?php echo $app->favicon['16']; ?>" alt="<?php echo $this->escape($app->title); ?>" /></span>
			<span class="app-title"><?php echo $this->escape($app->title); ?></span>
			<span class="app-actions joms_floatRight joms_positionAbsolute">
				<a class="app-action-about" href="javascript:void(0);" onclick="joms.apps.showAboutWindow('<?php echo $app->name; ?>');"><span><?php echo JText::_('CC APPLICATION LIST ABOUT'); ?></span></a>
				<a class="app-action-settings" href="javascript:void(0);" onclick="joms.apps.showSettingsWindow('<?php echo $app->id; ?>','<?php echo $app->name; ?>');"><span><?php echo JText::_('CC APPLICATION COLUMN SETTINGS'); ?></span></a>
				<a class="app-action-privacy" href="javascript:void(0);" onclick="joms.apps.showPrivacyWindow('<?php echo $app->name; ?>');"><span><?php echo JText::_('CC APPLICATION COLUMN PRIVACY'); ?></span></a>
			</span>
		</div>
	<?php } ?>
	
	<?php if ($itemType=='browse') { ?>
		<div class="app-item <?php echo $this->escape($app->name); ?> app-item-browse">
			<span class="app-avatar"><img width="48" src="<?php echo $app->favicon['64']; ?>" alt="<?php echo $this->escape($app->title); ?>" /></span>
			<span class="app-title"><?php echo $this->escape($app->title); ?></span>
			<span class="app-description"><?php echo $this->escape($app->description); ?></span>
			<span class="app-actions">
				<a class="app-action-add" href="javascript:void(0);" onclick="joms.editLayout.addApp('<?php echo $this->escape($app->name); ?>', '<?php echo $app->position; ?>');"><?php echo JText::_('CC APPLICATION LIST ADD'); ?></a>
			</span>
		</div>
	<?php } ?>
<?php
	}
}
else
{
?>
<div class="community-empty-list"><?php echo JText::_('CC NO MORE APPS TO BE ADDED');?></div>
<?php
}
?>