<?php
/**
 * Export products
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: export_productexport.php 665 2009-01-02 07:40:08Z Suami $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' ); 
?>
<table class="adminlist">
	<thead>
		<tr><th colspan="2"><?php echo JText::_('GENERAL_EXPORT_OPTIONS'); ?></th></tr>
	</thead>
	<tbody>
		<?php
		echo $this->loadTemplate('exportlocation');
		echo $this->loadTemplate('exportlimits');
		?>
	</tbody>
</table>
