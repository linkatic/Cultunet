<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.utilities.date');

class CFieldsDate
{
	/**
	 * Method to format the specified value for text type
	 **/	 	
	function getFieldData( $value )
	{
		if( empty( $value ) )
			return $value;
		
		if(! class_exists('CFactory'))
		{
			require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
		}
		require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'models' . DS . 'profile.php' );
				
		$model	=& CFactory::getModel( 'profile' );				
		$myDate = $model->formatDate($value); 
		
		return $myDate;
	}
	
	function getFieldHTML( $field , $required )
	{		
		$html	= '';
				
		$day	= '';
		$month	= 0;
		$year	= '';		

		if(! empty($field->value))
		{
		    if(! is_array($field->value))
		    {
				$myDateArr	= explode(' ', $field->value);
			}
			else
			{
			    $myDateArr[0]  = $field->value[2] . '-' . $field->value[1] . '-' . $field->value[0];
			}
			
			if(is_array($myDateArr) && count($myDateArr) > 0)
			{
				$myDate	= explode('-', $myDateArr[0]);
				
				$day	= !empty($myDate[2]) ? $myDate[2] : '';
				$month	= !empty($myDate[1]) ? $myDate[1] : 0;
				$year	= !empty($myDate[0]) ? $myDate[0] : '';								
			}
		}		
				
		$months	= Array(
						JText::_('January'),
						JText::_('February'),
						JText::_('March'),
						JText::_('April'),
						JText::_('May'),
						JText::_('June'),
						JText::_('July'),
						JText::_('August'),
						JText::_('September'),
						JText::_('October'),
						JText::_('November'),
						JText::_('December')
						);

        $class	= ($field->required == 1) ? ' required' : '';
        CFactory::load( 'helpers' , 'string' );
		$html .= '<div class="jomTips tipRight" style="display: inline-block;" title="' . $field->name . '::' . CStringHelper::escape( $field->tips ). '">';
		$html .= '<input type="textbox" size="3" maxlength="2" name="field' . $field->id . '[]" value="' . $day . '" class="inputbox validate-custom-date' . $class . '" /> ' . JText::_('CC DAY FORMAT');
		$html .= '&nbsp;/&nbsp;<select name="field' . $field->id . '[]" class="select validate-custom-date' . $class . '">';

		$defaultSelected	= '';
		
		//@rule: If there is no value, we need to default to a default value
		if( $month == 0 )
		{
			$defaultSelected	.= ' selected="selected"';
		}
		$html	.= '<option value=""' . $defaultSelected . '>' . JText::_('CC SELECT BELOW') . '</option>';

		for( $i = 0; $i < count($months); $i++)
		{
			if(($i + 1)== $month)
			{
				$html .= '<option value="' . ($i + 1) . '" selected="selected">' . $months[$i] . '</option>';
			}
			else
			{
				$html .= '<option value="' . ($i + 1) . '">' . $months[$i] . '</option>';
			}
		}
		$html .= '</select>&nbsp;/&nbsp;';
		$html .= '<input type="textbox" size="5" maxlength="4" name="field' . $field->id . '[]" value="' . $year . '" class="inputbox validate-custom-date' . $class . '" /> ' . JText::_('CC YEAR FORMAT');
		$html .= '<span id="errfield'.$field->id.'msg" style="display:none;">&nbsp;</span>';
		$html .= '</div>';
		
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
		
		if(is_array($value))
		{
			if( empty( $value[0] ) || empty( $value[1] ) || empty( $value[2] ) )
			{
				$finalvalue = '';
			}
			else
			{
				$day	= intval($value[0]);
				$month	= intval($value[1]);
				$year	= intval($value[2]);
				
				$day 	= !empty($day) 		? $day 		: 1;
				$month 	= !empty($month) 	? $month 	: 1;
				$year 	= !empty($year) 	? $year 	: 1970;
				
				$finalvalue	= $year . '-' . $month . '-' . $day . ' 23:59:59';
			}
		}
			
		return $finalvalue;	
	}
}