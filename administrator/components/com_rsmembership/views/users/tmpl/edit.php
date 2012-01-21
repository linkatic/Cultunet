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

	var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&]", "i");
	
	// do field validation
	if (trim(form.elements['u[name]'].value) == "")
		alert( "<?php echo JText::_( 'You must provide a name.', true ); ?>" );
	else if (form.elements['u[username]'].value == "")
		alert( "<?php echo JText::_( 'You must provide a user login name.', true ); ?>" );
	else if (r.exec(form.elements['u[username]'].value) || form.elements['u[username]'].value.length < 2)
		alert( "<?php echo JText::_( 'WARNLOGININVALID', true ); ?>" );
	else if (trim(form.elements['u[email]'].value) == "")
		alert( "<?php echo JText::_( 'You must provide an email address.', true ); ?>" );
	else
	{
		var dt = $('user-pane').getElements('dt');
		
		for (var i=0; i<dt.length; i++)
		{
			if (dt[i].className == 'open')
				$('tabposition').value = i;
		}
		
		submitform(pressbutton);
	}
}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=users&task=edit'); ?>" method="post" name="adminForm" id="adminForm">
<?php echo $this->tabs->startPane('user-pane'); ?>

<?php echo $this->tabs->startPanel(JText::_('RSM_USER_INFO'), 'user-info'); ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('Username'); ?>"><label for="username"><?php echo JText::_('Username'); ?></label></span></td>
			<td><input type="text" name="u[username]" value="<?php echo $this->escape($this->row->username); ?>" id="username" size="120" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('Name'); ?>"><label for="name"><?php echo JText::_('Name'); ?></label></span></td>
			<td><input type="text" name="u[name]" value="<?php echo $this->escape($this->row->name); ?>" id="name" size="120" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('Email'); ?>"><label for="email"><?php echo JText::_('Email'); ?></label></span></td>
			<td><input type="text" name="u[email]" value="<?php echo $this->escape($this->row->email); ?>" id="email" size="120" /></td>
		</tr>
		<?php foreach ($this->fields as $field) { ?>
		<tr>
			<td width="200"><?php echo $field[0]; ?></td>
			<td><?php echo $field[1]; ?></td>
		</tr>
		<?php } ?>
	</table>
<?php echo $this->tabs->endPanel(); ?>

<?php echo $this->tabs->startPanel(JText::_('RSM_MEMBERSHIPS'), 'user-memberships'); ?>
	<div class="button2-left"><div class="blank"><a class="modal" title="Select the path" href="" rel="{handler: 'iframe', size: {x: 660, y: 475}}" id="valuesChange" onclick="this.href = '<?php echo JRoute::_('index.php?option=com_rsmembership&controller=users&task=editmembership&user_id='.$this->row->user_id.'&tmpl=component'); ?>';"><?php echo JText::_('New'); ?></a></div></div>
	<span class="rsmembership_clear"></span>
	
	<div id="addmemberships_ajax">
	<?php $this->display('memberships'); ?>
	</div>
<?php echo $this->tabs->endPanel(); ?>

