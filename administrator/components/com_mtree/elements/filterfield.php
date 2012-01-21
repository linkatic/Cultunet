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
 * Parameter for filtering a field based on simple operator (=,>,<,>=,<= & !=)
 *
 * @author 		Lee Cher Yeong <mtree@mosets.com>
 * @package 	Mosets Tree
 * @subpackage	Parameter
 * @since		2.1
 */

class JElementFilterfield extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Filter Field';
	
	/**
	* Maximum length of a caption before it's cut off
	*
	* @access	protected
	* @var		int
	*/
	var $_max_caption_length = 23;
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		$size = ( $node->attributes('size') ? 'size="'.$node->attributes('size').'"' : '' );
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="text_area"' );
		
		if( empty($value) && !is_array($value) ) {
			$value = array('','=','');
		}
		
		$html 		= '';
		$db			= & JFactory::getDBO();
		$db->setQuery( 'SELECT cf_id AS value, caption AS text FROM #__mt_customfields WHERE published = 1 AND iscore = 0' );
		$customfields[] = JHTML::_('select.option', '', JText::_('- Select a field -') );
		$customfields = array_merge( $customfields, $db->loadObjectList() );
		
		// trim long caption
		$i = 0;
		foreach( $customfields AS $customfield )
		{
			if( JString::strlen($customfields[$i]->text) > ($this->_max_caption_length -3) )
			{
				$customfields[$i]->text = JString::substr( $customfields[$i]->text, 0, ($this->_max_caption_length -3) ) . '...';
			}
			$i++;
		}
		$html .= JHTML::_('select.genericlist',  $customfields, $control_name.'['.$name.'][]', 'class="inputbox"', 'value', 'text', $value[0], $control_name.$name);

		$operators[] = JHTML::_('select.option', '=', JText::_('is equal to'));
		$operators[] = JHTML::_('select.option', '!=', JText::_('is not equal to'));
		$operators[] = JHTML::_('select.option', '>', JText::_('is more than'));
		$operators[] = JHTML::_('select.option', '<', JText::_('is less than'));

		$html .= JHTML::_('select.genericlist',  $operators, $control_name.'['.$name.'][]', 'class="inputbox"', 'value', 'text', $value[1], $control_name.$name);
		
		$html .= '<input type="text" name="'.$control_name.'['.$name.'][]" id="'.$control_name.$name.'" value="'.$value[2].'" '.$class.' '.$size.' />';

		return $html;
		
	}
}
