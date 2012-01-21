<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
$token = JHTML::_('form.token');
?>

<script type="text/javascript">
function submitbutton(pressbutton)
{
	var form = document.adminForm;
	
	if (pressbutton == 'cancel')
	{
		submitform(pressbutton);
		return;
	}
	
	// do field validation
	if (form.name.value.length == 0)
		alert('<?php echo JText::_('RSM_MEMBERSHIP_NAME_ERROR', true); ?>');
	else
	{
		var dt = $('membership-pane').getElements('dt');
			
		for (var i=0; i<dt.length; i++)
		{
			if (dt[i].className == 'open')
				$('tabposition').value = i;
		}
		
		submitform(pressbutton);
	}
}

function rsm_enable_coupon(what)
{
	if (what == 1)
	{
		$('coupon').disabled = false;
		$('coupon_price').disabled = false;
	}
	else
	{
		$('coupon').disabled = true;
		$('coupon_price').disabled = true;
	}
}

function rsm_enable_renewal_price(what)
{
	if (what == 1)
		$('renewal_price').disabled = false;
	else
		$('renewal_price').disabled = true;
}

function rsm_enable_trial(what)
{
	if (what == 1)
	{
		$('trial_price').disabled = false;
		$('trial_period').disabled = false;
		$('trial_period_type').disabled = false;
	}
	else
	{
		$('trial_price').disabled = true;
		$('trial_period').disabled = true;
		$('trial_period_type').disabled = true;
	}
}

function rsm_enable_gid(what)
{
	if (what == 1)
	{
		$('gid_subscribe').disabled = false;
		$('gid_expire').disabled = false;
	}
	else
	{
		$('gid_subscribe').disabled = true;
		$('gid_expire').disabled = true;
	}
}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=memberships&task=edit'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php echo $this->tabs->startPane('membership-pane'); ?>

