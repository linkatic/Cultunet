<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CFieldsEmail
{
	/**
	 * Method to format the specified value for text type
	 **/	 	
	function getFieldData( $value )
	{
		if( empty( $value ) )
			return $value;
		
		CFactory::load( 'helpers' , 'linkgenerator' );
		
		return CLinkGeneratorHelper::getEmailURL($value);
	}
	
	function getFieldHTML( $field , $required )
	{
		// If maximum is not set, we define it to a default
		$field->max	= empty( $field->max ) ? 200 : $field->max;

		$class	= ($field->required == 1) ? ' required' : '';
		CFactory::load( 'helpers' , 'string' );
		ob_start();
?>
	<input title="<?php echo $field->name . '::'. CStringHelper::escape( $field->tips );?>" type="text" value="<?php echo $field->value;?>" id="field<?php echo $field->id;?>" name="field<?php echo $field->id;?>" maxlength="<?php echo $field->max;?>" size="40" class="jomTips tipRight inputbox validate-profile-email<?php echo $class;?>" />
	<span id="errfield<?php echo $field->id;?>msg" style="display:none;">&nbsp;</span>
<?php
		$html	= ob_get_contents();
		ob_end_clean();

		return $html;
	}
	
	function isValid( $value , $required )
	{
		CFactory::load( 'helpers' , 'validate' );
		
		$isValid	= CValidateHelper::email( $value );

		if( !empty($value) && $isValid )
		{
			return true;
		}
		else if( empty($value) && !$required )
		{
			return true;
		}

		return false; 
	}
}