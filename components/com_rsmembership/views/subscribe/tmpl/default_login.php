<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<p><?php echo JText::_('RSM_SUBSCRIBE_PLEASE_LOGIN'); ?></p>
<form method="post" action="<?php echo JRoute::_('index.php'); ?>">
<fieldset class="input">
	<p id="com-form-login-username">
		<label for="username"><?php echo JText::_('Username') ?></label><br />
		<input name="username" id="username" type="text" class="inputbox" alt="username" size="18" />
	</p>
	<p id="com-form-login-password">
		<label for="passwd"><?php echo JText::_('Password') ?></label><br />
		<input type="password" id="passwd" name="passwd" class="inputbox" size="18" alt="password" />
	</p>
	<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
	<p id="com-form-login-remember">
		<label for="remember"><?php echo JText::_('Remember me') ?></label>
		<input type="checkbox" id="remember" name="remember" class="inputbox" value="yes" alt="Remember Me" />
	</p>
	<?php endif; ?>
	<input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" />
	
	<?php echo $this->token; ?>
	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="login" />
	<input type="hidden" name="return" value="<?php echo $this->return; ?>" />
</fieldset>
</form>

<p><?php echo JText::_('RSM_SUBSCRIBE_PLEASE_ELSE'); ?></p>