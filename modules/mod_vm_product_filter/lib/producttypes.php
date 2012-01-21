<?php
global $mosConfig_absolute_path, $vm_mainframe, $sess;
// Load the virtuemart main parse code
if( file_exists(dirname(dirname(dirname(__FILE__))).'/../../components/com_virtuemart/virtuemart_parser.php' )) {
	require_once( dirname(dirname(dirname(__FILE__))).'/../../components/com_virtuemart/virtuemart_parser.php' );
} else {
	require_once( dirname(dirname(dirname(__FILE__))).'/../components/com_virtuemart/virtuemart_parser.php' );
}

/**
 * $ModDesc
 *
 * @version		$Id: helper.php $Revision
 * @package		modules
 * @subpackage	$Subpackage.
 * @copyright	Copyright (C) Dec 2009 IceTheme.com.All rights reserved.
 * @license		GNU General Public License version 2
 * -------------------------------------
 * Based on Module Libs From LandOfCoder
 * @copyright (C) May 2010 LandOfCoder.com <@emai:landofcoder@gmail.com, @site: http://landofcoder.com>.
 */
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Get a collection of categories
 */
class JElementproducttypes extends JElement {

	/*
	 * Category name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'fgroup';

	/**
	 * fetch Element
	 */
	function fetchElement($name, $value, &$node, $control_name){
      
	   $db = new ps_DB;
        $query  = " SELECT * FROM `#__{vm}_product_type`"
                . " WHERE  1=1 ";
           //     . " ORDER BY parameter_list_order ";
       	$db->setQuery( $query );
        $data = $db->loadObjectList();
       //         echo '<pre>'.print_r($data,1); die;
        $tmp = array();
		$tmp[0] = new stdClass();
		$tmp[0]->product_type_id = '';
		$tmp[0]->product_type_name = JText::_("---------- Select All ----------");
        $options = array_merge($tmp,$data);

        return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]',
                      'class="inputbox" size="5" style="width:95%;" multiple="multiple"',
                      'product_type_id', 'product_type_name', $value, $control_name.$name);
        return $data;
	}
}

?>