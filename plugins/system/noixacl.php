<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.installer.installer' );
// including a Adapter LIB
$adapterLibFile = JPATH_ADMINISTRATOR.DS."components".DS."com_noixacl".DS."libraries".DS."adapter.php";
if( file_exists($adapterLibFile) ){
	include $adapterLibFile;
}
/**
 * Example system plugin
 */
class plgSystemNoixACL extends JPlugin
{
//rrr
	var $globArrMultiGroups;
	/**
	 * Constructor
	 */
	public function plgSystemNoixACL( &$subject, $config )
	{
		parent::__construct( $subject, $config );
	}
	
	/**
	 * Do load rulles and start checking function
	 */
	public function onAfterRoute()
	{
		$option = JRequest::getCMD('option');
		
		$adapterLibFile = JPATH_ADMINISTRATOR.DS."components".DS."com_noixacl".DS."libraries".DS."adapter.php";
		
		//return false if not exists file
		if( !file_exists($adapterLibFile) ){
			return false;
		}
		
		if( $option == 'com_installer' || $option == 'com_noixacl'){
			$pathFile = JPATH_ADMINISTRATOR.DS."components".DS."com_noixacl".DS."libraries".DS.'installer'.DS.'adapters'.DS.'adapter.php';
			if( file_exists($pathFile) ){
				require_once $pathFile;
				// Get an installer instance
				$installer =& JInstaller::getInstance();
				$adapterInstaller = new JInstallerAdapter($installer);
				$installer->setAdapter('adapter',$adapterInstaller);
			}
		}
		
		
		
		$db = JFactory::getDBO();
		$app =& JFactory::getApplication();
		$user = Jfactory::getUSER();
		$acl =& JFactory::getACL();

		//if user not logged return false;
		if( $user->id == 0 ){
			return false;
		}
		
		//geting usertype from user
		$arrMultiGroups[] = $user->usertype;
		$arrUserGroupId = array( $user->gid );
		
		//get multigrop names if user have it
		$sqlGetMultigroups = "SELECT grp.name, grp.id FROM #__core_acl_aro_groups as grp, #__noixacl_multigroups multigrp WHERE grp.id = multigrp.id_group AND multigrp.id_user = {$user->id}";
		$db->setQuery( $sqlGetMultigroups );
		$multiGroups = $db->loadObjectList();

		if( !empty($multiGroups) ){
			foreach($multiGroups as $mgrp){
				$arrMultiGroups[] = $mgrp->name;
				$arrUserGroupId[] = $mgrp->id;
			}
		}
//rrr
		$this->globArrMultiGroups = $arrMultiGroups;
		
		
		

		//get access level
		$groupList = implode(',', $arrUserGroupId);
		$query = "SELECT id_levels FROM #__noixacl_groups_level WHERE id_group IN ({$groupList})";
		$db->setQuery( $query );
		$glevels = $db->loadObjectList();

		if( !empty($glevels) ){
			foreach($glevels as $glevel){
				$arrUserLevels = explode(',',$glevel->id_levels);
				if( !empty($arrUserLevels) ){
					foreach($arrUserLevels as $groupLevel)
					{
						if( $groupLevel > $user->get('aid') ){
							$user->aid = $groupLevel;
						}
					}
				}
			}
		}
		
		
		
        //geting rules from multigroups
//		$acl_sql = "SELECT aco_section,aco_value,aro_section,aro_value,axo_section,axo_value FROM #__noixacl_rules WHERE ACO_VALUE<>'BLOCK' AND aro_section = 'users' AND aro_value IN ('". implode("','",$arrMultiGroups) ."')";
		$acl_sql = "SELECT aco_section,aco_value,aro_section,aro_value,axo_section,axo_value FROM #__noixacl_rules WHERE aro_section = 'users' AND aro_value IN ('". implode("','",$arrMultiGroups) ."')";
		$db->setQuery( $acl_sql );
		$rulles = $db->loadObjectList();

		if( count($rulles) > 0 ){
			/**
			 * Adding a rule to joomlaACL
			 */
			foreach($rulles as $r){
				$acl->addACL( $r->aco_section, $r->aco_value, $r->aro_section, $user->usertype, $r->axo_section, $r->axo_value );
			}
		}
        $this->controlAdapterAccess();
	}

