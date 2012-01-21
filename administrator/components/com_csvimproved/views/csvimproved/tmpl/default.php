<?php
/**
 * Default page for CSV Improved
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: default.php 666 2009-01-02 07:55:31Z Suami $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

global $mainframe; ?>

<div id="main">
	<div id="cpanel">
		<?php echo $this->cpanel_images->templates; ?>
		<?php echo $this->cpanel_images->import; ?>
		<?php echo $this->cpanel_images->export; ?>
		<?php echo $this->cpanel_images->maintenance; ?>
		<br class="clear" />
		<?php echo $this->cpanel_images->log; ?>
		<?php echo $this->cpanel_images->availablefields; ?>
		<?php echo $this->cpanel_images->help; ?>
		<?php echo $this->cpanel_images->about; ?>
	</div>
</div>
