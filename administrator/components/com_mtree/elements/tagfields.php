<?php
/**
 * @version		$Id$
 * @package		Mosets Tree
 * @copyright	(C) 2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('JPATH_BASE') or die();

/**
 * Renders a list of taggable fields
 *
 * @author 		Lee Cher Yeong <mtree@mosets.com>
 * @package 	Mosets Tree
 * @subpackage	Parameter
 * @since		2.1
 */

class JElementTagFields extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Tag Fields';

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
		$db->setQuery( 'SELECT cf_id AS value, caption AS text FROM #__mt_customfields WHERE published = 1 AND tag_search = 1' );
		$fields		= $db->loadObjectList();
		
		if( !is_array($value) ) {
			$value = array($value);
		}
		
		$html = '';
		
		$i = 0;
		foreach( $fields AS $field )
		{
			if( JString::strlen($fields[$i]->text) > ($this->_max_caption_length -3) )
			{
				$fields[$i]->text = JString::substr( $fields[$i]->text, 0, ($this->_max_caption_length -3) ) . '...';
			}
			$i++;
		}
		$html .= JHTML::_('select.genericlist',  $fields, $control_name.'['.$name.'][]', 'class="inputbox"', 'value', 'text', $value[0], $control_name.$name);

		return $html;
		
	}
}