	public function controlAdapterAccess()
	{
		//instance application
		$app =& JFactory::getApplication();
		//get component
		$option = JRequest::getCMD('option');
		//instance adapters lib
		$adapterControl = new Adapters();
		//get name of application
		$applicationName = $app->getName();
		
		$this->redirectAdminComUsers();
		
		//verify if access a component
		if( !empty($option) ){
			
			//geting a Adapter
			$myAdapter = $adapterControl->getAdapter($option);
			$this->executeAdapterPlugin($myAdapter);
			
			if( $applicationName == 'site' ){
				//get Itemid
				$Itemid = JRequest::getInt('Itemid');
				
				if( $Itemid > 0 ){
					$myAdapter = $adapterControl->getAdapter('com_menus');
					$this->executeAdapterPlugin($myAdapter);
				}
			}
		}
		
		//get all adapters
		$listAdapters = $adapterControl->listAdapters();
		if( !empty( $listAdapters ) ){
			foreach($listAdapters as $adapterObject){
				if( array_key_exists("pluginExecution", $adapterObject->xmlData) && $adapterObject->xmlData["pluginExecution"] == 'independent'){
					$this->executeAdapterPlugin($adapterObject,false);
				}
			}
		}
	}
	
	public function redirectAdminComUsers()
	{
		//instance application
		$app =& JFactory::getApplication();
		//get component
		$option = JRequest::getCMD('option');
		//get name of application
		$applicationName = $app->getName();
		
		//verify if access a component
		if( !empty($option) ){
			//if try access com_users you go redirect to noixACL users
			if($option == 'com_users' && $applicationName == 'administrator'){
				//get task execution
				$task = JRequest::getCMD('task');
				//url to recdirect
				$urlUsers = 'index.php?option=com_noixacl&controller=aclusers';

				if( $task == 'edit' ){
					$cid = JRequest::getVar('cid');
					$urlUsers .= '&task=edit&cid[]='.intval($cid[0]);
				}
				
				$app->redirect($urlUsers,"");
				return false;
			}
		}
	}
	
	public function executeAdapterPlugin($myAdapter,$checkRule = true)
	{
		//instance application
		$app =& JFactory::getApplication();
		//get component
		$option = JRequest::getCMD('option');
		//instance adapters lib
		$adapterControl = new Adapters();
		//get name of application
		$applicationName = $app->getName();
		
		if( is_object($myAdapter) ){
			//include file
			if( !file_exists($myAdapter->xmlData['pathPlugin']) ){
				JError::raiseError('noixACL',JText::_('Plugin adapter file not exists'));
				return false;
			}
			
			//include adapter plugin file
			// MIKE CHANGE include to include_once
			include_once $myAdapter->xmlData['pathPlugin'];

			//instance a plugin
			$instancePlugin = new $myAdapter->xmlData['plugin'];
			//call method same name of application in plugin of adapter
			if( method_exists($instancePlugin,$applicationName) ){
				//get params from
				$pluginParams = $instancePlugin->$applicationName();
				if( $checkRule ){
					//checking rulle access
					//rrr
					if ($this->globArrMultiGroups) $pluginParams['groups'] = $this->globArrMultiGroups;

					$canAccess = $adapterControl->checkRule($applicationName,$myAdapter,$pluginParams);
					/**
					 * block access
					 */
					if( !$canAccess ){
						// MIKE ADDED code for redirect instead of errorpage
						// commented raiserror line
						global $mainframe;

						if ($applicationName='com_content'&&JRequest::getCMD('task')=='apply')
							$mainframe->redirect('index.php?option=com_content&sectionid='.JRequest::getVar( 'redirect', JRequest::getVar( 'sectionid', 0, '', 'int' ), 'post', 'int' ).'&task=edit&cid[]='.JRequest::getVar( 'cid', array(0), '', 'array' ), JText::_('You dont have permission to access'));
						else
							$mainframe->redirect($_SERVER['HTTP_REFERER'], JText::_('You dont have permission to access'));
						//JError::raiseError('noixACL',JText::_('You dont have permission to access'));
						return false; 
					}
				}
			}
		}
	}
	public function onAfterDispatch()
	{
		$contentpluginFile = JPATH_ADMINISTRATOR.DS."components".DS."com_noixacl".DS."adapters".DS."content".DS."plugin.php";
		if( file_exists($contentpluginFile ) ){
			include_once $contentpluginFile ;
			//instance application
			$app =& JFactory::getApplication();
			//get component
			$option = JRequest::getCMD('option');
			//get name of application
			$applicationName = $app->getName();
			//get task
			$task = JRequest::getCMD('task');
	
			if ($option=='com_content') {
				$instancePlugin = new PluginContent;
				$applicationName = "after".$applicationName;
				if( method_exists($instancePlugin,$applicationName) )
					$instancePlugin->$applicationName();
			}
		}	
	}
}