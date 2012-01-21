<?php
/**
 * @category	Helper
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CStringHelper
{
	/**
	 * Tests a bunch of text and see if it contains html tags.
	 * 
	 * @param	$text	String	A text value.
	 * @return	$text	Boolean	True if the text contains html tags and false otherwise.	 	 	 
	 **/	 		
	static public function isHTML( $text )
	{
		$pattern	= '/\<p\>|\<br\>|\<br \/\>|\<b\>|\<div\>/i';
		preg_match( $pattern , JString::strtolower($text) , $matches );

		return empty($matches ) ? false : true;
	}
	
	/**
	 * Automatically converts new line to html break tag.
	 * 
	 * @param	$text	String	A text value.
	 * @return	$text	String	A formatted data which contains html break tags.	 	 	 
	 **/	 	
	static public function nl2br( $text )
	{
		$text	= JString::str_ireplace(array("\r\n", "\r", "\n"), "<br />", $text );
		return preg_replace("/(<br\s*\/?>\s*){3,}/", "<br /><br />", $text);
	}
	
	static public function isPlural($num)
	{
		return !CStringHelper::isSingular($num);
	}
	
	static public function isSingular($num)
	{
		$config =& CFactory::getConfig();
		$singularnumbers = $config->get('singularnumber');
		$singularnumbers = explode(',', $singularnumbers);
		
		return in_array($num, $singularnumbers);
	}
	
	static public function escape($var, $function='htmlspecialchars')
	{
		if (in_array($function, array('htmlspecialchars', 'htmlentities')))
		{
			return call_user_func($function, $var, ENT_COMPAT, 'UTF-8');
		}
		return call_user_func($function, $var);
	}
	
	/**
	 * @deprecated
	 */	 	
	static public function clean($string)
	{
		jimport('joomla.filter.filterinput');
		$safeHtmlFilter =  JFilterInput::getInstance();
		return $safeHtmlFilter->clean($string);
		/*
		// Replace other special chars  
		$specialCharacters = array(
			'§' => '',
			'\\' => '',
			'/' => '',
			'’' => "'",
			'"' => '',
		);
		foreach( $specialCharacters as $character => $replacement )
		{
			$string	= JString::str_ireplace( $character , $replacement , $string );
		}
		*/
		return $string;
	}
	
	static public function replaceThumbnails( $data )
	{
		// Replace matches for {user:thumbnail:ID} so that this can be fixed even if the caching is enabled.
		$html	= preg_replace_callback('/\{user:thumbnail:(.*)\}/', array('CStringHelper','replaceThumbnail') , $data );
		
		return $html;
	}
	
	static public function replaceThumbnail(  $matches )
	{
		static	$data = array();
		
		if( !isset($data[$matches[1]]) )
		{
			$user	= CFactory::getUser( $matches[1] );
			$data[ $matches[1] ]	= $user->getThumbAvatar();
		}
		
		return $data[ $matches[1] ];
	}	
	
	static public function trim( $value , $length )
	{
		if( JString::strlen($value) > $length )
		{
			return JString::substr( $value , 0 , $length ) . '<span>...</span>';
		}
		return $value;
	}
}

/**
 * Deprecated since 1.8
 */
function cIsPlural($num)
{
	return !CStringHelper::isSingular( $num );
}

/**
 * Deprecated since 1.8
 */
function cIsSingular($num)
{
	return CStringHelper::isSingular( $num );
}

/**
 * Deprecated since 1.8
 */
function cEscape($var, $function='htmlspecialchars')
{
	return CStringHelper::escape( $var , $function );
}

/**
 * Deprecated since 1.8
 */
function cCleanString($string)
{
	return CStringHelper::clean( $string );
}

/**
 * Deprecated since 1.8
 */
function cReplaceThumbnails( $data )
{
	return CStringHelper::replaceThumbnails( $data );
}	

/**
 * Deprecated since 1.8
 */
function cTrimString( $value , $length )
{
	return CStringHelper::trim( $value , $length );
}