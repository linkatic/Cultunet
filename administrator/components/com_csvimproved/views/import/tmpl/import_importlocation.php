<?php
/**
 * Import location
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: import_importlocation.php 754 2009-02-14 10:53:32Z Suami $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' ); 
?>
<tr>
	<td>
		<?php echo JHTML::tooltip(JText::_('Choose this option to import a file stored on your local computer'), JText::_('Import a CSV File'), 'tooltip.png', '', '', false); ?>
		<?php echo JText::_('UPLOAD_FILE'); ?>
	</td>
	<td><input type="file" name="file" id="file" size="120" onChange="document.adminForm.cb1.value = 1" /></td>
</tr>
<tr>
	<td>
		<?php echo JHTML::tooltip(JText::_('Choose this option to import a file stored on the server. The file can be uploaded via FTP first then selected here. This is handy for large files.<br /><br />The full path including filename must be specified.'), JText::_('Load from server'), 'tooltip.png', '', '', false); ?>
		<?php echo JText::_('Load from server'); ?>
	</td>
	<td><input type="text" size="120" value="<?php echo JPATH_SITE.DS ?>media" name="local_csv_file" onChange="document.adminForm.cb1.value = 2"/></td>
</tr>
