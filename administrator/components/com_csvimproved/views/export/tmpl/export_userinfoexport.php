<?php
/**
 * Export orders
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: export_orderexport.php 681 2009-01-07 07:12:53Z Suami $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' ); 
?>
<div id="userinfoexport">
	<table class="adminlist">
		<thead>
			<tr><th colspan="2"><?php echo JText::_('EXPORT_USER_INFO_OPTIONS'); ?></th></tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<?php echo JHTML::tooltip(JText::_('EXPORT_USER_INFO_ADDRESS_INFO'), JText::_('EXPORT_USER_INFO_ADDRESS'), 'tooltip.png', '', '', false); ?>
					<?php echo JText::_('EXPORT_USER_INFO_ADDRESS'); ?>
				</td>
				<td>
					<?php echo str_replace('name="address"', 'name="userinfo_address"', $this->lists['address']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JHTML::tooltip(JText::_('EXPORT_USER_INFO_VENDOR_INFO'), JText::_('EXPORT_USER_INFO_VENDOR'), 'tooltip.png', '', '', false); ?>
					<?php echo JText::_('EXPORT_USER_INFO_VENDOR'); ?>
				</td>
				<td>
					<?php echo $this->lists['vendors']; ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JHTML::tooltip(JText::_('EXPORT_USER_INFO_PERMS_INFO'), JText::_('EXPORT_USER_INFO_PERMS'), 'tooltip.png', '', '', false); ?>
					<?php echo JText::_('EXPORT_USER_INFO_PERMS'); ?>
				</td>
				<td>
					<?php echo $this->lists['permissions']; ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>