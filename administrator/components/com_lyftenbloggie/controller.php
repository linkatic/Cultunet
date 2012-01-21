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

jimport('joomla.application.component.controller');

/**
 * @package Joomla
 * @subpackage JomProducts
 * @since 1.0
 */
class LyftenBloggieController extends JController
{
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Display the view
	 */
	function display()
	{
		$viewName	= JRequest::getCmd( 'view' , 'lyftenbloggie' );

		// validate controller
		$views = array('about', 'profiles', 'bookmark', 'bookmarks', 'categories', 'category', 'comment', 'comments', 'entries', 'entry', 'lyftenbloggie', 'settings', 'tag', 'tags', 'themes', 'update', 'userselement', 'groups', 'plugins', 'addons', 'email');
		if (!in_array($viewName, $views)) $viewName = 'lyftenbloggie';

		// Set the default layout and view name
		$layout		= JRequest::getCmd( 'layout' , 'default' );

		// Get the document object
		$document	=& JFactory::getDocument();

		// Get the view type
		$viewType	= $document->getType();
		
		// Get the view
		$view		=& $this->getView( $viewName , $viewType );
		$model		=& $this->getModel( $viewName );

		if( $model )
		{
			$view->setModel( $model , $viewName );
		}

		// Set the layout
		$view->setLayout( $layout );

		// Display the view
		$view->display();
	}
	
}
?>