<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
?>
<form action="index.php?option=com_rsmembership&view=configuration" method="post" name="adminForm" id="adminForm">
<?php
echo $this->pane->startPane('configuration-pane');

echo $this->pane->startPanel(JText::_('RSM_GENERAL'), 'general');
?>
<div class="col100">
	<fieldset class="adminform">
		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<span class="hasTip" title="<?php echo JText::_('RSM_LICENSE_CODE_DESC'); ?>">
				<?php echo JText::_('RSM_LICENSE_CODE'); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="global_register_code" id="global_register_code" size="35" value="<?php echo $this->escape($this->config->global_register_code); ?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<span class="hasTip" title="<?php echo JText::_('RSM_DATE_TIME_DESC'); ?>">
				<?php echo JText::_('RSM_DATE_TIME'); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="date_format" id="date_format" size="35" value="<?php echo $this->escape($this->config->date_format); ?>" />
			</td>
		</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>
<?php
echo $this->pane->endPanel();

echo $this->pane->startPanel(JText::_('RSM_PATCHES'), 'configuration-patches');
?>
<div class="col100">
	<p><?php echo JText::_('RSM_PATCHES_DESC'); ?></p>
	<fieldset class="adminform">
		<table class="adminlist">
		<thead>
		<tr>
			<th><?php echo JText::_('TYPE'); ?></th>
			<th><?php echo JText::_('FILE'); ?></th>
			<th><?php echo JText::_('RSM_STATUS'); ?></th>
			<th><?php echo JText::_('RSM_PATCHED'); ?></th>
		</tr>
		</thead>
		<tr>
			<td nowrap="nowrap" width="1%"><?php echo JText::_('RSM_MODULE_PATCH'); ?></td>
			<td nowrap="nowrap" width="1%"><?php echo $this->module_helper; ?></td>
			<td nowrap="nowrap" width="1%"><?php echo $this->module_writable ? '<strong style="color: green">'.JText::_('WRITABLE').'</strong>' : '<strong style="color: red">'.JText::_('UNWRITABLE').'</strong>'; ?></td>
			<td>
			<?php if ($this->module_patched) { ?>
				<strong style="color: green"><?php echo JText::_('RSM_PATCH_APPLIED'); ?></strong>
				<button type="button" onclick="submitbutton('unpatchmodule')" <?php if (!$this->module_writable) { ?>disabled="disabled"<?php } ?>><?php echo JText::_('RSM_REMOVE_PATCH'); ?></button>
			<?php } else { ?>
				<strong style="color: red"><?php echo JText::_('RSM_PATCH_NOT_APPLIED'); ?></strong>
				<button type="button" onclick="submitbutton('patchmodule')" <?php if (!$this->module_writable) { ?>disabled="disabled"<?php } ?>><?php echo JText::_('RSM_APPLY_PATCH'); ?></button>
			<?php } ?>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('RSM_MENU_PATCH'); ?></td>
			<td><?php echo $this->menu_helper; ?></td>
			<td nowrap="nowrap" width="1%"><?php echo $this->menu_writable ? '<strong style="color: green">'.JText::_('WRITABLE').'</strong>' : '<strong style="color: red">'.JText::_('UNWRITABLE').'</strong>'; ?></td>
			<td>
			<?php if ($this->menu_patched) { ?>
				<strong style="color: green"><?php echo JText::_('RSM_PATCH_APPLIED'); ?></strong>
				<button type="button" onclick="submitbutton('unpatchmenu')" <?php if (!$this->menu_writable) { ?>disabled="disabled"<?php } ?>><?php echo JText::_('RSM_REMOVE_PATCH'); ?></button>
			<?php } else { ?>
				<strong style="color: red"><?php echo JText::_('RSM_PATCH_NOT_APPLIED'); ?></strong>
				<button type="button" onclick="submitbutton('patchmenu')" <?php if (!$this->menu_writable) { ?>disabled="disabled"<?php } ?>><?php echo JText::_('RSM_APPLY_PATCH'); ?></button>
			<?php } ?>
			</td>
		</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>
<?php
echo $this->pane->endPanel();

echo $this->pane->startPanel(JText::_('RSM_EXPIRE_NOTIFICATION_SETTINGS'), 'expiration');
?>
<div class="col100">
	<fieldset class="adminform">
		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<span class="hasTip" title="<?php echo JText::_('RSM_EXPIRE_EMAILS_DESC'); ?>">
				<?php echo JText::_('RSM_EXPIRE_EMAILS'); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="expire_emails" id="expire_emails" size="35" value="<?php echo $this->escape($this->config->expire_emails); ?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<span class="hasTip" title="<?php echo JText::_('RSM_EXPIRE_CHECK_IN_DESC'); ?>">
				<?php echo JText::_('RSM_EXPIRE_CHECK_IN'); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="expire_check_in" id="expire_check_in" size="35" value="<?php echo $this->escape($this->config->expire_check_in); ?>" />
				<?php echo JText::_('MINUTES'); ?>
			</td>
		</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>
