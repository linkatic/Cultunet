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
function validate_upgrade()
{
	var form = document.membershipForm;
	var msg = new Array();
	
	if (!document.getElementById('rsm_checkbox_agree').checked)
		msg.push("<?php echo JText::_('RSM_PLEASE_AGREE_MEMBERSHIP', true); ?>");
	
	if (msg.length > 0)
	{
		alert(msg.join("\n"));
		return false;
	}
	
	return true;
}
</script>

<?php if ($this->params->get('show_page_title', 1)) { ?>
<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php } ?>

<form method="post" action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=upgradepaymentredirect'); ?>" name="membershipForm" onsubmit="return validate_upgrade();">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
<tr>
	<td width="30%" height="40"><?php echo JText::_('RSM_UPGRADE'); ?>:</td>
	<td><?php echo $this->upgrade->fromname; ?> <?php echo JText::_('to'); ?> <?php echo $this->upgrade->toname; ?></td>
</tr>
<tr>
	<td colspan="2"><hr /></td>
</tr>
<tr>
	<td width="30%" height="40"><?php echo JText::_('RSM_TOTAL_COST'); ?>:</td>
	<td><?php echo $this->total; ?></td>
</tr>
<tr>
	<td width="30%" height="40"><?php echo JText::_('Name'); ?>:</td>
	<td><?php echo $this->escape($this->user->get('name')); ?></td>
</tr>
<tr>
	<td height="40"><?php echo JText::_( 'Email' ); ?>:</td>
	<td><?php echo $this->escape($this->user->get('email')); ?></td>
</tr>
<?php foreach ($this->fields as $field) { ?>
<tr>
	<td height="40"><?php echo $field[0]; ?></td>
	<td><?php echo $field[1]; ?></td>
</tr>
<?php } ?>
<tr>
	<td width="30%" height="40"><?php echo JText::_('RSM_PAY_WITH'); ?>:</td>
	<td>
	<?php
	$i = 0;
	if (!empty($this->payments))
		foreach ($this->payments as $plugin => $paymentname) { $i++; ?>
		<p><input <?php echo $i == 1 ? 'checked="checked"' : ''; ?> type="radio" name="payment" value="<?php echo $this->escape($plugin); ?>" id="payment<?php echo $i; ?>" /> <label for="payment<?php echo $i; ?>"><?php echo $this->escape($paymentname); ?></label></p>
	<?php } ?>
	</td>
</tr>
<?php if (!empty($this->membershipterms)) { ?>
<tr>
	<td width="30%" height="40">&nbsp;</td>
	<td><input type="checkbox" id="rsm_checkbox_agree" /> <a class="modal" rel="{handler: 'iframe', size: {x: 660, y: 475}}" href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=terms&cid='.$this->membershipterms->id.':'.JFilterOutput::stringURLSafe($this->membershipterms->name).'&tmpl=component'); ?>" target="_blank"><?php echo JText::_('RSM_I_AGREE'); ?> (<?php echo $this->membershipterms->name; ?>)</a></td>
</tr>
<?php } ?>
</table>
<input type="button" class="button" value="<?php echo JText::_('RSM_BACK'); ?>" onclick="document.location='<?php echo JRoute::_('index.php?option=com_rsmembership&view=mymembership&cid='.$this->cid); ?>'" name="Cancel" />
<input type="submit" class="button" value="<?php echo JText::_('RSM_UPGRADE'); ?>" name="Submit" />

<?php echo $this->token; ?>
<input type="hidden" name="option" value="com_rsmembership" />
<input type="hidden" name="view" value="upgrade" />
<input type="hidden" name="task" value="upgradepaymentredirect" />
<input type="hidden" name="cid" value="<?php echo $this->cid; ?>" />
</form>