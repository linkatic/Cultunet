<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controller' );

/**
 * Jom Social Component Controller
 */
class CommunityControllerTemplates extends CommunityController
{
	function __construct()
	{
		parent::__construct();
	}
	
	function ajaxChangeTemplate( $templateName )
	{
		$response	= new JAXResponse();
		
		if( $templateName == 'none' )
		{
			// Previously user might already selected a template, hide the files
			$response->addScriptCall( 'azcommunity.resetTemplateFiles();' );
			
			// Close all files if it is already editing
			$response->addScriptCall( 'azcommunity.resetTemplateForm();' );
		}
		else
		{
			$html	= '<div id="template-files">';
			$html	.= '<h3>' . JText::_('CC SELECT FILE') . '</h3>';
			

			$templatePath	= COMMUNITY_BASE_PATH . DS . 'templates' . DS . JString::strtolower( $templateName );
			
			$files			= array();
	
			if( $handle = @opendir($templatePath) )
			{
				while( false !== ( $file = readdir( $handle ) ) )
				{
					$filePath	= $templatePath . DS . $file;
					
					// Do not get '.' or '..' or '.svn' since we only want folders.
					if( $file != '.' && $file != '..' && $file != '.svn' && !(JString::stristr( $file , '.js')) && !is_dir($filePath) )
					{
						$files[]	= $file;
					}
				}
			}

			$html	.= '<select name="file" onchange="azcommunity.editTemplate(\'' . $templateName . '\',this.value);">';
			$html	.= '<option value="none" selected="true">' . JText::_('CC SELECT A FILE') . '</option>';
			for( $i = 0; $i < count( $files ); $i++ )
			{
				$html .= '<option value="' . $files[$i] . '">' . $files[$i] . '</option>';
			}
			$html	.= '</select>';
			
			$html	.= '</div>';
			$response->addAssign( 'templates-files-container' , 'innerHTML' , $html );
		}

		return $response->sendResponse();
	}
	
	/**
	 * Ajax method to load a template file
	 *
	 * @param	$templateName	The template name
	 * @param	$fileName	The file name
	 **/	 
	function ajaxLoadTemplateFile( $templateName , $fileName , $override )
	{
		$response	= new JAXResponse();

		if( $fileName == 'none')
		{
			$response->addScriptCall( 'azcommunity.resetTemplateForm();' );
		}
		else
		{
			$filePath	= COMMUNITY_BASE_PATH . DS . 'templates' . DS . JString::strtolower( $templateName ) . DS . JString::strtolower( $fileName );
			
			if( $override )
				$filePath	= JPATH_ROOT . DS . 'templates' . DS . JString::strtolower( $templateName ) . DS . 'html' . DS . 'com_community' . DS . JString::strtolower( $fileName );
			
			jimport('joomla.filesystem.file');
			
			$contents	= JFile::read( $filePath );
	
			$response->addAssign( 'data' , 'value' , $contents );
			$response->addAssign( 'fileName' , 'value' , $fileName );
			$response->addAssign( 'templateName' , 'value' , $templateName );
			$response->addAssign( 'filePath' , 'innerHTML' , $filePath );
		}

		return $response->sendResponse();
	}
	
	function ajaxSaveTemplateFile( $templateName , $fileName , $fileData , $override )
	{
		$response	= new JAXResponse();
		
		$filePath	= COMMUNITY_BASE_PATH . DS . 'templates' . DS . JString::strtolower( $templateName ) . DS . JString::strtolower( $fileName );

		if( $override )
			$filePath	= JPATH_ROOT . DS . 'templates' . DS . JString::strtolower( $templateName ) . DS . 'html' . DS . 'com_community' . DS . JString::strtolower( $fileName );
		
		jimport( 'joomla.filesystem.file' );
		
		if( JFile::write( $filePath , $fileData ) )
		{
			$response->addScriptCall('joms.jQuery("#status").html("' . JText::sprintf('%1$s saved successfully.' , $fileName ) . '");');
			$response->addScriptCall('joms.jQuery("#status").attr("class","info");');
		}
		else
		{
			$response->addScriptCall( 'alert' , JText::_('CC ERROR WHILE SAVING FILE PLEASE CHECK PERMISSIONS OF FILE') );
		}

		return $response->sendResponse();
	}
	
	function save()
	{
		$mainframe	=& JFactory::getApplication();
		$params		= JRequest::getVar('params', array(), 'post', 'array');
		$element	= JRequest::getString( 'id' );
		$override	= JRequest::getVar( 'override' );
        $task       = JRequest::getCmd( 'task' );     

		if( $override )
		{
			$file		= JPATH_ROOT . DS . 'templates' . DS . $element . DS . 'params.ini';
		}
		else
		{
			$file		= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'templates' . DS . $element . DS . 'params.ini';
		}

		jimport('joomla.filesystem.file');
		
		$registry	= new JRegistry();
		$registry->loadArray($params);
		$raw		= $registry->toString();
		
		if( !empty( $raw ) )
		{
			if( !JFile::write( $file , $raw ) )
			{
				$mainframe->redirect( 'index.php?option=com_community&view=templates&layout=edit&id=' . $element , JText::_('Error when trying to save template parameters') , 'error' );
			}
		}
		
		switch($task){
            case 'apply';
                $link   = 'index.php?option=com_community&view=templates&layout=edit&override='.$override.'&id='.$element;
                break;
            case 'save';
            default:
                $link   = 'index.php?option=com_community&view=templates';
                break;
        }
		
		$mainframe->redirect( $link , JText::_('Template parameter saved') );
	} 
	
	function apply()
	{
        $this->save();   
    }
	
	function edit()
	{
		$id			= JRequest::getVar( 'cid' );
		$mainframe	=& JFactory::getApplication();
		
		$mainframe->redirect( 'index.php?option=com_community&view=templates&layout=edit&id=' . $id[0] );
	}
}