<?php
echo $this->pane->endPanel();

echo $this->pane->startPanel(JText::_('RSM_SUBSCRIBER_FIELDS'), 'subscriber-fields');
?>
<div class="col100">
	<fieldset class="adminform">
		<table class="admintable">
		<tr>
			<td><?php echo JText::sprintf('RSM_FIELDS_MOVED', '<a href="'.JRoute::_('index.php?option=com_rsmembership&view=fields').'">'.JText::_('RSM_FIELDS').'</a>'); ?></td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>
<?php
echo $this->pane->endPanel();

echo $this->pane->startPanel(JText::_('RSM_SUBSCRIBING'), 'configuration-subscribing');
?>
<div class="col100">
	<fieldset class="adminform">
		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_SHOW_LOGIN_DESC'); ?>"><?php echo JText::_('RSM_SHOW_LOGIN'); ?></span></td>
			<td><?php echo $this->lists['show_login']; ?></td>
		</tr>
		<tr>
			<td width="100" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_CREATE_USER_WHEN_DESC'); ?>"><?php echo JText::_('RSM_CREATE_USER_WHEN'); ?></span></td>
			<td><?php echo $this->lists['create_user_instantly']; ?></td>
		</tr>
		<tr>
			<td width="100" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_CHOOSE_USERNAME_DESC'); ?>"><?php echo JText::_('RSM_CHOOSE_USERNAME'); ?></span></td>
			<td><?php echo $this->lists['choose_username']; ?></td>
		</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>
<?php
echo $this->pane->endPanel();

echo $this->pane->startPanel(JText::_('RSM_OTHER'), 'configuration-misc');
?>
<div class="col100">
	<fieldset class="adminform">
		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_CURRENCY_DESC'); ?>"><?php echo JText::_('RSM_MEMBERSHIP_CURRENCY'); ?></span></td>
			<td><input class="text_area" type="text" name="currency" id="currency" size="35" value="<?php echo $this->escape($this->config->currency); ?>" /></td>
		</tr>
		<tr>
			<td width="100" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_PRICE_FORMAT_DESC'); ?>"><?php echo JText::_('RSM_PRICE_FORMAT'); ?></span></td>
			<td><input class="text_area" type="text" name="price_format" id="price_format" size="35" value="<?php echo $this->escape($this->config->price_format); ?>" /></td>
		</tr>
		<tr>
			<td width="100" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_PRICE_SHOW_FREE_DESC'); ?>"><?php echo JText::_('RSM_PRICE_SHOW_FREE'); ?></span></td>
			<td><?php echo $this->lists['price_show_free']; ?></td>
		</tr>
		<tr>
			<td width="100" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_DELETE_PENDING_AFTER_DESC'); ?>"><?php echo JText::_('RSM_DELETE_PENDING_AFTER'); ?></span></td>
			<td><input class="text_area" type="text" name="delete_pending_after" id="delete_pending_after" size="35" value="<?php echo $this->config->delete_pending_after; ?>" /> <?php echo JText::_('RSM_HOURS'); ?></td>
		</tr>
		<tr>
			<td width="100" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_DISABLE_REGISTRATION_DESC'); ?>"><?php echo JText::_('RSM_DISABLE_REGISTRATION'); ?></span></td>
			<td><?php echo $this->lists['disable_registration']; ?></td>
		</tr>
		<tr>
			<td width="100" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_REGISTRATION_PAGE_DESC'); ?>"><?php echo JText::_('RSM_REGISTRATION_PAGE'); ?></span></td>
			<td><input class="inputbox" type="text" <?php echo $this->config->disable_registration ? '' : 'disabled="disabled"'; ?> name="registration_page" id="registration_page" size="75" value="<?php echo $this->escape($this->config->registration_page); ?>" /></td>
		</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>
<?php
echo $this->pane->endPanel();

