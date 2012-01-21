<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'apps.php' );
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'profile.php' );

/**
 * Configuration view for Jom Social
 */
class CommunityViewUsers extends JView
{
	/**
	 * The default method that will display the output of this view which is called by
	 * Joomla
	 * 
	 * @param	string template	Template file name
	 **/	 	
	function display( $tpl = null )
	{
		if( $this->getLayout() == 'edit' )
		{
			$this->_displayEditLayout( $tpl );
			return;
		}

		// Set the titlebar text
		JToolBarHelper::title( JText::_('CC USERS'), 'users' );

		// Add the necessary buttons
		JToolBarHelper::back('Home' , 'index.php?option=com_community');
		JToolBarHelper::divider();
		JToolBarHelper::trash('delete', JText::_('CC DELETE'));
		
		$model		=& $this->getModel( 'Users' );
		
		$users		=& $model->getAllUsers();
		$pagination	=& $model->getPagination();
		$mainframe	=& JFactory::getApplication();
		$filter_order		= $mainframe->getUserStateFromRequest( "com_community.users.filter_order",		'filter_order',		'a.name',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( "com_community.users.filter_order_Dir",	'filter_order_Dir',	'',			'word' );

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		
		// Load tooltips
		JHTML::_('behavior.tooltip', '.hasTip');
		
		$usertype			= JRequest::getVar('usertype' , 'all' , 'POST' );
		
		$this->assignRef( 'usertype'	, $usertype );
		$this->assignRef( 'users' 		, $users );
		$this->assignRef( 'lists' 		, $lists );
		$this->assignRef( 'pagination'	, $pagination );
		
		
		parent::display( $tpl );
	}

	function _displayEditLayout( $tpl )
	{
		// Load frontend language file.
		$lang	=& JFactory::getLanguage();
		$lang->load('com_community' , JPATH_ROOT );
		
		$userId		= JRequest::getVar( 'id' , '' , 'REQUEST' );
		$user		= CFactory::getUser( $userId );
		
		// Set the titlebar text
		JToolBarHelper::title( $user->username , 'users' );
		
 		// Add the necessary buttons
 		JToolBarHelper::back('Back' , 'index.php?option=com_community&view=users');
 		JToolBarHelper::divider();
 		JToolBarHelper::cancel('removeavatar',JText::_('CC REMOVE AVATAR') );
		JToolBarHelper::save();
 		
		
		$model		=& $this->getModel( 'Users' );
		$profile	= $model->getEditableProfile( $user->id );

		$config		=& CFactory::getConfig();
		
		$params		= $user->getParams();
		$userDST	= $params->get('daylightsavingoffset' );
		$offset		= (!empty($userDST) ) ? $userDST : $config->get( 'daylightsavingoffset' );
		
		$counter	= -12;
		$options	= array();
		for( $i=0 ; $i <= 24; $i++ , $counter++ )
		{
			$options[]	= JHTML::_( 'select.option' , $counter , $counter );
		}
		$offsetList	= JHTML::_(	'select.genericlist',  $options , 'daylightsavingoffset', 'class="inputbox" size="1"', 'value', 'text', $offset );	


		$user->profile	=& $profile;
 		$this->assignRef( 'user' , $user );
 		$this->assignRef( 'params' , $user->getParameters(true) );
 		$this->assignRef( 'offsetList'	, $offsetList );
 		
 		parent::display( $tpl );
	}

	/**
	 * Method to get the publish status HTML
	 *
	 * @param	object	Field object
	 * @param	string	Type of the field
	 * @param	string	The ajax task that it should call
	 * @return	string	HTML source
	 **/
	function getPublish( &$row , $type , $ajaxTask )
	{

		$imgY	= 'tick.png';
		$imgX	= 'publish_x.png';

		$image	= $row->$type ? $imgX : $imgY;

		$alt	= $row->$type ? JText::_('CC PUBLISHED') : JText::_('CC UNPUBLISHED');

		$href = '<a href="javascript:void(0);" onclick="azcommunity.togglePublish(\'' . $ajaxTask . '\',\'' . $row->id . '\',\'' . $type . '\');">';
		$href  .= '<span><img src="images/' . $image . '" border="0" alt="' . $alt . '" /></span></a>';

		return $href;
	}
	
	function getConnectType( $userId )
	{
		$model	=& $this->getModel( 'Users' );
		
		$type	= $model->getUserConnectType( $userId );

		$image	= '';
		switch( $type )
		{
			case 'facebook':
				$image	= '<img src="' . rtrim( JURI::root() , '/' ) . '/administrator/components/com_community/assets/icons/facebook.gif" />';
				break;
			case 'joomla':
			default:
				$image	= '<img src="' . rtrim( JURI::root() , '/' ) . '/administrator/components/com_community/assets/icons/joomla.gif" />';
				break;
		}
		return $image;
	}
	
	/**
	 * Private method to set the toolbar for this view
	 * 
	 * @access private
	 * 
	 * @return null
	 **/	 	 
	function setToolBar()
	{

	}
}