<?php echo $this->tabs->startPanel(JText::_('RSM_MEMBERSHIP_INFO'), 'membership-info'); ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_DESC'); ?>"><label for="name"><?php echo JText::_('RSM_MEMBERSHIP'); ?></label></span></td>
			<td><input type="text" name="name" value="<?php echo $this->escape($this->row->name); ?>" id="name" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('Category'); ?>"><label for="name"><?php echo JText::_('Category'); ?></label></span></td>
			<td><?php echo $this->lists['categories']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_SKU_DESC'); ?>"><label for="sku"><?php echo JText::_('RSM_MEMBERSHIP_SKU'); ?></label></span></td>
			<td><input type="text" name="sku" value="<?php echo $this->escape($this->row->sku); ?>" id="sku" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_TERM_DESC'); ?>"><label for="term_id"><?php echo JText::_('RSM_TERM'); ?></label></span></td>
			<td><?php echo $this->lists['terms']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_THUMB_DESC'); ?>"><label for="thumb"><?php echo JText::_('RSM_MEMBERSHIP_THUMB'); ?></label></span></td>
			<td>
			<?php if (!empty($this->row->thumb)) { ?>
			<p><?php echo JHTML::_('image', JURI::root().'components/com_rsmembership/assets/thumbs/'.$this->row->thumb, ''); ?></p>
			<p><input type="checkbox" value="1" name="thumb_delete" /> <?php echo JText::_('RSM_DELETE_THUMB'); ?></p>
			<?php } ?>
			<input type="file" name="thumb" value="" id="thumb" size="100" />
			</td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_THUMB_WIDTH_DESC'); ?>"><label for="thumb_w"><?php echo JText::_('RSM_MEMBERSHIP_THUMB_WIDTH'); ?></label></span></td>
			<td>
			<input type="checkbox" value="1" name="thumb_resize" /> <?php echo JText::_('RSM_RESIZE_TO'); ?>
			<input type="text" name="thumb_w" value="<?php echo $this->row->thumb_w; ?>" id="thumb_w" size="10" maxlength="255" /> <?php echo JText::_('RSM_MEMBERSHIP_PX'); ?></td>
		</tr>
		<tr>
			<td width="200" valign="top"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_DESCRIPTION_DESC'); ?>"><label for="description"><?php echo JText::_('RSM_MEMBERSHIP_DESCRIPTION'); ?></label></span>
			<br /><br />
			<?php echo JText::_('RSM_MEMBERSHIP_DESCRIPTION_PLACEHOLDERS'); ?>
			<span class="rsmembership_<?php echo strpos($this->row->description, '{extras}') !== false ? 'green' : 'red'; ?>">{extras}</span> - <?php echo JText::_('RSM_PLACEHOLDER_EXTRAS'); ?><br />
			<span class="rsmembership_<?php echo strpos($this->row->description, '{buy}') !== false ? 'green' : 'red'; ?>">{buy}</span> - <?php echo JText::_('RSM_PLACEHOLDER_BUY'); ?><br />
			<span class="rsmembership_<?php echo strpos($this->row->description, '{price}') !== false ? 'green' : 'red'; ?>">{price}</span> - <?php echo JText::_('RSM_PLACEHOLDER_PRICE'); ?><br />
			</td>
			<td><?php echo $this->editor->display('description',$this->row->description,500,250,70,10); ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('PUBLISHED_DESC'); ?>"><label for="published"><?php echo JText::_('PUBLISHED'); ?></label></span></td>
			<td><?php echo $this->lists['published']; ?></td>
		</tr>
	</table>
	<fieldset>
	<legend><?php echo JText::_('RSM_MEMBERSHIP_ONE_TIME_PRICE_SETTINGS'); ?></legend>
	<p><?php echo JText::_('RSM_MEMBERSHIP_ONE_TIME_PRICE_SETTINGS_DESC'); ?></p>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_PRICE_DESC'); ?>"><label for="price"><?php echo JText::_('RSM_MEMBERSHIP_PRICE'); ?></label></span></td>
			<td><input type="text" name="price" value="<?php echo $this->row->price; ?>" id="price" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_USE_RENEWAL_PRICE_DESC'); ?>"><label for="use_renewal_price"><?php echo JText::_('RSM_MEMBERSHIP_USE_RENEWAL_PRICE'); ?></label></span></td>
			<td><?php echo $this->lists['use_renewal_price']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_RENEWAL_PRICE_DESC'); ?>"><label for="renewal_price"><?php echo JText::_('RSM_MEMBERSHIP_RENEWAL_PRICE'); ?></label></span></td>
			<td><input type="text" name="renewal_price" value="<?php echo $this->row->renewal_price; ?>" <?php echo !$this->row->use_renewal_price ? 'disabled="disabled"' : ''; ?> id="renewal_price" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_USE_COUPON_DESC'); ?>"><label for="use_coupon"><?php echo JText::_('RSM_MEMBERSHIP_USE_COUPON'); ?></label></span></td>
			<td><?php echo $this->lists['use_coupon']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_COUPON_DESC'); ?>"><label for="coupon"><?php echo JText::_('RSM_MEMBERSHIP_COUPON'); ?></label></span></td>
			<td><input type="text" name="coupon" value="<?php echo $this->escape($this->row->coupon); ?>" id="coupon" <?php echo !$this->row->use_coupon ? 'disabled="disabled"' : ''; ?> maxlength="64" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_COUPON_PRICE_DESC'); ?>"><label for="coupon_price"><?php echo JText::_('RSM_MEMBERSHIP_COUPON_PRICE'); ?></label></span></td>
			<td><input type="text" name="coupon_price" value="<?php echo $this->row->coupon_price; ?>" id="coupon_price" <?php echo !$this->row->use_coupon ? 'disabled="disabled"' : ''; ?> maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_USE_TRIAL_PERIOD_DESC'); ?>"><label for="use_trial_period"><?php echo JText::_('RSM_MEMBERSHIP_USE_TRIAL_PERIOD'); ?></label></span></td>
			<td><?php echo $this->lists['use_trial_period']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_TRIAL_PRICE_DESC'); ?>"><label for="trial_price"><?php echo JText::_('RSM_MEMBERSHIP_TRIAL_PRICE'); ?></label></span></td>
			<td><input type="text" name="trial_price" value="<?php echo $this->row->trial_price; ?>" id="trial_price" <?php echo !$this->row->use_trial_period ? 'disabled="disabled"' : ''; ?> maxlength="255" /></td>
		</tr>
	</table>
	</fieldset>
	
	<fieldset>
	<legend><?php echo JText::_('RSM_MEMBERSHIP_RECURRING_PRICE_SETTINGS'); ?></legend>
	<p><?php echo JText::_('RSM_MEMBERSHIP_RECURRING_PRICE_SETTINGS_DESC'); ?></p>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_RECURRING_DESC'); ?>"><label for="recurring"><?php echo JText::_('RSM_MEMBERSHIP_RECURRING'); ?></label></span></td>
			<td><?php echo $this->lists['recurring']; ?></td>
		</tr>
	</table>
	</fieldset>
	
	<fieldset>
	<legend><?php echo JText::_('RSM_MEMBERSHIP_LENGTH_SETTINGS'); ?></legend>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_PERIOD_DESC'); ?>"><label for="period"><?php echo JText::_('RSM_MEMBERSHIP_PERIOD'); ?></label></span></td>
			<td><input type="text" name="period" value="<?php echo $this->row->period; ?>" id="period" maxlength="255" /> <?php echo $this->lists['period_type']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_TRIAL_PERIOD_DESC'); ?>"><label for="trial_period"><?php echo JText::_('RSM_MEMBERSHIP_TRIAL_PERIOD'); ?></label></span></td>
			<td><input type="text" name="trial_period" value="<?php echo $this->row->trial_period; ?>" <?php echo !$this->row->use_trial_period ? 'disabled="disabled"' : ''; ?> id="trial_period" maxlength="255" /> <?php echo $this->lists['trial_period_type']; ?></td>
		</tr>
	</table>
	</fieldset>
	
	<fieldset>
	<legend><?php echo JText::_('RSM_MEMBERSHIP_TRIAL_SETTINGS'); ?></legend>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_UNIQUE_DESC'); ?>"><label for="unique"><?php echo JText::_('RSM_MEMBERSHIP_UNIQUE'); ?></label></span></td>
			<td><?php echo $this->lists['unique']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_NO_RENEW_DESC'); ?>"><label for="no_renew"><?php echo JText::_('RSM_MEMBERSHIP_NO_RENEW'); ?></label></span></td>
			<td><?php echo $this->lists['no_renew']; ?></td>
		</tr>
	</table>
	</fieldset>
