<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');
 
//JRequest::setVar( 'no_html', 1 ,'REQUEST');
//test 
// we detech here whether the user from iphone or normal web.
// if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod'))
// {
// 	$document =& JFactory::getDocument();
// 	$document->setMetaData( 'viewport', 'width=device-width, initial-scale=1, user-scalable=no' );
// 	
// 	JRequest::setVar('tmpl', 'component');
// 	JRequest::setVar('format', 'iphone');
// 
// }

// During ajax calls, the following constant might not be called
if(!defined('JPATH_COMPONENT'))
{
	define('JPATH_COMPONENT', dirname(__FILE__));
}

require_once ( JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'defines.community.php');

// Require the base controller
require_once (COMMUNITY_COM_PATH.DS.'libraries'.DS.'error.php');
require_once (COMMUNITY_COM_PATH.DS.'controllers'.DS.'controller.php');
require_once (COMMUNITY_COM_PATH.DS.'libraries'.DS.'apps.php' );
require_once (COMMUNITY_COM_PATH.DS.'libraries'.DS.'core.php');
require_once (COMMUNITY_COM_PATH.DS.'libraries'.DS.'template.php');
require_once (COMMUNITY_COM_PATH.DS.'views'.DS.'views.php');
require_once (COMMUNITY_COM_PATH.DS.'helpers'.DS.'url.php');
require_once (COMMUNITY_COM_PATH.DS.'helpers'.DS.'ajax.php');
require_once (COMMUNITY_COM_PATH.DS.'helpers'.DS.'time.php');
require_once (COMMUNITY_COM_PATH.DS.'helpers'.DS.'owner.php');
require_once (COMMUNITY_COM_PATH.DS.'helpers'.DS.'azrul.php');
require_once (COMMUNITY_COM_PATH.DS.'helpers'.DS.'string.php');
require_once (COMMUNITY_COM_PATH.DS.'events'.DS.'router.php');


JTable::addIncludePath( COMMUNITY_COM_PATH . DS . 'tables' );

jimport('joomla.utilities.date');

//@todo: only load related language file
$view	= JRequest::getCmd('view', 'frontpage');
$task 	= JRequest::getCmd('task', '');
$tmpl	= JRequest::getCmd( 'tmpl' , '' ,'GET' );

// Run scheduled task and exit.
if (JRequest::getCmd('task', '', 'GET') == 'cron')
{
	CFactory::load('libraries', 'cron');

	$cron = new CCron();
	$cron->run();
	exit;
}

$config	= CFactory::getConfig();
if ($config->get('sendemailonpageload'))
{
	CFactory::load('libraries', 'cron');

	$cron = new CCron();
	$cron->sendEmailsOnPageLoad();
}

// If the task is 'azrul_ajax', it would be an ajax call and core file
// should not be processing it.
if($task != 'azrul_ajax')
{
	jimport('joomla.filesystem.file');
	
	$mainframe 	=& JFactory::getApplication();
		
	//check if all zend package is installed.
	for($i=1; $i<=10; $i++)
	{
		$file = JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'Zend'.DS.'zend_install_validation'.DS.'zend.package'.$i.'.ini';		
		if(!JFile::exists($file))
		{
			$message 		= JText::sprintf('CC ZEND PLUGIN PACK MISSING', $i);
			$instruction	= JText::sprintf('CC ZEND PLUGIN PACK MISSING INSTRUCTION', $i, 'http://www.jomsocial.com', 'http://www.jomsocial.com');
			
			$mainframe->enqueueMessage($message, 'error');
			echo '<div>'.$instruction.'</div>';
			return;
		}
	}
	
	//check if zend plugin is enalble.
	$zend = JPluginHelper::getPlugin('system', 'zend');	
	if(empty($zend))
	{
		$message 		= JText::_('CC ZEND PLUGIN DISABLED');
		$instruction 	= JText::_('CC ZEND PLUGIN DISABLED INSTRUCTION');
		
		$mainframe->enqueueMessage($message, 'error');
		echo '<div>'.$instruction.'</div>';
		return;
	}
	
//	$time_start = microtime(true);

	// Trigger system start
	if(function_exists('xdebug_memory_usage')) {
		$mem = xdebug_memory_usage();
		$tm	 = xdebug_time_index();
		
		$db = JFactory::getDBO();
		$db->debug(1);
	}
	
	require_once( JPATH_COMPONENT . DS . 'libraries' . DS . 'apps.php' );
	$appsLib	=& CAppPlugins::getInstance();
	$appsLib->loadApplications();
	
	// Only trigger applications and set active URI when needed
	if( $tmpl != 'component' )
	{
		
		$args = array();
		$appsLib->triggerEvent( 'onSystemStart' , $args );

		// Set active URI
		CFactory::setCurrentURI();
	}

	// Normal call
	// Component configuration
	$config = array('name'=>JString::strtolower(JRequest::getCmd('view', 'frontpage')));

	// Create the controller
	$viewController = JString::strtolower($config['name']);
	if( JFile::exists( JPATH_COMPONENT.DS.'controllers'.DS.$viewController.'.php' ) )
	{
		// If the controller is one of our controller, include the file
		// If not, it could be other 3rd party controller. Do not throw error message yet
		require_once (JPATH_COMPONENT.DS.'controllers'.DS.$viewController.'.php');	
	}
	
	$viewController = JString::ucfirst($viewController);
	$viewController = 'Community'.$viewController.'Controller';
	
	
	// Trigger onBeforeControllerCreate (pass controller name by reference to allow override)
	$args 	= array();
	$args[]	= &$viewController;

	$results = $appsLib->triggerEvent( 'onBeforeControllerCreate' , $args );
	
	// make sure none of the $result is false
	// If true, then one of the plugin is trying to override the controller creation
	// since we could only create 1 controller, we will pick the very first one only
	// plugin trigger function will return true if plugin want to intercept it
	if(!empty($results) && ( in_array(true, $results) ) ) 
	{
		// 3rd party override used
		// @todo: use Reflection API to ensure that the class actually implement
		// our controller interface to avoid error
	} 
	
	if( !class_exists($viewController) )
	{
		JError::raiseError(500, 'Controller '. $viewController . ' not found!');
	}
	
	$controller = new $viewController($config);

	// Perform the Request task
	$controller->execute(JRequest::getCmd('task', ''));

	$jConfig	=& JFactory::getConfig();
	// Some hosting providers has xdebug installed for debugging purposes. We really shouldn't show this
	// on live site unless they turn on debugging mode.
	if(function_exists('xdebug_memory_usage') && $jConfig->getValue('debug') )
	{
		$memNow = xdebug_memory_usage();
		$db = JFactory::getDBO();
		$db->debug(1);
		
		echo '<div style="clear:both">&nbsp;</div><pre>';
		echo 'Start usage : ' . cConvertMem($mem) . '<br/>';
		echo 'End usage   : ' . cConvertMem($memNow) . '<br/>';
		echo 'Mem usage   : ' . cConvertMem($memNow - $mem) . '<br/>';
		echo 'Peak mem    : ' . cConvertMem(xdebug_peak_memory_usage()). '<br/>';
		echo 'Time        : ' . (xdebug_time_index() - $tm) . '<br/>';
		echo 'Query       : ' . $db->getTicker();
		echo '</pre>';
		
		// Log average page load
		jimport('joomla.filesystem.file');
		$content = JFile::read(COMMUNITY_COM_PATH . DS . 'access.log');
		$params = new JParameter($content);
		
		$today = strftime('%Y-%m-%d');
		$loadTime = $params->get($today, 0);
		if($loadTime > 0){
			$loadTime = ($loadTime + (xdebug_time_index() - $tm)) / 2; 
		}
		else
		{
			$loadTime = (xdebug_time_index() - $tm);
		}
		$params->set($today, $loadTime);
		JFile::write(COMMUNITY_COM_PATH . DS . 'access.log', $params->toString());
		
	}
	echo getJomSocialPoweredByLink();
	
	
//	 getTriggerCount
//	$appLib = CAppPlugins::getInstance();
//	echo 'Trigger count: '. $appLib->triggerCount . '<br/>';
//	$time_end = microtime(true);
//	$time = $time_end - $time_start;
//	echo $time;
}


