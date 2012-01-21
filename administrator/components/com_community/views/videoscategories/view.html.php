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
class CommunityViewVideosCategories extends JView
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

		$categories	=& $this->get( 'Categories' );
		$pagination	=& $this->get( 'Pagination' );
		
		// Escape the output
		CFactory::load( 'helpers' , 'string' );
		foreach ($categories as $row)
		{
			$row->name	= CStringHelper::escape($row->name);
			$row->description	= CStringHelper::escape($row->description);
		}
		
		$this->assignRef( 'categories'	, $categories );
		$this->assignRef( 'pagination'	, $pagination );
		parent::display( $tpl );
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
		JToolBarHelper::title( JText::_('CC VIDEO CATEGORIES'), 'videoscategories');
		
		// Add the necessary buttons
		JToolBarHelper::back('Home' , 'index.php?option=com_community');
		JToolBarHelper::divider();
		JToolBarHelper::publishList( 'publish' , JText::_('CC PUBLISH') );
		JToolBarHelper::unpublishList( 'unpublish' , JText::_('CC UNPUBLISH') );
		JToolBarHelper::divider();
		JToolBarHelper::trash( 'removecategory', JText::_('CC DELETE'));
		JToolBarHelper::addNew( 'newcategory' , JText::_('CC NEW') );
	}
}