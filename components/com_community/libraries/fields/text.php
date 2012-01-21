<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CFieldsText
{
	function getFieldHTML( $field , $required )
	{
		// If maximum is not set, we define it to a default
		$field->max	= empty( $field->max ) ? 200 : $field->max;
		CFactory::load( 'helpers' , 'string' );
		$class	= ($field->required == 1) ? ' required' : '';
		$html	= '<input title="' . $field->name . '::'. CStringHelper::escape( $field->tips ).'" type="text" value="' . $field->value . '" id="field' . $field->id . '" name="field' . $field->id . '" maxlength="' . $field->max . '" size="40" class="jomTips tipRight inputbox' . $class . '" />';
		$html   .= '<span id="errfield'.$field->id.'msg" style="display:none;">&nbsp;</span>';

		return $html;
	}
	
	function isValid( $value , $required )
	{
		if( $required && empty($value))
		{
			return false;
		}		
		return true;
	}
}