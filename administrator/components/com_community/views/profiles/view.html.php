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
class CommunityViewProfiles extends JView
{
	/**
	 * The default method that will display the output of this view which is called by
	 * Joomla
	 * 
	 * @param	string template	Template file name
	 **/	 	
	function display( $tpl = null )
	{
		$profile	=& $this->getModel( 'Profiles' );
		
		$fields		=& $profile->getFields();
		$pagination	=& $profile->getPagination();
		
		// Load tooltips
		JHTML::_('behavior.tooltip', '.hasTip');

		$this->assignRef( 'fields' 		, $fields );
		$this->assignRef( 'pagination'	, $pagination );
		parent::display( $tpl );
	}

	/**
	 * Method to get the Field type in text
	 * 
	 * @param	string	Type of field
	 * 
	 * @return	string	Text representation of the field type.
	 **/	 
	function getFieldText( $type )
	{
		$model	=& $this->getModel( 'Profiles' );
		$types	= $model->getProfileTypes();
		$value	= isset( $types[ $type ] ) ? $types[ $type ] : '';
		
		return $value;
// 		switch( $type )
// 		{
// 			case 'list':
// 				$type	= JText::_('CC MULTIPLE SELECT');
// 				break;
// 			case 'select':
// 				$type	= JText::_('CC SELECT');
// 				break;
// 			case 'text':
// 				$type	= JText::_('CC TEXTBOX');
// 				break;
// 			case 'radio':
// 				$type	= JText::_('CC RADIO');
// 				break;
// 			case 'checkbox':
// 				$type	= JText::_('CC CHECKBOX');
// 				break;
// 			case 'date':
// 			 	$type	= JText::_('CC DATE');
// 			 	break;
// 			case 'textarea':
// 				$type	= JText::_('CC TEXTAREA');
// 				break;
// 			case 'url':
// 				$type	= JText::_('CC URL');
// 				break;
// 			case 'country':
// 				$type	= JText::_('CC COUNTRY');
// 				break;
// 			case 'email':
// 				$type	= JText::_('CC EMAIL');
// 				break;
// 			case 'time':
// 				$type	= JText::_('CC TIME');
// 				break;
// 			default:
// 				$type	= JText::_('CC UNKNOWN');
// 				break;
// 		}
// 		return $type;
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
	
	/**
	 * Method to get the publish status HTML
	 *
	 * @param	object	Field object
	 * @param	string	Type of the field
	 * @param	string	The ajax task that it should call
	 * @return	string	HTML source
	 **/
	function showPublish( &$row , $type)
	{

		$imgY	= 'tick.png';
		$imgX	= 'publish_x.png';

		$image	= $row->$type ? $imgY : $imgX;

		$alt	= $row->$type ? JText::_('CC PUBLISHED') : JText::_('CC UNPUBLISHED');

		$href  = '<span><img src="images/' . $image . '" border="0" alt="' . $alt . '" /></span>';

		return $href;
	}
	
	/**
	 * Private method to set the toolbar for this view
	 * 
	 * @access private
	 * 
	 * @return null
	 **/	 	 
	function setToolBar()
	{

		// Set the titlebar text
		JToolBarHelper::title( JText::_('CC CUSTOM PROFILES'), 'profiles' );

		// Add the necessary buttons
		JToolBarHelper::back('Home' , 'index.php?option=com_community');
		JToolBarHelper::divider();
		JToolBarHelper::publishList('publish', JText::_('CC PUBLISH'));
		JToolBarHelper::unpublishList('unpublish', JText::_('CC UNPUBLISH'));
		JToolBarHelper::divider();
		JToolBarHelper::trash('removefield', JText::_('CC DELETE'));
		JToolBarHelper::addNew('newgroup', JText::_('CC NEW GROUP'));
		JToolBarHelper::addNew('newfield', JText::_('CC NEW FIELD'));
	}
}