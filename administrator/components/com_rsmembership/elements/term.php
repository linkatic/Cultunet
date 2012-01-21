<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class JElementTerm extends JElement
{
   /**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Term';
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		$document =& JFactory::getDocument();
		$fieldName	= $control_name.'['.$name.']';

		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');

		$row =& JTable::getInstance('RSMembership_Terms', 'Table');
		if ($value)
			$row->load($value);
		else
			$row->name = JText::_('RSM_SELECT_TERM');

		$js = "
		function elSelectEvent(id, title) {
			document.getElementById('a_id').value = id;
			document.getElementById('a_name').value = title;
			document.getElementById('sbox-window').close();
		}";

		$document->addScriptDeclaration($js);

		JHTML::_('behavior.modal', 'a.modal');

		$html  = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="a_name" value="'.$row->name.'" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('RSM_SELECT').'"  href="index.php?option=com_rsmembership&controller=terms&task=element&tmpl=component" rel="'."{handler: 'iframe', size: {x: 650, y: 375}}".'">'.JText::_('RSM_SELECT').'</a></div></div>'."\n";
		$html .= "\n".'<input type="hidden" id="a_id" name="'.$fieldName.'" value="'.$value.'" />';
		
		return $html;
	}
}
?>