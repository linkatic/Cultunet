<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
?> 

<script type="text/javascript">
function validate_subscribe()
{
	var form = document.membershipForm;
	var msg = new Array();
	
	<?php if (!empty($this->membershipterms)) { ?>
	if (!document.getElementById('rsm_checkbox_agree').checked)
		msg.push("<?php echo JText::_('RSM_PLEASE_AGREE_MEMBERSHIP', true); ?>");
	<?php } ?>
	
	<?php if ($this->choose_username && !$this->logged) { ?>
	if (!validate_username(form.username.value))
		msg.push("<?php echo JText::_('RSM_PLEASE_TYPE_USERNAME', true); ?>");
	
	if (document.getElementById('rsm_username_message').className == 'rsm_error')
		msg.push("<?php echo JText::_('RSM_USERNAME_NOT_OK', true); ?>");
	<?php } ?>
	
	<?php if (!$this->logged) { ?>
	if (form.name.value.length == 0)
		msg.push("<?php echo JText::_('RSM_PLEASE_TYPE_NAME', true); ?>");
	<?php } ?>
	
	<?php if (!$this->logged) { ?>
	regex=/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
	if (form.email.value.length == 0 || !regex.test(form.email.value))
		msg.push("<?php echo JText::_('RSM_PLEASE_TYPE_EMAIL', true); ?>");
	<?php } ?>
	
	<?php foreach ($this->fields_validation as $validation) { ?>
		<?php echo $validation; ?>
	<?php } ?>
	
	if (msg.length > 0)
	{
		alert(msg.join("\n"));
		return false;
	}
	
	return true;
}

function validate_username(username)
{
	if (username.length < 2 || /\W/.test(username))
		return false;
	
	return true;
}

var rsm_wait_ajax = false;
var rsm_timeout = false;

function rsm_ajax_flag()
{
	if (rsm_timeout)
		clearTimeout(rsm_timeout);
	rsm_wait_ajax = true;
	rsm_timeout = setTimeout(function () { rsm_wait_ajax = false; rsm_check_username(document.getElementById('rsm_username')); } , 2000);
}

function rsm_check_username(what)
{
	what.value = what.value.replace(/[^a-zA-Z_0-9\.]+/g, '');
	username = what.value;
	
	var message = document.getElementById('rsm_username_message');
	
	if (!validate_username(username))
	{
		message.style.display = '';
		message.className = 'rsm_error';
		message.innerHTML = "<?php echo $this->escape(JText::_('RSM_PLEASE_TYPE_USERNAME', true)); ?>";
		return false;
	}
	
	document.getElementById('rsm_loading').style.display = '';
	
	message.style.display = 'none';
	message.className = '';
	message.innerHTML = '';
	
	if (rsm_wait_ajax)
		return true;
	
	xmlHttp = rsm_get_xml_http_object();
	var url = 'index.php?option=com_rsmembership&task=checkusername';
	
	params  = 'username=' + document.getElementById('rsm_username').value;
	params += '&name=' + document.getElementById('name').value;
	params += '&email=' + document.getElementById('email').value;
	xmlHttp.open("POST", url, true);
	
	//Send the proper header information along with the request
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.setRequestHeader("Content-length", params.length);
	xmlHttp.setRequestHeader("Connection", "close");

	xmlHttp.send(params);
	
	xmlHttp.onreadystatechange = function() {
		if (xmlHttp.readyState == 4 && xmlHttp.responseText.indexOf('|') > -1)
		{
			document.getElementById('rsm_loading').style.display = 'none';
			rsm_wait_ajax = false;
			
			var ol = document.getElementById('rsm_suggestions_ol');
			for (var i=ol.childNodes.length - 1; i>=0; i--)
				ol.removeChild(ol.childNodes[i]);
			
			is_available = false;
			var suggestions = xmlHttp.responseText.split('|');
			for (var i=0; i<suggestions.length; i++)
			{
				if (suggestions[i] == what.value)
					is_available = true;
				
				var a = document.createElement('a');
				a.innerHTML = suggestions[i];
				a.href = 'javascript: void(0);';
				a.onclick = function () { rsm_add_username(this.innerHTML); };
				
				var li = document.createElement('li');
				li.appendChild(a);
				
				ol.appendChild(li);
			}
			
			message.style.display = '';
			
			var suggestions = document.getElementById('rsm_suggestions');
			suggestions.style.display = '';
			suggestions.style.opacity = '1';
			suggestions.style.filter = 'alpha(opacity = 100)';
			suggestions.FadeState = 2;
			
			if (is_available)
			{
				suggestions.style.display = 'none';
				message.className = 'rsm_ok';
				message.innerHTML = "<?php echo $this->escape(JText::_('RSM_USERNAME_IS_OK', true)); ?>";
			}
			else
			{			
				message.className = 'rsm_error';
				message.innerHTML = "<?php echo $this->escape(JText::_('RSM_USERNAME_NOT_OK', true)); ?>";
			}
		}
	}
}

