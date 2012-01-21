<?php
/**
 * @category	Module
 * @package		JomSocial
 * @subpackage	OnlineUsers
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'helpers' . DS . 'string.php' );

class modOnlineUsersHelper
{
	function getUsersData( &$params )
	{
		$model	=& CFactory::getModel( 'user' );
		$limit	= $params->get('count', '5');
		
		$rows = $model->getOnlineUsers( $limit );
		$_members = array();

		if ( !empty( $rows ) ) 
		{
			// preload users
			$CFactoryMethod = get_class_methods('CFactory');					
			if(in_array('loadUsers', $CFactoryMethod))
			{
				$uids = array();
				foreach($rows as $m)
				{
					$uids[] = $m->id;
				}
				CFactory::loadUsers($uids);
			}
		
			foreach ( $rows as $data )
			{
				$user = CFactory::getUser( $data->id );
	
				$_obj				= new stdClass();
			    $_obj->id    		= $data->id;
                $_obj->name      	= CStringHelper::escape( $user->getDisplayName() );
                $_obj->avatar      	= $user->getThumbAvatar();
				$_obj->usertype		= $user->usertype;
				$_obj->link    		= CRoute::_( 'index.php?option=com_community&view=profile&userid=' . $data->id );
				$_members[]	= $_obj;
			}
		}
		return $_members;	
	}	
	
	function getTotalOnline() 
	{
	    $db		=& JFactory::getDBO();
		
		$total = new stdClass();
				
		$query 	= ' SELECT ' . $db->nameQuote('session_id') . ', ' . $db->nameQuote('username') .', ' . $db->nameQuote('userid')
				. ' FROM ' . $db->nameQuote('#__session') 
				. ' WHERE ' . $db->nameQuote('client_id') . ' = 0 AND ' . $db->nameQuote('guest') . ' = 0 GROUP BY ' . $db->nameQuote('userid');
		$db->setQuery($query);
		$row = $db->loadAssocList();
			
		$total->users = count($row);
		
		if($db->getErrorNum())
		{
			JError::raiseWarning( 500, $db->stderr() );
		}
		
		$query 	= ' SELECT count(' . $db->nameQuote('session_id') . ') AS guests '
				. ' FROM ' . $db->nameQuote('#__session') 
				. ' WHERE ' . $db->nameQuote('client_id') . ' = 0 AND ' . $db->nameQuote('guest') . ' = 1';
		$db->setQuery($query);
		$total->guests = $db->loadResult();
		
		return $total;
	}
	
}