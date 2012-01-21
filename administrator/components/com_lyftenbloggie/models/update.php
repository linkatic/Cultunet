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
class LyftenBloggieModelUpdate extends JModel
{

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Method to update the system
	 **/
	function update($update, $patch=null)
	{
		$download_link 	= base64_decode($update);

		$installer 		= new BloggieInstaller();
		if(!$result 	= $installer->download($download_link))
		{
			$this->setError($installer->getError());
			return false;
		}

		//Update patch IDs
		if($patch)
		{
			$settings = & BloggieSettings::getInstance();
			$patched = $settings->get('patches').','.$patch;
			$settings->update('patches', $patched);
		}

		return true;
	}

}