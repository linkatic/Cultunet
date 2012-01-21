<?php
/**
 * @package	JomSocial
 * @subpackage Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
?>
<form method="post" action="<?php echo CRoute::getURI();?>" name="saveProfile">
<?php echo $beforeFormDisplay;?>
<?php if( $jConfig->getValue('sef') ){ ?>
<div class="ctitle"><h2><?php echo JText::_('CC YOUR PROFILE URL'); ?></h2></div>
<div class="cRow" style="padding: 5px 0 0;">
	<?php echo JText::sprintf('CC YOUR CURRENT PROFILE URL' , $prefixURL );?>
	<input id="alias" name="alias" class="inputbox" type="alias" value="<?php echo $user->getAlias();?>" />
</div>
<?php }?>
<div class="ctitle"><h2><?php echo JText::_('CC EDIT PREFERENCES'); ?></h2></div>
<table class="formtable" cellspacing="1" cellpadding="0">
<tr>
	<td class="key" style="width: 300px;">
		<label for="activityLimit" class="label title">
			<?php echo JText::_('CC PREFERENCES ACTIVITY LIMIT'); ?>
		</label>
	</td>
	<td class="value">
            <input type="text" id="activityLimit" name="activityLimit" value="<?php echo $params->get('activityLimit', 20 );?>" size="5" maxlength="3" />
	</td>
</tr>
</table>
<?php echo $beforeFormDisplay;?>
<div style="text-align: center;"><input type="submit" class="button" value="<?php echo JText::_('CC BUTTON SAVE'); ?>" /></div>
</form>