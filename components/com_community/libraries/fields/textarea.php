<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CFieldsTextarea
{
	function getFieldHTML( $field , $required )
	{
		$config	=& CFactory::getConfig();
		$js	= '/assets/validate-1.5';
		$js .= ( $config->getBool('usepackedjavascript') ) ? '.pack.js' : '.js';
		CAssets::attach($js, 'js');
		
		// If maximum is not set, we define it to a default
		$field->max	= empty( $field->max ) ? 200 : $field->max;
	 
		$class	= ($field->required == 1) ? ' required' : '';
		CFactory::load( 'helpers' , 'string' );
		$html	= '<textarea id="field' . $field->id . '" name="field' . $field->id . '" class="jomTips tipRight inputbox textarea' . $class . '" title="' . $field->name . '::' . CStringHelper::escape( $field->tips ) . '">' . $field->value . '</textarea>';
		$html   .= '<span id="errfield'.$field->id.'msg" style="display:none;">&nbsp;</span>';
		$html	.= '<script type="text/javascript">cvalidate.setMaxLength("#field' . $field->id . '", "' . $field->max . '");</script>';
		
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
