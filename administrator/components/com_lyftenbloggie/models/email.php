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
 * @since 1.1.0
 */
class LyftenBloggieModelEmail extends JModel
{
	var $_id;
	var $_data;
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		
		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);		
	}
	
	/**
	 * Method to set the identifier
	 **/
	function setId($id)
	{
		// Set id and wipe data
		$this->_id	    = $id;
		$this->_data	= null;
	}
	
	/**
	 * Overridden get method to get properties
	 **/
	function get($property, $default=null)
	{
		if ($this->_data) {
			if(isset($this->_data->$property)) {
				return $this->_data->$property;
			}
		}
		return $default;
	}

	/**
	 * Method to get data
	 **/
	function getEmails()
	{
		$files = JFolder::files( BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'emails', '.tpl');
		
		$i = 0;
		foreach($files as $file)
		{
			$this->_data[$i]['file'] = $file;
			$this->_data[$i]['date'] = date("F d Y H:i:s", filemtime(BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'emails'.DS.$file));
			$i++;
		}

		return $this->_data;
	}
}