<?php echo $this->tabs->endPanel(); ?>

<?php echo $this->tabs->startPanel(JText::_('RSM_MEMBERSHIP_STOCK_ACTIVATION'), 'membership-stock-activation'); ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_STOCK_DESC'); ?>"><label for="stock"><?php echo JText::_('RSM_MEMBERSHIP_STOCK'); ?></label></span></td>
			<td><input type="text" name="stock" value="<?php echo $this->row->stock; ?>" id="stock" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_ACTIVATION_DESC'); ?>"><label for="activation"><?php echo JText::_('RSM_MEMBERSHIP_ACTIVATION'); ?></label></span></td>
			<td><?php echo $this->lists['activation']; ?></td>
		</tr>
		<tr>
			<td colspan="2"><strong class="rsmembership_critical"><?php echo JText::_('RSM_MEMBERSHIP_USER_TYPE_WARNING'); ?></strong></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_SUBSCRIBE_USER_TYPE_ENABLE_DESC'); ?>"><label for="gid_enable"><?php echo JText::_('RSM_MEMBERSHIP_SUBSCRIBE_USER_TYPE_ENABLE'); ?></label></span></td>
			<td><?php echo $this->lists['gid_enable']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_SUBSCRIBE_USER_TYPE_DESC'); ?>"><label for="gid_subscribe"><?php echo JText::_('RSM_MEMBERSHIP_SUBSCRIBE_USER_TYPE'); ?></label></span></td>
			<td><?php echo $this->lists['gid_subscribe']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_EXPIRE_USER_TYPE_DESC'); ?>"><label for="gid_expire"><?php echo JText::_('RSM_MEMBERSHIP_EXPIRE_USER_TYPE'); ?></label></span></td>
			<td><?php echo $this->lists['gid_expire']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_DISABLE_EXPIRED_ACCOUNT_DESC'); ?>"><label for="disable_expired_account"><?php echo JText::_('RSM_MEMBERSHIP_DISABLE_EXPIRED_ACCOUNT'); ?></label></span></td>
			<td><?php echo $this->lists['disable_expired_account']; ?></td>
		</tr>
	</table>
