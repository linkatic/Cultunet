<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CFieldsRadio
{
	function getFieldHTML( $field , $required )
	{
		$html				= '';
		$selectedElement	= 0;		
		$class				= ($field->required == 1) ? ' required validate-custom-radio' : '';
		$elementSelected	= 0;
		$elementCnt	        = 0;		
		
		for( $i = 0; $i < count( $field->options ); $i++ )
		{
		    $option		= $field->options[ $i ];
			$selected	= ( $option == $field->value ) ? ' checked="checked"' : '';
			
			if( empty( $selected ) )
			{
				$elementSelected++;
			}			
			$elementCnt++;				
		}
		
		
		$cnt = 0;
		CFactory::load( 'helpers' , 'string' );
		$html	.= '<div class="jomTips tipRight" style="display: inline-block;" title="' . $field->name . '::' . CStringHelper::escape( $field->tips ). '">';
		for( $i = 0; $i < count( $field->options ); $i++ )
		{
		    $option		= $field->options[ $i ];
		    
// 		    if(($field->required == 1) && ($elementSelected == $elementCnt) && ($cnt == 0)){
// 		       $selected	= ' checked="checked"'; //default checked for the 1st item.
// 		    } else {
// 		       $selected	= ( $option == $field->value ) ? ' checked="checked"' : '';
// 		    }		    
// 		    $cnt++;
			
			$selected	= ( $option == $field->value ) ? ' checked="checked"' : '';		    		    
			
			$html 	.= '<label class="lblradio-block">';
			$html	.= '<input type="radio" name="field' . $field->id . '" value="' . $option . '"' . $selected . '  class="radio '.$class.'" style="margin: 0 5px 0 0;" />';			
			$html	.= JText::_( $option ) . '</label>';
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
}