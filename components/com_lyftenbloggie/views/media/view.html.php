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

jimport( 'joomla.application.component.view');

/**
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.0
 */
class LyftenBloggieViewMedia extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		
		//initialize variables
		$document 	= & JFactory::getDocument();
		$vLayout 	= JRequest::getCmd( 'layout', 'default' );

		//Determine where to go
		if($vLayout == 'thumbs')
		{
			$this->displayThumbs();
			$tpl = 'thumbs';
		}else{

			//initialize variables
			$type 		= JRequest::getVar( 'type', 'image');
			$msg 		= urldecode(JRequest::getVar( 'msg'));
			$uri     	= & JFactory::getURI();

			// validate type
			$types = array('image', 'flash');
			if (!in_array($type, $types)) $type = 'image';

			$document->setTitle(JText::_('INSERT '.$type));

			$this->assign('type', 	$type);
			$this->assign('action', $uri->toString());
			$this->assign('msg', 	$msg);
		}
		
		parent::display($tpl);
	}
	
	function displayThumbs()
	{
		global $mainframe;

		// Do not allow cache
		JResponse::allowCache(false);

		//initialize variables
		$ckednum 	= JRequest::getVar( 'CKEditorFuncNum', 1);
		$type 		= JRequest::getVar( 'type', 'image');

		$this->assign('baseURL', 		COM_MEDIA_BASEURL);
		$this->assignRef('data', 		$this->get($type));
	}
}
