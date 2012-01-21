<?php
/**
* @version		$Id: select.php 10824 2008-08-27 17:20:01Z tcp $
* @package		Joomla.Framework
* @subpackage	HTML
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * Utility class for creating HTML select lists
 *
 * @static
 * @package 	NOIXACL
 * @subpackage	HTML
 * @since		1.5
 */
class JHTMLCheckbox
{
	/**
	* Generates an Array HTML checkbox list
	*
	* @param array An array of objects
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <input> tag
	* @param string The name of the object variable for the option value
	* @param string The name of the object variable for the option text
	* @param string/array the objects checked
	* @param string/array object disabled
	* @returns string HTML for the select list
	*/
	function genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = null, $disabled = null, $idtag = false, $translate = false )
	{
		reset( $arr );
		$html = array();

		if ( is_array($attribs) ){
			$attribs = JArrayHelper::toString($attribs);
		}

		$id_text = $name;
		if ( $idtag ){
			$id_text = $idtag;
		}

		for ($i=0, $n=count( $arr ); $i < $n; $i++ ){
			$k	= $arr[$i]->$key;
			$t	= $translate ? JText::_( $arr[$i]->$text ) : $arr[$i]->$text;
			$id	= ( isset($arr[$i]->id) ? @$arr[$i]->id : null);

			$extra	= '';
			$extra	.= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
			
			if( is_array( $disabled ) ){
				foreach( $disabled as $val ){
					$k2 = is_object( $val ) ? $val->$key : $val;
					if ($k == $k2){
						$extra .= " disabled=\"disabled\"";
						break;
					}
				}
			}
            else {
				$extra .= ((string)$k == (string)$disabled ? " disabled=\"disabled\"" : '');
			}
			
			if ( is_array( $selected ) ){
				foreach( $selected as $val ){
					$k2 = is_object( $val ) ? $val->$key : $val;
					if( $k == $k2 ){
						$extra .= " checked=\"checked\"";
						break;
					}
				}
			}
			
			if( !is_null($selected) && is_string($selected) ) {
				$extra .= ((string)$k == (string)$selected ? " checked=\"checked\"" : '');
			}
			
			$html[] = "\n\t<input type=\"checkbox\" name=\"$name\" id=\"$id_text$k\" value=\"".$k."\"$extra $attribs />"
					. "\n\t<label for=\"$id_text$k\">$t</label>";
		}
		
		return $html;
	}
}