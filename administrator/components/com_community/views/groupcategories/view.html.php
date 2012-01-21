<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

/**
 * Configuration view for Jom Social
 */
class CommunityViewGroupCategories extends JView
{
	/**
	 * The default method that will display the output of this view which is called by
	 * Joomla
	 * 
	 * @param	string template	Template file name
	 **/	 	
	function display( $tpl = null )
	{
		$document	=& JFactory::getDocument();
		$mainframe	=& JFactory::getApplication();
		$categories	=& $this->get( 'Categories' );
		$pagination	=& $this->get( 'Pagination' );

		$filter_order		= $mainframe->getUserStateFromRequest( "com_community.filter_order",		'filter_order',		'a.name',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( "com_community.filter_order_Dir",	'filter_order_Dir',	'',			'word' );

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		
		// Escape the output
		CFactory::load( 'helpers' , 'string' );
		foreach ($categories as $row)
		{
			$row->name	= CStringHelper::escape($row->name);
			$row->description	= CStringHelper::escape($row->description);
		}
		
		$this->assignRef( 'lists' 		, $lists );
		$this->assignRef( 'categories'	, $categories );
		$this->assignRef( 'pagination'	, $pagination );
		parent::display( $tpl );
	}

	function setToolBar()
	{
		// Set the titlebar text
		JToolBarHelper::title( JText::_('CC GROUP CATEGORIES'), 'groupcategories');
		
		// Add the necessary buttons
		JToolBarHelper::back('Home' , 'index.php?option=com_community');
		JToolBarHelper::divider();
		JToolBarHelper::trash( 'removecategory', JText::_('CC DELETE'));
		JToolBarHelper::addNew( 'newcategory' , JText::_('CC NEW') );
	}
}