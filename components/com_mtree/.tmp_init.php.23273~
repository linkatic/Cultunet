<?php
/**
 * @version		$Id: init.php 625 2009-03-29 07:16:20Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

global $mtconf;
if(!isset($mtconf)) {
	if( !isset($database) ) {
		$database 	=& JFactory::getDBO();
	}
	require( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_mtree'.DS.'config.mtree.class.php');
	$mtconf	= new mtConfig($database);
}
?>