<?php
/**
 * Log results
 *
 * @package CSVImproved
 * @subpackage Log
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: log.php 946 2009-08-03 09:47:37Z Roland $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' ); 
?>
<form action="index.php" method="post" name="adminForm">
	<table class="adminlist" id="logs">
		<thead>
		<tr>
			<th width="20">
			<?php echo JText::_('ID'); ?>
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->logentries); ?>);" />
			</th>
			<th class="title">
			<?php echo JText::_('ACTION'); ?>
			</th>
			<th class="title">
			<?php echo JText::_('ACTION_TYPE'); ?>
			</th>
			<th class="title">
			<?php echo JText::_('TEMPLATE_NAME'); ?>
			</th>
			<th class="title">
			<?php echo JText::_('TIMESTAMP'); ?>
			</th>
			<th class="title">
			<?php echo JText::_('USER'); ?>
			</th>
			<th class="title">
			<?php echo JText::_('RECORDS'); ?>
			</th>
		</tr>
		</thead>
		<?php
		/* Check for logentries */
		if ($this->logentries) {
			for ($i=0, $n=count( $this->logentries ); $i < $n; $i++) {
				$row = $this->logentries[$i];
				
				/* Pseudo entry for satisfying Joomla */
				$row->checked_out = 0;
				
				$link 	= 'index2.php?option=com_csvimproved&task=logdetails&controller=log&hidemainmenu=1&cid[]='. $row->id;
				$checked = JHTML::_('grid.checkedout',  $row, $i);
				$user = JFactory::getUser($row->userid);
				?>
				<tr>
					<td align="center">
					<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td>
					<?php echo $checked; ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>"><?php echo JText::_($row->action); ?></a>
					</td>
					<td>
						<?php echo JText::_($row->action_type); ?>
					</td>
					<td>
						<?php echo $row->template_name; ?>
					</td>
					<td>
						<?php echo $row->logstamp; ?>
					</td>
					<td>
						<?php echo $user->name; ?>
					</td>
					<td>
						<?php echo $row->records; ?>
					</td>
				</tr>
				<?php
			}
		}
		else echo '<tr><td colspan="8" class="center">'.JText::_('NO_LOG_ENTRIES_FOUND').'</td></tr>';
		?>
		<tr>
            <td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
         </tr>
		</table>
	<input type="hidden" name="option" value="com_csvimproved" />
	<input type="hidden" name="task" value="log" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="log" />
</form>

<script type="text/javascript">
	function submitbutton(pressbutton) {
		// Some conditions
		document.adminForm.task.value=pressbutton;
		if (pressbutton != 'logdetails') {
			if (pressbutton == 'remove') msg = '<?php echo JText::_('LOG_ARE_YOU_SURE_REMOVE');?>';
			else if (pressbutton == 'remove_all') msg = '<?php echo JText::_('LOG_ARE_YOU_SURE_REMOVE_ALL');?>';
			if (confirm(msg)) {
				// More conditions
				submitform(pressbutton);
			}
		}
		else submitform(pressbutton);
	}
	jQuery("table#logs tr:even").addClass("row0");
	jQuery("table#logs tr:odd").addClass("row1");
</script>

