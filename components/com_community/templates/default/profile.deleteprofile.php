<?php
/**
 * @package	JomSocial
 * @subpackage Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
?>
<div class="ctitle"><h2><?php echo JText::_('CC DELETE PROFILE TITLE'); ?></h2></div>

<p><?php echo JText::_('CC DELETE PROFILE DESCRIPTION'); ?></p>
<p><span style="color:red;font-weight:bold"><?php echo JText::_('CC DELETE WARNING'); ?></span></p>

<form method="post" action="<?php echo CRoute::getURI();?>" name="deleteProfile">

<table class="formtable" cellspacing="1" cellpadding="0">
<tr>
	<td class="key"></td>
	<td class="value">
		<input type="submit" class="button" value="<?php echo JText::_('CC YES DELETE MY PROFILE'); ?>" />
		<input type="submit" class="button" value="<?php echo JText::_('CC NO I CHANGED MY MIND'); ?>" onclick="history.back(); return false;" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</td>
</tr>
</table>

</form>