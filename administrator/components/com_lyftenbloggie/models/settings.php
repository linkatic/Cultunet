<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

/**
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.0
 */
class LyftenBloggieModelSettings extends JModel
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Method to get Comment systems
	 **/
	function getAvatarSystems()
	{
		$options 	= array();

		$query = 'SELECT LOWER(name) as id, title'
			. ' FROM #__bloggies_plugins'
			. ' WHERE published = 1'
			. ' AND LOWER(type) = \'avatar\''
			;
		$this->_db->setQuery( $query );
		
		if (!$options = $this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
			return array();
		}

		return $options;
	}

	/**
	 * Method to get Comment systems
	 **/
	function getCommentSystems()
	{
		$options 	= array();
		$options[] 	= JHTML::_('select.option', '0', JText::_('DISABLED'), 'id', 'title');

		$query = 'SELECT LOWER(name) as id, title'
			. ' FROM #__bloggies_plugins'
			. ' WHERE published = 1'
			. ' AND LOWER(type) = \'comment\''
			;
		$this->_db->setQuery( $query );
		
		if (!$syetems = $this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
			return $options;
		}

		$options = array_merge($options, $syetems);
		return $options;
	}

	/**
	 * Method to get Comment systems
	 **/
	function getEditors()
	{
		$options 	= array();
		$options[] 	= JHTML::_('select.option', 'joomla', JText::_('JOOMLAS EDITOR'), 'id', 'title');
		$query = 'SELECT LOWER(name) as id, title'
			. ' FROM #__bloggies_plugins'
			. ' WHERE published = 1'
			. ' AND LOWER(type) = \'editor\''
			;
		$this->_db->setQuery( $query );
		
		if (!$syetems = $this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
			return $options;
		}

		$options = array_merge($options, $syetems);
		return $options;
	}

	/**
	 * Method to store the entry
	 **/
	function store($data)
	{
		global $mainframe;

		$settings	=& BloggieSettings::getInstance();

		// Get PostingControl and add it to the parameter variables in the request
		$PostingControl		= JRequest::getVar( 'PostingControl', null, 'post', 'array' );		

		if (is_array($PostingControl))
		{
			$data['settings']['PostingControl'] = implode(",", $PostingControl);
		}
		
		// Get PublishControl and add it to the parameter variables in the request
		$PublishControl		= JRequest::getVar( 'PublishControl', null, 'post', 'array' );		
		if (is_array($PublishControl))
		{
			$data['settings']['PublishControl'] = implode(",", $PublishControl);
		}

		//Captcha Check
		if(isset($data['settings']['enableCaptcha']) && $data['settings']['enableCaptcha'])
		{
			if(!function_exists('ImageCreate'))
			{
				$mainframe->enqueueMessage(JText::_('CAPTCHA ERROR'), 'error');
				$data['settings']['enableCaptcha'] = '0';
			}
		}

		//fix charset
		if(isset($data['settings']['charset']))
		{
			if ( in_array( $data['settings']['charset'], array( 'utf8', 'utf-8', 'UTF8' ) ) ) {
				$data['settings']['charset'] = 'UTF-8';
			}
		}

		// Save Settings
		$settings->save($data['settings']);

		//Captcha Akismet API Key
		if(isset($data['settings']['spamCheck']) && $data['settings']['spamCheck'])
		{
			$akismet = BloggieFactory::load('spam', 'libraries');
			if (!$akismet->verify())
			{
				$mainframe->enqueueMessage($akismet->getError(), 'error');
			}
		}

		return true;
	}
	
	/**
	 * Method to perform needed maintenance
	 **/
	function databaseOptimise()
	{
		global $mainframe;

		$msg = JText::_('DATABASE OPTIMISED');
				
		$this->_db->setQuery("SHOW TABLES FROM `" . $mainframe->getCfg('db') . "`" );
		$tables = $this->_db->loadResultArray();

		if(is_array($tables)) {
			$this->_db->setQuery("OPTIMIZE TABLE `" . implode("` , `",$tables) . "`;" );
			if (!$this->_db->query()) {
				$msg = $this->_db->getErrorMsg();
				$this->setError($this->_db->getErrorMsg());
			}
		}else{
			$msg = JText::_('UNABLE TO FIND TABLE TO OPTIMISED');
		}
	
		return $msg;
	}
}