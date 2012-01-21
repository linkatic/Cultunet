<?php
/**
 * @package		JomSocial
 * @subpackage	Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * Templating system for JomSocial
 */ 
class CTemplate
{
    var $vars; /// Holds all the template variables

	static public function getPoweredByLink()
	{
		return " ";
		$jConfig	= JFactory::getConfig();
		$siteName	= $jConfig->getValue( 'sitename' );
		
		return 'Powered by <a href="http://www.jomsocial.com/">JomSocial</a> for <a href="' . JURI::root() . '">' . $siteName . '</a>';
	}
	
    function renderModules($position, $attribs = array())
    {
    	jimport( 'joomla.application.module.helper' );
    	
		$modules 	= JModuleHelper::getModules( $position );
		$modulehtml = '';
		
		foreach($modules as $module)
		{			
			// If style attributes are not given or set, we enforce it to use the xhtml style
			// so the title will display correctly.
			if( !isset($attribs['style'] ) )
				$attribs['style']	= 'xhtml';

			$modulehtml .= JModuleHelper::renderModule($module, $attribs);
		}
		
		echo $modulehtml;
    }

	function escape( $text )
	{
		CFactory::load( 'helpers' , 'string' );
		
		return CStringHelper::escape( $text );
	}
	
    /**
     * Constructor
     *
     * @param $file string the file name you want to load
     */
    function CTemplate($file = null)
	{
        $this->file = $file;
        @ini_set('short_open_tag', 'On');
        $this->set('dummy', true);
    }
    
    function _getTemplateParams( $currentFolder )
    {
		// Test if template override exists in joomla's template folder
		$mainframe		=& JFactory::getApplication();
		$config			=& CFactory::getConfig();
		$overridePath	= JPATH_ROOT . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'html';
		$overrideExists	= JFolder::exists( $overridePath . DS . 'com_community' );
		$contents		= '';

		$default		= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'templates' . DS . 'default' . DS . 'params.ini';

		// Always load default template params
		if( JFile::exists( $default ) )
		{
			$contents	= JFile::read( $default );
		}
		$params	= new JParameter( $contents );
		
		if( $overrideExists && JFile::exists( JPATH_ROOT . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'params.ini') )
		{
			$contents	= JFile::read( JPATH_ROOT . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'params.ini' );
			$params->bind( $contents );
		}
		elseif( JFile::exists( $currentFolder . DS . 'params.ini' ) )
		{
			$contents	= JFile::read( $currentFolder . DS . 'params.ini' );
			$params->bind( $contents );
		}
		
		return $params;
	}
	
    /**
     * Get the template full path name, given a templaet name code
     */	     
    function _getTemplateFullpath($file)
    {
    	if(!JString::strpos($file, '.php'))
    	{
    		$file	= $this->_getTemplateFolder( $file ) . DS . $file . '.php';
    	}
    	
    	return $file;
	}
	
	function _getTemplateFolder( $file )
	{
    	$config		=& CFactory::getConfig();
		// Test if template override exists in joomla's template folder
		$mainframe		=& JFactory::getApplication();
		$overridePath	= JPATH_ROOT . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'html' . DS . 'com_community';
		$overrideExists	= JFolder::exists( $overridePath );
		$templatePath	= COMMUNITY_COM_PATH . DS . 'templates' . DS . $config->get('template');

		// Test override path first
		if( JFile::exists( $overridePath . DS . $file .'.php' ) )
		{
			// Load the override template.
			$folder	= $overridePath;
		}
		else if( JFile::exists(  $templatePath . DS . $file . '.php' ) && !$overrideExists )
		{
   			// If override fails try the template set in config
			$folder	= $templatePath;
		}
		else
		{
			// We assume to use the default template
			$folder	= COMMUNITY_COM_PATH . DS . 'templates' . DS . 'default';																    			    			
		}

		return $folder;
	}
	
    /**
     * Set a template variable.
     */
    function set($name, $value) {
        $this->vars[$name] = $value; //is_object($value) ? $value->fetch() : $value;
    }
    
