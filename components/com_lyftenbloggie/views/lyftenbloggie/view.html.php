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

class LyftenBloggieViewLyftenBloggie extends JView
{
	
	
	/**
	 * Creates the View
	 **/
	function display( $tpl = null )
	{
		global $mainframe;

		//initialize variables
		$document 	= & JFactory::getDocument();
		$tag		= JArrayHelper::getValue( $_REQUEST, 'tag', 0 );
		$category	= JArrayHelper::getValue( $_REQUEST, 'category', 0 );
		$author 	= JRequest::getVar('author', 0, '', 'int');
		$settings 	= & BloggieSettings::getInstance();
		$title 		= JText::_('BLOG');
	
		// Get the page/component configuration
		$params = &$mainframe->getParams('com_lyftenbloggie');

		// Request variables
		$limit		= $params->get('display_num', $mainframe->getCfg('list_limit'));
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		JRequest::setVar('limit', (int)$limit);

		//Assign Template Layout
		$template = BloggieTemplate::getInstance();
		$template->setTemplateFile('index');
		$template->setStyles();

		//Get Data data
		if (JRequest::getInt('year') && JRequest::getInt('month')) {
			$year 		= JRequest::getInt('year');
			$month 		= JRequest::getInt('month');
			$archive 	= JHTML::_('date', strtotime($year.'-'.$month), '%B %Y'); 
		}

		// Get data from the model
		$entries	= &$this->get('Data');
		$total		= $this->get('Total');

		//add alternate feed link
		if ($settings->get('useRSSFeed'))
		{
			if ($settings->get('useRSS1')) {
				$rss = array('type' => 'application/rss+xml', 'title' => 'RSS 1.0');
				$document->addHeadLink(JRoute::_(LyftenBloggieHelperRoute::getBlogFeedRoute('rss').'&format=feed'), 'alternate', 'rel', $rss);
			}
			if ($settings->get('useAtom')) {
				$atom = array('type' => 'application/atom+xml', 'title' => 'Atom');
				$document->addHeadLink(JRoute::_(LyftenBloggieHelperRoute::getBlogFeedRoute('atom').'&format=feed'), 'alternate', 'rel', $atom);
			}
		}

		// Get the menu item object
		$menus = &JSite::getMenu();
		$menu  = $menus->getActive();

		//Pathway and Message
		$pathway 	= & $mainframe->getPathWay();
		$message 	= (empty($total)) ? JText::_('NO BLOG ENTRIES FOUND') : '';
		if($tag) {
			$title 	= & $this->get('TagInfo');
			$title 	= ($title) ? $title : JText::_('NOT FOUND');
			$message = (empty($total)) ? JText::sprintf('NO BLOG ENTRIES TAGGED', $tag) : '';
			$pathway->addItem( $this->escape($title), JRoute::_('index.php?option=com_lyftenbloggie&tag='.$tag));
		}
		if($category) {
			$message 	= (empty($total)) ? JText::sprintf('NO BLOG ENTRIES CATEGORY', $category) : '';
			$title 		= & $this->get('CategoryInfo');
			if(is_object($menu) && !isset($menu->query['category'])) {
				$pathway->addItem( $this->escape($title), JRoute::_('index.php?option=com_lyftenbloggie&category='.$category));
			}
		}
		if(isset($archive)) {
			$message = (empty($total)) ? JText::sprintf('NO BLOG ENTRIES FOR', $archive) : '';
			$pathway->addItem( $this->escape($archive), JRoute::_('index.php?option=com_lyftenbloggie&year='.$year.'&month='.$month.'&day=0'));
		}
		if($author){
			$author_data = &JFactory::getUser((int)$author);
			$title 		= $author_data->get('name');
			$message 	= (empty($total)) ? JText::sprintf('NO BLOG ENTRIES AUTHOR', $title) : '';
			$pathway->addItem( JText::_('AUTHORS') );
			$pathway->addItem( $this->escape($title), JRoute::_('index.php?option=com_lyftenbloggie&author='.$author));
		}

		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		if (is_object( $menu )) {
			$menu_params = new JParameter( $menu->params );
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	$title);
			}
		} else {
			$params->set('page_title',	$title);
		}

		// Handle the metadata for the entries list
		$document->setTitle($params->get('page_keywords'));
		$document->setMetadata( 'keywords' , $params->get('page_keywords') );

		if ($mainframe->getCfg('MetaTitle') == '1') {
				$mainframe->addMetaTag('title', $params->get('page_title'));
		}

		// Create the pagination object
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);

		//assign variables to template
		$template->assign('entries' , 		$entries);
		$template->assign('pageNav' , 		$pageNav);
		$template->assign('message' , 		$message);
		
		$author_blog		= & JFactory::getUser($author);
		
		if($author) {
			$author_blog		= & JFactory::getUser($author);
			$template->assign('author_blog', JText::_('BLOG DE ').$author_blog->name); 
		}
		else $template->assign('author_blog', JText::_('BLOG DE CULTUNET.COM')); 

		//display template
		$template->getOutput();
		return;
	}
}
?>