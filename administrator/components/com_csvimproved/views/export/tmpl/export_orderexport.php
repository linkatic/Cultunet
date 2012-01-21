<?php
/**
 * Export orders
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: export_orderexport.php 909 2009-05-22 17:58:50Z Roland $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<div id="orderexport">
	<table class="adminlist">
		<thead>
			<tr><th colspan="2"><?php echo JText::_('Export order options'); ?></th></tr>
		</thead>
		<tbody>
		<tr>
			<td>
				<?php echo JHTML::tooltip(JText::_('EXPORT_ORDER_NUMBER_INFO'), JText::_('EXPORT_ORDER_NUMBER'), 'tooltip.png', '', '', false); ?>
				<?php echo JText::_('EXPORT_ORDER_NUMBER'); ?>
			</td>
			<td>
				<input type="text" name="ordernostart" id="ordernostart" /> - <input type="text" name="ordernoend" id="ordernoend" />
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JHTML::tooltip(JText::_('EXPORT_ORDER_DATE_INFO'), JText::_('EXPORT_ORDER_DATE'), 'tooltip.png', '', '', false); ?>
				<?php echo JText::_('EXPORT_ORDER_DATE'); ?>
			</td>
			<td>
				<?php echo JHTML::_( 'calendar', '', 'orderdatestart', 'orderdatestart', '%d-%m-%Y %H:%M:%S', 'size="25"'); ?>
				 - 
				<?php echo JHTML::_( 'calendar', '', 'orderdateend', 'orderdateend', '%d-%m-%Y %H:%M:%S', 'size="25"'); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JHTML::tooltip(JText::_('EXPORT_ORDER_STATUS_INFO'), JText::_('EXPORT_ORDER_STATUS'), 'tooltip.png', '', '', false); ?>
				<?php echo JText::_('EXPORT_ORDER_STATUS'); ?>
			</td>
			<td>
				<?php echo $this->lists['orderstatus']; ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JHTML::tooltip(JText::_('EXPORT_ORDER_CURRENCY_INFO'), JText::_('EXPORT_ORDER_CURRENCY'), 'tooltip.png', '', '', false); ?>
				<?php echo JText::_('EXPORT_ORDER_CURRENCY'); ?>
			</td>
			<td>
				<?php echo $this->lists['ordercurrency']; ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JHTML::tooltip(JText::_('EXPORT_ORDER_PRICE_INFO'), JText::_('EXPORT_ORDER_PRICE'), 'tooltip.png', '', '', false); ?>
				<?php echo JText::_('EXPORT_ORDER_PRICE'); ?>
			</td>
			<td>
				<input type="text" name="orderpricestart" id="orderpricestart" /> - <input type="text" name="orderpriceend" id="orderpriceend" />
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JHTML::tooltip(JText::_('EXPORT_ORDER_USER_INFO'), JText::_('EXPORT_ORDER_USER'), 'tooltip.png', '', '', false); ?>
				<?php echo JText::_('EXPORT_ORDER_USER'); ?>
			</td>
			<td>
				<div id="searchuser"><input type="text" name="searchuserbox" id="searchuserbox" value="" /></div>
				<table id="selectuserid" class="adminlist">
					<thead>
					<tr><th><?php echo JText::_('EXPORT_USER_ID'); ?></th><th><?php echo JText::_('EXPORT_USERNAME');?></th></tr>
					</thead>
				</table>
				<?php echo $this->lists['orderuser']; ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JHTML::tooltip(JText::_('EXPORT_ORDER_PRODUCT_INFO'), JText::_('EXPORT_ORDER_PRODUCT'), 'tooltip.png', '', '', false); ?>
				<?php echo JText::_('EXPORT_ORDER_PRODUCT'); ?>
			</td>
			<td>
				<div id="searchproduct"><input type="text" name="searchproductbox" id="searchproductbox" value="" /></div>
				<table id="selectproductsku" class="adminlist">
					<thead>
					<tr><th><?php echo JText::_('EXPORT_PRODUCT_SKU'); ?></th><th><?php echo JText::_('EXPORT_PRODUCT_NAME');?></th></tr>
					</thead>
				</table>
				<?php echo $this->lists['orderproduct']; ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JHTML::tooltip(JText::_('EXPORT_ORDER_SHIPPING_INFO'), JText::_('EXPORT_ORDER_SHIPPING'), 'tooltip.png', '', '', false); ?>
				<?php echo JText::_('EXPORT_ORDER_SHIPPING'); ?>
			</td>
			<td>
			<?php echo str_replace('name="address"', 'name="order_address"', $this->lists['address']); ?>
			</td>
		</tr>
		</tbody>
	</table>
</div>