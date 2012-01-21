<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');

class JElementMembershipCategory extends JElement
{
	/**
	* Element name
	*
	* @access       protected
	* @var          string
	*/
	var    $_name = 'Membership Category';

	function fetchElement($name, $value, &$node, $control_name)
	{
		// Base name of the HTML control.
		$ctrl  = $control_name .'['. $name .']';

		// Construct an array of the HTML OPTION statements.
		$options = array ();

		// Construct the various argument calls that are supported.
		$attribs       = ' ';
		if ($v = $node->attributes( 'size' )) {
				$attribs       .= 'size="'.$v.'"';
		}
		if ($v = $node->attributes( 'class' )) {
				$attribs       .= 'class="'.$v.'"';
		} else {
				$attribs       .= 'class="inputbox"';
		}
		if ($m = $node->attributes( 'multiple' ))
		{
				$attribs       .= ' multiple="multiple"';
				$ctrl          .= '[]';
		}
		
		$db = JFactory::getDBO();
		
		$options = array();
		$options[0] = new stdClass();
		$options[0]->id = 0;
		$options[0]->name = JText::_('RSM_NO_CATEGORY');
		$db->setQuery("SELECT * FROM #__rsmembership_categories ORDER BY ordering");
		$options = array_merge($options, $db->loadObjectList());
		
		if ($value == '')
			$value = $options;

		// Render the HTML SELECT list.
		return JHTML::_('select.genericlist', $options, $ctrl, $attribs, 'id', 'name', $value, $control_name.$name );
	}
}
?>