<?php echo $this->tabs->endPanel(); ?>

<?php echo $this->tabs->startPanel(JText::_('RSM_MEMBERSHIP_EXTRAS'), 'membership-extras'); ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200" valign="top"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_EXTRAS_DESC'); ?>"><label for="extras"><?php echo JText::_('RSM_MEMBERSHIP_EXTRAS'); ?></label></span></td>
			<td>
			<?php if ($this->hasExtra) { ?>
				<?php echo $this->lists['extras']; ?>
			<?php } else { ?>
				<?php echo JText::_('RSM_PLEASE_ADD_EXTRA'); ?>
				<input type="hidden" name="extras[]" value="" />
			<?php } ?>
			</td>
		</tr>
	</table>
<?php echo $this->tabs->endPanel(); ?>

<?php echo $this->tabs->startPanel(JText::_('RSM_SHARED'), 'membership-shared'); ?>
	<?php if (!empty($this->row->id)) { ?>
	<div class="button2-left"><div class="blank"><a class="modal" title="Select the path" rel="{handler: 'iframe', size: {x: 660, y: 475}}" href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=share&membership_id='.$this->row->id.'&tmpl=component'); ?>"><?php echo JText::_('RSM_ADD_CONTENT'); ?></a></div></div>
	<span class="rsmembership_clear" style="margin-bottom: 10px;"></span>
	<div id="addmembershipfolders_ajax">
	<?php $this->display('shared'); ?>
	</div>
	<?php } else { ?>
	<?php echo JText::_('RSM_SHARED_SAVE_FIRST'); ?>
	<?php } ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
	<tr>
		<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_SHARE_REDIRECT_DESC'); ?>"><label for="share_redirect"><?php echo JText::_('RSM_MEMBERSHIP_SHARE_REDIRECT'); ?></label></span></td>
		<td><input type="text" name="share_redirect" value="<?php echo $this->escape($this->row->share_redirect); ?>" id="share_redirect" size="120" maxlength="255" /></td>
	</tr>
	</table>
<?php echo $this->tabs->endPanel(); ?>

<?php echo $this->tabs->startPanel(JText::_('RSM_MEMBERSHIP_MESSAGES'), 'membership-messages'); ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_ACTION_DESC'); ?>"><label for="action"><?php echo JText::_('RSM_MEMBERSHIP_ACTION'); ?></label></span></td>
			<td><?php echo $this->lists['action']; ?></td>
		</tr>
		<tr>
			<td width="200" valign="top"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_THANKYOU_DESC'); ?>"><label for="thankyou"><?php echo JText::_('RSM_MEMBERSHIP_THANKYOU'); ?></label></span></td>
			<td><?php echo $this->editor->display('thankyou',$this->row->thankyou,500,250,70,10); ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_REDIRECT_DESC'); ?>"><label for="redirect"><?php echo JText::_('RSM_MEMBERSHIP_REDIRECT'); ?></label></span></td>
			<td><input type="text" name="redirect" value="<?php echo $this->escape($this->row->redirect); ?>" id="redirect" size="120" maxlength="255" /></td>
		</tr>
	</table>
<?php echo $this->tabs->endPanel(); ?>

