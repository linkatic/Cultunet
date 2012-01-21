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
class CommunityViewGroups extends JView
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
		
		// Get required data's
		$groups		=& $this->get( 'Groups' );

		$categories	=& $this->get( 'Categories' );
		$pagination	=& $this->get( 'Pagination' );

		// We need to assign the users object to the groups listing to get the users name.
		for( $i = 0; $i < count( $groups ); $i++ )
		{
			$row		=& $groups[$i];
			$row->user	=& JFactory::getUser( $row->ownerid );
		}
		$mainframe	=& JFactory::getApplication();
		$filter_order		= $mainframe->getUserStateFromRequest( "com_community.groups.filter_order",		'filter_order',		'a.name',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( "com_community.groups.filter_order_Dir",	'filter_order_Dir',	'',			'word' );
		$search				= $mainframe->getUserStateFromRequest( "com_community.users.search", 'search', '', 'string' );
		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		
		$catHTML	= $this->_getCategoriesHTML( $categories );

		$this->assignRef( 'groups' 		, $groups );
		$this->assignRef( 'categories' 	, $catHTML);
		$this->assignRef( 'lists' 		, $lists );
		$this->assignRef( 'search'		, $search );
		$this->assignRef( 'pagination'	, $pagination );

		parent::display( $tpl );
	}

	
	function _getCategoriesHTML( &$categories )
	{
		// Check if there are any categories selected
		$category	= JRequest::getInt( 'category' , 0 );

		$select	= '<select name="category" onchange="submitform();">';

		$select	.= ( $category == 0 ) ? '<option value="0" selected="true">' : '<option value="0">';
		$select .= JText::_('CC ALL GROUPS') . '</option>';
		
		for( $i = 0; $i < count( $categories ); $i++ )
		{
			$selected	= ( $category == $categories[$i]->id ) ? ' selected="true"' : '';
			$select	.= '<option value="' . $categories[$i]->id . '"' . $selected . '>' . $categories[$i]->name . '</option>';
		}
		$select	.= '</select>';
		
		return $select;
	}


	/**
	 * Method to get the publish status HTML
	 *
	 * @param	object	Field object
	 * @param	string	Type of the field
	 * @param	string	The ajax task that it should call
	 * @return	string	HTML source
	 **/	 	
	function getPublish( &$row , $type , $ajaxTask )
	{
	
		$imgY	= 'tick.png';
		$imgX	= 'publish_x.png';
		
		$image	= $row->$type ? $imgY : $imgX;
		
		$alt	= $row->$type ? JText::_('CC PUBLISHED') : JText::_('CC UNPUBLISHED');
		
		$href = '<a href="javascript:void(0);" onclick="azcommunity.togglePublish(\'' . $ajaxTask . '\',\'' . $row->id . '\',\'' . $type . '\');">';
		$href  .= '<span><img src="images/' . $image . '" border="0" alt="' . $alt . '" /></span></a>';
		
		return $href;
	}
	
	function setToolBar()
	{
		// Set the titlebar text
		JToolBarHelper::title( JText::_('CC GROUPS'), 'groups');
		
		// Add the necessary buttons
		JToolBarHelper::back('Home' , 'index.php?option=com_community');
		JToolBarHelper::divider();
		JToolBarHelper::deleteList( JText::_('CC GROUP DELETION WARNING') , 'deleteGroup' , JText::_('CC DELETE') );
		JToolBarHelper::divider();
		JToolBarHelper::publishList( 'publish' , JText::_('CC PUBLISH') );
		JToolBarHelper::unpublishList( 'unpublish' , JText::_('CC UNPUBLISH') );
	}
}