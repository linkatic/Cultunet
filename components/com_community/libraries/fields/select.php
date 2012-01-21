<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CFieldsSelect
{
	function getFieldHTML( $field , $required, $isDropDown = true)
	{
		$class		= ($field->required == 1) ? ' required' : '';		
		$optionSize	= 1; // the default 'select below'
		
		if( !empty( $field->options ) )
		{
			$optionSize	+= count($field->options);
		}
		
		$dropDown	= ($isDropDown) ? '' : ' size="'.$optionSize.'"';
		CFactory::load( 'helpers' , 'string' );
		$html		= '<select id="field'.$field->id.'" name="field' . $field->id . '"' . $dropDown . ' class="jomTips tipRight select'.$class.'" title="' . $field->name . '::' . CStringHelper::escape( $field->tips ). '">';
		
		$defaultSelected	= '';
		
		//@rule: If there is no value, we need to default to a default value
		if(empty( $field->value ) )
		{
			$defaultSelected	.= ' selected="selected"';
		}
		
		if($isDropDown)
		{
			$html	.= '<option value="" ' . $defaultSelected . '>' . JText::_('CC SELECT BELOW') . '</option>';
		}	
		
		if( !empty( $field->options ) )
		{
			$selectedElement	= 0;
			
			foreach( $field->options as $option )
			{
				$selected	= ( $option == $field->value ) ? ' selected="selected"' : '';
				
				if( !empty( $selected ) )
				{
					$selectedElement++;
				}
				
				$html	.= '<option value="' . $option . '"' . $selected . '>' . JText::_( $option ) . '</option>';
			}
			
			if($selectedElement == 0)
			{
				//if nothing is selected, we default the 1st option to be selected.
				$eleName	= 'field'.$field->id;
				$js			=<<< HTML
					   <script type='text/javascript'>
						   var slt = document.getElementById('$eleName');
						   if(slt != null)
						   {
						       slt.options[0].selected = true;
						   }
					   </script>
HTML;
			}
		}
		$html	.= '</select>';
		$html   .= '<span id="errfield'.$field->id.'msg" style="display:none;">&nbsp;</span>';
		
		return $html;
	}
}