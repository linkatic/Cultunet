<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CFieldsUrl
{
	/**
	 * Method to format the specified value for text type
	 **/	 	
	function getFieldData( $value )
	{
		if( empty( $value ) )
			return $value;
		
		return '<a href="' . $value . '" target="_blank">' . $value . '</a>';
	}
	
	function getFieldHTML( $field , $required )
	{
		// If maximum is not set, we define it to a default
		$field->max	= empty( $field->max ) ? 200 : $field->max;

		$class	= ($field->required == 1) ? ' required' : '';
		$scheme	= '';
		$host	= '';

		if(! empty($field->value))
		{
			$url	= parse_url($field->value);
			
			$scheme	= isset( $url[ 'scheme' ] ) ? $url['scheme'] : 'http://';
			$host	= isset( $url[ 'host' ] ) ? $url['host'] : '';
			$path	= isset( $url[ 'path'] ) ? $url['path'] : '';
			$query	= isset( $url[ 'query'] ) ? '?' . $url['query'] : '';
			$fragment = isset( $url['fragment'] ) ? '#' . $url['fragment'] : '' ;
			$field->value	= $host . $path . $query . $fragment;
		}
		CFactory::load( 'helpers' , 'string' );
		ob_start();
?>
	<select name="field<?php echo $field->id;?>[]">
		<option value="http://"<?php echo ($scheme == 'http') ? ' selected="selected"' : '';?>><?php echo JText::_('http://');?></option>
		<option value="https://"<?php echo ($scheme == 'https') ? ' selected="selected"' : '';?>><?php echo JText::_('https://');?></option>
	</select>
	<input title="<?php echo $field->name . '::'. CStringHelper::escape( $field->tips );?>" type="text" value="<?php echo $field->value;?>" id="field<?php echo $field->id;?>" name="field<?php echo $field->id;?>[]" maxlength="<?php echo $field->max;?>" size="40" class="jomTips tipRight inputbox validate-profile-url<?php echo $class;?>" />
	<span id="errfield<?php echo $field->id;?>msg" style="display:none;">&nbsp;</span>
<?php
		$html	= ob_get_contents();
		ob_end_clean();

		return $html;
	}
	
	function isValid( $value , $required )
	{
		CFactory::load( 'helpers' , 'validate' );
		
		$isValid	= CValidateHelper::url( $value );
		
		$url		= parse_url( $value );
		$host		= isset($url['host']) ? $url['host'] : '';

		if( !$isValid && $required )
			return false;
		else if( !empty($host) && !$isValid )
			return false; 

		return true;
	}
	
	function formatdata( $value )
	{
		if( empty( $value[0] ) || empty( $value[1] ) )
		{
			$value = '';
		}
		else
		{

			$scheme	= $value[ 0 ];
			$url	= $value[ 1 ];
			$value	= $scheme . $url;			
		}
		return $value;
	}
}