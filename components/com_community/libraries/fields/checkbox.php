<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CFieldsCheckbox
{	
	function _translateValue( &$string )
	{
		$string	= JText::_( $string );
	}

	/**
	 * Method to format the specified value for text type
	 **/	 	
	function getFieldData( $value )
	{
		// Since multiple select has values separated by commas, we need to replace it with <br />.
		$fieldArray	= explode ( ',' , $value );
		
		array_walk($fieldArray, array('CFieldsCheckbox', '_translateValue'));		
		
		$fieldValue = implode('<br />', $fieldArray);				
		return $fieldValue;
	}	
	
	function getFieldHTML( $field , $required )
	{
		$class				= ($field->required == 1) ? ' required validate-custom-checkbox' : '';
		$lists				= is_array( $field->value ) ? $field->value : explode(',', $field->value);
		$html				= '';
		$elementSelected	= 0;
		$elementCnt	        = 0;
		
		foreach( $field->options as $option )
		{
			$selected	= in_array( JString::trim( $option ) , $lists ) ? ' checked="checked"' : '';
			
			if( empty( $selected ) )
			{
				$elementSelected++;
			}
			$elementCnt++;
		}
		
		$cnt = 0;
		CFactory::load( 'helpers' , 'string' );
		$html	.= '<div class="jomTips tipRight" style="display: inline-block;" title="' . $field->name . '::' . CStringHelper::escape( $field->tips ). '">';
		foreach( $field->options as $option )
		{
			$selected	= in_array( $option, $lists ) ? ' checked="checked"' : '';		    
						
			$html .= '<label class="lblradio-block">';
			$html .= '<input type="checkbox" name="field' . $field->id . '[]" value="' . $option . '"' . $selected . ' class="checkbox '.$class.'" style="margin: 0 5px 5px 0;" />';
			$html .= JText::_( $option ) . '</label>';
			
			$cnt++;
		}
		$html   .= '<span id="errfield'.$field->id.'msg" style="display: none;">&nbsp;</span>';
		$html	.= '</div>';		
		
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
	
	function formatdata( $value )
	{
		$finalvalue = '';
		if(!empty($value))
		{
			foreach($value as $listValue){
				$finalvalue	.= $listValue . ',';
			}
		}					
		return $finalvalue;	
	}
}