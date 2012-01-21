<?php
/**
 * Log results
 *
 * @todo add link to online documentation
 * @package CSVImproved
 * @subpackage Log
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: availablefields.php 890 2009-04-23 17:02:24Z Suami $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' ); 
?>
<form action="index.php" method="post" name="adminForm">
	<table class="adminlist">
		<tr>
			<td><?php echo $this->list['templatetypes']; ?> <input type="submit" value="<?php echo JText::_('GO'); ?>" /></td>
		</tr>
	</table>
	<table class="adminlist">
		<thead>
		<tr>
			<th width="20">
			<?php echo JText::_('ID'); ?>
			</th>
			<th class="title">
			<?php echo JText::_('CSVI NAME'); ?>
			</th>
			<th class="title">
			<?php echo JText::_('VM NAME'); ?>
			</th>
			<th class="title">
			<?php echo JText::_('TABLE'); ?>
			</th>
		</tr>
		</thead>
		<?php
		$k = 0;
		for ($i=0, $n=count( $this->availablefields ); $i < $n; $i++) {
			$row = $this->availablefields[$i];
			?>
			<tr class="<?php echo 'row'. $k; ?>">
				<td align="center">
				<?php echo $this->pagination->getRowOffset($i); ?>
				</td>
				<td>
					<?php echo JHTML::_('link', 'http://www.csvimproved.com/csv-improved-documentation/available-fields/'.str_replace('_', '', $row->csvi_name).'.html', $row->csvi_name, 'target="_blank"'); ?>
				</td>
				<td>
					<?php echo $row->vm_name; ?>
				</td>
				<td>
					<?php echo $row->vm_table; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		<tr>
            <td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
         </tr>
		</table>
	<input type="hidden" name="option" value="com_csvimproved" />
	<input type="hidden" name="task" value="availablefields" />
	<input type="hidden" name="controller" value="availablefields" />
</form>