<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<p><?php echo JText::_('RSM_PAYMENT_INTEGRATIONS_MESSAGE'); ?></p>
<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&view=payments'); ?>" method="post" name="adminForm">
	<div id="editcell1">
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5"><?php echo JText::_( '#' ); ?></th>
				<th><?php echo JText::_('RSM_PAYMENT_TYPE'); ?></th>
			</tr>
			</thead>
	<?php
	$k = 0;
	$i = 0;
	$n = count($this->payments);
	foreach ($this->payments as $row)
	{
	?>
		<tr class="row<?php echo $k; ?>">
			<td><?php echo $i+1; ?></td>
			<td><?php echo $row; ?></td>
		</tr>
	<?php
		$i++;
		$k=1-$k;
	}
	?>
		</table>
	</div>
	
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_rsmembership" />
	<input type="hidden" name="view" value="payments" />
	<input type="hidden" name="task" value="" />
</form>