<?php echo $this->tabs->startPanel(JText::_('RSM_MEMBERSHIP_EMAIL_SETTINGS'), 'membership-email-settings'); ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_EMAIL_MODE_DESC'); ?>"><label for="user_email_mode"><?php echo JText::_('RSM_EMAIL_MODE'); ?></label></span></td>
			<td><?php echo $this->lists['user_email_mode']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_FROM_DESC'); ?>"><label for="user_email_from"><?php echo JText::_('RSM_MEMBERSHIP_FROM'); ?></label></span></td>
			<td><input type="text" name="user_email_from" value="<?php echo $this->escape($this->row->user_email_from); ?>" id="user_email_from" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_FROM_ADDR_DESC'); ?>"><label for="user_email_from_addr"><?php echo JText::_('RSM_MEMBERSHIP_FROM_ADDR'); ?></label></span></td>
			<td><input type="text" name="user_email_from_addr" value="<?php echo $this->escape($this->row->user_email_from_addr); ?>" id="user_email_from_addr" size="120" maxlength="255" /></td>
		</tr>
	</table>
<?php echo $this->tabs->endPanel(); ?>

<?php echo $this->tabs->startPanel(JText::_('RSM_MEMBERSHIP_USER_EMAIL'), 'membership-user-email'); ?>
	<?php echo $this->pane->startPane('email-pane'); ?>
	<?php echo $this->pane->startPanel(JText::_('RSM_MEMBERSHIP_USER_EMAIL_NEW'), 'membership-user-email-new'); ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td colspan="2"><strong><?php echo JText::_('RSM_MEMBERSHIP_USER_EMAIL_NEW_DESC'); ?></strong></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_SUBJECT_DESC'); ?>"><label for="user_email_new_subject"><?php echo JText::_('RSM_MEMBERSHIP_SUBJECT'); ?></label></span></td>
			<td><input type="text" name="user_email_new_subject" value="<?php echo $this->escape($this->row->user_email_new_subject); ?>" id="user_email_new_subject" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200" valign="top"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_MESSAGE_DESC'); ?>"><label for="user_email_new_text"><?php echo JText::_('RSM_MEMBERSHIP_MESSAGE'); ?></label></span></td>
			<td><?php echo $this->editor->display('user_email_new_text',$this->row->user_email_new_text,500,250,70,10); ?></td>
		</tr>
		<tr>
			<td colspan="2"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_ATTACHMENTS_DESC'); ?>"><label for="user_email_file_id"><?php echo JText::_('RSM_MEMBERSHIP_ATTACHMENTS'); ?></label></span>
			<span class="rsmembership_clear" style="margin-bottom: 10px;"></span>			
			<?php if (!empty($this->row->id)) { ?>
			<div class="button2-left"><div class="blank"><a class="modal" title="Select the path" rel="{handler: 'iframe', size: {x: 660, y: 475}}" href="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=files&view=files&task=addfile&tmpl=component&membership_id='.$this->row->id.'&function=addsubscriberfiles'); ?>"><?php echo JText::_('RSM_ADD_FILES'); ?></a></div></div>
			<span class="rsmembership_clear" style="margin-bottom: 10px;"></span>
			<div id="addsubscriberfiles_ajax">
			<?php $this->display('files'); ?>
			</div>
			<?php } else { ?>
			<?php echo JText::_('RSM_ATTACHMENT_FILES_SAVE_FIRST'); ?>
			<?php } ?>
			</td>
		</tr>
	</table>
	<?php echo $this->pane->endPanel(); ?>
	
	<?php echo $this->pane->startPanel(JText::_('RSM_MEMBERSHIP_USER_EMAIL_APPROVED'), 'membership-user-email-approved'); ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td colspan="2"><strong><?php echo JText::_('RSM_MEMBERSHIP_USER_EMAIL_APPROVED_DESC'); ?></strong></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_SUBJECT_DESC'); ?>"><label for="user_email_approved_subject"><?php echo JText::_('RSM_MEMBERSHIP_SUBJECT'); ?></label></span></td>
			<td><input type="text" name="user_email_approved_subject" value="<?php echo $this->escape($this->row->user_email_approved_subject); ?>" id="user_email_approved_subject" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200" valign="top"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_MESSAGE_DESC'); ?>"><label for="user_email_approved_text"><?php echo JText::_('RSM_MEMBERSHIP_MESSAGE'); ?></label></span></td>
			<td><?php echo $this->editor->display('user_email_approved_text',$this->row->user_email_approved_text,500,250,70,10); ?></td>
		</tr>
	</table>
	<?php echo $this->pane->endPanel(); ?>
	
	<?php echo $this->pane->startPanel(JText::_('RSM_MEMBERSHIP_USER_EMAIL_RENEW'), 'membership-user-email-renew'); ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td colspan="2"><strong><?php echo JText::_('RSM_MEMBERSHIP_USER_EMAIL_RENEW_DESC'); ?></strong></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_SUBJECT_DESC'); ?>"><label for="user_email_renew_subject"><?php echo JText::_('RSM_MEMBERSHIP_SUBJECT'); ?></label></span></td>
			<td><input type="text" name="user_email_renew_subject" value="<?php echo $this->escape($this->row->user_email_renew_subject); ?>" id="user_email_renew_subject" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200" valign="top"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_MESSAGE_DESC'); ?>"><label for="user_email_renew_text"><?php echo JText::_('RSM_MEMBERSHIP_MESSAGE'); ?></label></span></td>
			<td><?php echo $this->editor->display('user_email_renew_text',$this->row->user_email_renew_text,500,250,70,10); ?></td>
		</tr>
	</table>
	<?php echo $this->pane->endPanel(); ?>
		
	<?php echo $this->pane->startPanel(JText::_('RSM_MEMBERSHIP_USER_EMAIL_UPGRADE'), 'membership-user-email-upgrade'); ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td colspan="2"><strong><?php echo JText::_('RSM_MEMBERSHIP_USER_EMAIL_UPGRADE_DESC'); ?></strong></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_SUBJECT_DESC'); ?>"><label for="user_email_upgrade_subject"><?php echo JText::_('RSM_MEMBERSHIP_SUBJECT'); ?></label></span></td>
			<td><input type="text" name="user_email_upgrade_subject" value="<?php echo $this->escape($this->row->user_email_upgrade_subject); ?>" id="user_email_upgrade_subject" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200" valign="top"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_MESSAGE_DESC'); ?>"><label for="user_email_upgrade_text"><?php echo JText::_('RSM_MEMBERSHIP_MESSAGE'); ?></label></span></td>
			<td><?php echo $this->editor->display('user_email_upgrade_text',$this->row->user_email_upgrade_text,500,250,70,10); ?></td>
		</tr>
	</table>
	<?php echo $this->pane->endPanel(); ?>
	
	<?php echo $this->pane->startPanel(JText::_('RSM_MEMBERSHIP_USER_EMAIL_ADDEXTRA'), 'membership-user-email-addextra'); ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td colspan="2"><strong><?php echo JText::_('RSM_MEMBERSHIP_USER_EMAIL_ADDEXTRA_DESC'); ?></strong></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_SUBJECT_DESC'); ?>"><label for="user_email_addextra_subject"><?php echo JText::_('RSM_MEMBERSHIP_SUBJECT'); ?></label></span></td>
			<td><input type="text" name="user_email_addextra_subject" value="<?php echo $this->escape($this->row->user_email_addextra_subject); ?>" id="user_email_addextra_subject" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200" valign="top"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_MESSAGE_DESC'); ?>"><label for="user_email_addextra_text"><?php echo JText::_('RSM_MEMBERSHIP_MESSAGE'); ?></label></span></td>
			<td><?php echo $this->editor->display('user_email_addextra_text',$this->row->user_email_addextra_text,500,250,70,10); ?></td>
		</tr>
	</table>
	<?php echo $this->pane->endPanel(); ?>
	
	<?php echo $this->pane->startPanel(JText::_('RSM_MEMBERSHIP_USER_EMAIL_EXPIRE'), 'membership-user-email-expire'); ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td colspan="2"><strong><?php echo JText::_('RSM_MEMBERSHIP_USER_EMAIL_EXPIRE_DESC'); ?></strong></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_EXPIRE_NOTIFY_INTERVAL_DESC'); ?>"><label for="expire_notify_interval"><?php echo JText::_('RSM_MEMBERSHIP_EXPIRE_NOTIFY_INTERVAL'); ?></label></span></td>
			<td><input type="text" name="expire_notify_interval" value="<?php echo $this->escape($this->row->expire_notify_interval); ?>" id="expire_notify_interval" size="5" maxlength="255" /> <?php echo JText::_('RSM_DAYS'); ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_SUBJECT_DESC'); ?>"><label for="user_email_expire_subject"><?php echo JText::_('RSM_MEMBERSHIP_SUBJECT'); ?></label></span></td>
			<td><input type="text" name="user_email_expire_subject" value="<?php echo $this->escape($this->row->user_email_expire_subject); ?>" id="user_email_expire_subject" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200" valign="top"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_MESSAGE_DESC'); ?>"><label for="user_email_expire_text"><?php echo JText::_('RSM_MEMBERSHIP_MESSAGE'); ?></label></span></td>
			<td><?php echo $this->editor->display('user_email_expire_text',$this->row->user_email_expire_text,500,250,70,10); ?></td>
		</tr>
	</table>
	<?php echo $this->pane->endPanel(); ?>
	
	<?php echo $this->pane->endPane(); ?>
