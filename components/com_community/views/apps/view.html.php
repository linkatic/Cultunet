<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
jimport( 'joomla.utilities.arrayhelper');

class CommunityViewApps extends CommunityView
{
	/**
	 * Show the application edit page
	 */	 	
	function edit()
	{
		if(!$this->accessAllowed('registered')){
			return;
		}
		
		$my 	    = CFactory::getUser();
		$appsModel	=& CFactory::getModel('apps');
		
		//------ pre-1.8 ------//
		// Get coreapps
		$coreApps		= $appsModel->getCoreApps();
		for( $i = 0; $i < count($coreApps); $i++)
		{
			$appInfo	= $appsModel->getAppInfo( $coreApps[$i]->apps );

			// @rule: Try to get proper app id from #__community_users table first.
			$id		= $appsModel->getUserApplicationId( $coreApps[$i]->apps , $my->id );

			// @rule: If there aren't any records, we need to get it from #__plugins table.
			if( empty( $id ) )
			{
				$id			= $appsModel->getPluginId( $coreApps[$i]->apps , null , true );
			}
			
			$coreApps[$i]->id			= $id;
			$coreApps[$i]->title		= $appInfo->title;
			$coreApps[$i]->description	= $appInfo->description;
			$coreApps[$i]->name          = $coreApps[$i]->apps;
			//$coreApps[$i]->coreapp		= $params->get( 'coreapp' );
			
			//Get application favicon
			if( JFile::exists( JPATH_ROOT . DS . 'plugins' . DS . 'community' . DS . $coreApps[$i]->apps . DS . 'favicon_64.png' ) )
			{
				$coreApps[$i]->appFavicon	= rtrim(JURI::root(),'/') . '/plugins/community/' . $coreApps[$i]->apps . '/favicon_64.png';
			}
			else
			{
				$coreApps[$i]->appFavicon	= rtrim(JURI::root(),'/') . '/components/com_community/assets/app_favicon.png';
			}
		}
		//------ pre-1.8 ------//
		
		// Get user apps
		$userApps = $appsModel->getUserApps($my->id);

		$appItems = array();
		$appItems['sidebar-top-core'] = '';
		$appItems['sidebar-bottom-core'] = '';
		$appItems['sidebar-top'] = '';
		$appItems['sidebar-bottom'] = '';
		$appItems['content'] = '';
		$appItems['content-core'] = '';
		
		$appsList	= array();
		
		for( $i=0; $i<count($userApps); $i++ )
		{
			// TODO: getUserApps should return all this value already
			$id			= $appsModel->getPluginId( $userApps[$i]->apps , null , true );
			$appInfo	= $appsModel->getAppInfo( $userApps[$i]->apps );			
			$params		= new JParameter( $appsModel->getPluginParams( $id , null ) );			
			$isCoreApp  = $params->get( 'coreapp' );

			$userApps[$i]->title       = isset( $appInfo->title ) ? $appInfo->title : '';
			$userApps[$i]->description = isset( $appInfo->description ) ? $appInfo->description : '';
			$userApps[$i]->coreapp     = $isCoreApp; // Pre 1.8x
			$userApps[$i]->isCoreApp   = $isCoreApp;
			$userApps[$i]->name        = $userApps[$i]->apps;

			//------ pre-1.8 ------//
			if( JFile::exists( JPATH_ROOT . DS . 'plugins' . DS . 'community' . DS . $userApps[$i]->apps . DS . 'favicon_64.png' ) )
			{
				$userApps[$i]->appFavicon	= rtrim(JURI::root(),'/') . '/plugins/community/' . $userApps[$i]->apps . '/favicon_64.png';
			} else {
				$userApps[$i]->appFavicon	= rtrim(JURI::root(),'/') . '/components/com_community/assets/app_favicon.png';
			}
			//------ pre-1.8 ------//
			
			if( JFile::exists( JPATH_ROOT . DS . 'plugins' . DS . 'community' . DS . $userApps[$i]->apps . DS . 'favicon.png' ) )
			{
				$userApps[$i]->favicon['16'] = rtrim(JURI::root(),'/') . '/plugins/community/' . $userApps[$i]->apps . '/favicon.png';
			} else {
				$userApps[$i]->favicon['16'] = rtrim(JURI::root(),'/') . '/components/com_community/assets/app_favicon.png';
			}
			
			$position = $userApps[$i]->position . (($isCoreApp) ? '-core' : '');
			$appsList[ $position ][]	= $userApps[ $i ];  
		}

		foreach( $appsList as $position => $apps )
		{
			$tmpl = new CTemplate();
			$tmpl->set('apps'     , $apps );
			$tmpl->set('itemType', 'edit');
			
			$appItems[ $position ]	.= $tmpl->fetch( 'application.item');
		}
		
		// Get available apps for comparison
		$appsModel		=& CFactory::getModel('apps');
		$apps			= $appsModel->getAvailableApps(false);		
		$appsname		= array();
		$availableApps 	= array();
		if(!empty($apps))
		{
			foreach($apps as $data)
			{
				array_push($availableApps, $data->name);
			}
		}		

		// Check if apps exist, if not delete it.
		$obsoleteApps = array();
		$obsoleteApps = array_diff($appsname, $availableApps);
		if(!empty($obsoleteApps))
		{
			foreach($obsoleteApps as $key=>$obsoleteApp)
			{				
				$appRecords = $appsModel->checkObsoleteApp($obsoleteApp);			
				
				if(empty($appRecords))
				{
					if($appRecords==NULL)
					{
						$appsModel->removeObsoleteApp($obsoleteApp);
					}
					
					unset($userApps[$key]);
				}
			}		
			$userApps = array_values($userApps);
		}

		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('CC MY APPS'));
		$this->addPathway( JText::_('CC MY APPS') );
		$this->showSubMenu(); // pre-1.8
		
