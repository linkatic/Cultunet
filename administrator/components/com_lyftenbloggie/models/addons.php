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
 * @since 1.0.2
 */
class LyftenBloggieModelAddons extends JModel
{
	var $_data;
	var $_pagination;

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		global $mainframe, $option;

		$limit		= $mainframe->getUserStateFromRequest('com_lyftenbloggie.addons.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest('com_lyftenbloggie.addons.limitstart', 'limitstart', 0, 'int' );

		$this->setState('limit', 		$limit);
		$this->setState('limitstart', 	$limitstart);
	}

	/**
	 * Method to get data
	 **/
	function getData()
	{
		// Lets load the files if it doesn't already exist
		if (empty($this->_data))
		{
			$args 			= array( 'page' => $this->getState('limitstart'), 'per_page'=> $this->getState('limit'), 'product'=>'2', 'version'=>BLOGGIE_COM_VERSION );
			$this->_data 	= BloggieAdmin::getRemoteData('plugins', $args);
		}

		return $this->_data;
	}

	/**
	 * Method to get a pagination object
	 **/
	function getPagination()
	{
		// Lets load the files if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			if(isset($this->_data['brezza'])) {
			$tatal = (isset($this->_data['brezza']['detail']['total'])) ? $this->_data['brezza']['detail']['total'] : count($this->_data['brezza']['plugins']);
			}else{
				$tatal = 0;
			}
			$this->_pagination = new JPagination( $tatal, $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Method to install an addon to the system
	 **/
	function install($addon)
	{
		$download_link 	= base64_decode($addon);
		$installer 		= new BloggieInstaller();
		if(!$result 	= $installer->download($download_link))
		{
			$this->setError($installer->getError());
			return false;
		}

		return true;
	}
}