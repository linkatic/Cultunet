<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CFieldsCountry
{
	/**
	 * Method to format the specified value for text type
	 **/	 	
	function getFieldData( $value )
	{
		if( empty( $value ) )
			return $value;
		
		return $value;
	}
	
	function getFieldHTML( $field , $required )
	{
		// If maximum is not set, we define it to a default
		$field->max	= empty( $field->max ) ? 200 : $field->max;

		$class	= ($field->required == 1) ? ' required' : '';
		
		jimport( 'joomla.filesystem.file' );
		$file	= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'fields' . DS . 'countries.xml';
		
		if( JFile::exists( $file ) )
		{
			$contents	= JFile::read( $file );
			$parser		=& JFactory::getXMLParser('Simple');
			$parser->loadFile( $file );
			$document	=& $parser->document;
	
			$element		=& $document->getElementByPath( 'countries' );
			$countries		= $element->children();

			CFactory::load( 'helpers' , 'string' );
			ob_start();
?>
			<select id="field<?php echo $field->id;?>" name="field<?php echo $field->id;?>" class="jomTips tipRight select validate-country<?php echo $class;?> inputbox" title="<?php echo $field->name;?>::<?php echo CStringHelper::escape( $field->tips );?>">
				<option value=""<?php echo empty($field->value) ? ' selected="selected"' : '';?>><?php echo JText::_('CC SELECT A COUNTRY');?></option>
			<?php
			foreach($countries as $country )
			{
				$name	= $country->getElementByPath('name')->data();
				
			?>
				<option value="<?php echo $name;?>"<?php echo ($field->value == $name) ? ' selected="selected"' : '';?>><?php echo JText::_($name);?></option>
			<?php			
			}
			?>
			</select>
			<span id="errfield<?php echo $field->id;?>msg" style="display:none;">&nbsp;</span>
<?php
			$html	= ob_get_contents();
			ob_end_clean();
		}
		else
		{
			$html	= JText::_('Countries list not found');
		}

		return $html;
	}
	
	function isValid( $value , $required )
	{
		if( $value === 'selectcountry' && $required )
			return false;
			
		return true;
	}

}