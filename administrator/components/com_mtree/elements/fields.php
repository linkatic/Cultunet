<?php
/**
 * @version		$Id$
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('JPATH_BASE') or die();

/**
 * Renders a list of fields
 *
 * @author 		Lee Cher Yeong <mtree@mosets.com>
 * @package 	Mosets Tree
 * @subpackage	Parameter
 * @since		2.1
 */

class JElementFields extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Fields';

	/**
	* Maximum length of a caption before it's cut off
	*
	* @access	protected
	* @var		int
	*/
	var $_max_caption_length = 23;
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		$db			= & JFactory::getDBO();
		$db->setQuery( 'SELECT * FROM #__mt_customfields WHERE published = 1' );
		$fields		= $db->loadObjectList();
		
		if( !is_array($value) ) {
			$value = array($value);
		}
		
		$html = '';

		foreach( $fields AS $field )
		{
			$html .= '<div style="width:125px;float:left;padding: 4px 3px"><input type="checkbox"';
			$html .= ' name="' . $control_name.'['.$name.'][]' . '"';
			$html .= ' id="' . $control_name.$name.$field->cf_id . '"';
			$html .= ' value="' . $field->cf_id . '"';
			if( in_array($field->cf_id,$value) ) {
				$html .= ' checked';
			}
			$html .= ' />';
			$field->caption = stripslashes($field->caption);
			$html .= '&nbsp;<label for="' . $control_name.$name.$field->cf_id . '" title="' . $field->caption . '">';
			if( JString::strlen($field->caption) > ($this->_max_caption_length -3) )
			{
				$html .= JString::substr($field->caption, 0, ($this->_max_caption_length -3));
				$html .= '...';
			} else {
				$html .= $field->caption;
			}
			$html .= '</label>';
			$html .= '</div>';
		}
		return $html;
		
	}
}
