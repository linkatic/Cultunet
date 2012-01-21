<?php
/**
 * Import page
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: import.php 938 2009-06-23 18:39:36Z Roland $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
JHTML::_('behavior.modal');
JHTML::_('behavior.tooltip');
?>
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
	<table id="templates" class="adminlist">
	<thead><tr>
		<th class="showallbutton">
			<?php echo JHTML::tooltip(JText::_('SHOW_ALL_BUTTON_IMPORT'), JText::_('IMPORT'), 'tooltip.png', '', '', false); ?>
			<a class="showexportoption" onclick="switchRow('all'); showExportDiv();" title="<?php echo JText::_('Template name'); ?>"><?php echo JText::_('Template name'); ?></a>
		</th>
		<th><?php echo JText::_('Template type'); ?></th>
		<th><?php echo JText::_('Field delimiter'); ?></th>
		<th><?php echo JText::_('Text delimiter'); ?></th>
		<th><?php echo JText::_('Use column headers'); ?></th>
		<th><?php echo JText::_('Preview'); ?></th>
		<th><?php echo JText::_('Number of fields'); ?></th>
	</tr></thead>
	<tbody>
	<?php 
		$selected = JRequest::getInt('template_id', $this->templates[0]->template_id);
		$k = 1; foreach ($this->templates as $key => $details) { ?>
		<?php $rid = rand(); ?>
		<tr id="<?php echo $rid; ?>" class="row<?php echo $k = 1 - $k; ?> optionrow_block">
			<td><?php echo JHTML::_('select.radiolist', array($details), 'template_id', 'class="inputbox" onclick="switchRow(\''.$rid.'\'); showExportDiv(\''.strtolower($details->template_type).'\');"', 'template_id', 'template_name', $selected); ?></td>
			<td><?php echo JText::_($details->template_type); ?></td>
			<td class="center"><?php echo $details->field_delimiter; ?></td>
			<td class="center"><?php echo $details->text_enclosure; ?></td>
			<td class="center"><?php if ($details->use_column_headers) echo JText::_('Yes');
				else echo JText::_('No');?></td>
			<td class="center"><?php if ($details->show_preview) echo JText::_('Yes');
				else echo JText::_('No');?></td>
			<?php $link = JRoute::_('index3.php?option=com_csvimproved&controller=templates&task=listfields&template_id='.$details->template_id); ?>
			<td><?php echo JHTML::_('link', $link, $details->numberoffields.' '.JText::_('FIELDS'), array('class' => 'modal', 'rel' => '{handler: \'iframe\', size: {x: 600, y: 400}}')); ?></td>
			<!-- <td class="center"><?php echo $details->numberoffields; ?></td> -->
		</tr>
	<?php } ?>
	</tbody>
	<tfoot>
		<tr><td colspan="9"></td></tr>
	</tfoot>
	</table>
	<br />
	<?php echo $this->loadTemplate('productimport'); ?>
	<input type="hidden" id="cb1" name="selectfile" value="1" />
	<input type="hidden" name="option" value="com_csvimproved" />
	<input type="hidden" name="task" value="importfile" />
	<input type="hidden" name="controller" value="importfile" />
</form>