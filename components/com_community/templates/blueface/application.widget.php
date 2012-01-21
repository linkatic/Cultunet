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
<!-- begin: .app-widget -->
<div id="<?php echo 'jsapp-' . $app->id; ?>" class="<?php if($app->core) echo " app-core";  ?> app-widget app-box">
	
	<!-- begin: .app-widget-header -->
	<div class="app-widget-header">
		<div class="app-box-header">
			<h2 class="app-box-title"><span class="app-widget-title"><?php echo $app->title; ?></span></h2>
			<?php if( $isOwner && $app->hasConfig ){ ?>
    		  <a title="<?php echo JText::_('CC EDIT');?>" href="javascript:void(0);" class="app-box-menu-icon" onclick="joms.apps.showSettingsWindow('<?php echo $app->id;?>','<?php echo $app->name;?>');"></a>
    		<?php } ?>
		</div>
	</div>
	<!-- end: .app-widget-header -->
	
	<!-- begin: .app-widget-content -->
	<div class="app-widget-content">
		<?php echo $app->data; ?>
	</div>
	<!-- end: .app-widget-content -->
</div>
<!-- end: .app-widget -->

