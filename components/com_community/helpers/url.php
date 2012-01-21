<?php
/**
 * @category	Helper
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CUrl {
	
	static public function build($view , $task = '' , $keys = null , $route = true )
	{
		// View cannot be empty. Assertion must be included here.
		CError::assert( $view , '' , '!empty' , __FILE__ , __LINE__ );

		$url	= 'index.php?option=com_community&view=' . $view;
		
		// Task might be optional
		$url	.= ( !empty($task) ) ? '&task=' . $task : '';
		
		if( !is_null( $keys ) && is_array( $keys ) )
		{
			foreach( $keys as $key => $value )
			{
				$url	.= '&' . $key . '=' . $value;
			}
		}
		
		// Test if it needs JRoute
		if( $route )
		{
			return CRoute::_( $url );
		}
		
		return $url;
	}
	
	function test()
	{
		return 'CUrl::test()';
	}
}

class CUrlHelper
{
	/**
	 * Create a link to a user profile
	 * 
	 * @param	id		integer		ther user id
	 * @param	route   bool		do we want to wrap it with Jroute func ?
	 */ 
	static public function userLink( $id , $route = true )
	{
		$url	= 'index.php?option=com_community&view=profile&userid='.$id;
		if( $route ) $url = CRoute::_( $url );
		return $url;
	}

	/**
	 * Create a link to a group page
	 * 
	 * @param	id		integer		ther user id
	 * @param	route   bool		do we want to wrap it with Jroute func ?
	 */
	static public function groupLink( $id , $route = true )
	{
		$url = 'index.php?option=com_community&view=groups&task=viewgroup&groupid='.$id;
		if($route) $url = CRoute::_($url);  
		return $url;
	}

	/**
	 * Create a link to a event page
	 * 
	 * @param	id		integer		ther user id
	 * @param	route   bool		do we want to wrap it with Jroute func ?
	 */ 
	static public function eventLink( $id , $route = true )
	{
		$url = 'index.php?option=com_community&view=events&task=viewevent&eventid='.$id;
		if($route) $url = CRoute::_($url);  
		return $url;
	}
	
	/** Method to get avatar's uri
	 *
	 */
	static public function avatarURI($avatar='', $defaultFileName='')
	{
		$config		=& CFactory::getConfig();
		$stripCdn	= true;
		
		if (empty($avatar) || !JFile::exists(JPATH_ROOT.DS.$avatar))
		{
			$avatar	= 'components/com_community/assets/'.$defaultFileName;
			$stripCdn = false;
		}
		
		$imgBaseUrl	= $config->get('imagebaseurl');
		$baseUrl	= ($imgBaseUrl && $stripCdn) ? $imgBaseUrl : JURI::root();
		
		
		// Strip cdn path if necessary
		$path = $avatar;		
		
		$cdnpath = $stripCdn ? $config->get('imagecdnpath') : '';
		if($cdnpath)
		{
			$path = str_replace($cdnpath, '',$path);
		}
		
		$path	= ltrim( $path , '/' );
		$baseUrl = rtrim( $baseUrl, '/' ) . '/' . $path;
		$baseUrl = str_replace("\\", "/", $baseUrl);
		
		
		return $baseUrl;
		
	}
		 	
}

/**
 * Deprecated since 1.8
 * Use CUrlHelper::userLink instead. 
 */
function cUserLink($id, $route = true)
{
	return CUrlHelper::userLink( $id , $route );
}

/**
 * Deprecated since 1.8
 * Use CUrlHelper::groupLink instead. 
 */
function cGroupLink($id, $route = true)
{
	return CUrlHelper::groupLink( $id , $route );
}


/**
 * Deprecated since 1.8
 * Use CUrlHelper::groupLink instead. 
 */
function cEventLink($id, $route = true)
{
	return CUrlHelper::eventLink( $id , $route );
}
