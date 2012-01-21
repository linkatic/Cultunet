<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CFieldsList
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
		
		array_walk($fieldArray, array('CFieldsList', '_translateValue'));		
		
		$fieldValue = implode('<br />', $fieldArray);				
		return $fieldValue;
	}
	
	function getFieldHTML( $field , $required )
	{
		$class	= ($field->required == 1) ? ' required' : '';
		
		$lists	 = explode(',', $field->value);
		CFactory::load( 'helpers' , 'string' );
		$html	= '<select id="field'.$field->id.'" name="field' . $field->id . '[]" type="select-multiple" multiple="multiple" class="jomTips tipRight select'.$class.'" title="' . $field->name . '::' . CStringHelper::escape( $field->tips ) . '">';

		$elementSelected	= 0;
		
		foreach( $field->options as $option )
		{
			$selected	= in_array( $option, $lists ) ? ' selected="selected"' : '';
			
			if( empty($selected) )
			{
				$elementSelected++;
			}
			$html	.= '<option value="' . $option . '"' . $selected . '>' . JText::_( $option ) . '</option>'; 
		}

		if($elementSelected == 0)
		{
			//if nothing is selected, we default the 1st option to be selected.
			$elementName = 'field'.$field->id;
			$html .=<<< HTML
				   
				   <script language='javascript'>
					   var slt = document.getElementById('$elementName');
					   if(slt != null){
					      slt.options[0].selected = true;					       
					   }
				   </script>
HTML;
		}
		$html	.= '</select>';
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