<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

/**
 * Content Component Controller
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class CommunityBaseController extends JController
{
	var $_modelInstances = array();
	var $_libraryInstances = array();
	var $_viewInstances = array();
	var $_name;
	private $_icon = 'generic';
	var $my = null;
	
	public function getName(){
		return $this->_name;
	}
	
	public function CommunityBaseController($config = array()){
		
		if(!empty($config)){
			$this->_name = $config['name'];
		}
		$this->my =& JFactory::getUser();
	}
	
	/**
	 * Deprecated since 1.8.x
	 */	 	
	public function _notify($cmd, $from, $to, $subject, $body, $template='', $params = '')
	{
		CFactory::load( 'libraries' , 'notification' );
		return CNotificationLibrary::add( $cmd , $from , $to , $subject , $body , $template , $params );
	}
	
	/**
	 * A guest trying to use registered-only part of the system via ajax. Display
	 * a link to register	  
	 */	 	
	public function ajaxBlockUnregister()
	{
		$objResponse	= new JAXResponse();
		$uri			= CFactory::getLastURI();
		$uri			= base64_encode($uri);
		$config			=& CFactory::getConfig();
		$tmpl			= new CTemplate();
		$tmpl->set( 'uri' , $uri );
		$tmpl->set( 'config'	, $config );
		$html			= $tmpl->fetch( 'block.unregistered' );

		$objResponse->addAssign('cWindowContent', 'innerHTML', $html);	
		$objResponse->addScriptCall('cWindowResize', 260);
		return $objResponse->sendResponse();
	}
	
	/**
	 * Block user access to the  controller method.
	 */	 	
	public function blockUserAccess()
	{
		$document 	=& JFactory::getDocument();
		$document->setTitle(JText::_('CC ACCESS FORBIDDEN'));
		//echo JText::_('CC ACCESS FORBIDDEN');
		
		$tmpl = new CTemplate();														
		echo $tmpl->fetch('notice.access');
		
		return true;

	}
	
	// Block non-login mebers
	public function blockUnregister()
	{
		$my		= CFactory::getUser();
		$config	=& CFactory::getConfig();
		
		if($my->id == 0)
		{
			$config	=& CFactory::getConfig();

			$uri	= CRoute::getURI();
			$uri	= base64_encode($uri);
			$tmpl	= new CTemplate();

			$fbHtml	= '';

			if( $config->get('fbconnectkey') && $config->get('fbconnectsecret') )
			{
				CFactory::load( 'libraries' , 'facebook' );
				$facebook	= new CFacebook( FACEBOOK_LOGIN_NOT_REQUIRED );
				$fbHtml		= $facebook->getButtonHTML();
			}
			$tmpl->set( 'fbHtml' , $fbHtml );						
			$tmpl->set( 'return' , $uri );
			$tmpl->set( 'config' , $config );
			$html	= $tmpl->fetch( 'guests.denied' );
			echo $html;
			return true;
		}
		
		return false;
	}
	
	/**
	 * Return the view object, which will output the final html. The view object
	 * is a singleton
	 * 	 	 
	 * @param	string		view name
	 * #param	string		view class prefix, optional	 
	 * @param	string		document type, html/pdf/etc/
	 * @return	object		the view object	 
	 */	 	
	public function getView($viewName ='frontpage', $prefix = '', $viewType = '')
	{
		return CFactory::getView($viewName, $prefix, $viewType);
	}
	
	
	public function loadHelper($name){
		include_once(JPATH_COMPONENT.DS.'helpers'.DS.$name.'.php');
	}
	
	public function getLibrary( $name = '', $prefix = '', $config = array() ){
		if(!isset($this->_libraryInstances[$name]))
		{
			include_once(JPATH_COMPONENT.DS.'libraries'.DS.$name.'.php');
			$classname = 'CommunityLib'.$name;
			$this->_libraryInstances[$name] = new $classname;
		}
		return $this->_libraryInstances[$name];
	}
	
	//debug data
	private function _dump(&$data){
	
	    echo '<pre>';
		print_r($data);
		echo '</pre>';
		exit;
		
	}
	
	/**
	 * Return the model object, responsible for all db manipulation. Singleton
	 * 	 
	 * @param	string		model name
	 * @param	string		any class prefix
	 * @return	object		model object	 
	 */	 	
	public function getModel( $name = '', $prefix = '', $config = array() )
	{
		return CFactory::getModel($name, $prefix, $config);
	}
	
	// Our own display function
	public function display($cachable = false)
	{
		$document =& JFactory::getDocument();

		$viewType	= $document->getType();	
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
 		$viewLayout	= JRequest::getCmd( 'layout', 'default' );
		
		$view = & $this->getView( $viewName, '', $viewType);

		// Display the view
		if ($cachable) {
			global $option;
			$cache =& JFactory::getCache($option, 'view');
			$cache->get($view, 'display');
		} else {
			$view->profile();
		}
	}
	
	/**
	 * Execute a request
	 */	 	
	public function execute($task= '')
	{
		global  $mainframe;
		$document 	=& JFactory::getDocument();
		$my 		=& JFactory::getUser();
		$pathway 	=& $mainframe->getPathway();
		
		$menus		=& JSite::getMenu();
		$menuitem	=& $menus->getActive();

		$userId		= JRequest::getVar( 'userid','','GET');
		$tmpl		= JRequest::getVar( 'tmpl','' , 'REQUEST');
		$format		= JRequest::getVar( 'format' , '' , 'REQUEST' );
		$nohtml		= JRequest::getVar( 'no_html' , '' , 'REQUEST' );
		if($tmpl != 'component' && $format != 'feed'  && $format != 'ical' && $nohtml != 1 && $format != 'raw' )
		{
			// This is to fix MSIE that has incorrect user agent because jquery doesn't detect correctly.
			$ieFix		= "<!--[if IE 6]>
	<script type=\"text/javascript\">
	var jomsIE6 = true;
	</script>
	<![endif]-->";
	
			$document->addCustomTag($ieFix);
		}
		
		// Add custom css for the specific view if needed.
		$config		= CFactory::getConfig();
		$viewName	= JString::strtolower( JRequest::getVar('view' , '' , 'REQUEST' ) );
		jimport( 'joomla.filesystem.file' );
		
		if( $config->get( 'enablecustomviewcss' ) )
		{
			$template	= $config->get('template');
			$path		= COMMUNITY_COM_PATH . DS . 'templates' . DS . $template . DS . 'css';
			
			// Attach css if needed.
			if( JFile::exists( $path . DS . $viewName . '.css' ) )
			{
				CAssets::attach( JURI::root() . 'components/com_community/templates/' . $template . '/css/' . $viewName . '.css' , 'css');
			}
		}

		$env = CTemplate::getEnvironment();
		$html = '<div id="community-wrap" class="on-' . $env->joomlaTemplate . '">';
		
		// Build the component menu module
		ob_start();
		CTemplate::renderModules('js_top');
		$moduleHTML = ob_get_contents();
		ob_end_clean();
		$html .= $moduleHTML;
		
		// Build the content HTML
		ob_start();
		
		CFactory::load( 'helpers' , 'azrul' );

		$inbox =& $this->getModel('inbox');
		$unread = $inbox->countUnRead(array('user_id'=>$my->id));
		$param = array('inbox' => $unread);

		if( checkFolderExist( COMMUNITY_COM_PATH . DS . 'views' . DS . $viewName ) ) 
		{
			if(!empty($task) && method_exists($this, $task))
			{
				// Restrict the view for FREE VERSION
				$restrictedView	= restrictView( $viewName, $task );
				
				if( COMMUNITY_FREE_VERSION && $restrictedView )
				{
					ob_start();
					$output = ob_get_contents();
					ob_end_clean();
					
					$tmpl	= new CTemplate();
					$output	.= $tmpl->fetch( 'freeversion.view' );		
				}	
				else
				{
					if( method_exists( $this , '_viewEnabled') && !$this->_viewEnabled() )
					{
						echo (property_exists( $this , '_disabledMessage') ) ? $this->_disabledMessage : JText::_('Function is disabled');
					}
					else
					{
						$this->$task();
					}
					
					$output = ob_get_contents();
					ob_end_clean();
				}
			}
			else
			{
				$this->display();
				$output = ob_get_contents();
				ob_end_clean();				
			}
			
			// Build toolbar HTML
			ob_start();
			$view =& $this->getView(JRequest::getCmd('view', 'frontpage'));
			$view->showToolbar($param);
			
						
			// Header title will use view->title. If not specified, we will
			// use current page title
			$headerTitle = !empty($view->title) ? $view->title : $document->getTitle();
			$view->showHeader($headerTitle, $this->_icon );

			$header = ob_get_contents();
			ob_end_clean();
			
			$html .= $header;  
			
		}
		else
		{
			ob_start();
			$view =& $this->getView( 'frontpage' );
			$view->showToolbar($param);
			$output = ob_get_contents();
			ob_end_clean();
			
			$tmpl	= new CTemplate();
			
			$output	.= $tmpl->fetch( 'freeversion.view' );
		} 
		
		// block member to access profile owner details
		if( $this->block($userId,$viewName) ){
    
			ob_start();
			$output  = ob_get_contents();
			ob_end_clean();
			
			$tmpl	 = new CTemplate();
			$output	.= $tmpl->fetch( 'block.denied' );
			
        }
		
		// Build the component bottom module
		ob_start();
		CTemplate::renderModules('js_bottom');
		$moduleHTML = ob_get_contents();
		ob_end_clean();

		$html .= $output.$moduleHTML.'</div>';
		
		CFactory::load( 'helpers' , 'string' );
		$html	= CStringHelper::replaceThumbnails($html);
		$html = JString::str_ireplace(array('{error}','{warning}', '{info}'), '', $html);
		echo $html;
	}
	
	/**
	 * Execute ajax request
	 */	 	
	public function executeAjax($method, $ajaxArg)
	{
		if(!empty($method) && method_exists($this, $method))
		{
			$this->$method($ajaxArg);
			//call_user_func('$this->'.$method, $ajaxArg);
		}
		else
		{
			$this->display();
		}
	}
	
	/**
	 * restrict blocked user to access owner details
	 */
	public function block( $userId, $viewName )
	{
		$my		= CFactory::getUser();
		$view	= array('photos','videos','friends','profile','inbox');
		
		if( in_array($viewName,$view) && !empty($userId) && $userId!=$my->id ){
		
			$block 	=& $this->getModel('block');
	
			if( $block->getBlockStatus($my->id,$userId) )
			{
				return true;
			}
			
		}
		
		return false;
	}	
	/**
	 * restrict blocked user to access owner details
	 */	 	
	public function ajaxBlock()
	{
		$objResponse	= new JAXResponse();
		$uri			= CFactory::getLastURI();
		$uri			= base64_encode($uri);
		$config			=& CFactory::getConfig();
		$tmpl			= new CTemplate();
		$tmpl->set( 'uri' , $uri );
		$tmpl->set( 'config'	, $config );
		$html			= $tmpl->fetch( 'block.denied' );

		$objResponse->addAssign('cWindowContent', 'innerHTML', $html);	
		$objResponse->addScriptCall('cWindowResize', 260);
		return $objResponse->sendResponse();
	}	 	
	

}
