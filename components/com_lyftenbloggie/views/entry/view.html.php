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
class LyftenBloggieViewEntry extends JView
{
	/**
	 * Creates the Entry page
	 **/
	function display( $tpl = null )
	{
		global $mainframe;

		//initialize variables
		$document 	= & JFactory::getDocument();
		$user		= & JFactory::getUser();
		$dispatcher = & JDispatcher::getInstance();
		$params 	= & $mainframe->getParams('com_lyftenbloggie');
		$settings 	= & BloggieSettings::getInstance();

		//Assign Template Layout
		$template = BloggieTemplate::getInstance();
		$template->setTemplateFile('entry');
		$template->setStyles();

		// Get the menu item object
		$menus = &JSite::getMenu();
		$menu  = $menus->getActive();

		//Get Data data
		if (JRequest::getInt('year') && JRequest::getInt('month') && JRequest::getInt('day')) {
			$year 		= JRequest::getInt('year');
			$month 		= JRequest::getInt('month');
			$archive 	= JHTML::_('date', strtotime($year.'-'.$month), '%B %Y'); 
		}

		//Load route helper
		require_once (JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php');

		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');

		// load entry data
		$entry 	= BloggieFactory::getEntry();
		$author = BloggieFactory::getProfile($entry->created_by);

		//Assign Comment form
		if($entry->allowComments) {
			$uri =& JFactory::getURI();
			$this->assign('action', $uri->toString());
		}

		$iparams	=& $entry->attribs;
		$params->merge($iparams);
		
		if (($entry->id == 0))
		{
			$id = JRequest::getVar('id', 0, '', 'int');
			JError::raiseWarning( 'SOME_ERROR_CODE', JText::sprintf( 'ENTRY BLANK NOT FOUND', $id ));
			$mainframe->redirect( JRoute::_('index.php?option=com_lyftenbloggie', false) );
		}
		
		//pathway
		$pathway 	= & $mainframe->getPathWay();
		if(is_object($menu) && !isset($menu->query['category'])) {
			if(isset($archive)) {
				$pathway->addItem( $this->escape($archive), JRoute::_('index.php?option=com_lyftenbloggie&year='.$year.'&month='.$month.'&day=0'));
			}else{
				$pathway->addItem( $this->escape($entry->cattitle), JRoute::_('index.php?category='.$entry->catslug));
			}
		}
		$pathway->addItem( $this->escape($entry->title), JRoute::_('index.php?view=entry&id='.$entry->id));
		
		/*
		 * Handle the metadata
		 */
		if (is_object($menu)) {
			$menu_params = new JParameter( $menu->params );
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	$entry->title);
			}
		} else {
			$params->set('page_title',	$entry->title);
		}

		//Create the document
		$doc_title = $params->get( 'page_title' );
		
		$document->setTitle($doc_title);
		
		if ($entry->metadesc) {
			$document->setDescription( $entry->metadesc );
		}
		
		if ($entry->metakey) {
			$document->setMetadata('keywords', $entry->metakey);
		}
		
		if ($mainframe->getCfg('MetaTitle') == '1') {
			$mainframe->addMetaTag('title', $entry->title);
		}
		
		if ($mainframe->getCfg('MetaAuthor') == '1') {
			$mainframe->addMetaTag('author', $entry->author->username);
		}

		$mdata = new JParameter($entry->metadata);
		$mdata = $mdata->toArray();
		foreach ($mdata as $k => $v)
		{
			if ($v) {
				$document->setMetadata($k, $v);
			}
		}

		// Process the prepare content plugins
		JPluginHelper::importPlugin('content');
		$results = $dispatcher->trigger('onPrepareContent', array (& $entry, & $params, $limitstart));

		// Handle display events
		$entry->event = new stdClass();
		$results = $dispatcher->trigger('onAfterDisplayTitle', array ($entry, &$params, $limitstart));
		$entry->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onBeforeDisplayContent', array (& $entry, & $params, $limitstart));
		$entry->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onAfterDisplayContent', array (& $entry, & $params, $limitstart));
		$entry->event->afterDisplayContent = trim(implode("\n", $results));
		
		//No reason to look for comments if not using them
		if ($settings->get('typeComments', 'default') == 'default') {
		
			//get comments
			$commentsData	= & $this->get('Comments');
			$pageNav 		= & $this->get('Pagination');
			$totalcoms		= & $this->get('Total');

			//assign comment variables to template
			$template->assign('commentsData' , 	$commentsData);
			$template->assign('pageNav' , 		$pageNav);
			$template->assign('totalcoms' , 	$totalcoms);
			$template->assign('user' ,			$user);
		}

		//assign variables to template
		
		
		//Mod by Vicente Gimeno (vgimeno@linkatic.com)
		//Limpiamos el código HTML en la ficha del post extendido 
		$entry->text = strip_tags($entry->text,'<p><ul><li><b><br>');
		
		
		
		$template->assign('entry' , 			$entry);
		$template->assign('author' , 			$author);
		$template->assign('entryTitle' ,		$entry->title);
		$template->assign('entryID' ,			$entry->id);
		$template->assign('allowComments' ,		$entry->allowComments);
		
		//print_r($entry);
		//display template
		$template->getOutput();
		return;
	}
}
?>