/**
 * Entry poitn for all ajax call
 */
function communityAjaxEntry($func, $args = null)
{
	// For AJAX calls, we need to load the language file manually.
	$lang =& JFactory::getLanguage();
	$lang->load( 'com_community' );
	
	$response = new JAXResponse();
	$output = '';
	
	require_once( JPATH_COMPONENT . DS . 'libraries' . DS . 'apps.php' );
	$appsLib	=& CAppPlugins::getInstance();
	$appsLib->loadApplications();
	$triggerArgs = array();
	$triggerArgs[] = $func;
 	$triggerArgs[] = $args;
	$triggerArgs[] = $response;
	
	$results = $appsLib->triggerEvent( 'onAjaxCall' , $triggerArgs );
	if( in_array( false, $results ) )
	{
		$output		= $response->sendResponse();
	} 
	else 
	{
	
		$calls		= explode( ',' , $func );
	
		if(is_array($calls) && $calls[0] == 'plugins')
		{
			// Plugins ajax calls go here
			$func		= $_REQUEST['func'];
	
			// Load CAppPlugins
			if(!class_exists('CAppPlugins'))
			{
				require_once( JPATH_COMPONENT . DS . 'libraries' . DS . 'apps.php');
			}
	
			$apps		=& CAppPlugins::getInstance();
			$plugin  	=& $apps->get($calls[1]);
			$method		= $calls[2];
	
			// Move the $response object to be the first in the array so that the plugin knows
			// the first argument is always the JAXResponse object
			array_unshift($args, $response);
	
			// Call plugin AJAX method. Caller method's should only return the JAXResponse object.
			$response	= call_user_func_array( array($plugin, $method) , $args);

			$output		= $response->sendResponse();
		}
		else
		{
			// Built-in ajax calls go here
			$config		= array();
			$func		= $_REQUEST['func'];
			$callArray	= explode(',', $func);
			$viewController = JString::strtolower($callArray[0]);
	
			if(($viewController == 'videos' || $viewController == 'photos' || $viewController == 'groups' ) && COMMUNITY_FREE_VERSION ) 
			{				
				$tmpl	= new CTemplate();
				$output	= $tmpl->fetch( 'freeversion.ajax' );
				$response->addAssign('cWindowContent', 'innerHTML', $output );
				$response->addScriptCall('cWindowResize' , 500 );
				return $response->sendResponse();
			}
			
			require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'controllers' . DS . $viewController . '.php' );
			$viewController = JString::ucfirst($viewController);
			$viewController	= 'Community'.$viewController.'Controller';
			$controller		 = new $viewController($config);
	
			// Perform the Request task
			$output = call_user_func_array(array(&$controller, $callArray[1]), $args);
		}
	}
	return $output;
}

function cConvertMem($size)
{
	$unit=array('b','Kb','Mb','Gb','Tb','Pb');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

