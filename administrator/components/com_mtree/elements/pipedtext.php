<?php
/**
 * @version		$Id$
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Renders a piped text element. eg: jpg|jpeg|png|gif
 *
 * @package 	Mosets Tree
 * @subpackage	Parameter
 * @since		2.1
 */

class JElementPipedText extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'PipedText';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="text_area"' );
		
        /*
         * Required to avoid a cycle of encoding &
         * html_entity_decode was used in place of htmlspecialchars_decode because
         * htmlspecialchars_decode is not compatible with PHP 4
         */
		$arr_value = array();
		if( is_array($value) ) 
		{
			foreach( $value AS $v )
			{
		        $arr_value[] = htmlspecialchars(html_entity_decode($v, ENT_QUOTES), ENT_QUOTES);
			}
		} else {
	        $arr_value[] = htmlspecialchars(html_entity_decode($value, ENT_QUOTES), ENT_QUOTES);
		}
		$str_value = implode('|',$arr_value);
		
		if( $node->attributes('cols') > 0 && $node->attributes('rows') > 0 ) {
			$html = '<textarea name="'.$control_name.'['.$name.']" id="'.$control_name.$name.'" rows="'.$node->attributes('rows').'" cols="'.$node->attributes('cols').'" '.$class.' />';
			$html .= $str_value;
			$html .= '</textarea>';
		} else {
			$size = ( $node->attributes('size') ? 'size="'.$node->attributes('size').'"' : '' );
			$html = '<input type="text" name="'.$control_name.'['.$name.']" id="'.$control_name.$name.'" value="'.$str_value.'" '.$class.' '.$size.' />';
		}
		
		return $html;
	}
}