<?php echo $this->tabs->endPanel(); ?>

<?php echo $this->tabs->startPanel(JText::_('RSM_MEMBERSHIP_ADMIN_EMAIL'), 'membership-admin-email'); ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td colspan="2"><strong><?php echo JText::_('RSM_MEMBERSHIP_ADMIN_EMAIL_DESC'); ?></strong></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_EMAIL_MODE_DESC'); ?>"><label for="admin_email_mode"><?php echo JText::_('RSM_EMAIL_MODE'); ?></label></span></td>
			<td><?php echo $this->lists['admin_email_mode']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_TO_DESC'); ?>"><label for="admin_email_to_addr"><?php echo JText::_('RSM_MEMBERSHIP_TO'); ?></label></span></td>
			<td><input type="text" name="admin_email_to_addr" value="<?php echo $this->escape($this->row->admin_email_to_addr); ?>" id="admin_email_to_addr" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_SUBJECT_DESC'); ?>"><label for="admin_email_subject"><?php echo JText::_('RSM_MEMBERSHIP_SUBJECT'); ?></label></span></td>
			<td><input type="text" name="admin_email_subject" value="<?php echo $this->escape($this->row->admin_email_subject); ?>" id="admin_email_subject" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200" valign="top"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_MESSAGE_DESC'); ?>"><label for="admin_email_text"><?php echo JText::_('RSM_MEMBERSHIP_MESSAGE'); ?></label></span></td>
			<td><?php echo $this->editor->display('admin_email_text',$this->row->admin_email_text,500,250,70,10); ?></td>
		</tr>
	</table>
<?php echo $this->tabs->endPanel(); ?>

<?php echo $this->tabs->startPanel(JText::_('RSM_MEMBERSHIP_ADVANCED_CUSTOMIZATION'), 'membership-advanced'); ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200" valign="top"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_CUSTOM_CODE_DESC'); ?>"><label for="custom_code"><?php echo JText::_('RSM_MEMBERSHIP_CUSTOM_CODE'); ?></label></span></td>
			<td><textarea rows="15" cols="70" name="custom_code" id="custom_code"><?php echo $this->escape($this->row->custom_code); ?></textarea></td>
		</tr>
	</table>
<?php echo $this->tabs->endPanel(); ?>

<?php echo $this->tabs->endPane(); ?>

	<?php echo $token; ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_rsmembership" />
	<input type="hidden" name="view" value="memberships" />
	<input type="hidden" name="controller" value="memberships" />
	<input type="hidden" name="task" value="edit" />

	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	
	<input type="hidden" name="tabposition" value="0" id="tabposition" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>