<?php echo $this->tabs->startPanel(JText::_('RSM_TRANSACTION_HISTORY'), 'user-transactions'); ?>
<table class="adminlist">
<thead>
	<tr>
		<th width="5"><?php echo JText::_( '#' ); ?></th>
		<th width="20"><?php echo JText::_('Delete'); ?></th>
		<th width="140"><?php echo JText::_('RSM_TYPE'); ?></th>
		<th><?php echo JText::_('RSM_DETAILS'); ?></th>
		<th width="200"><?php echo JText::_('RSM_DATE'); ?></th>
		<th width="110"><?php echo JText::_('RSM_IP'); ?></th>
		<th><?php echo JText::_('RSM_PRICE'); ?></th>
		<th><?php echo JText::_('RSM_STATUS'); ?></th>
		<th><?php echo JText::_('RSM_GATEWAY'); ?></th>
		<th><?php echo JText::_('RSM_HASH'); ?></th>
	</tr>
	</thead>
	<?php
	$i = 0;
	$k = 0;
	foreach ($this->transactions as $row) { ?>
	<tr class="row<?php echo $k; ?>">
		<td><?php echo $i+1; ?></td>
		<td align="center"><a class="delete-item" onclick="return confirm('<?php echo JText::_('RSM_CONFIRM_DELETE'); ?>')" href="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=transactions&task=remove&cid[]='.$row->id.'&'.JUtility::getToken().'=1&tabposition=2&user_id='.$this->row->user_id); ?>"><?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/remove.png', JText::_('Delete')); ?></a></td>
		<td><?php echo JText::_('RSM_TRANSACTION_'.strtoupper($row->type)); ?></td>
		<td><?php
			$params = RSMembershipHelper::parseParams($row->params);
			switch ($row->type)
			{
				case 'new':
					if (!empty($params['membership_id']))
						echo $this->cache->memberships[$params['membership_id']];
					if (!empty($params['extras']))
						foreach ($params['extras'] as $extra)
							if (!empty($extra))
								echo '<br />- '.$this->cache->extra_values[$extra];
				break;
				
				case 'upgrade':
					if (!empty($params['from_id']) && !empty($params['to_id']))
						echo $this->cache->memberships[$params['from_id']].' -&gt; '.$this->cache->memberships[$params['to_id']];
				break;
				
				case 'addextra':
					if (!empty($params['extras']))
						foreach ($params['extras'] as $extra)
							echo $this->cache->extra_values[$extra].'<br />';
				break;
				
				case 'renew':
					if (!empty($params['membership_id']))
						echo $this->cache->memberships[$params['membership_id']];
				break;
			}
			?>
		</td>
		<td><?php echo date(RSMembershipHelper::getConfig('date_format'), $row->date); ?></td>
		<td><?php echo $row->ip; ?></td>		
		<td><?php echo RSMembershipHelper::getPriceFormat($row->price); ?></td>
		<td><?php echo JText::_('RSM_TRANSACTION_STATUS_'.strtoupper($row->status)); ?></td>
		<td><?php echo $row->gateway; ?></td>
		<td><?php echo empty($row->hash) ? '<em>'.JText::_('RSM_NO_HASH').'</em>' : $row->hash; ?></td>
	</tr>
	<?php
		$i++;
		$k=1-$k;
	}
	?>
</table>
<?php echo $this->tabs->endPanel(); ?>

<?php echo $this->tabs->startPanel(JText::_('RSM_ACCESS_LOGS'), 'access-logs'); ?>
<table class="adminlist">
	<thead>
	<tr>
		<th width="5"><?php echo JText::_( '#' ); ?></th>
		<th width="200"><?php echo JText::_('RSM_DATE'); ?></th>
		<th width="110"><?php echo JText::_('RSM_IP'); ?></th>
		<th><?php echo JText::_('RSM_PATH'); ?></th>
	</tr>
	</thead>
	<?php
	$k = 0;
	$i = 0;
	$n = count($this->row->logs);
	foreach ($this->row->logs as $row)
	{
	?>
		<tr class="row<?php echo $k; ?>">
			<td><?php echo $i+1; ?></td>
			<td><?php echo date(RSMembershipHelper::getConfig('date_format'), $row->date); ?></td>
			<td><?php echo $row->ip; ?></td>
			<td><?php echo $row->path; ?></td>
		</tr>
	<?php
		$i++;
		$k=1-$k;
	}
	?>
</table>
<?php echo $this->tabs->endPanel(); ?>

<?php echo $this->tabs->endPane(); ?>
	
<?php echo $token; ?>
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="option" value="com_rsmembership" />
<input type="hidden" name="controller" value="users" />
<input type="hidden" name="task" value="edit" />
<input type="hidden" name="view" value="users" />

<input type="hidden" name="mu[user_id]" value="<?php echo $this->row->user_id; ?>" />
<input type="hidden" name="u[id]" value="<?php echo $this->row->user_id; ?>" />

<input type="hidden" name="tabposition" value="0" id="tabposition" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>