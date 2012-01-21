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
class CommunityViewTemplates extends JView
{
	/**
	 * The default method that will display the output of this view which is called by
	 * Joomla
	 * 
	 * @param	string template	Template file name
	 **/	 	
	function display( $tpl = null )
	{
		// Load tooltips
		JHTML::_('behavior.tooltip', '.hasTip');

		if( $this->getLayout() == 'edit' )
		{
			$this->_displayEditLayout( $tpl );
			return;
		}


		// Set the titlebar text
		JToolBarHelper::title( JText::_('CC TEMPLATES'), 'templates' );

		// Add the necessary buttons
		JToolBarHelper::back('Home' , 'index.php?option=com_community');
		
		$this->assignRef( 'templates' , $this->getTemplates() );
		parent::display( $tpl );
	}
	
	function _displayEditLayout( $tpl )
	{
		$element	= JRequest::getString( 'id' );
		$override	= JRequest::getInt( 'override' );

		// @rule: Test if this folder really exists
		$path		= COMMUNITY_BASE_PATH . DS . 'templates' . DS . $element;
		
		if( $override )
			$path	= JPATH_ROOT . DS . 'templates' . DS . $element . DS . 'html' . DS . 'com_community';
	
		if( !JFolder::exists( $path ) )
		{
			$mainframe	=& JFactory::getApplication();
			$mainframe->redirect( 'index.php?option=com_community&view=templates' , JText::_('Invalid template') , 'error');
		}

		$params		= $this->getParams( $element , $override );
		$files		= $this->getFiles( $element , $override );

		// Set the titlebar text
		JToolBarHelper::title( JText::_('CC TEMPLATES'), 'templates' );

		// Add the necessary buttons
 		JToolBarHelper::back('Back' , 'index.php?option=com_community&view=templates');
 		JToolBarHelper::divider();
		JToolBarHelper::save(); 
		JToolBarHelper::apply();
				
		$this->assignRef( 'files' , $files );
		$this->assignRef( 'template' , $this->getTemplate( $element , $override ) );
		$this->assignRef( 'params' , $params );
		$this->assignRef( 'override' , $override );
 		parent::display( $tpl );
	}
	
	function getParams( $element , $override )
	{
		$templatesPath	= COMMUNITY_BASE_PATH . DS . 'templates';
		
		if( $override )
		{
			$xml	= JPATH_ROOT . DS . 'templates' . DS . $element . DS . COMMUNITY_TEMPLATE_XML;
			$raw	= JPATH_ROOT . DS . 'templates' . DS . $element . DS . 'params.ini';
		}
		else
		{
			$xml	= $templatesPath . DS . $element . DS . COMMUNITY_TEMPLATE_XML;
			$raw	= $templatesPath . DS . $element . DS . 'params.ini';
		}
		
		$rawContent		= '';
		
		if( JFile::exists( $raw ) )
		{
			$rawContent	= JFile::read( $raw );
		}
		
		return new JParameter( $rawContent , $xml , 'template' );
	}
	
	function getTemplates()
	{
		$templatesPath	= COMMUNITY_BASE_PATH . DS . 'templates';
		$templates		= array();
		$folders		= JFolder::folders( $templatesPath );
		$overrideFolders	= JFolder::folders( JPATH_ROOT . DS . 'templates' );
		
		// @rule: Retrieve template overrides folder
		foreach( $overrideFolders as $overrideFolder )
		{
			// Only add templates that really has overrides
			if( JFolder::exists( JPATH_ROOT . DS . 'templates' . DS . $overrideFolder . DS . 'html' . DS . 'com_community' ) )
			{
				$path			= JPATH_ROOT . DS . 'templates' . DS . $overrideFolder . DS . 'html' . DS . 'com_community' . DS . COMMUNITY_TEMPLATE_XML;
				$obj			= new stdClass();
				$obj->element	= $overrideFolder;
				$obj->override	= true;
				if( JFile::exists( $path ) )
				{
					$obj->info	= JApplicationHelper::parseXMLInstallFile(  $path );
				}
				else
				{
					$obj->info	= false;
				}
				$templates[]	= $obj;
			}
		}

		// @rule: Retrieve jomsocial template folders
		foreach( $folders as $folder )
		{
			$obj			= new stdClass();
			$obj->element	= $folder;
			$obj->override	= false;
			if( JFile::exists( $templatesPath . DS . $folder . DS . COMMUNITY_TEMPLATE_XML ) )
			{
				$obj->info	= JApplicationHelper::parseXMLInstallFile(  $templatesPath . DS . $folder . DS . COMMUNITY_TEMPLATE_XML );
			}
			else
			{
				$obj->info	= false;
			}
			$templates[]	= $obj;
		}

		return $templates;
	}
	
	function getTemplate( $element , $override )
	{
		$templatesPath	= COMMUNITY_BASE_PATH . DS . 'templates';
		if( $override )
		{
			$templatesPath	= JPATH_ROOT . DS . 'templates' . DS . $element . DS . 'html' . DS . 'com_community';
		}

		$obj			= new stdClass();
		$obj->element	= $element;
		$obj->override	= $override;
		if( JFile::exists( $templatesPath . DS . $element . DS . COMMUNITY_TEMPLATE_XML ) )
		{
			$obj->info	= JApplicationHelper::parseXMLInstallFile(  $templatesPath . DS . $element . DS . COMMUNITY_TEMPLATE_XML );
		}
		else
		{
			$obj->info	= false;
		}
		return $obj;
	}
		
	function getFiles( $element , $override )
	{
		$path	= COMMUNITY_BASE_PATH . DS . 'templates' . DS . $element;
		
		if( $override )
		{
			$path	= JPATH_ROOT . DS  . 'templates' . DS . $element . DS . 'html' . DS . 'com_community';
		}
		$files	= JFolder::files( $path );
		
		return $files;
	}
	
	/**
	 * Public method to get the templates listings
	 * 
	 * @access private
	 * 
	 * @return null
	 **/
	function getTemplatesListing()
	{
		$templatesPath	= COMMUNITY_BASE_PATH . DS . 'templates';
		$templates		= array();

		if( $handle = @opendir($templatesPath) )
		{
			while( false !== ( $file = readdir( $handle ) ) )
			{
				// Do not get '.' or '..' or '.svn' since we only want folders.
				if( $file != '.' && $file != '..' && $file != '.svn' )
					$templates[]	= $file;
			}
		}
		
		
		$html	= '<select name="template" style="width: 200px;" onchange="azcommunity.changeTemplate(this.value);">';
		
		$html	.= '<option value="none" selected="true">' . JText::_('CC SELECT A TEMPLATE') . '</option>';
		for( $i = 0; $i < count( $templates ); $i++ )
		{
			$html	.= '<option value="' . $templates[$i] . '">' . $templates[$i] . '</option>';
		}
		$html	.= '</select>';
		
		return $html;
	}
}