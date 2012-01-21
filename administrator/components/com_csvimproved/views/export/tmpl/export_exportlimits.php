<?php
/**
 * Export limits
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: export_exportlimits.php 861 2009-04-05 01:22:34Z Suami $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' ); 
?>
<tr>
	<td>
		<?php echo JHTML::tooltip(JText::_('Specify the number of rows to export. This is not the same as the number of products because a product can be more than 1 row.'), JText::_('EXPORT_NUMBER_RECORDS'), 'tooltip.png', '', '', false); ?>
		<?php echo JText::_('EXPORT_NUMBER_RECORDS'); ?>
	</td>
	<td>
		<?php echo JText::_('EXPORT_NUMBER_RECORDS'); ?> <input type="text" name="recordstart" id="recordstart" />
		<?php echo JText::_('EXPORT_START_RECORD'); ?> <input type="text" name="recordend" id="recordend" />
	</td>
	<tr>
		<td>
			<?php echo JHTML::tooltip(JText::_('EXPORT_GROUPBY_INFO'), JText::_('EXPORT_GROUPBY'), 'tooltip.png', '', '', false); ?>
			<?php echo JText::_('EXPORT_GROUPBY'); ?>
		</td>
		<td>
		<input type="checkbox" name="groupby" id="groupby" value="1" checked="checked" />
		</td>
	</tr>
</tr>