function rsm_add_username(username)
{
	var message = document.getElementById('rsm_username_message');
	
	document.getElementById('rsm_username').value = username;
	rsm_fade('rsm_suggestions');
	
	message.style.display = '';
	message.className = 'rsm_ok';
	message.innerHTML = "<?php echo $this->escape(JText::_('RSM_USERNAME_IS_OK', true)); ?>";
}

function rsm_refresh_captcha()
{
	document.getElementById('submit_captcha_image').src = '<?php echo JURI::root(); ?>index.php?option=com_rsmembership&task=captcha&sid=' + Math.random();
	return false;
}
</script>

<?php if ($this->params->get('show_page_title', 1)) { ?>
<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php } ?>

<?php if (!$this->logged && $this->show_login) { ?>
	<?php echo $this->loadTemplate('login'); ?>
<?php } ?>

<form method="post" action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=validatesubscribe'); ?>" name="membershipForm" onsubmit="return validate_subscribe();">
<fieldset class="input">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
<?php if ($this->choose_username) { ?>
<tr>
	<td width="30%" height="40" valign="middle"><label for="rsm_username"><?php echo JText::_('RSM_USERNAME'); ?>:</label></td>
	<?php if (!$this->logged) { ?>
  	<td>
		<input type="text" name="username" id="rsm_username" size="40" value="<?php echo !empty($this->data->username) ? $this->escape($this->data->username) : ''; ?>" onkeyup="return rsm_check_username(this)" onkeydown="rsm_ajax_flag()" maxlength="50" /><?php echo JText::_('RSM_REQUIRED'); ?> <?php echo JHTML::image('components/com_rsmembership/assets/images/load.gif', 'Loading', 'id="rsm_loading" style="display: none;"'); ?>
		<span class="rsm_clear"></span>
		<div id="rsm_username_message" style="display: none"></div>
		<span class="rsm_clear"></span>
		<div id="rsm_suggestions" style="display: none">
			<p><strong><?php echo JText::_('RSM_HERE_ARE_SOME_USERNAME_SUGGESTIONS'); ?></strong><br /><a href="javascript: void(0);" onclick="rsm_check_username(document.getElementById('rsm_username'))"><strong><?php echo JText::_('RSM_CHECK_OTHER_SUGGESTIONS'); ?></strong></a></p>
			<ol id="rsm_suggestions_ol"></ol>
		</div>
	</td>
	<?php } else { ?>
	<td><?php echo $this->escape($this->user->get('username')); ?></td>
	<?php } ?>
</tr>
<?php } ?>
<tr>
	<td width="30%" height="40"><label for="name"><?php echo JText::_('Name'); ?>:</label></td>
	<?php if (!$this->logged) { ?>
  	<td><input type="text" name="name" id="name" size="40" value="<?php echo !empty($this->data->name) ? $this->escape($this->data->name) : ''; ?>" maxlength="50" /><?php echo JText::_('RSM_REQUIRED'); ?></td>
	<?php } else { ?>
	<td><?php echo $this->escape($this->user->get('name')); ?></td>
	<?php } ?>
