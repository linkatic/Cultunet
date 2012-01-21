<?php
/**
 * Element: Checkbox
 * Displays options as checkboxes
 *
 * @package     NoNumber! Elements
 * @version     1.6.0
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright (C) 2010 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// Ensure this file is being included by a parent file
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Checkbox Element
 */
class JElementCheckbox extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Checkbox';

	function fetchElement( $name, $values, &$node, $control_name )
	{
		if ( !is_array( $values ) ) {
			$values = explode( ',', $values );
		}

		$options = array();
		foreach ( $node->children() as $option ) {
			$text		= $option->data();
			$val		= $option->attributes( 'value' );
			$disabled	= $option->attributes( 'disabled' );
			$option = '<input type="checkbox" id="'.$control_name.$name.$val.'" name="'.$control_name.'['.$name.'][]" value="'.$val.'"';
			if ( in_array( $val, $values ) ) {
				$option .= ' checked="checked"';
			}
			if ( $disabled ) {
				$option .= ' disabled="disabled"';
			}
			$option .= ' /> '.JText::_( $text );
			$options[] = $option;
		}

		return implode( '&nbsp;&nbsp;&nbsp;', $options );
	}
}