    /**
     * Set a template variable by reference
     */
    function setRef($name, &$value) {
        $this->vars[$name] =& $value; //is_object($value) ? $value->fetch() : $value;
    }

	function addStylesheet( $file )
	{
		$mainframe	=& JFactory::getApplication();  
		$cfg		=& CFactory::getConfig();
    	
		if(!JString::strpos($file, '.css'))
		{
    		$filename	= $file;
    		
			jimport( 'joomla.filesystem.file' );
			jimport( 'joomla.filesystem.folder' );
			
    		// Test if template override exists in joomla's template folder
    		$overridePath	= JPATH_ROOT . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'html';
    		$overrideExists	= JFolder::exists( $overridePath . DS . 'com_community' );
			$template		= COMMUNITY_COM_PATH . DS . 'templates' . DS . $cfg->get('template') . DS . 'css' . DS . $filename . '.css';
			
    		// Test override path first
    		if( JFile::exists( $overridePath . DS . 'com_community' . DS . 'css' . DS . $filename . '.css') )
    		{
    			// Load the override template.
    			$file	= '/templates/' . $mainframe->getTemplate() . '/html/com_community/css/' . $filename . '.css';
			}
    		else if( JFile::exists( $template ) && !$overrideExists )
    		{
	   			// If override fails try the template set in config
				$file	=  '/components/com_community/templates/' . $cfg->get('template') . '/css/' . $filename . '.css';
    		}
    		else
    		{	
    			// We assume to use the default template
    			$file	= '/components/com_community/templates/default/css/' . $filename . '.css';
			}
    	}

    	CAssets::attach( $file , 'css' , rtrim( JURI::root() , '/' ) );
	}
	
	/***
	 * Allow a template to include other template and inherit all the variable
	 */	 	
	function load($file)
	{
		if($this->vars)
        	extract($this->vars, EXTR_REFS); 
        	
		$file = $this->_getTemplateFullpath($file);
		include($file);
		return $this;
	}
	
	
    /**
     * Open, parse, and return the template file.
     *
     * @param $file string the template file name
     */
    function fetch($file = null)
	{
		
		if( JRequest::getVar('format') == 'iphone' )
		{				
			$file	.= '.iphone';
		}   	    
		
		$folder		= $this->_getTemplateFolder( $file );
		$file		= $this->_getTemplateFullpath( $file );
    	
        if(!$file)
		{
			$file = $this->file;
		}
        
        if((JRequest::getVar('format') == 'iphone') && (!JFile::exists($file)))
        {
        	//if we detected the format was iphone and the template file was not there, return empty content.
        	return '';
        }

		// @rule: always add jomsocial config object in the template scope so we don't really need
		// to always set it.
		if( !isset( $this->vars['config'] ) && empty($this->vars['config']) )
		{
			$this->vars['config']	= CFactory::getConfig();
		}

		// @rule: Extract template parameters for template providers
		if( !isset( $this->params ) && empty( $this->params ) )
		{
			$this->params	= $this->_getTemplateParams( $folder );
		}

		if($this->vars)
        	extract($this->vars, EXTR_REFS);          // Extract the vars to local namespace

        ob_start();                    // Start output buffering
        require($file);                // Include the file
        $contents = ob_get_contents(); // Get the contents of the buffer
        ob_end_clean();                // End buffering and discard
        return $contents;              // Return the contents
    }
    
    function object_to_array($obj)
	{
       $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
       $arr = array();
       foreach ($_arr as $key => $val) {
               $val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
               $arr[$key] = $val;
       }
       return $arr;
	}
    
    function getEnvironment()
	{
        jimport('joomla.environment.browser'); 
        
        $app     =& JFactory::getApplication();
        $browser =  JBrowser::getInstance();
        
        $env->joomlaTemplate = $app->getTemplate();
        $env->browserName    = $browser->getBrowser();
        
        return $env;
    }

}