</tr>
<tr>
	<td height="40"><label for="email"><?php echo JText::_( 'Email' ); ?>:</label></td>
	<?php if (!$this->logged) { ?>
	<td><input type="text" id="email" name="email" size="40" value="<?php echo !empty($this->data->email) ? $this->escape($this->data->email) : ''; ?>" maxlength="100" /><?php echo JText::_('RSM_REQUIRED'); ?></td>
	<?php } else { ?>
	<td><?php echo $this->escape($this->user->get('email')); ?></td>
	<?php } ?>
</tr>
<?php foreach ($this->fields as $field) { ?>
<tr>
	<td height="40"><?php echo $field[0]; ?></td>
	<td><?php echo $field[1]; ?></td>
</tr>
<?php } ?>
<?php if (!empty($this->membershipterms)) { ?>
<tr>
	<td width="30%" height="40">&nbsp;</td>
  	<td><input type="checkbox" id="rsm_checkbox_agree" /> <a class="modal" rel="{handler: 'iframe', size: {x: 660, y: 475}}" href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=terms&cid='.$this->membershipterms->id.':'.JFilterOutput::stringURLSafe($this->membershipterms->name).'&tmpl=component'); ?>" target="_blank"><?php echo JText::_('RSM_I_AGREE'); ?> (<?php echo $this->membershipterms->name; ?>)</a></td>
</tr>
<?php } ?>
<?php if ($this->use_captcha) { ?>
	<tr>
		<td><label for="submit_captcha"><span class="hasTip" title="<?php echo JText::_('RSM_CAPTCHA_DESC'); ?>"><?php echo JText::_('RSM_CAPTCHA'); ?></span></label></td>
		<td>
			<?php if ($this->use_builtin) { ?>
			<img src="<?php echo JURI::root(true); ?>/index.php?option=com_rsmembership&amp;task=captcha" id="submit_captcha_image" alt="Antispam" /><span class="hasTip" title="<?php echo JText::_('RSM_REFRESH_CAPTCHA_DESC'); ?>"><a style="border-style: none" href="javascript: void(0)" onclick="return rsm_refresh_captcha();"><img src="<?php echo JURI::root(true); ?>/components/com_rsmembership/assets/images/refresh.gif" alt="<?php echo JText::_('RSM_REFRESH_CAPTCHA'); ?>" border="0" onclick="this.blur()" align="top" /></a></span><br /><input type="text" name="captcha" id="submit_captcha" size="40" value="" class="inputbox" />
			<?php } elseif ($this->use_recaptcha) { ?>
				<?php echo $this->show_recaptcha; ?>
			<?php } ?>
		</td>
	</tr>
<?php } ?>
</table>

<?php if ($this->membership->use_coupon && strlen($this->membership->coupon) > 0) { ?>
</fieldset>
<fieldset class="input">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
<tr>
	<td colspan="2"><?php echo JText::_('RSM_COUPON_DESC'); ?></td>
</tr>
<tr>
	<td width="30%" height="40"><label for="coupon"><?php echo JText::_('RSM_COUPON'); ?>:</label></td>
	<td><input type="text" name="coupon" id="coupon" size="40" value="" maxlength="64" /></td>
</tr>
</table>
<?php } ?>

<input type="submit" class="button" value="<?php echo JText::_('RSM_NEXT'); ?>" name="Submit" />
</fieldset>

<?php echo $this->token; ?>
<input type="hidden" name="option" value="com_rsmembership" />
<input type="hidden" name="view" value="subscribe" />
<input type="hidden" name="task" value="validatesubscribe" />
<input type="hidden" name="cid" value="<?php echo $this->membership->id; ?>" />
</form>