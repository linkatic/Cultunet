<?php
/**
 * @category	Helper
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CUserHelper
{
	static public function getUserId( $username )
	{
		$db		=& JFactory::getDBO();
		$query	= 'SELECT ' . $db->nameQuote( 'id' ) . ' '
				. 'FROM ' . $db->nameQuote( '#__users' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'username' ) . '=' . $db->Quote( $username );
	
		$db->setQuery( $query );
		
		$id		= $db->loadResult();
	
		return $id;
	}

	static function getThumb( $userId , $imageClass = '' , $anchorClass = '' )
	{
		CFactory::load( 'helpers' , 'string' );
		$user	= CFactory::getUser( $userId );
		
		$imageClass		= (!empty( $imageClass ) ) ? ' class="' . $imageClass . '"' : '';
		$anchorClass	= ( !empty( $anchorClass ) ) ? ' class="' . $anchorClass . '"' : '';
		
		$data	= '<a href="' . CRoute::_('index.php?option=com_community&view=profile&userid=' . $user->id ) . '"' . $anchorClass . '>';
		$data	.= '<img src="{user:thumbnail:' . $userId . '}" alt="' . CStringHelper::escape( $user->getDisplayName() ) . '"' . $imageClass . ' />';
		$data	.= '</a>';
		
		return $data;
	}

	/**
	 * Get the html code to be added to the page
	 * 
	 * return	$html	String
	 */	 	
	static public function getBlockUserHTML( $userId, $isBlocked )
	{
		$my    = CFactory::getUser();
		$html = '';
		
		if(!empty($my->id)) {
		
		    $tmpl  = new Ctemplate();
	  
		    $tmpl->set( 'userId'   , $userId );
		    $tmpl->set( 'isBlocked', $isBlocked);
		    $html = $tmpl->fetch( 'block.user' );
		    
	  	}
	  	
		return $html;
	}
}

/**
 * Deprecated since 1.8
 * Use CUserHelper::getUserId instead. 
 */
function cGetUserId( $username )
{
	return CUserHelper::getUserId( $username );
}

/**
 * Deprecated since 1.8
 * Use CUserHelper::getThumb instead. 
 */
function cGetUserThumb( $userId , $imageClass = '' , $anchorClass = '' )
{
	return CUserHelper::getThumb( $userId , $imageClass , $anchorClass );
}

/**
 * Deprecated since 1.8
 * Use CValidateHelper::username instead. 
 */
function cValidUsername( $username )
{
	CFactory::load( 'helpers' , 'validate' );
	
	return CValidateHelper::username( $username );
}

function getBlockUserHTML( $userId, $isBlocked )
{
	return CUserHelper::getBlockUserHTML( $userId , $isBlocked );
}