<?php
/**
 * Export location
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: export_exportlocation.php 867 2009-04-10 22:52:09Z Suami $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' ); 
?>
<tr>
	<td>
		<?php echo JHTML::tooltip(JText::_('Download the file to your local computer'), JText::_('EXPORT_TO_DOWNLOAD'), 'tooltip.png', '', '', false); ?>
		<?php echo JText::_('EXPORT_TO_DOWNLOAD'); ?>
	</td>
	<td><input type="radio" name="exportto" id="todownload" value="todownload" checked="checked" onClick="jQuery('#exportlocation').hide();" /></td>
</tr>
<tr>
	<td>
		<?php echo JHTML::tooltip(JText::_('Saves the file on the server'), JText::_('EXPORT_TO_LOCAL'), 'tooltip.png', '', '', false); ?>
		<?php echo JText::_('EXPORT_TO_LOCAL'); ?>
	</td>
	<td><input type="radio" name="exportto" id="tofile" value="tofile" onClick="jQuery('#exportlocation').show();" /></td>
</tr>
<tr>
	<td colspan="2">
		<div id="exportlocation" style="display: none;">
			<?php echo JHTML::tooltip(JText::_('EXPORT_LOCATION_INFO'), JText::_('EXPORT_LOCATION'), 'tooltip.png', '', '', false); ?>
			<input type="text" size="120" name="localfile" value="<?php echo JPATH_ROOT.DS."media"; ?>" />
		</div>
	</td>
</tr>
