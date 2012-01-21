<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

class CommunityModelConfiguration extends JModel
{
	/**
	 * Configuration data
	 * 
	 * @var object
	 **/	 	 	 
	var $_params;

	/**
	 * Configuration for ini path
	 * 
	 * @var string
	 **/	 	 	 
// 	var $_ini	= '';

	/**
	 * Configuration for xml path
	 * 
	 * @var string
	 **/	 	 	 
	var $_xml	= '';
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		$mainframe	=& JFactory::getApplication();

		// Test if ini path is set
// 		if( empty( $this->_ini ) )
// 		{
// 			$this->_ini	= JPATH_COMPONENT . DS . 'config.ini';
// 		}

		// Test if ini path is set
		if( empty( $this->_xml ) )
		{
			$this->_xml	= JPATH_COMPONENT . DS . 'config.xml';
		}
		
		// Call the parents constructor
		parent::__construct();

		// Get the pagination request variables
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( 'com_community.limitstart', 'limitstart', 0, 'int' );

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}
	
	/**
	 * Returns the configuration object
	 *
	 * @return object	JParameter object
	 **/	 
	function getParams()
	{
		// Test if the config is already loaded.
		if( !$this->_params )
		{
			jimport( 'joomla.filesystem.file');
			$ini	= JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_community' . DS . 'default.ini';
			$data	= JFile::read($ini);
			
			// Load default configuration
			$this->_params	= new JParameter( $data );

			$config		=& JTable::getInstance( 'configuration' , 'CommunityTable' );
			$config->load( 'config' );
			
			// Bind the user saved configuration.
			$this->_params->bind( $config->params );
		}
		return $this->_params;
	}
	
	/**
	 * Save the configuration to the config file
	 * 
	 * @return boolean	True on success false on failure.
	 **/
	function save()
	{
		jimport('joomla.filesystem.file');

		$config	=& JTable::getInstance( 'configuration' , 'CommunityTable' );
		$config->load( 'config' );
		
		$registry	=& JRegistry::getInstance( 'community' );
		$registry->loadINI( $config->params , 'community' );

		$postData	= JRequest::get( 'post' , 2 );
		
		$token		= JUtility::getToken();
		unset($postData[$token]);

		foreach( $postData as $key => $value )
		{
			
			if( $key != 'task' && $key != 'option' && $key != 'view' && $key != $token )
			{
				$registry->setValue( 'community.' . $key , $value );
			}
				
		}

		// Get the complete INI string
		$config->params	= $registry->toString( 'INI' , 'community' );
		
		// Save it
		if(!$config->store() )
		{
			return false;
		}
		return true;
	}
}