		CFactory::load( 'libraries' , 'window' );
		CWindow::load();
		CAssets::attach('/assets/jquery.tablednd_0_5.js', 'js'); // pre-1.8
		CAssets::attach('/assets/ui.core.js', 'js');
		CAssets::attach('/assets/ui.sortable.js', 'js');
		CAssets::attach('/assets/applayout.js', 'js');
		
		$tmpl	= new CTemplate();
		$tmpl->set('coreApplications' , $coreApps ); // pre-1.8
		$tmpl->set('applications'	  , $userApps ); // pre-1.8
		$tmpl->set('appItems'		  , $appItems );
		
		echo $tmpl->fetch( 'applications.edit' );
	}
	
	
	/**
	 * Browse all available apps
	 */	 	
	function browse($data)
	{
		$this->addPathway( JText::_('CC BROWSE APPS') );
		
		// Load window library
		CFactory::load( 'libraries' , 'window' );
		
		// Load necessary window css / javascript headers.
		CWindow::load();
		
		$mainframe =& JFactory::getApplication();
		$my		= CFactory::getUser();
		
		
		$pathway 	=& $mainframe->getPathway();

		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('CC BROWSE APPS'));

		// Attach apps-related js
		$this->showSubMenu();
	
		// Get application's favicon
		$addedAppCount	= 0;
		foreach( $data->applications as $appData )
		{	
			if( JFile::exists( JPATH_ROOT . DS . 'plugins' . DS . 'community' . DS . $appData->name . DS . 'favicon_64.png' ) )
			{
				$appData->appFavicon	= rtrim(JURI::root(),'/') . '/plugins/community/' . $appData->name . '/favicon_64.png';
			}
			else
			{
				$appData->appFavicon	= rtrim(JURI::root(),'/') . '/components/com_community/assets/app_favicon.png';
			}
			
			// Get total added applications
			$addedAppCount	= $appData->added == 1 ? $addedAppCount+1 : $addedAppCount;
		}
		
		$tmpl	= new CTemplate();
		$tmpl->set( 'applications' , $data->applications );
		$tmpl->set( 'pagination' , $data->pagination );
		$tmpl->set( 'addedAppCount' , $addedAppCount );

		echo $tmpl->fetch( 'applications.browse' );
	}
	
	function ajaxBrowse($data)
	{
		$mainframe =& JFactory::getApplication();
		$my		= CFactory::getUser();

		// Get application's favicon
		$addedAppCount	= 0;

		foreach( $data->applications as $appData )
		{	
			if( JFile::exists( JPATH_ROOT . DS . 'plugins' . DS . 'community' . DS . $appData->name . DS . 'favicon_64.png' ) )
			{
				$appData->favicon['64'] = rtrim(JURI::root(),'/') . '/plugins/community/' . $appData->name . '/favicon_64.png';
			}
			else
			{
				$appData->favicon['64'] = rtrim(JURI::root(),'/') . '/components/com_community/assets/app_favicon.png';
			}
			// Get total added applications
			//$addedAppCount	= $appData->added == 1 ? $addedAppCount+1 : $addedAppCount;
		}

		$tmpl = new CTemplate();
		$tmpl->set('apps'     , $data->applications );
		$tmpl->set('itemType', 'browse');

		echo $tmpl->fetch('application.item');		
	}

	function _addSubmenu()
	{
		$this->addSubmenuItem('index.php?option=com_community&view=apps', JText::_('CC MY APPS') );
		$this->addSubmenuItem('index.php?option=com_community&view=apps&task=browse', JText::_('CC BROWSE APPS') );
	}

	function showSubmenu(){
		$this->_addSubmenu();
		parent::showSubmenu();
	}
}