echo $this->pane->startPanel(JText::_('RSM_CAPTCHA'), 'captcha');
?>
<div class="col100">
	<fieldset class="adminform">
		<table class="admintable">
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key">
				<span class="hasTip" title="<?php echo JText::_('RSM_CAPTCHA_ENABLE_DESC'); ?>">
				<?php echo JText::_('RSM_CAPTCHA_ENABLE'); ?>
				</span>
			</td>
			<td>
				<?php echo $this->lists['captcha_enabled']; ?>
			</td>
		</tr>
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key">
				<span class="hasTip" title="<?php echo JText::_('RSM_CAPTCHA_ENABLED_FOR_DESC'); ?>">
				<?php echo JText::_('RSM_CAPTCHA_ENABLED_FOR'); ?>
				</span>
			</td>
			<td>
				<?php echo $this->lists['captcha_enabled_for']; ?>
			</td>
		</tr>
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key">
				<span class="hasTip" title="<?php echo JText::_('RSM_CAPTCHA_CHARACTERS_DESC'); ?>">
				<?php echo JText::_('RSM_CAPTCHA_CHARACTERS'); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="captcha_characters" id="captcha_characters" <?php echo $this->config->captcha_enabled != 1 ? ' disabled="disabled"' : ''; ?> size="35" value="<?php echo $this->escape($this->config->captcha_characters); ?>" />
			</td>
		</tr>
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key">
				<span class="hasTip" title="<?php echo JText::_('RSM_CAPTCHA_LINES_DESC'); ?>">
				<?php echo JText::_('RSM_CAPTCHA_LINES'); ?>
				</span>
			</td>
			<td>
				<?php echo $this->lists['captcha_lines']; ?>
			</td>
		</tr>
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key">
				<span class="hasTip" title="<?php echo JText::_('RSM_CAPTCHA_CASE_SENSITIVE_DESC'); ?>">
				<?php echo JText::_('RSM_CAPTCHA_CASE_SENSITIVE'); ?>
				</span>
			</td>
			<td>
				<?php echo $this->lists['captcha_case_sensitive']; ?>
			</td>
		</tr>
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key">
				<span class="hasTip" title="<?php echo JText::_('RSM_RECAPTCHA_PUBLIC_KEY_DESC'); ?>">
				<?php echo JText::_('RSM_RECAPTCHA_PUBLIC_KEY'); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="recaptcha_public_key" id="recaptcha_public_key" <?php echo $this->config->captcha_enabled != 2 ? ' disabled="disabled"' : ''; ?> size="35" value="<?php echo $this->escape($this->config->recaptcha_public_key); ?>" />
			</td>
		</tr>
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key">
				<span class="hasTip" title="<?php echo JText::_('RSM_RECAPTCHA_PRIVATE_KEY_DESC'); ?>">
				<?php echo JText::_('RSM_RECAPTCHA_PRIVATE_KEY'); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="recaptcha_private_key" id="recaptcha_private_key" <?php echo $this->config->captcha_enabled != 2 ? ' disabled="disabled"' : ''; ?> size="35" value="<?php echo $this->escape($this->config->recaptcha_private_key); ?>" />
			</td>
		</tr>
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key">
				<span class="hasTip" title="<?php echo JText::_('RSM_RECAPTCHA_THEME_DESC'); ?>">
				<?php echo JText::_('RSM_RECAPTCHA_THEME'); ?>
				</span>
			</td>
			<td>
				<?php echo $this->lists['recaptcha_theme']; ?>
			</td>
		</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>
<?php
echo $this->pane->endPanel();

echo $this->pane->endPane();
?>

<?php echo JHTML::_('form.token'); ?>
<input type="hidden" name="option" value="com_rsmembership" />
<input type="hidden" name="view" value="rsmembership" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="configuration" />
<input type="hidden" name="tabposition" id="tabposition" value="0" />
</form>

<script type="text/javascript">
function rsm_enable_registration(what)
{
	if (what == 1)
		$('registration_page').disabled = false;
	else
		$('registration_page').disabled = true;
}

function rsm_captcha_enable(what)
{
	$('captcha_enabled_for0').disabled = true;
	$('captcha_enabled_for1').disabled = true;
	$('captcha_characters').disabled = true;
	$('captcha_lines0').disabled = true;
	$('captcha_lines1').disabled = true;
	$('captcha_case_sensitive0').disabled = true;
	$('captcha_case_sensitive1').disabled = true;
		
	$('recaptcha_public_key').disabled = true;
	$('recaptcha_private_key').disabled = true;
	$('recaptcha_theme').disabled = true;
		
	if (what == 1)
	{
		$('captcha_enabled_for0').disabled = false;
		$('captcha_enabled_for1').disabled = false;
		
		$('captcha_characters').disabled = false;
		$('captcha_lines0').disabled = false;
		$('captcha_lines1').disabled = false;
		$('captcha_case_sensitive0').disabled = false;
		$('captcha_case_sensitive1').disabled = false;
	}
	else if (what == 2)
	{
		$('captcha_enabled_for0').disabled = false;
		$('captcha_enabled_for1').disabled = false;
		
		$('recaptcha_public_key').disabled = false;
		$('recaptcha_private_key').disabled = false;
		$('recaptcha_theme').disabled = false;
	}
}

function submitbutton(pressbutton)
{
	var form = document.adminForm;
	
	if (pressbutton == 'cancel')
	{
		submitform(pressbutton);
		return;
	}
	
	var dt = $('configuration-pane').getElements('dt');
	for (var i=0; i<dt.length; i++)
	{
		if (dt[i].className == 'open')
			$('tabposition').value = i;
	}
	submitform(pressbutton);
}
</script>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>