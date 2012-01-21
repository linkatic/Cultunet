<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');
jimport ( 'joomla.application.component.view' );

class CommunityViewConnect extends CommunityView
{
	function receiver()
	{
		$tmpl	= new CTemplate();
		
		echo $tmpl->fetch( 'connect.receiver' );
	}
	
	function update()
	{
		$config		=& CFactory::getConfig();
		CFactory::load( 'libraries' , 'facebook' );
		jimport('joomla.user.helper');
		
		// Once they reach here, we assume that they are already logged into facebook.
		// Since CFacebook library handles the security we don't need to worry about any intercepts here.
		$facebook		= new CFacebook();
		$connectModel	=& CFactory::getModel( 'Connect' );
		$connectTable	=& JTable::getInstance( 'Connect' , 'CTable' );
		$mainframe		=& JFactory::getApplication();
		$config			=& CFactory::getConfig();
		
		$connectId		= $facebook->getUserId();
		$connectTable->load( $connectId );
		
		$tmpl		= new CTemplate();

		CFactory::load( 'libraries' , 'facebook' );
		$facebook		= new CFacebook();
		$fields			= array( 'first_name' , 'last_name' , 'birthday' , 'current_location' , 'status' , 'pic' , 'sex' , 'name' , 'pic_square' , 'profile_url' , 'pic_big' , 'current_location');
		$user			= $facebook->getUserInfo( $fields );

		$tmpl->set( 'user'	, $user );
		
		echo $tmpl->fetch( 'facebook.update' );
	}

	function invite()
	{
		CFactory::load( 'libraries' , 'facebook' );
		$facebook	= new CFacebook();
		$config		=& CFactory::getConfig();
		$tmpl		= new CTemplate();
		$tmpl->set( 'facebook' , $facebook );
		$tmpl->set( 'config' , $config );

		echo $tmpl->fetch( 'facebook.invite' );
	}
}
