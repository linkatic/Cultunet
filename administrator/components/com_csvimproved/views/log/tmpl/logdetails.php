<?php
/**
 * Details for a log entry
 *
 * @package CSVImproved
 * @subpackage Log
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: logdetails.php 681 2009-01-07 07:12:53Z Suami $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' ); 
?>
<form action="index.php" method="post" name="adminForm">
	<table class="adminlist">
		<thead>
			<tr>
				<th class="title"><?php echo JText::_('Status'); ?></th>
				<th class="title"><?php echo JText::_('Records'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		for ($i=0, $n=count($this->logstatistics); $i < $n; $i++) {
			$row = $this->logstatistics[$i];
			if (!empty($row->status)) {
			?>
			<tr class="<?php echo 'row'.$k = 1 - $k; ?>">
				<td>
				<?php echo JText::_($row->status); ?>
				</td>
				<td>
				<?php echo JText::_($row->total); ?>
				</td>
			</tr>
			<?php
			}
		}
		?>
		</tbody>
	</table>
	<br />
	<table class="adminlist">
		<thead>
		<tr>
			<th class="title">
			<?php echo JText::_('Log message'); ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		for ($i=0, $n=count( $this->logdetails ); $i < $n; $i++) {
			$row = $this->logdetails[$i];
			?>
			<tr class="<?php echo 'row'.$k = 1 - $k; ?>">
				<td>
				<?php echo JText::_($row->description); ?>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
		</table>
	<input type="hidden" name="option" value="com_csvimproved" />
	<input type="hidden" name="task" value="log" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="log" />
</form>
