<?php
/**
 * Export products
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: export_productexport.php 902 2009-05-10 01:50:10Z Suami $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' ); 
?>
<div id="productexport">
	<table class="adminlist">
		<thead>
			<tr><th colspan="2"><?php echo JText::_('EXPORT_PRODUCTEXPORT'); ?></th></tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<?php echo JHTML::tooltip(JText::_('EXPORT_PRODUCT_CATEGORY_INFO'), JText::_('EXPORT_PRODUCT_CATEGORY'), 'tooltip.png', '', '', false); ?>
					<?php echo JText::_('EXPORT_PRODUCT_CATEGORY'); ?>
				</td>
				<td>
					<?php echo $this->lists['product_categories']; ?></td>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JHTML::tooltip(JText::_('EXPORT_PRODUCT_SKU_FILTER_INFO'), JText::_('EXPORT_PRODUCT_SKU_FILTER'), 'tooltip.png', '', '', false); ?>
					<?php echo JText::_('EXPORT_PRODUCT_SKU_FILTER'); ?>
				</td>
				<td>
					<input type="text" name="productskufilter" id="productskufilter" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JHTML::tooltip(JText::_('EXPORT_PRODUCT_STOCK_LEVEL_INFO'), JText::_('EXPORT_PRODUCT_STOCK_LEVEL'), 'tooltip.png', '', '', false); ?>
					<?php echo JText::_('EXPORT_PRODUCT_STOCK_LEVEL'); ?>
				</td>
				<td>
					<input type="text" name="stocklevelstart" id="stocklevelstart" /> - <input type="text" name="stocklevelend" id="stocklevelend" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JHTML::tooltip(JText::_('EXPORT_CURRENCY_INFO'), JText::_('EXPORT_CURRENCY'), 'tooltip.png', '', '', false); ?>
					<?php echo JText::_('EXPORT_CURRENCY'); ?>
				</td>
				<td>
					<?php echo $this->lists['targetcurrency']; ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>
