<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

$total = 0;
$total += $this->membership->price;
?> 

<?php if ($this->params->get('show_page_title', 1)) { ?>
<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php } ?>

<form method="post" action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=paymentredirect'); ?>" name="membershipForm">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
<tr>
	<td width="30%" height="40"><?php echo JText::_('RSM_MEMBERSHIP'); ?>:</td>
	<td><?php echo $this->membership->name; ?> - <?php echo RSMembershipHelper::getPriceFormat($this->membership->price); ?></td>
</tr>
<?php if (strlen($this->data->coupon) > 0) { ?>
<tr>
	<td width="30%" height="40"><?php echo JText::_('RSM_COUPON'); ?>:</td>
	<td><?php echo $this->data->coupon; ?></td>
</tr>
<?php } ?>
<?php if (!empty($this->extras)) foreach ($this->extras as $extra) { $total += $extra->price; ?>
<tr>
	<td width="30%" height="40"><?php echo JText::_('RSM_MEMBERSHIP_EXTRA'); ?>:</td>
	<td><?php echo $extra->name; ?> - <?php echo RSMembershipHelper::getPriceFormat($extra->price); ?></td>
</tr>
<?php } ?>
<tr>
	<td colspan="2"><hr /></td>
</tr>
<tr>
	<td width="30%" height="40"><?php echo JText::_('RSM_TOTAL_COST'); ?>:</td>
	<td><?php echo RSMembershipHelper::getPriceFormat($total); ?></td>
</tr>
<?php if ($this->choose_username) { ?>
<tr>
	<td width="30%" height="40"><?php echo JText::_('USERNAME'); ?>:</td>
	<?php if (!$this->logged) { ?>
  	<td><?php echo $this->escape($this->data->username); ?></td>
	<?php } else { ?>
	<td><?php echo $this->escape($this->user->get('username')); ?></td>
	<?php } ?>
</tr>
<?php } ?>
<tr>
	<td width="30%" height="40"><?php echo JText::_('Name'); ?>:</td>
	<?php if (!$this->logged) { ?>
  	<td><?php echo $this->escape($this->data->name); ?></td>
	<?php } else { ?>
	<td><?php echo $this->escape($this->user->get('name')); ?></td>
	<?php } ?>
</tr>
<tr>
	<td height="40"><?php echo JText::_( 'Email' ); ?>:</td>
	<?php if (!$this->logged) { ?>
	<td><?php echo $this->escape($this->data->email); ?></td>
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
<?php if ($total > 0) { ?>
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
<?php } ?>
</table>
<input type="button" class="button" value="<?php echo JText::_('RSM_BACK'); ?>" onclick="document.location='<?php echo JRoute::_('index.php?option=com_rsmembership&view=subscribe&cid='.$this->membership->id.'&task=back'); ?>'" name="Cancel" />
<input type="submit" class="button" value="<?php echo JText::_('RSM_SUBSCRIBE'); ?>" name="Submit" />

<?php echo $this->token; ?>
<input type="hidden" name="option" value="com_rsmembership" />
<input type="hidden" name="view" value="subscribe" />
<input type="hidden" name="task" value="paymentredirect" />
<input type="hidden" name="cid" value="<?php echo $this->membership->id; ?>" />
</form>