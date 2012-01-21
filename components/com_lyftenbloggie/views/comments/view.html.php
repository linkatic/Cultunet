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
class LyftenBloggieViewComments extends JView
{
	/**
	 * Creates the comments page
	 **/
	function display( $tpl = null )
	{
		global $mainframe;

		//initialize variables
		$document 	= & JFactory::getDocument();
		$user		= & JFactory::getUser();
		$menus		= & JSite::getMenu();
		$menu    	= $menus->getActive();
		$params 	= & $mainframe->getParams('com_lyftenbloggie');
		$settings 	= & BloggieSettings::getInstance();

		//Assign Template Layout
		$template = BloggieTemplate::getInstance();
		$template->setTemplateFile('comments');
		$template->setStyles();

		//Get Data data
		if (JRequest::getInt('year') && JRequest::getInt('month') && JRequest::getInt('day')) {
			$year 		= JRequest::getInt('year');
			$month 		= JRequest::getInt('month');
			$archive 	= JHTML::_('date', strtotime($year.'-'.$month), '%B %Y'); 
		}
		
		//Load route helper
		require_once (JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php');

		$id	= JRequest::getInt('id', 0);

		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		//get faq Entry data
		$entry 				= & $this->get('Entry');

		//Make sure entry is there
		if (($entry->id == 0))
		{	
			return JError::raiseError( 404, JText::sprintf( 'ENTRY BLANK NOT FOUND', $id ) );
		}

		//Get entry params
		$params->merge($entry->attribs);
		
		//pathway
		$pathway 	= & $mainframe->getPathWay();
		if(isset($archive)) {
			$pathway->addItem( $this->escape($archive), JRoute::_('index.php?option=com_lyftenbloggie&year='.$year.'&month='.$month.'&day=0'));
		}else{
			$pathway->addItem( $this->escape($entry->cattitle), JRoute::_('index.php?option=com_lyftenbloggie&category='.$entry->catslug));
		}
		$pathway->addItem( $this->escape($entry->title), JRoute::_('index.php?option=com_lyftenbloggie&view=entry'.$entry->archive.'&id='.$entry->slug));
		$pathway->addItem( JText::_('COMMENTS'));
		
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

		/*
		 * Create the document title
		 */
		$document->setTitle($entry->title.' - '.JText::_('COMMENTS'));
		
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
			$mainframe->addMetaTag('author', $entry->author);
		}

		$mdata = new JParameter($entry->metadata);
		$mdata = $mdata->toArray();
		foreach ($mdata as $k => $v)
		{
			if ($v) {
				$document->setMetadata($k, $v);
			}
		}

		//No reason to look for comments if not using them
		if ($settings->get('typeComments', 'default') == 'default')
		{
			//get comments
			$commentsData	= & $this->get('Comments');
			$totalcoms		= & $this->get('Total');
			$pageNav 		= & $this->get( 'Pagination' );

			//assign variables to template
			$view = '&view=comments';
			$template->assign('commentsData' , 	$commentsData);
			$template->assign('pageNav' , 		$pageNav);
			$template->assign('totalcoms' , 	$totalcoms);
			$template->assign('user' ,			$user);
			$template->assign('view' ,			$view);
		}
		
		//assign variables to template
		$template->assign('entry',	$entry);
		
		//display template
		$template->getOutput();
		return;
	}
}
?>