<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class CommunityAppsController extends CommunityBaseController
{
	var $_name = "Application";
	var $_icon = 'apps';
	var $_pagination='';
	
	public function display()
	{
		$appsView 	=& CFactory::getView('apps');
		echo $appsView->get('edit');
	}
	
	/**
	 * Browse all available application in the system
	 */	 	
	public function browse()
	{
		// Get the proper views and models
		$view	 	=& CFactory::getView('apps');
		$appsModel	=& CFactory::getModel('apps');
		$my			= CFactory::getUser();
		$data		= new stdClass();
		
		// Check permissions
		if($my->id == 0)
		{
			return $this->blockUnregister();
		}
		
		// Get the application listing
		$apps		= $appsModel->getAvailableApps();

		for( $i = 0; $i < count( $apps ); $i++ )
		{
			$app		=& $apps[$i];
			$app->title = $app->title;
			$app->added	= $appsModel->isAppUsed( $my->id , $app->name ) ? true : false;
		}

		$data->applications	= $apps;
		$data->pagination	=& $appsModel->getPagination();
		
		echo $view->get( __FUNCTION__ , $data );
	}

	/**
	 *	Displays the application author info which is fetched from the manifest / .xml file
	 *	
	 *	@params	$appName	String	Application element name	 	 
	 */	 	
	public function ajaxShowAbout($appName)
	{
		$my				= CFactory::getUser();
		
		// Check permissions
		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}

		$objResponse   = new JAXResponse();

		$appLib =& CAppPlugins::getInstance();
		$html = $appLib->showAbout($appName);
		
		// Change cWindow title
		$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC ABOUT APPLICATION TITLE'));
		$objResponse->addAssign('cWindowContent', 'innerHTML', $html);
		$objResponse->addScriptCall('cWindowResize', 150);
		
		return $objResponse->sendResponse();
	}
	
	/**
	 * Save Profile ordering
	 */	 	
	public function ajaxSaveOrder($newOrder)
	{
		// Check permissions
		$my =& JFactory::getUser();
		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}

		$objResponse = new JAXResponse();

		$newOrder = explode('&', $newOrder);
				
		$appsModel =& CFactory::getModel('apps');
		$appsModel->setOrder($my->id, $newOrder);

		$objResponse->addScriptCall('joms.editLayout.doneSaving');
		return $objResponse->sendResponse();
	}

	/**
	 * Store new apps positions in database
	 */	 	
	public function ajaxSavePosition($position, $newOrder)
	{
			// Check permissions
		$my				=& JFactory::getUser();

		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}
		
		$objResponse	= new JAXResponse();
		if(!empty($newOrder))
		{
			
			$appsModel		=& CFactory::getModel('apps');
			$ordering		= array();
			$newOrder		= explode('&', $newOrder);
			$i 				= 0;
	
			foreach($newOrder as $order)
			{
				$data = explode('=', $order);
				$ordering[$data[1]]= $i;
				$i++;
			}
			
			$appsModel->setOrdering($my->id, $position, $ordering);
		}
		
		$objResponse->addScriptCall('void', 0);

		return $objResponse->sendResponse();
	}

	/**
	 *	Ajax method to display the application settings
	 *
	 *	@params	$id	Int	Application id.
	 *	@params	$appName	String	Application element
	 **/	 	 	 	 	
	public function ajaxShowSettings($id, $appName)
	{
		// Check permissions
		$my				=& JFactory::getUser();

		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}

		$objResponse   = new JAXResponse();
				
		$appsModel	=& CFactory::getModel('apps');
		$lang		=& JFactory::getLanguage();
		$lang->load( 'plg_' . JString::strtolower( $appName ) , JPATH_ROOT . DS . 'administrator' );
		
		$xmlPath	= JPATH_PLUGINS . DS . 'community' . DS . $appName . DS.'config.xml';
		jimport( 'joomla.filesystem.file' );
		
		if( JFile::exists($xmlPath) )
		{
			$paramStr = $appsModel->getUserAppParams($id);
			$params = new JParameter( $paramStr, $xmlPath );
			$paramData = (isset($params->_xml['_default']->param)) ? $params->_xml['_default']->param : array();		
			
			$html  = '<form method="POST" action="" name="appSetting" id="appSetting">';
			$html .= $params->render();
			$html .= '<input type="hidden" value="'.$id.'" name="appid"/>';
			$html .= '<input type="hidden" value="'.$appName.'" name="appname"/>';
			$html .= '</form>';    
			
			$objResponse->addAssign('cWindowContent', 'innerHTML', $html);

			if(! empty($paramData))
			{
				$objResponse->addScriptCall('cWindowActions', '<input onclick="joms.apps.saveSettings()" type="submit" value="' . JText::_('CC APPLICATION BTN SAVE') . '" class="button" name="Submit"/>');
			}
			
			$parser =& JFactory::getXMLParser('Simple');
			$parser->loadFile($xmlPath);
			$document =& $parser->document;
			$element  =& $document->getElementByPath('height');
			$height = (!empty($element))? $element->data() : 0;		

			if($height)
			{
				$windowHeight = $height;
			}
			else
			{
				$windowHeight = 100 + ($params->getNumParams() * 30);
				$windowHeight = ($windowHeight>=300)? 300 : $windowHeight;
			}
		}
		else
		{
			$objResponse->addAssign('cWindowContent', 'innerHTML', '<div class-"ajax-notice-apps-configure">'.JText::_('CC APPLICATION AJAX NO CONFIG').'</div>');
			$windowHeight = 100;
		}
				
		$objResponse->addScriptCall('cWindowResize', $windowHeight);
		$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC APPLICATION SETTINGS TITLE'));		
		return $objResponse->sendResponse();
	}
	
	/**
	 *
	 */	 	
	public function ajaxSaveSettings($postvars)
	{
		// Check permissions
		$my				=& JFactory::getUser();

		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}
		$objResponse   = new JAXResponse();
		$appsModel		=& CFactory::getModel('apps');
		
		$appName 	= $postvars['appname'];
		$id				= $postvars['appid'];

		// @rule: Test if app is core app as we need to add into the db
		$pluginId		= $appsModel->getPluginId( $appName );
		$appParam	= new JParameter( $appsModel->getPluginParams( $pluginId ) );

		if( $pluginId && $my->id != 0 && $appParam->get('coreapp') )
		{
			// Add new app in the community plugins table
			$appsModel->addApp($my->id, $appName);
			
			// @rule: For core applications, the ID might be referring to Joomla's id. Get the correct id if needed.
			$id 		= $appsModel->getUserApplicationId( $appName , $my->id );
		}

		// Make sure this is valid for current user
		if(!$appsModel->isOwned($my->id, $id))
		{
			// It could be that the app is a core app.
			
			$objResponse->addAlert('CC PERMISSION ERROR');
			return $objResponse->sendResponse();
		}
		
		$post = array();
		
		// convert $postvars to normal post 
		$pattern    = "'params\[(.*?)\]'s";
		for($i =0; $i< count($postvars); $i++)
		{
			if(!empty($postvars[$i]) && is_array($postvars[$i])){
				$key = $postvars[$i][0];
				// Blogger view
				
				preg_match($pattern, $key, $matches);
				if($matches){
					$key = $matches[1];
				}
				$post[$key] = $postvars[$i][1];
			}
		}
		
		$xmlPath = JPATH_COMPONENT.DS.'applications'.DS.$appName.DS.$appName.'.xml';
		$params = new JParameter($appsModel->getUserAppParams($id), $xmlPath);
		$params->bind($post);
		//echo $params->toString();
		
		$appsModel->storeParams($id, $params->toString());

		$objResponse->addScriptCall('cWindowHide');
		return $objResponse->sendResponse();
	}
	
	/**
	 * Show privacy options for apps
	 */	 	
	public function ajaxShowPrivacy($appName)
	{
		// Check permissions
		$my				=& JFactory::getUser();

		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}
		
		$objResponse   = new JAXResponse();
		
		//$appLib =& $this->getLibrary('apps');
		$appLib =& CAppPlugins::getInstance();
		$html = $appLib->showPrivacy($appName);
		$action = '<input onclick="joms.apps.savePrivacy()" type="submit" value="' . JText::_('CC APPLICATION BTN SAVE') . '" class="button" name="Submit"/>';
		
		// Change cWindow title
		$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC APPLICATION PRIVACY TITLE'));
		$objResponse->addAssign('cWindowContent', 'innerHTML', $html);
		$objResponse->addScriptCall('cWindowActions', $action);
		$objResponse->addScriptCall('cWindowResize', 160);

		return $objResponse->sendResponse();	
	}
	
	/**
	 * Show privacy options for apps
	 */	 	
	public function ajaxSavePrivacy($appName, $val)
	{
		// Check permissions
		$my				=& JFactory::getUser();

		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}
		$objResponse   = new JAXResponse();
		$appsModel	=& CFactory::getModel('apps');
		
		// @rule: Test if app is core app as we need to add into the db
		$pluginId		= $appsModel->getPluginId( $appName );
		$appParam	= new JParameter( $appsModel->getPluginParams( $pluginId ) );

		if( $pluginId && $my->id != 0 && $appParam->get('coreapp') )
		{
			// Add new app in the community plugins table
			$appsModel->addApp($my->id, $appName);
		}
		
		$appsModel->setPrivacy($my->id, $appName, $val);
		
		$objResponse->addScriptCall('cWindowHide');
		return $objResponse->sendResponse();
		
	}
	
	/**
	 * Remove an application from the users list.
	 *
	 * @param	$id	int	Application id
	 */
	public function ajaxRemove( $id )
	{
		// Check permissions
		$my				=& JFactory::getUser();

		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}

		$objResponse   = new JAXResponse();
		$appModel	= CFactory::getModel('apps');
		
		$name	= $appModel->getAppName($id);
		$appModel->deleteApp($my->id, $id);
		
		$theApp = $appModel->getAppInfo($name);

		CFactory::load ( 'libraries', 'activities' );
		
		$act = new stdClass();
		$act->cmd 		= 'application.remove';
		$act->actor 	= $my->id;
		$act->target 	= 0;
		$act->title		= JText::_('CC ACTIVITIES APPLLICATIONS REMOVED');
		$act->content	= '';
		$act->app		= $name;
		$act->cid		= 0;
		
		
		CActivityStream::add($act);
		
		CFactory::load( 'libraries' , 'userpoints' );		
		CUserPoints::assignPoint('application.remove');

		$objResponse->addScriptCall("joms.jQuery('#app-{$id}').remove();");
		$objResponse->addAssign('cWindowContent', 'innerHTML', '<div class-"ajax-notice-apps-removed">'.JText::_('CC APPLICATION AJAX REMOVED').'</div>' );
		return $objResponse->sendResponse();
		
	}
	
	/**
	 * Add an application for the user
	 *
	 * @param	$name	string Application name / element
	 */	 	
	public function ajaxAdd( $name )
	{
		// Check permissions
		$my				=& JFactory::getUser();

		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}

		$objResponse   = new JAXResponse();
		$appModel	= CFactory::getModel('apps');
		
		// Get List of added apps
		$apps			= $appModel->getAvailableApps();
		$addedApps		= array();
		
		for( $i = 0; $i < count( $apps ); $i++ )
		{
			$app			=& $apps[$i];
			
			if( $appModel->isAppUsed( $my->id , $app->name ) )
				$addedApps[]	= $app;
		}	
		
		if( !COMMUNITY_FREE_VERSION || ( COMMUNITY_FREE_VERSION && count($addedApps) < COMMUNITY_FREE_VERSION_APPS_LIMIT ) ){
			$appModel->addApp($my->id, $name);
			$theApp = $appModel->getAppInfo($name);
			$appId	= $appModel->getUserApplicationId( $name , $my->id );
			
			$act = new stdClass();
			$act->cmd 		= 'application.add';
			$act->actor 	= $my->id;
			$act->target 	= 0;
			$act->title		= JText::_('CC ACTIVITIES APPLICATIONS ADDED');
			$act->content	= '';
			$act->app		= $name;
			$act->cid		= $my->id;
	
			
			CFactory::load ( 'libraries', 'activities' );
			CActivityStream::add( $act);
			
			CFactory::load( 'libraries' , 'userpoints' );		
			CUserPoints::assignPoint('application.add');		
	
			// Change cWindow title
			$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC ADD APPLICATION TITLE'));
	
			$formAction	= CRoute::_('index.php?option=com_community&view=friends&task=deleteSent' , false );
			$action		= '<form name="cancelRequest" action="" method="POST">';
			$action		.= '<input type="button" class="button" name="save" onclick="joms.apps.showSettingsWindow(\'' . $appId .'\',\'' . $name . '\');" value="' . JText::_('CC BUTTON SETTINGS') . '" />&nbsp;';
			$action		.= '<input type="button" class="button" onclick="cWindowHide();return false;" name="cancel" value="'.JText::_('CC BUTTON CLOSE').'" />';
			$action		.= '</form>';
			
			$objResponse->addAssign('cWindowContent', 'innerHTML', '<div class="ajax-notice-apps-added">'.JText::_( 'CC APPLICATION AJAX ADDED' ).'</div>');
			//$objResponse->addScriptCall("joms.jQuery('#" . $name . "-status').html('<strong>" . JText::_('CC APPLICATION LIST ADDED') . "</strong>');");
			$objResponse->addScriptCall("joms.jQuery('." . $name . " .added-button').remove();");
			$objResponse->addScriptCall("joms.jQuery('." . $name . "').append('<span class=\"added-ribbon\">".JText::_('CC APPLICATION LIST ADDED')."</span>');");
			
			$objResponse->addScriptCall('cWindowActions', $action);
		}else{
			// Change cWindow title
			$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC ADD APPLICATION TITLE'));
	
			$formAction	= CRoute::_('index.php?option=com_community&view=friends&task=deleteSent' , false );
			$action		= '<form name="cancelRequest" action="" method="POST">';
			$action		.= '<input type="button" class="button" onclick="cWindowHide();return false;" name="cancel" value="'.JText::_('CC BUTTON CLOSE').'" />';
			$action		.= '</form>';
			
			$objResponse->addAssign('cWindowContent', 'innerHTML', '<div class="ajax-notice-apps-added">'.JText::_( 'CC APPLICATION AJAX ADD LIMIT REACHED' ).'</div>');
			$objResponse->addScriptCall('cWindowActions', $action);		
		}
		
		return $objResponse->sendResponse();
	}
	
	public function ajaxRefreshLayout($id, $position)
	{
		$objResponse	= new JAXResponse();
		
		$my = CFactory::getUser();
		
		$appsModel	=& CFactory::getModel('apps');
		$element = $appsModel->getAppName($id);
		$pluginId = $appsModel->getPluginId($element);
		
		$params =& JPluginHelper::getPlugin('community', JString::strtolower($element));
		$dispatcher = & JDispatcher::getInstance();
		
		$pluginClass = 'plgCommunity'.$element;
		
        //$plugin = new $pluginClass($dispatcher, (array)($params));
        $plugin	= JTable::getInstance( 'App' , 'CTable' );
		$plugin->loadUserApp($my->id, $element);
		
		
		switch($position)
		{		
			case "apps-sortable-side-top":
				$position = "sidebar-top";
				break;
			case "apps-sortable-side-bottom":
				$position = "sidebar-bottom";
				break;
			case "apps-sortable":
			default:
				$position = "content";
				break;
		}
		
		$appInfo = $appsModel->getAppInfo($element);
		
        //$plugin->setNewLayout($position);
        $plugin->postion = $position;
        
        $appsLib	=& CAppPlugins::getInstance();
        $app = $appsLib->triggerPlugin('onProfileDisplay', $appInfo->name, $my->id);

		$tmpl = new CTemplate();
		$tmpl->set( 'app' 		, $app );
		$tmpl->set( 'isOwner'	, $appsModel->isOwned($my->id, $id) );


		switch($position)
		{
			case 'sidebar-top':
			case 'sidebar-bottom':
				$wrapper = $tmpl->fetch( 'application.widget' );
				break;
			default:
				$wrapper = $tmpl->fetch( 'application.box' );
		}
		
				
 		$wrapper = str_replace("\r\n", "", $wrapper);
 		$wrapper = str_replace("\n", "", $wrapper);
 		$wrapper = addslashes($wrapper);
		
		$objResponse->addScriptCall("jQuery('#jsapp-".$id."').before('$wrapper').remove();");
		//$objResponse->addScriptCall('joms.plugin.'.$element.'.refresh()');
		
		//$refreshActions = $plugin->getRefreshAction();		
		
		return $objResponse->sendResponse();
	}
	
	public function ajaxBrowse( $position='content' )
	{
		// Get the proper views and models
		$view	 	=& CFactory::getView('apps');
		$appsModel	=& CFactory::getModel('apps');
		$my			=& CFactory::getUser();
		$data		= new stdClass();
		
		// Check permissions
		if($my->id == 0)
		{
			return $this->blockUnregister();
		}
		
		// Get the application listing
		$apps		= $appsModel->getAvailableApps( false );
		$realApps	= array();
		for( $i = 0; $i < count( $apps ); $i++ )
		{
			$app	  	   =& $apps[$i];
			if( !$appsModel->isAppUsed( $my->id , $app->name ) && $app->coreapp != '1' )
			{
				$app->position = $position;
				$realApps[]		= $app;
			}
		}

		$data->applications	= $realApps;
		
		$objResponse = new JAXResponse();
		
		$html	=  $view->get('ajaxBrowse', $data);
		$objResponse->addAssign("cwin_logo", 'innerHTML', JText::_('CC BROWSE APPS'));
		$objResponse->addAssign("cWindowContent", 'innerHTML', $html);

		$objResponse->addScriptCall('cWindowResize', 400);
		
		return $objResponse->sendResponse();
	}

	// TODO: Put back COMMUNITY_FREE constrains.
	public function ajaxAddApp($name, $position)
	{
		// Check permissions
		$my =& JFactory::getUser();
		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}

		// Add application
		$appModel = CFactory::getModel('apps');
		$appModel->addApp($my->id, $name, $position);

		// Activity stream
		$act = new stdClass();
		$act->cmd 		= 'application.add';
		$act->actor 	= $my->id;
		$act->target 	= 0;
		$act->title		= JText::_('CC ACTIVITIES APPLICATIONS ADDED');
		$act->content	= '';
		$act->app		= $name;
		$act->cid		= $my->id;

		CFactory::load ( 'libraries', 'activities' );
		CActivityStream::add( $act );
		
		// User points
		CFactory::load( 'libraries' , 'userpoints' );
		CUserPoints::assignPoint('application.add');

		// Get application
		$id	       = $appModel->getUserApplicationId( $name , $my->id );		
		$appInfo   = $appModel->getAppInfo($name);
		$params	   = new JParameter( $appModel->getPluginParams( $id , null ) );
		$isCoreApp = $params->get( 'coreapp' );		

		$app->id          = $id;
		$app->title       = isset( $appInfo->title ) ? $appInfo->title : '';
		$app->description = isset( $appInfo->description ) ? $appInfo->description : '';
		$app->isCoreApp   = $isCoreApp;
		$app->name        = $name;

		if( JFile::exists( JPATH_ROOT . DS . 'plugins' . DS . 'community' . DS . $name . DS . 'favicon.png' ) )
		{
			$app->favicon['16'] = rtrim(JURI::root(),'/') . '/plugins/community/' . $name . '/favicon.png';
		} else {
			$app->favicon['16'] = rtrim(JURI::root(),'/') . '/components/com_community/assets/app_favicon.png';
		}

		$tmpl = new CTemplate();
		$tmpl->set('apps'     , array( $app) );
		$tmpl->set('itemType', 'edit');
		$html = $tmpl->fetch('application.item');
		
		$objResponse   = new JAXResponse();
		$objResponse->addScriptCall('joms.editLayout.addAppToLayout', $position, $html);
		$objResponse->addScriptCall('cWindowHide();');
		
		return $objResponse->sendResponse();
	}
	
}
