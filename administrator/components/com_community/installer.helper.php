<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
define('DBVERSION', '7');

require_once(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_community' . DS . 'defaultItems.php');

/**
 * This is the helper file of the installer
 * during the installation process 
 **/
class communityInstallerHelper
{
	var $backendPath;
	var $frontendPath;
	var $successStatus;
	var $failedStatus;
	var $notApplicable;
	var $totalStep;
	var $pageTitle;
	var $verifier;
	var $display;
	var $dbhelper;
	
	function communityInstallerHelper()
	{
		jimport( 'joomla.filesystem.folder' );
		jimport( 'joomla.filesystem.file' );
		jimport( 'joomla.filesystem.archive' );
		$this->backendPath   = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_community' . DS;
		$this->frontendPath  = JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS;
		$this->successStatus = '<div style="float:left;">.....&nbsp;</div><div style="color:#009900;">'.JText::_('CC DONE').'</div><div style="clear:both;"></div>';
		$this->failedStatus	 = '<div style="float:left;">.....&nbsp;</div><div style="color:red;">'.JText::_('CC FAILED').'</div><div style="clear:both;"></div>';
		$this->notApplicable = '<div style="float:left;">.....&nbsp;</div><div>'.JText::_('CC NOT APPLICABLE').'</div><div style="clear:both;"></div>';
		$this->totalStep = 11;
		
		$this->verifier = new communityInstallerVerifier();
		$this->display	= new communityInstallerDisplay();
		$this->dbhelper = new communityInstallerDBAction();
		$this->template	= new communityInstallerTemplate();
	}
	
	function getVersion()
	{
		$parser		=& JFactory::getXMLParser('Simple');
		
		// Load the local XML file first to get the local version
		$xml		= JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_community' . DS . 'community.xml';
		
		$parser->loadFile( $xml );
		$document	=& $parser->document;
		
		$element		=& $document->getElementByPath( 'version' );
		$version		= $element->data();
		
		return $version;
	}
	
	function getErrorMessage($error="", $extraInfo="")
	{
		switch($error)
		{
			case 0:
				$errorWarning = $error . '-' . $extraInfo . ' : ' . JText::_('CC INVALID WARN');
				break;
			case 1:
				$errorWarning = $error . '-' . $extraInfo . ' : ' . JText::_('CC MISSING FILE WARN');
				break;
			case 2:
				$errorWarning = $error . '-' . $extraInfo . ' : ' . JText::_('CC BACKEND EXTRACT FAILED WARN');
				break;
			case 3:
				$errorWarning = $error . '-' . $extraInfo . ' : ' . JText::_('CC AJAX INSTALL FAILED WARN');
				break;
			case 4:
				$errorWarning = $error . '-' . $extraInfo . ' : ' . JText::_('CC FRONTEND EXTRACT FAILED WARN');
				break;
			case 5:
				$errorWarning = $error . '-' . $extraInfo . ' : ' . JText::_('CC TEMPLATE EXTRACT FAILED WARN');
				break;
			case 6:
				$errorWarning = $error . '-' . $extraInfo . ' : ' . JText::_('CC DB PREPARATION FAILED WARN');
				break;
			case 7:
				$errorWarning = $error . '-' . $extraInfo . ' : ' . JText::_('CC DB UPDATE FAILED WARN');
				break;
			case 101:
				$errorWarning = $error . ' : ' . JText::sprintf('CC UNSUPPORTED PHP VERSION', $extraInfo);
				break;
			default:
				$error = (!empty($error))? $error : '99';
				$errorWarning = $error . '-' . $extraInfo . ' : ' . JText::_('CC UNEXPECTED ERROR WARN');
				break;
		}		
		
		ob_start();
		?>
		<div style="font-weight: 700; color: red; padding-top:10px">
			<?php echo $errorWarning; ?>
		</div>	
		<div id="communityContainer" style="margin-top:10px">
			<div><?php echo JText::_('CC INSTALL ERROR HELP'); ?></div>
			<div><a href="http://www.jomsocial.com/docs/Installation_Troubleshooting_%26_FAQ">http://www.JomSocial.com/docs/Installation_Troubleshooting_&_FAQ</a></div>
		</div>
		<?php
		$errorMsg = ob_get_contents();
		@ob_end_clean();
		
		return $errorMsg;		
	}

	function getAutoSubmitFunction()
	{
		ob_start();
		?>
		<script type="text/javascript">
		var i=3;
		
		function countDown() 
		{
			if(i >= 0)
			{
				document.getElementById("timer").innerHTML = i;
				i = i-1;
				var c = window.setTimeout("countDown()", 1000);
			}
			else 
			{
				document.installform.submit();
			}
		}
		
		window.addEvent('domready', function() { 
			countDown();
		});
		
		</script>
		<?php
		$autoSubmit = ob_get_contents();
		@ob_end_clean();
		
		return $autoSubmit;
	}
	
	function checkRequirement( $step )
	{
		$status				= true;
		$this->pageTitle 	= JText::_('CC CHECKING REQUIREMENT');
		
		$html = '';		
			
		$html .= '<div style="width:100px; float:left;">' . JText::_('CC BACKEND ARCHIVE') . '</div>';
		if(!$this->verifier->checkFileExist($this->backendPath.'backend.zip'))
		{
			$html .= $this->failedStatus;
			$status = false;
			$errorCode = '1a';
		}
		else
		{
			$html .= $this->successStatus;
		}
		
		$html .= '<div style="width:100px; float:left;">' . JText::_('CC AJAX ARCHIVE') . '</div>';
		if(!$this->verifier->checkFileExist($this->frontendPath.'azrul.zip'))
		{
			$html .= $this->failedStatus;
			$status = false;
			$errorCode = '1b';
		}
		else
		{
			$html .= $this->successStatus;
		}
		
		$html .= '<div style="width:100px; float:left;">' . JText::_('CC FRONTEND ARCHIVE') . '</div>';
		if(!$this->verifier->checkFileExist($this->frontendPath.'frontend.zip'))
		{
			$html .= $this->failedStatus;
			$status = false;
			$errorCode = '1c';
		}
		else
		{
			$html .= $this->successStatus;
		}
		
		$html .= '<div style="width:100px; float:left;">' . JText::_('CC TEMPLATE ARCHIVE') . '</div>';
		if(!$this->verifier->checkFileExist($this->frontendPath.'templates.zip'))
		{
			$html .= $this->failedStatus;
			$status = false;
			$errorCode = '1d';
		}
		else
		{
			$html .= $this->successStatus;
		}
		
		$html .= '<div style="width:100px; float:left;">' . JText::_('CC CORE PLUGIN ARCHIVE') . '</div>';
		if(!$this->verifier->checkFileExist($this->frontendPath.'ai_plugin.zip'))
		{
			$html .= $this->failedStatus;
			$status = false;
			$errorCode = '1e';
		}
		else
		{
			$html .= $this->successStatus;
		}
		
		if($status)
		{
			$autoSubmit = $this->getAutoSubmitFunction();
			//$form = $this->getInstallForm(2);
			$message = $autoSubmit.$html;
		}
		else
		{
			$errorMsg = $this->getErrorMessage(1, $errorCode);
			$message = $html.$errorMsg;
			$step = $step - 1;
		}
		
		$drawdata 			= new stdClass();
		$drawdata->message	= $message;
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('CC CHECKING REQUIREMENT');
		$drawdata->install 	= 1;
		
		return $drawdata;
	}
	
	function installBackend( $step )
	{	
		$html = '';		
			
		$html .= '<div style="width:100px; float:left;">'.JText::_('CC INSTALLATION').'</div>';
		
		$zip			= $this->backendPath . 'backend.zip';
		$destination	= $this->backendPath;
		
		if( $this->extractArchive( $zip , $destination ) )
		{
			$html .= $this->successStatus;
			$autoSubmit = $this->getAutoSubmitFunction();
			//$form = $this->getInstallForm(3);
			$message = $autoSubmit.$html;
			$status = true;
		}
		else
		{
			$html .= $this->failedStatus;
			$errorMsg = $this->getErrorMessage(2, '2');
			$message = $html.$errorMsg;
			$status = false;
			$step = $step - 1;
		}
		
		$drawdata 			= new stdClass();
		$drawdata->message	= $message;
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('CC INSTALLING BACKEND SYSTEM');
		$drawdata->install 	= 1;
		
		return $drawdata;
	}
	
	function installAjax( $step )
	{		
		$status = true;
		
		$html = '';
		
		$html .= '<div style="width:100px; float:left;">'.JText::_('CC EXTRACTION').'</div>';
		$db		=& JFactory::getDBO();
		
		if($this->azrulSystemNeedsUpdate() )
		{
			$zip			= $this->frontendPath . 'azrul.zip';
			$destination	= JPATH_PLUGINS . DS . 'system';
						
			if( $this->extractArchive( $zip , $destination ) )
			{
				$html .= $this->successStatus;
				$html .= '<div style="width:100px; float:left;">'.JText::_('CC INSTALLATION').'</div>';
				
				// Try to remove the old record.
				if($this->dbhelper->deleteTableEntry('#__plugins', 'element', 'azrul.system'))
				{
					$status = false;
					$errorCode = '3b'.$db->getErrorNum();
				}		
				
				$plugin	= new stdClass();
				$plugin->name		= 'Azrul System Mambot';
				$plugin->element	= 'azrul.system';
				$plugin->folder		= 'system';
				$plugin->published	= 1;
				$plugin->ordering	= '-1000';
				
				if(!$this->dbhelper->insertTableEntry('#__plugins', $plugin))
				{
					$status = false;
					$errorCode = '3c'.$db->getErrorNum();
				}				
			}
			else
			{
				$status = false;
				$errorCode = '3a';
			}
		}
		
		if($status)
		{
			$html .= $this->successStatus;
			$autoSubmit = $this->getAutoSubmitFunction();
			//$form = $this->getInstallForm(4);
			$message = $autoSubmit.$html;
		}
		else
		{
			$html .= $this->failedStatus;
			$errorMsg = $this->getErrorMessage(3, $errorCode);
			$message = $html.$errorMsg;
			$step = $step - 1;
		}
		
		$drawdata 			= new stdClass();
		$drawdata->message	= $message;
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('CC INSTALLING AJAX SYSTEM');
		$drawdata->install 	= 1;
		
		return $drawdata;
	}
	
	function installFrontend( $step )
	{		
		$html = '';
		
		$html .= '<div style="width:100px; float:left;">'.JText::_('CC INSTALLATION').'</div>';
			
		$zip			= $this->frontendPath . 'frontend.zip';
		$destination	= $this->frontendPath;
		
		if( $this->extractArchive( $zip , $destination ) )
		{
			$html .= $this->successStatus;
			
			if(!JFolder::exists(JPATH_ROOT . DS . 'images'. DS . 'photos') )
			{
				if( !JFolder::create( JPATH_ROOT . DS . 'images' . DS . 'photos') )
				{
					$html .= '<div>There was an error when creating the default photos folder due to permission issues. Please ensure that the folder <strong>' . JPATH_ROOT . DS . 'images' . DS . 'photos</strong> is created manually.</div>';
				}
			}
			
			if(!JFolder::exists(JPATH_ROOT . DS . 'images'. DS . 'avatar') )
			{
				if( !JFolder::create( JPATH_ROOT . DS . 'images' . DS . 'avatar') )
				{
					$html .= '<div>There was an error when creating the avatar folder due to permission issues. Please ensure that the folder <strong>' . JPATH_ROOT . DS . 'images' . DS . 'avatar</strong> is created manually.</div>';
				}
			}
			
			if(!JFolder::exists(JPATH_ROOT . DS . 'images'. DS . 'originalphotos') )
			{
				if( !JFolder::create( JPATH_ROOT . DS . 'images' . DS . 'originalphotos') )
				{
					$html .= '<div>There was an error when creating the original photos folder due to permission issues. Please ensure that the folder <strong>' . JPATH_ROOT . DS . 'images' . DS . 'originalphotos</strong> is created manually.</div>';
				}
			}
			
			$autoSubmit = $this->getAutoSubmitFunction();
			//$form = $this->getInstallForm(5);
			$message = $autoSubmit.$html;
			$status = true;
		}
		else
		{
			$html .= $this->failedStatus;
			$errorMsg = $this->getErrorMessage(4, '4');
			$message = $html.$errorMsg;
			$status = false;
			$step = $step - 1;
		}
		
		$drawdata 			= new stdClass();
		$drawdata->message	= $message;
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('CC INSTALLING FRONTEND SYSTEM');
		$drawdata->install 	= 1;
		
		return $drawdata;
	}
	
	function installTemplate( $step )
	{		
		$html = '';
		
		$html .= '<div style="width:100px; float:left;">'.JText::_('CC INSTALLATION').'</div>';
			
		$zip			= $this->frontendPath . 'templates.zip';
		$destination	= $this->frontendPath;
		
		if( $this->extractArchive( $zip , $destination ) )
		{
			$html .= $this->successStatus;
			$autoSubmit = $this->getAutoSubmitFunction();
			//$form = $this->getInstallForm(6);
			$message = $autoSubmit.$html;
			$status = true;
		}
		else
		{
			$html .= $this->failedStatus;
			$errorMsg = $this->getErrorMessage(5, '5');
			$message = $html.$errorMsg;
			$status = false;
			$step = $step - 1;
		}
		
		$drawdata 			= new stdClass();
		$drawdata->message	= $message;
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('CC INSTALLING TEMPLATE');
		$drawdata->install 	= 1;
		
		return $drawdata;
	}
	
	function prepareDatabase( $step )
	{		
		$html  = '';
		$html .= '<div style="width:100px; float:left;">'.JText::_('CC PREPARATION').'</div>';
		
		$queryResult = $this->dbhelper->createDefaultTable();
				
		if( empty($queryResult) )
		{
			$html .= $this->successStatus;
			$autoSubmit = $this->getAutoSubmitFunction();
			//$form = $this->getInstallForm(7);
			$message = $autoSubmit.$html;
			$status = true;
		}
		else
		{
			$html .= $this->failedStatus;
			$errorMsg = $this->getErrorMessage(6, $queryResult);
			$message = $html.$errorMsg;
			$status = false;
			$step = $step - 1;
		}
		
		$drawdata 			= new stdClass();
		$drawdata->message	= $message;
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('CC PREPARING DATABASE');
		$drawdata->install 	= 1;
		
		return $drawdata;
	}
	
	function updateDatabase( $step )
	{
		$db			=& JFactory::getDBO();		
		$html 		= '';			
		$status 	= true;
		$stopUpdate = false;
		$continue 	= false;
		
		// Insert configuration codes if needed
		$hasConfig = $this->dbhelper->_isExistDefaultConfig();
				
		if( !$hasConfig )
		{
			$html .= '<div style="width:150px; float:left;">'.JText::_('CC UPDATE CONFIG').'</div>';
			
			$obj			= new stdClass();
			$obj->name		= 'dbversion';
			$obj->params	= DBVERSION;
			if( !$db->insertObject( '#__community_config' , $obj ) )
			{
				$html .= $this->failedStatus;
				$status = false;
				$errorCode = '7a';
			}
			else
			{
				$default	= JPATH_BASE . DS . 'components' . DS . 'com_community' . DS . 'default.ini';
				$registry	=& JRegistry::getInstance( 'community' );
				$registry->loadFile( $default , 'INI' , 'community' );
		
				// Set the site name
				$mainframe	=& JFactory::getApplication();
				$registry->setValue( 'community.sitename' , $mainframe->getCfg('sitename') );
		
				// Set the photos path
				$photoPath	= rtrim( dirname( JPATH_BASE ) , '/' );
				$registry->setValue( 'community.photospath' , $photoPath . DS . 'images' );
		
				// Set the videos folder
				$registry->setValue( 'community.videofolder' , 'images' );
		
				// Store the config
				$obj			= new stdClass();
				$obj->name		= 'config';
				$obj->params	= $registry->toString( 'INI' , 'community' );
		
				if( !$this->dbhelper->insertTableEntry( '#__community_config' , $obj ) )
				{
					$html .= $this->failedStatus;
					ob_start();
					?>
					<div>
						Error when trying to create default configurations.
						Please proceed to the configuration and set your own configuration instead.
					</div>
					<?php
					$html .= ob_get_contents();
					@ob_end_clean();
				}
				else
				{
					$html .= $this->successStatus;
				}
			}
		}
		else
		{
			$dbversionConfig	= $this->dbhelper->getDBVersion();
			$dbversion 			= (empty($dbversionConfig))? 0 : $dbversionConfig;
			
			if($dbversion < DBVERSION)
			{
				$updater =  new communityInstallerUpdate();
				
				$html .= '<div style="width:150px; float:left;">'.JText::_('Updating DB from version '.$dbversion).'</div>';
				$updateResult = call_user_func(array( $updater , 'update_'.$dbversion ) );
				$stopUpdate = (empty($updateResult->stopUpdate))? false : true;
				
				if($updateResult->status)
				{
					$html .= $this->successStatus;
					$status = true;
					
					$dbversion++;
					
// 					$query = 'SELECT ' . $db->nameQuote( 'name' ) . ' FROM ' . $db->nameQuote( '#__community_config' ) . ' WHERE ' . $db->nameQuote( 'name' ) . '=' . $db->quote( 'dbversion' ) . ' LIMIT 1';
// 					$db->setQuery( $query );
// 					$dbversionConfig = $db->loadResult();
					
					if(($dbversionConfig===null) && ($dbversionConfig!==0))
					{
						$this->dbhelper->insertDBVersion($dbversion);
					}
					else
					{
						$this->dbhelper->updateDBVersion($dbversion);
					}
					
					if($dbversion < DBVERSION)
					{
						$continue = true;
					}
				}
				else
				{
					$html .= $this->failedStatus;
					$status = false;
					$errorCode = $updateResult->errorCode;
				}
				
				$html .= $updateResult->html;
			}
		}
		
		if(!$stopUpdate)
		{
			if(!$continue)
			{
				// Need to update the menu's component id if this is a reinstall
				if( menuExist() )
				{
					$html .= '<div style="width:150px; float:left;">'.JText::_('CC UPDATE MENU ITEMS').'</div>';
					if( !updateMenuItems() )
					{
						ob_start();
						?>
						<p style="font-weight: 700; color: red;">
							System encountered an error while trying to update the existing menu items. You will need
							to update the existing menu structure manually.
						</p>
						<?php
						$html .= ob_get_contents();
						@ob_end_clean();
						$html .= $this->failedStatus;;
					}
					else
					{
						$html .= $this->successStatus;
					}
				}
				else
				{
					$html .= '<div style="width:150px; float:left;">'.JText::_('CC CREATE MENU ITEMS').'</div>';
					if( !addMenuItems() )
					{
						ob_start();
						?>
						<p style="font-weight: 700; color: red;">
							System encountered an error while trying to create a menu item. You will need
							to create your menu item manually.
						</p>
						<?php
						$html .= ob_get_contents();
						@ob_end_clean();
						$html .= $this->failedStatus;;
					}
					else
					{
						$html .= $this->successStatus;
					}
				}
				
				//clean up registration table if the table installed previously.
				$this->dbhelper->cleanRegistrationTable();
		
				// Test if we are required to add default custom fields
				$html .= '<div style="width:150px; float:left;">'.JText::_('CC ADD DEFAULT CUSTOM FIELD').'</div>';
				if( needsDefaultCustomFields() )
				{
					addDefaultCustomFields();
					$html .= $this->successStatus;
				}
				else
				{
					$html .= $this->notApplicable;
				}
		
				// Test if we are required to add default group categories
				$html .= '<div style="width:150px; float:left;">'.JText::_('CC ADD DEFAULT GROUP CATEGORIES').'</div>';
				if( needsDefaultGroupCategories() )
				{
					addDefaultGroupCategories();
					$html .= $this->successStatus;
				}
				else
				{
					$html .= $this->notApplicable;
				}
		
				// Test if we are required to add default videos categories
				$html .= '<div style="width:150px; float:left;">'.JText::_('CC ADD DEFAULT VIDEO CATEGORIES').'</div>';
				if( needsDefaultVideosCategories() )
				{
					addDefaultVideosCategories();
					$html .= $this->successStatus;
				}
				else
				{
					$html .= $this->notApplicable;
				}

				// Test if we are required to add default event categories
				$html .= '<div style="width:150px; float:left;">'.JText::_('CC ADD DEFAULT EVENT CATEGORIES').'</div>';
				if( needsDefaultEventsCategories() )
				{
					addDefaultEventsCategories();
					$html .= $this->successStatus;
				}
				else
				{
					$html .= $this->notApplicable;
				}
		
				// Test if we are required to add default user points
				$html .= '<div style="width:150px; float:left;">'.JText::_('CC ADD DEFAULT USERPOINTS').'</div>';
				if( needsDefaultUserPoints() )
				{
					//clean up userpoints table if the table installed from previous version of 1.0.128
					$this->dbhelper->cleanUserPointsTable();
					addDefaultUserPoints();
					$html .= $this->successStatus;
				} 
				else 
				{
					//cleanup some unused action rules.
					$this->dbhelper->cleanUserPointsTable(array('friends.request.add','friends.request.reject','friends.request.cancel','friends.invite'));
					$html .= $this->notApplicable;
				}
			}
			
			if( $status )
			{
				if(!empty($continue))
				{
					$step = $step - 1;
				}
				
				$autoSubmit = $this->getAutoSubmitFunction();
				$message = $autoSubmit.$html;
			}
			else
			{
				$errorMsg = $this->getErrorMessage(7, $errorCode);
				$message = $html.$errorMsg;
				$step = $step - 1;
			}
		}
		else
		{
			$message = $html;
		}
		
		$drawdata 			= new stdClass();
		$drawdata->message	= $message;
		$drawdata->status 	= $status;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('CC UPDATING DATABASE');
		$drawdata->install 	= 1;
		
		return $drawdata;
	}
	
	function installPlugin( $step )
	{
		$db =& JFactory::getDBO();

		
		// @rule: Rename community in xml file to JomSocial
		jimport( 'joomla.filesystem.file' );
		$file		= JPATH_ROOT . DS . 'administrator' . DS .'components' . DS . 'com_community' . DS . 'community.xml';
		$content	= JFile::read( $file );
		$content	= JString::str_ireplace( '<name>Community</name>' , '<name>JomSocial</name>' , $content );
		
		JFile::write( $file , $content );
		
		$html  = '';
		$html .= '<div style="width:150px; float:left;">'.JText::_('CC EXTRACTING PLUGIN').'</div>';
		
		$pluginFolder = $this->frontendPath . 'ai_plugin';
		if(!JFolder::exists($pluginFolder))
		{
			JFolder::create($pluginFolder);
		}	
		$zip			= $this->frontendPath . 'ai_plugin.zip';
		$destination	= $pluginFolder;
		
		if( $this->extractArchive( $zip , $destination ) )
		{
			$html .= $this->successStatus;
			
			$plugins		= new stdClass();
			$response 		= new stdClass();
			$response->msg 	= '';
			$miscMsg		= '';
					
			$plugins->deleteuser->zip  		= $this->frontendPath . 'ai_plugin' . DS . 'plg_jomsocialuser.zip';
			$plugins->deleteuser->path 		= JPATH_ROOT . DS . 'plugins' . DS . 'user';
			$plugins->deleteuser->name 		= 'User - Jomsocial User';
			$plugins->deleteuser->element 	= 'jomsocialuser';
			$plugins->deleteuser->folder 	= 'user';
			$plugins->deleteuser->params 	= '';
			$plugins->deleteuser->lang 		= '';
			
			$plugins->walls->zip  			= $this->frontendPath . 'ai_plugin' . DS . 'plg_walls.zip';
			$plugins->walls->path 			= JPATH_ROOT . DS . 'plugins' . DS . 'community';
			$plugins->walls->name 			= 'Walls';
			$plugins->walls->element 		= 'walls';
			$plugins->walls->folder 		= 'community';
			$plugins->walls->params 		= 'cache=1';
			$plugins->walls->lang 			= 'en-GB.plg_walls.ini';
			
			foreach($plugins as $plugin)
			{
				$html .= '<div style="width:150px; float:left;">'.JText::_('Installing Plugin : ' . $plugin->name).'</div>';
				if(!JFolder::exists($plugin->path))
				{
					JFolder::create($plugin->path);
				}
				
				if( $this->extractArchive( $plugin->zip , $plugin->path ) )
				{
					//move the language file to the correct location
					if(!empty($plugin->lang))
					{
						$langSrc = $plugin->path . DS . $plugin->lang;
						$langDest = JPATH_ROOT . DS . 'administrator' . DS . 'language' . DS . 'en-GB' . DS . $plugin->lang;
						if(JFile::exists($langDest))
						{
							if(!JFile::delete($langDest))
							{
								$miscMsg .= '<div style="float:left"> Unable to delete ' . $langDest . '. Please update your language file manually.</div><div style="clear:both;"></div>';
							}
						}
							
						if(!JFile::move($langSrc, $langDest))
						{
							$miscMsg .= '<div style="float:left"> Unable to move ' . $langSrc . ' to ' . $langDest . '. Please copy your language file manually.</div><div style="clear:both;"></div>';
						}
					}
				
					//delete old plugin entry before install
					$sql = 'DELETE FROM ' 
						 			. $db->nameQuote('#__plugins') . ' '
						 . 'WHERE ' . $db->nameQuote('element') . '=' . $db->quote($plugin->element) . ' AND '
						 		    . $db->nameQuote('folder') . '=' . $db->quote($plugin->folder);
					$db->setQuery($sql);
					$db->Query();
					
					//insert plugin again
					$sql 	= 'INSERT INTO ' . $db->nameQuote( '#__plugins' ) 
							. '('
								. $db->nameQuote( 'name' ) . ', '
								. $db->nameQuote( 'element' ) . ', '
								. $db->nameQuote( 'folder' ) . ', '
								. $db->nameQuote( 'access' ) . ', '
								. $db->nameQuote( 'ordering' ) . ', '
								. $db->nameQuote( 'published' ) . ', '
								. $db->nameQuote( 'iscore' ) . ', '
								. $db->nameQuote( 'client_id' ) . ', '
								. $db->nameQuote( 'params' ) . ' '
							. ') '
							. 'VALUES('
								. $db->quote( $plugin->name ) . ', '
								. $db->quote( $plugin->element ) . ', '
								. $db->quote( $plugin->folder ) . ', '
								. $db->quote( '0' ) . ', '
								. $db->quote( '0' ) . ', '
								. $db->quote( '1' ) . ', '
								. $db->quote( '0' ) . ', '
								. $db->quote( '0' ) . ', '
								. $db->quote( $plugin->params ) . ' '
							. ') ';
					$db->setQuery($sql);
					$db->Query();
					if($db->getErrorNum()){
						JError::raiseError( 500, $db->stderr());
						$html .= $this->failedStatus;
					}
					else
					{
						$html .= $this->successStatus.$miscMsg;
					}
				}
				else
				{
					$html .= $this->failedStatus;
				}
			}
			
			//remove deleteuser plugin if exist as it is deprecated
			$sql = 'DELETE FROM ' 
				 			. $db->nameQuote('#__plugins') . ' '
				 . 'WHERE ' . $db->nameQuote('element') . '=' . $db->quote('deleteuser') . ' AND '
				 		    . $db->nameQuote('folder') . '=' . $db->quote('user');
			$db->setQuery($sql);
			$db->Query();
			
			if(JFile::exists(JPATH_ROOT.DS.'plugins'.DS.'user'.'deleteuser.php'))
			{
				JFile::delete(JPATH_ROOT.DS.'plugins'.DS.'user'.'deleteuser.php');
			}
			
			if(JFile::exists(JPATH_ROOT.DS.'plugins'.DS.'user'.'deleteuser.xml'))
			{
				JFile::delete(JPATH_ROOT.DS.'plugins'.DS.'user'.'deleteuser.xml');
			}
		}
		else
		{
			$html .= $this->failedStatus;
		}
		
		JFolder::delete($pluginFolder);
		
		$autoSubmit = $this->getAutoSubmitFunction();
		//$form = $this->getInstallForm(100);
		
		$message = $autoSubmit.$html;
		
		$drawdata 			= new stdClass();
		$drawdata->message	= $message;
		$drawdata->status	= true;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('CC INSTALLING PLUGINS');
		$drawdata->install 	= 1;
		
		return $drawdata;
	}
	
	function installZendFramework( $step )
	{
		$html 			= '';
		$html 			.= '<div style="float:left;">Installing Zend Framework</div><div style="clear:both;"></div>';
		$html 			.= '<div style="float:left;">Please wait. The process may takes several minutes.</div><div style="clear:both;"></div>';
		
		$currentVersion	= 0;
		$packagesCount	= 0;
		$currentPackage	= JRequest::getInt('substep', 0);
		$currentInstalledPackage = 0;
		
		// connect to Jomsocial server
		$latestData		= $this->_getZendFrameworkCurrentData();
		
		// check the existence and it's version
		$pluginXml		= JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'zend.xml';
		if (JFile::exists($pluginXml))
		{
			$parser	=& JFactory::getXMLParser( 'Simple' );
			$parser->loadFile($pluginXml);
			$currentVersion	= $parser->document->getElementByPath('version')->data();
			$currentDesc	= $parser->document->getElementByPath('description')->data();
			$currentInstalledPackage	= $parser->document->getElementByPath('package');
			
            if (!$currentInstalledPackage)
			{
				$currentInstalledPackage	= substr($currentDesc, -1); // backward compability
			}
			else
			{
			     $currentInstalledPackage	= $currentInstalledPackage->data();
            }
		}
		
		// if it's connected to remote server and newer version is available
		// the we launch the remote installer.
		// note if $currentInstalledPackage equals to $packagesCount, that means
		// the all the sub packages are installed.
		if ($latestData && (($latestData->version > $currentVersion) || ($currentInstalledPackage != count($latestData->packages))))
		{
			$packagesCount	= count($latestData->packages);
			if ($currentPackage < $packagesCount)
			{
				$packageURL	= $latestData->packages[$currentPackage];
				$installer	= $this->_joomlaRemoteInstaller($packageURL);
				$currentPackage++;
				$html 	.= '<div style="float:left;">Installing from '.$packageURL.'</div><div style="clear:both;"></div>';
				$html	.= $this->successStatus;
				$step	= 9; // back to this step again for the remaining packages.
			}
		} else {
			if (!$latestData)
			{
				$html	.= $this->failedStatus;
				$html	.= '<div style="width:300px; float:left;">Failed to connect to remote server.</div>';
				$html	.= '<div style="width:300px; float:left;">Please install it separately.</div>';
			}
		}
		
		// when the installation is completed, we enable the plugin
		if ( $latestData 
			&& ($latestData->version == $currentVersion) 
			&& ($currentInstalledPackage == count($latestData->packages)) )
		{
			$db		=& JFactory::getDBO();
			$query	= 'UPDATE ' . $db->nameQuote('#__plugins') . ' SET ' . $db->nameQuote('published') . ' = ' . $db->quote(1)
					. ' WHERE ' . $db->nameQuote('element') . ' = ' . $db->quote('zend');
			$db->setQuery($query);
			if (!$db->query())
			{
				return $db->getErrorNum().':'.$db->getErrorMsg();
			}
		}
		
		$drawdata 			= new stdClass();
		$drawdata->message	= $html.$this->getAutoSubmitFunction();
		$drawdata->status	= true;
		$drawdata->step 	= $step;
		$drawdata->substep 	= $currentPackage;
		$drawdata->title 	= JText::_('CC INSTALLING ZEND FRAMEWORK');
		$drawdata->install 	= 1;
		
		return $drawdata;
	}
	
	function installationComplete( $step )
	{
		$cache =& JFactory::getCache();
		$cache->clean();
		
		$version	= communityInstallerHelper::getVersion();
		$successImg = 'http://www.jomsocial.com/images/install/success.png?url=' . urlencode( JURI::root() ) . '&version=' . $version;

		$file = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_community' . DS . 'installer.dummy.ini';
		if(JFile::exists($file) && JFile::delete($file))
		{
			$html  = '<div><img src='.$successImg.' /></div>';
			$html .= '<div style="margin: 30px 0; padding: 10px; background: #edffb7; border: solid 1px #8ba638; width: 50%; -moz-border-radius: 5px; -webkit-border-radius: 5px;">
			<div style="background: #edffb7 url(templates/khepri/images/toolbar/icon-32-apply.png) no-repeat 0 0;width: 32px; height: 32px; float: left; margin-right: 10px;"></div>
			<h3 style="padding: 0; margin: 0 0 5px;">Installation has been completed</h3>Please upgrade your Modules and Plugins too.</div>';					
		}
		else
		{
			$html  = '<div><img src='.$successImg.' /></div>';
			$html .= '<div style="margin: 30px 0; padding: 10px; background: #edffb7; border: solid 1px #8ba638; width: 50%; -moz-border-radius: 5px; -webkit-border-radius: 5px;">
			<div style="background: #edffb7 url(templates/khepri/images/toolbar/icon-32-apply.png) no-repeat 0 0;width: 32px; height: 32px; float: left; margin-right: 10px;"></div>
			<h3 style="padding: 0; margin: 0 0 5px;">Installation has been completed</h3>However we were unable to remove the file <b>installer.dummy.ini</b> located in the backend folder. Please remove it manually in order to completed the installation.</div>';			
		}
		
		ob_start();
	?>

		<div style="margin: 30px 0; padding: 10px; background: #fbfbfb; border: solid 1px #ccc; width: 50%; -moz-border-radius: 5px; -webkit-border-radius: 5px;">
			<h3 style="color: red;">IMPORTANT!!</h3>
			<div>Before you begin, you might want to take a look at the following documentations first</div>
			<ul style="background: none;padding: 0; margin-left: 15px;">
				<li style="background: none;padding: 0;margin:0;"><a href="http://www.jomsocial.com/docs/Create_menu_link" target="_blank">Creating menu links</a></li>
				<li style="background: none;padding: 0;margin:0;"><a href="http://www.jomsocial.com/docs/Cron_Setup" target="_blank">Setting up scheduled task to process emails.</a></li>
				<li style="background: none;padding: 0;margin:0;"><a href="http://www.jomsocial.com/docs/Installing_Applications" target="_blank">Installing applications for JomSocial</a></li>
				<li style="background: none;padding: 0;margin:0;"><a href="http://www.jomsocial.com/docs/Installing_Modules" target="_blank">Installing modules for JomSocial</a></li>
			</ul>
			<div>You can read the full documentation at <a href="http://www.jomsocial.com/docs.html" target="_blank">JomSocial Documentation</a></div>
		</div>

	<?php
		$content	= ob_get_contents();
		ob_end_clean();
		
		$html		.= $content;

		//$form = $this->getInstallForm(0, 0);
		$message = $html;
		
		$drawdata 			= new stdClass();
		$drawdata->message	= $message;
		$drawdata->status	= true;
		$drawdata->step 	= $step;
		$drawdata->title 	= JText::_('CC INSTALLATION COMPLETED');
		$drawdata->install	= 0;
		
		return $drawdata;
	}
	
	function install($step=1)
	{
		$db		=& JFactory::getDBO();
		
		switch($step)
		{
			case 1:
				//check requirement
				$status = $this->checkRequirement(2);
				break;
			case 2:
				//install backend system
				$status = $this->installBackend(3);
				break;
			case 3:
				//install ajax system
				$status = $this->installAjax(4);
				break;
			case 4:
				//install frontend system
				$status = $this->installFrontend(5);
				break;
			case 5:
				//install template
				$status = $this->installTemplate(6);
				break;
			case 6:
				//prepare database
				$status = $this->prepareDatabase(7);
				break;
			case 7:
			case 'UPDATE_DB':
				//update database
				$status = $this->updateDatabase(8);
				break;
			case 8:
				//install basic plugins
				$status = $this->installPlugin(9);
				break;
			case 9:
				//install Zend Framework
				$status = $this->installZendFramework(100);
				break;
			case 100:
				//show success message
				$status = $this->installationComplete(0);
				break;
			default:
				$status 			= new stdClass();
				$status->message	= $this->getErrorMessage(0, '0a');
				$status->step 		= '-99';
				$status->title 		= JText::_('CC JOMSOCIAL INSTALLER');
				$status->install 	= 1;
				break;
		}
		return $status;
	}
					
	/**
	 * Method to extract archive out
	 * 
	 * @returns	boolean	True on success false otherwise.
	 **/ 
	function extractArchive( $source , $destination )
	{
		// Cleanup path
		$destination	= JPath::clean( $destination );
		$source			= JPath::clean( $source );
	
		return JArchive::extract( $source , $destination );
	}
	
	/**
	 * Method to check if the system plugins exists
	 * 
	 * @returns boolean	True if system plugin needs update, false otherwise.
	 **/ 
	function azrulSystemNeedsUpdate()
	{
		$xml	= JPATH_PLUGINS . DS . 'system' . DS . 'azrul.system.xml';
		
		// Test if file exists
		if( file_exists( $xml ) )
		{
			// Load the parser and the XML file
			$parser		=& JFactory::getXMLParser( 'Simple' );
			$parser->loadFile( $xml );
			$document	=& $parser->document;
	
			$element	=& $document->getElementByPath( 'version' );
			$version	= doubleval( $element->data() );
	
			if( $version >= 2.7 && $version != 0 )
				return false;
		}
		
		return true;
	}
	
	function _joomlaRemoteInstaller($url)
	{
		if (!$url) return false;
		
		// using Joomla installer
		jimport( 'joomla.installer.installer' );
		jimport('joomla.installer.helper');
		
		// Download the package
		$file	= JInstallerHelper::downloadPackage($url);
		if (!$file)
		{
			// server connect failed
			return false;
		}
		$config	=& JFactory::getConfig();
		$tmp 	= $config->getValue('config.tmp_path');
		$package	= JInstallerHelper::unpack($tmp.DS.$file);
		if (!$package)
		{
			//unable to find install package
		}
		
		// Install the package
		$msg		= '';
		$installer	=& JInstaller::getInstance();
		if (!$installer->install($package['dir'])) {
			// There was an error installing the package
			$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Error'));
			$result = false;
		} else {
			// Package installed sucessfully
			$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Success'));
			$result = true;
		}
		
		// Clean up the install files
		if (!is_file($package['packagefile']))
		{
			$package['packagefile'] = $config->getValue('config.tmp_path').DS.$package['packagefile'];
		}
		JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);
		
// 		$installer->get('name');
// 		$installer->message;
// 		$installer->get('extension.message');
		return $result;
	}
	
	function _getZendFrameworkCurrentData()
	{
		$parser		=& JFactory::getXMLParser('Simple');
		$data		= new stdClass();
		
		// Get the xml file
// 		$site		= 'zend4joomla.googlecode.com';
// 		$xml		= 'files/jomsocial.xml';
		$site		= 'www.jomsocial.com';
		$xml		= 'jomsocial.xml';
		$contents	= '';
		
		$handle		= @fsockopen( $site , 80, $errno, $errstr, 30);
		
		if( $handle )
		{
			$out = "GET /$xml HTTP/1.1\r\n";
			$out .= "Host: $site\r\n";
			$out .= "Connection: Close\r\n\r\n";
		
			fwrite($handle, $out);

			$body		= false;
							
			while( !feof( $handle ) )
			{
				$return	= fgets( $handle , 1024 );
				
				if( $body )
				{
					$contents	.= $return;
				}
				
				if( $return == "\r\n" )
				{
					$body	= true;
				}
			}
			fclose($handle);
		} else {
			return false;
		}
		
		$parser->loadString( $contents );
		
		$document	=& $parser->document;
		
		/** Get version **/
		$element		=& $document->getElementByPath( 'zend/version' );
		$data->version	= $element->data();
		
		/** Get the total number of packages **/
		$packages		=& $document->getElementByPath( 'zend/packages' );
		$data->packages	= array();
		foreach($packages->children() as $package)
		{
			$data->packages[] = $package->data();
		}
		
		return $data;
	}
}

class communityInstallerDBAction
{
	function _getFields( $table = '#__community_groups' )
	{
		$result	= array();
		$db		=& JFactory::getDBO();
		
		$query	= 'SHOW FIELDS FROM ' . $db->nameQuote( $table );
	
		$db->setQuery( $query );
		
		$fields	= $db->loadObjectList();
	
		foreach( $fields as $field )
		{
			$result[ $field->Field ]	= preg_replace( '/[(0-9)]/' , '' , $field->Type );
		}
	
		return $result;
	}
	
	/*
	 * Check table column index whether exists or not.
	 * index name == column name.	 
	 */	 
	function _isExistTableColumn($tablename, $columnname)
	{
		$fields	= $this->_getFields($tablename);				
		if(array_key_exists($columnname, $fields))
		{
			return true;
		}		
		return false;
	}
	
	/*
	 * Check table index whether exists or not.
	 * index name.	 
	 */	 
	function _isExistTableIndex($tablename, $indexname)
	{	
		$db		=& JFactory::getDBO();
		
		$query	= 'SHOW INDEX FROM ' . $db->nameQuote( $tablename );

		$db->setQuery( $query );
		
		$indexes	= $db->loadObjectList();

		foreach( $indexes as $index )
		{
			$result[ $index->Key_name ]	= $index->Column_name;
		}
		
		if(array_key_exists($indexname, $result)){
			return true;
		}
		
		return false;
	}
	
	function _isExistDefaultConfig()
	{
		$db		=& JFactory::getDBO();
		
		$query	= 'SELECT COUNT(*) FROM ' 
				. $db->nameQuote( '#__community_config' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'name' ) . '=' . $db->Quote( 'config' );
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function cleanRegistrationTable()
	{
		$db	=& JFactory::getDBO();
		
		$query = 'TRUNCATE TABLE `#__community_register`';
		
		$db->setQuery( $query );
		$db->query();
	}
	
	function cleanUserPointsTable($ruleArr = null)
	{
		$db	=& JFactory::getDBO();
		
		if(is_null($ruleArr))
		{	
			//this delete sql was cater for version prior to JomSocial 1.1
			$query = "DELETE FROM `#__community_userpoints` where `rule_plugin` = 'com_community' and `action_string` in (
						'application.remove','group.create','group.leave','discussion.create','friends.add','album.create',
						'group.join','discussion.reply','group.wall.create','wall.create','profile.status.update','photo.upload',
						'application.add')";
		}
		else
		{
			$fieldName	= implode('\',\'', $ruleArr);
			$query = "DELETE FROM `#__community_userpoints` where `rule_plugin` = 'com_community' and `action_string` in ('".$fieldName."')";		
		}
					
		$db->setQuery( $query );
		$db->query();
	}
	
	function checkPhotoPrivacyUpdated()
	{
		$db		=& JFactory::getDBO();
		
		$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__community_photos_albums' );
		$query	.= ' WHERE `permissions` = ' . $db->Quote('all');
		$db->setQuery( $query );
	
		$isUpdated	= ( $db->loadResult() > 0 ) ? false : true;
		
		return $isUpdated;		
	}
		
	function deleteTableEntry($table, $column, $element)
	{
		$db		=& JFactory::getDBO();
		
		// Try to remove the old record.					
		$query	= 'DELETE FROM ' . $db->nameQuote( $table ) . ' '
		. 'WHERE ' . $db->nameQuote( $column ) . '=' . $db->quote($element);
		$db->setQuery( $query );
		$db->query();
		
		return $db->getErrorNum();
	}
	
	function insertTableEntry($table, $object)
	{
		$db		=& JFactory::getDBO();
		return $db->insertObject( $table , $object );
	}
	
	function createDefaultTable()
	{
		$db		=& JFactory::getDBO();
		
		$buffer = file_get_contents(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_community' . DS . 'install.mysql.utf8.sql');		
		jimport('joomla.installer.helper');
		$queries = JInstallerHelper::splitSql($buffer);

		if (count($queries) != 0)
		{
			// Process each query in the $queries array (split out of sql file).
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#') 
				{
					$db->setQuery($query);
					if (!$db->query()) 
					{
						return $db->getErrorNum().':'.$db->getErrorMsg();
					}
				}
			}
		}
		
		return false;
	}
	
	function getDBVersion()
	{
		$db		=& JFactory::getDBO();
		
		$sql = 'SELECT ' . $db->nameQuote('params') . ' '
			 . 'FROM ' . $db->nameQuote('#__community_config') . ' '
			 . 'WHERE ' . $db->nameQuote('name') . ' = ' . $db->quote('dbversion') .' '
			 . 'LIMIT 1';
		$db->setQuery($sql);
		$result = $db->loadResult();
				
		return $result;
	}
	
	function insertDBVersion($dbversion)
	{
		$db		=& JFactory::getDBO();
		
		$query	= 'INSERT INTO ' . $db->nameQuote( '#__community_config' ) 
				. '(' 
						. $db->nameQuote( 'name' ) . ', ' 
						. $db->nameQuote( 'params' ) 
				. ')'
				. 'VALUES('
						. $db->quote( 'dbversion' ) . ', ' 
						. $db->quote( $dbversion ) 
				. ')';
		$db->setQuery( $query );
		$db->Query();
	}
	
	function updateDBVersion($dbversion)
	{
		$db		=& JFactory::getDBO();
		
		$query	= 'UPDATE ' . $db->nameQuote( '#__community_config' ) 
				. 'SET ' 
						. $db->nameQuote( 'params' ) . ' = ' . $db->quote( $dbversion ) . ' ' 
				. 'WHERE'
						. $db->nameQuote( 'name' ) . ' = ' . $db->quote( 'dbversion' ) . ' ';
						
		$db->setQuery( $query );
		$db->Query();
	}
	
	function updateGroupMembersTable()
	{
		$db				=& JFactory::getDBO();
	
		// Update older admin values first.
		$query	= 'UPDATE ' . $db->nameQuote( '#__community_groups_members' ) . ' '
				. 'SET ' . $db->nameQuote( 'permissions' ) . '=' . $db->Quote( '1' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'permissions' )  . '=' . $db->Quote( 'admin' );
		$db->setQuery( $query );
		$db->query();
				
		// Update older member values first.
		$query	= 'UPDATE ' . $db->nameQuote( '#__community_groups_members' ) . ' '
				. 'SET ' . $db->nameQuote( 'permissions' ) . '=' . $db->Quote( '0' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'permissions' )  . '=' . $db->Quote( 'member' );
		$db->setQuery( $query );
		$db->query();
	
		// Modify the column type
		$query	= 'ALTER TABLE ' . $db->nameQuote('#__community_groups_members' ) . ' '
				. 'CHANGE `permissions` `permissions` INT(1) NOT NULL';
		$db->setQuery( $query );
		$db->query();
		
		return true;	
	}
}

class communityInstallerVerifier
{
	var $display;
	var $dbhelper;
	
	function communityInstallerVerifier()
	{
		$this->display	= new communityInstallerDisplay();
		$this->dbhelper	= new communityInstallerDBAction();
	}
	
	function isLatestFriendTable()
	{
		$fields	= $this->dbhelper->_isExistTableColumn( '#__community_users', 'friendcount' ); 		
		return $fields;
	}
	
	function isLatestGroupMembersTable()
	{
		$fields			= $this->dbhelper->_getFields( '#__community_groups_members' );
		$result			= array();
		if( array_key_exists('permissions' , $fields) )
		{
			if( $fields['permissions'] == 'varchar' )
			{
				return false;			
			}
		}
		return true;
	}
	
	function isPhotoPrivacyUpdated()
	{		
		return $this->dbhelper->checkPhotoPrivacyUpdated();	
	}
	
	function isLatestGroupTable()
	{
		$fields	= $this->dbhelper->_getFields();
	
		if(!array_key_exists( 'membercount' , $fields ) )
		{
			return false;
		}
	
		if(!array_key_exists( 'wallcount' , $fields ) )
		{
			return false;
		}
	
		if(!array_key_exists( 'discusscount' , $fields ) )
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * Method to check if the GD library exist
	 * 
	 * @returns boolean	return check status
	 **/ 
	function testImage()
	{
		$msg = '
			<style type="text/css">
			.Yes {
				color:#46882B;
				font-weight:bold;
			}
			.No {
				color:#CC0000;
				font-weight:bold;
			}
			.jomsocial_install tr {

			}
			.jomsocial_install td {
				color: #888;
				padding: 3px;
			}
			.jomsocial_install td.item {
				color: #333;
			}
			</style>	
			<div class="install-body" style="background: #fbfbfb; border: solid 1px #ccc; -moz-border-radius: 5px; -webkit-border-radius: 5px; padding: 20px; width: 50%;">
				<p>If any of these items are not supported (marked as <span class="No">No</span>), your system does not meet the requirements for installation. Some features might not be available. Please take appropriate actions to correct the errors.</p>
					<table class="content jomsocial_install" style="width: 100%; background">
						<tbody>';
		
		// @rule: Test for JPG image extensions
		$type = 'JPEG';
		if( function_exists( 'imagecreatefromjpeg' ) )
		{
			$msg .= $this->display->testImageMessage($type, true);
		}
		else
		{
			$msg .= $this->display->testImageMessage($type, false);
		}
		
		// @rule: Test for png image extensions
		$type = 'PNG';
		if( function_exists( 'imagecreatefrompng' ) )
		{
			$msg .= $this->display->testImageMessage($type, true);
		}
		else
		{
			$msg .= $this->display->testImageMessage($type, false);
		}
	
		// @rule: Test for gif image extensions
		$type = 'GIF';
		if( function_exists( 'imagecreatefromgif' ) )
		{
			$msg .= $this->display->testImageMessage($type, true);
		}
		else
		{
			$msg .= $this->display->testImageMessage($type, false);
		}
		
		$type = 'GD';
		if( function_exists( 'imagecreatefromgd' ) )
		{
			$msg .= $this->display->testImageMessage($type, true);
		}
		else
		{
			$msg .= $this->display->testImageMessage($type, false);
		}
		
		$type = 'GD2';
		if( function_exists( 'imagecreatefromgd2' ) )
		{
			$msg .= $this->display->testImageMessage($type, true);
		}
		else
		{
			$msg .= $this->display->testImageMessage($type, false);
		}
				
		$msg .= '
						</tbody>
					</table>

			</div>';
		
		return $msg;
	}
	
	function checkFileExist($file)
	{
		return file_exists($file);
	}
}

class communityInstallerUpdate
{
	var $verifier;
	var $dbhelper;
	var $helper; 
	
	function communityInstallerUpdate()
	{
		$this->verifier = new communityInstallerVerifier();
		$this->dbhelper = new communityInstallerDBAction();
		$this->helper 	= new communityInstallerHelper();
	}
	
	function update_0()
	{
		$db = JFactory::getDBO();
		$result = new stdClass();
		$status = true;
		$html = "";
		
		// Patch for groups.
		$html .= '<div style="width:150px; float:left;">'.JText::_('CC PATCHING DATABASE').'</div>';	
		if( !$this->verifier->isLatestGroupTable() || !$this->verifier->isLatestFriendTable() || !$this->verifier->isPhotoPrivacyUpdated())
		{
			$html	.= $this->helper->failedStatus;
			ob_start();
			?>
			<div style="font-weight: 700; color: red;">
				Looks like you are upgrading from an older version of JomSocial. There is an update
				in the newer version of JomSocial that requires a maintenance to be carried out. Kindly please
				proceed to the maintenance section at <a href="index.php?option=com_community&view=maintenance">HERE</a>.		
			</div>
			<?php
			$html .= ob_get_contents();
			@ob_end_clean();
						
			$result->html = $html;
			$result->status = false;
			$result->errorCode = '7b';
			$result->stopUpdate = true;
			return $result;
		}
		else
		{
			$html .= $this->helper->successStatus;
		}
				
		// Test if need to update the field 'permissions' in #__community_groups_members
		if( !$this->verifier->isLatestGroupMembersTable() )
		{
			$this->dbhelper->updateGroupMembersTable();
		}
		
		// add new path column.
		if(!$this->dbhelper->_isExistTableColumn( '#__community_photos_albums' , 'path' ) )
		{
			$sql = 'ALTER TABLE `#__community_photos_albums` ADD `path` VARCHAR( 255 ) NULL';
			$db->setQuery($sql);
			$db->query();
		}		
		
		// add ip to register table
		if(!$this->dbhelper->_isExistTableColumn( '#__community_register' , 'ip' ) )
		{
			$sql = 'ALTER TABLE `#__community_register` ADD `ip` VARCHAR( 25 ) NULL';
			$db->setQuery($sql);
			$db->query();
		}		
		
		// add last replied column
		if(!$this->dbhelper->_isExistTableColumn( '#__community_groups_discuss' , 'lastreplied' ) )
		{
			$sql = 'ALTER TABLE `#__community_groups_discuss` ADD `lastreplied` DATETIME NOT NULL AFTER `message`';
			$db->setQuery($sql);
			$db->query();
		}
		
		$result->html	= $html;
		$result->status = $status;
		
		if(!$status)
		{
			$result->errorCode = '7b';
		}
		return $result;
	}
	
	function update_1()
	{	
		$db = JFactory::getDBO();
		$result = new stdClass();
		$status = true;
		$html = "";
		$errorCode = "";
					
		if(!$this->dbhelper->_isExistTableIndex('#__community_msg_recepient', 'idx_isread_to_deleted'))
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_msg_recepient' ) . ' ADD INDEX `idx_isread_to_deleted` (`is_read`, `to`, `deleted`)';
			$db->setQuery( $query );
			$db->query();
		}
		
		if(!$this->dbhelper->_isExistTableIndex('#__community_apps', 'idx_userid'))
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_apps' ) . ' ADD INDEX `idx_userid` (`userid`)';
			$db->setQuery( $query );
			$db->query();
		}
		
		if(!$this->dbhelper->_isExistTableIndex('#__community_apps', 'idx_user_apps'))
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_apps' ) . ' ADD INDEX `idx_user_apps` (`userid`, `apps`)';
			$db->setQuery( $query );
			$db->query();
		}
		
		if(!$this->dbhelper->_isExistTableIndex('#__community_connection', 'idx_connect_to'))
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_connection' ) . ' ADD INDEX `idx_connect_to` (`connect_to`)';
			$db->setQuery( $query );
			$db->query();
		}
		
		if(!$this->dbhelper->_isExistTableIndex('#__community_groups_members', 'idx_memberid'))
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_groups_members' ) . ' ADD INDEX `idx_memberid` (`memberid`)';
			$db->setQuery( $query );
			$db->query();
		}
		
		if(!$this->dbhelper->_isExistTableIndex('#__community_fields_values', 'idx_user_fieldid'))
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_fields_values' ) . ' ADD INDEX `idx_user_fieldid` (`user_id`, `field_id`)';
			$db->setQuery( $query );
			$db->query();
		}		
				
		$result->html	= $html;		
		$result->status = $status;
		if(!$status)
		{
			$result->errorCode = $errorCode;
		}
		return $result;
	}
	
	function update_2()
	{	
		$db = JFactory::getDBO();
		$result = new stdClass();
		$status = true;
		$html = "";
		$errorCode = "";
					
		if(!$this->dbhelper->_isExistTableColumn( '#__community_photos_albums', 'type' ) )
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_photos_albums' ) . ' ADD `type` VARCHAR(255) NOT NULL DEFAULT \'user\'';
			$db->setQuery( $query );
			$db->query();
		}
		
		if(!$this->dbhelper->_isExistTableIndex('#__community_photos_albums', 'idx_type'))
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_photos_albums' ) . ' ADD INDEX `idx_type` (`type`)';
			$db->setQuery( $query );
			$db->query();
		}
		
		if(!$this->dbhelper->_isExistTableColumn( '#__community_photos_albums', 'groupid' ) )
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_photos_albums' ) . ' ADD `groupid` INT( 11 ) NOT NULL DEFAULT \'0\' AFTER `type`';
			$db->setQuery( $query );
			$db->query();			
		}		
		
		if(!$this->dbhelper->_isExistTableIndex('#__community_photos_albums', 'idx_groupid'))
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_photos_albums' ) . ' ADD INDEX `idx_groupid` (`groupid`)';
			$db->setQuery( $query );
			$db->query();
		}
				
		if(!$this->dbhelper->_isExistTableIndex('#__community_photos_albums', 'idx_albumtype'))
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_photos_albums' ) . ' ADD INDEX `idx_albumtype` (`id`,`type`)';
			$db->setQuery( $query );
			$db->query();
		}
		
		if(!$this->dbhelper->_isExistTableIndex('#__community_photos_albums', 'idx_creatortype'))
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_photos_albums' ) . ' ADD INDEX `idx_creatortype` (`creator`,`type`)';
			$db->setQuery( $query );
			$db->query();
		}
		
		if(!$this->dbhelper->_isExistTableColumn( '#__community_videos', 'groupid' ) )
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_videos' ) . ' ADD `groupid` INT( 11 ) UNSIGNED NOT NULL DEFAULT 0';
			$db->setQuery( $query );
			$db->query();
		}
		
		if(!$this->dbhelper->_isExistTableIndex('#__community_videos', 'idx_groupid'))
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_videos' ) . ' ADD INDEX `idx_groupid` (`groupid`)';
			$db->setQuery( $query );
			$db->query();
		}
		
		if(!$this->dbhelper->_isExistTableColumn( '#__community_groups', 'params' ) )
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_groups' ) . ' ADD `params` TEXT NOT NULL AFTER `membercount`';
			$db->setQuery( $query );
			$db->query();
		}
		
		if(!$this->dbhelper->_isExistTableColumn( '#__community_connection', 'created' ) )
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_connection' ) . ' ADD `created` DATETIME DEFAULT NULL';
			$db->setQuery( $query );
			$db->query();
		}
		
		if(!$this->dbhelper->_isExistTableColumn( '#__community_fields', 'registration' ) )
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_fields' ) . ' ADD `registration` tinyint(1) DEFAULT 1';
			$db->setQuery( $query );
			$db->query();
		}				
		
		$result->html	= $html;
		$result->status = $status;
		if(!$status)
		{
			$result->errorCode = $errorCode;
		}
		return $result;
	}
	
	function update_3()
	{
		$db = JFactory::getDBO();
		$result = new stdClass();
		$status = true;
		$html = "";
		$errorCode = "";
					
		if(!$this->dbhelper->_isExistTableIndex('#__community_connection', 'idx_connect_from'))
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_connection' ) . ' ADD INDEX `idx_connect_from` (`connect_from`)';
			$db->setQuery( $query );
			$db->query();
		}
		
		if(!$this->dbhelper->_isExistTableIndex('#__community_connection', 'idx_connect_tofrom'))
		{
			$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_connection' ) . ' ADD INDEX `idx_connect_tofrom` (`connect_to`, `connect_from`)';
			$db->setQuery( $query );
			$db->query();
		}		
		
		$result->html	= $html;
		$result->status = $status;
		if(!$status)
		{
			$result->errorCode = $errorCode;
		}
		return $result;		
	}
	
	function update_4()
	{
		$db = JFactory::getDBO();
		$result = new stdClass();
		$status = true;
		$html = "";
		$errorCode = "";
		
		$query	= 'UPDATE ' . $db->nameQuote( '#__community_groups_discuss' ) . ' SET ' . $db->nameQuote( 'lastreplied' ) . ' =  ' . $db->nameQuote( 'created' ) . ' WHERE  ' . $db->nameQuote( 'lastreplied' ) . ' = ' . $db->quote( '0000-00-00 00:00:00' );
		$db->setQuery( $query );
		$db->query();
				
		$result->html	= $html;
		$result->status = $status;
		if(!$status)
		{
			$result->errorCode = $errorCode;
		}
		return $result;
	}
	
	function update_5()
	{
		$db = JFactory::getDBO();
		$result = new stdClass();
		$status = true;
		$html = "";
		$errorCode = "";
		
		if( !$this->dbhelper->_isExistTableColumn('#__community_connection', 'msg') )
		{
    		$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_connection' ) . ' ADD ' . $db->nameQuote( 'msg' ) . ' TEXT NOT NULL ';
    		$db->setQuery( $query );
    		$db->query();
		}
		
		if( !$this->dbhelper->_isExistTableColumn('#__community_photos', 'filesize') )
		{				
    		$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_photos' ) . ' ADD ' . $db->nameQuote( 'filesize' ) . ' INT(11) NOT NULL DEFAULT \'0\' ';
    		$db->setQuery( $query );
    		$db->query();
		}  
		
		if( !$this->dbhelper->_isExistTableColumn('#__community_photos', 'storage') )
		{	
    		$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_photos' ) . ' ADD ' . $db->nameQuote( 'storage' ) . ' VARCHAR( 64 ) NOT NULL DEFAULT \'file\', ADD INDEX `idx_storage` ( `storage` )';
    		$db->setQuery( $query );
    		$db->query();
		}
		
		if( !$this->dbhelper->_isExistTableColumn('#__community_videos', 'filesize') )
		{		
    		$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_videos' ) . ' ADD ' . $db->nameQuote( 'filesize' ) . ' INT(11) NOT NULL DEFAULT \'0\' ';
    		$db->setQuery( $query );
    		$db->query();
		}
    	
        if( !$this->dbhelper->_isExistTableColumn('#__community_videos', 'storage') )	
        {
    		$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_videos' ) . ' ADD ' . $db->nameQuote( 'storage' ) . ' VARCHAR( 64 ) NOT NULL DEFAULT \'file\', ADD INDEX `idx_storage` ( `storage` ) ';
    		$db->setQuery( $query );
    		$db->query(); 
		}
		
		
		//get video folder
		$query	= 'SELECT  ' . $db->nameQuote( 'params' ) . ' FROM ' . $db->nameQuote( '#__community_config' ) . ' WHERE ' . $db->nameQuote( 'name' ) . ' = ' . $db->quote('config');
		$db->setQuery( $query );
		$row = $db->loadResult();		
		$params	= new JParameter( $row );
		$videofolder = $params->get('videofolder', 'images');		
		
		$query	= 'UPDATE ' . $db->nameQuote( '#__community_videos' ) . ' SET ' . $db->nameQuote( 'thumb' ) . ' = CONCAT(' . $db->quote( $videofolder . '/' ) . ', ' . $db->nameQuote( 'thumb' ) . ') ';
		$db->setQuery( $query );
		$db->query();
		
		$query	= 'UPDATE ' . $db->nameQuote( '#__community_videos' ) . ' SET ' . $db->nameQuote( 'path' ) . ' = CONCAT(' . $db->quote( $videofolder . '/' ) . ', ' . $db->nameQuote( 'path' ) . ') WHERE ' . $db->nameQuote( 'type' ) . ' = ' . $db->quote( 'file' );
		$db->setQuery( $query );
		$db->query();	
		
		$result->html	= $html;
		$result->status = $status;
		if(!$status)
		{
			$result->errorCode = $errorCode;
		}
		return $result;
	}
	
	function update_6()
	{
		$db = JFactory::getDBO();
		$result = new stdClass();
		$status = true;
		$html = "";
		$errorCode = "";

        if( !$this->dbhelper->_isExistTableColumn('#__community_photos', 'ordering') )
        {
    		$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_photos' ) . ' ADD ' . $db->nameQuote( 'ordering' ) . ' INT( 11 ) NOT NULL DEFAULT \'0\'';
    		$db->setQuery( $query );
    		$db->query();
    		}

        if( !$this->dbhelper->_isExistTableColumn('#__community_events', 'latitude') )
        {    		
    		$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_events' ) . ' ADD ' . $db->nameQuote( 'latitude' ) . ' float NOT NULL DEFAULT \'255\'';
    		$db->setQuery( $query );
    		$db->query();
    		}

        if( !$this->dbhelper->_isExistTableColumn('#__community_events', 'longitude') )
        {    		
    		$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_events' ) . ' ADD ' . $db->nameQuote( 'longitude' ) . ' float NOT NULL DEFAULT \'255\'';
    		$db->setQuery( $query );
    		$db->query();
    		}

        if( !$this->dbhelper->_isExistTableColumn('#__community_users', 'alias') )
        {    
    		$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_users' ) . ' ADD ' . $db->nameQuote( 'alias' ) . ' VARCHAR(255) NOT NULL';
    		$db->setQuery( $query );
    		$db->query();
    		}

        if( !$this->dbhelper->_isExistTableColumn('#__community_users', 'latitude') )
        {    
    		$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_users' ) . ' ADD ' . $db->nameQuote( 'latitude' ) . ' float NOT NULL DEFAULT \'255\'';
    		$db->setQuery( $query );
    		$db->query();
    		}

        if( !$this->dbhelper->_isExistTableColumn('#__community_users', 'longitude') )
        {    
    		$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_users' ) . ' ADD ' . $db->nameQuote( 'longitude' ) . ' float NOT NULL DEFAULT \'255\'';
    		$db->setQuery( $query );
    		$db->query();
    		}

        if( !$this->dbhelper->_isExistTableColumn('#__community_apps', 'position') )
        {    		
    		$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_apps' ) . ' ADD ' . $db->nameQuote( 'position' ) . ' VARCHAR(50) NOT NULL DEFAULT \'content\'';
    		$db->setQuery( $query );
    		$db->query();
    		}

        if( !$this->dbhelper->_isExistTableColumn('#__community_mailq', 'template') )
        {    
    		$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_mailq' ) . ' ADD ' . $db->nameQuote( 'template' ) . ' VARCHAR(255) NOT NULL';
    		$db->setQuery( $query );
    		$db->query();
    		}

        if( !$this->dbhelper->_isExistTableColumn('#__community_mailq', 'params') )
        {    
    		$query	= 'ALTER TABLE ' . $db->nameQuote( '#__community_mailq' ) . ' ADD ' . $db->nameQuote( 'params' ) . ' TEXT NOT NULL';
    		$db->setQuery( $query );
    		$db->query();
    	}
				
		$result->html	= $html;
		$result->status = $status;
		if(!$status)
		{
			$result->errorCode = $errorCode;
		}
		return $result;
	}
}

class communityInstallerDisplay
{
	function testImageMessage($type, $status=false)
	{
		$msg  = '';
		
		if( $status )
		{
			switch($type)
			{
				case 'GD':
				case 'GD2':
					$msg .= '<tr><td valign="top" class="item" width="200">' . $type . ' library</td><td valign="top"><span class="Yes">Yes</span></td><td>You will be able to use '.$type.' library to manipulate images.</td></tr>';
					break;
				default:
					$msg .= '<tr><td valign="top" class="item" width="200">' . $type . ' library</td><td valign="top"><span class="Yes">Yes</span></td><td>You will be able to upload '.$type.' images.</td></tr>';	
					break;
			}
		}
		else
		{
			switch($type)
			{
				case 'GD':
				case 'GD2':
					$msg .= '<tr><td valign="top" class="item" width="200">' . $type . ' library</td><td valign="top"><span class="No">No</span></td><td>You will <b>NOT</b> be able to use '.$type.' library to manipulate images.</td></tr>';
					break;
				default:
					$msg .= '<tr><td valign="top" class="item" width="200">' . $type . ' library</td><td valign="top"><span class="No">No</span></td><td>You will <b>NOT</b> be able to upload '.$type.' images.</td></tr>';
					break;
			}
		}
		
		return $msg;
	}
	
	// Some installer code
	function cInstallDraw($output, $step, $title, $status, $install= 1, $substep=0)
	{
		$html 		= '';
		$version	= communityInstallerHelper::getVersion();
		
		$html .= '
	<script type="text/javascript">
	$$("span.version").setHTML("Version ' . $version . '");
	var DOM = document.getElementById("element-box");
	DOM.setAttribute("id","element-box1");
	</script>
	
	<style type="text/css">
	/**
	 * Reset Joomla! styles
	 */
	div.t, div.b {
		height: 0;
		margin: 0;
		background: none;
	}
	
	body #content-box div.padding {
		padding: 0;
	}
	
	body div.m {
		padding: 0;
		border: 0;
	}
	
	.button1-left {
		background: transparent url('.JURI::root().'administrator/templates/khepri/images/j_button1_left.png) no-repeat scroll 0 0;
		float: left;
		margin-left: 5px;
		cursor: pointer;
	}
	
	.button1-left .next {
		background: transparent url('.JURI::root().'administrator/templates/khepri/images/j_button1_next.png) no-repeat scroll 100% 0;
		float: left;
		cursor: pointer;
	}
	
	.button-next,
	.button-next:focus {
		border: 0;
		background: none;
		font-size: 11px;
		height: 26px;
		line-height: 24px;
		cursor: pointer;
		font-weight: 700;
	}
	
	h1.steps{
		color:#0B55C4;
		font-size:20px;
		font-weight:bold;
		margin:0;
		padding-bottom:8px;
	}
	
	div.steps {
		font-size: 12px;
		font-weight: bold;
		padding-bottom: 12px;
		padding-top: 10px;
		background: url('.JURI::root().'administrator/templates/khepri/images/j_divider.png) 0 100% repeat-x;
	}
	
	div.on {
		color:#0B55C4;
	}
	
	#toolbar-box,
	#submenu-box,
	#header-box {
		display: none;
	}
	
	div#cElement-box div.m {
		padding: 5px 10px;
	}
	
	div#cElement-box div.t, div#cElement-box div.b {
		height: 6px;
		padding: 0;
		margin: 0;
		overflow: hidden;
	}
	
	div#cElement-box div.m {
		border-left: 1px solid #ccc;
		border-right: 1px solid #ccc;
		padding: 0 8px;
	}
	
	div#cElement-box div.t {
		background: url('.JURI::root().'administrator/templates/khepri/images/j_border.png) 0 0 repeat-x;
	}
	
	div#cElement-box div.t div.t {
		background: url('.JURI::root().'administrator/templates/khepri/images/j_crn_tr_light.png) 100% 0 no-repeat;
	}
	
	div#cElement-box div.t div.t div.t {
		background: url('.JURI::root().'administrator/templates/khepri/images/j_crn_tl_light.png) 0 0 no-repeat;
	}
	
	div#cElement-box div.b {
		background: url('.JURI::root().'administrator/templates/khepri/images/j_border.png) 0 100% repeat-x;
	}
	
	div#cElement-box div.b div.b {
		background: url('.JURI::root().'administrator/templates/khepri/images/j_crn_br_light.png) 100% 0 no-repeat;
	}
	
	div#cElement-box div.b div.b div.b {
		background: url('.JURI::root().'administrator/templates/khepri/images/j_crn_bl_light.png) 0 0 no-repeat;
	}
	#stepbar {
		float: left;
		width: 170px;
	}
	
	#stepbar div.box {
		background: url('.JURI::root().'administrator/components/com_community/box.jpg) 0 0 no-repeat;
		height: 140px;
	}
	
	#stepbar h1 {
		margin: 0;
		padding-bottom: 8px;
		font-size: 20px;
		color: #0B55C4;
		font-weight: bold;
		background: url('.JURI::root().'administrator/templates/khepri/images/j_divider.png) 0 100% repeat-x;
	}
	
	div#stepbar {
	  background: #f7f7f7;
	}
	
	div#stepbar div.t {
	  background: url('.JURI::root().'administrator/templates/khepri/images/j_border.png) 0 0 repeat-x;
	}
	
	div#stepbar div.t div.t {
	   background: url('.JURI::root().'administrator/templates/khepri/images/j_crn_tr_dark.png) 100% 0 no-repeat;
	}
	
	div#stepbar div.t div.t div.t {
	   background: url('.JURI::root().'administrator/templates/khepri/images/j_crn_tl_dark.png) 0 0 no-repeat;
	}
	
	div#stepbar div.b {
	  background: url('.JURI::root().'administrator/templates/khepri/images/j_border.png) 0 100% repeat-x;
	}
	
	div#stepbar div.b div.b {
	   background: url('.JURI::root().'administrator/templates/khepri/images/j_crn_br_dark.png) 100% 0 no-repeat;
	}
	
	div#stepbar div.b div.b div.b {
	   background: url('.JURI::root().'administrator/templates/khepri/images/j_crn_bl_dark.png) 0 0 no-repeat;
	}
	
	div#stepbar div.t, div#stepbar div.b {
		height: 6px;
		margin: 0;
		overflow: hidden;
		padding: 0;
	}
	
	div#stepbar div.m,
	div#cToolbar-box div.m {
		padding: 0 8px;
		border-left: 1px solid #ccc;
		border-right: 1px solid #ccc;
	}
	
	div#cToolbar-box {
		background: #f7f7f7;
		position: relative;
	}
	
	div#cToolbar-box div.m {
		padding: 0;
		height: 30px;
	}
	
	div#cToolbar-box {
		background: #fbfbfb;
	}
	
	div#cToolbar-box div.t,
	div#cToolbar-box div.b {
		height: 6px;
	}
	
	div#cToolbar-box span.title {
		color: #0B55C4;
		font-size: 20px;
		font-weight: bold;
		line-height: 30px;
		padding-left: 6px;
	}
	
	div#cToolbar-box div.t {
	  background: url('.JURI::root().'administrator/templates/khepri/images/j_border.png) 0 0 repeat-x;
	}
	
	div#cToolbar-box div.t div.t {
	   background: url('.JURI::root().'administrator/templates/khepri/images/j_crn_tr_med.png) 100% 0 no-repeat;
	}
	
	div#cToolbar-box div.t div.t div.t {
	   background: url('.JURI::root().'administrator/templates/khepri/images/j_crn_tl_med.png) 0 0 no-repeat;
	}
	
	div#cToolbar-box div.b {
	  background: url('.JURI::root().'administrator/templates/khepri/images/j_border.png) 0 100% repeat-x;
	}
	
	div#cToolbar-box div.b div.b {
	   background: url('.JURI::root().'administrator/templates/khepri/images/j_crn_br_med.png) 100% 0 no-repeat;
	}
	
	div#cToolbar-box div.b div.b div.b {
	   background: url('.JURI::root().'administrator/templates/khepri/images/j_crn_bl_med.png) 0 0 no-repeat;
	}
	</style>
	
	
	<table cellpadding="6" width="100%">
		<tr>
			<td rowspan="2" valign="top" width="10%">' . $this->cInstallDrawSidebar($step) . '</td>
			<td valign="top" height="30">' . $this->cInstallDrawTitle($title, $step, $status, $install, $substep) . '</td>
		</tr>
		<tr>
			<td valign="top">
				<div id="cElement-box">
					<div class="t">
				 		<div class="t">
							<div class="t"></div>
				 		</div>
					</div>
					<div class="m" style="height: 487px; padding: 0 10px;">
					'. $output . '
					</div>
					<div class="b">
						<div class="b">
							<div class="b"</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>';
	   		
		echo $html;
	}
	
	function cInstallDrawSidebar($activeSteps)
	{
		ob_start();
		?>
		
		<div id="stepbar">
			<div class="t">
				<div class="t">
					<div class="t"></div>
				</div>
			</div>
			<div class="m">
				<h1 class="steps">Steps</h1>
				<div id="stepFirst" class="steps<?php if($activeSteps == 1) echo " on"; ?>">1 : Welcome</div>
				<div class="steps<?php if($activeSteps == 2) echo " on"; ?>">2 : Checking Requirement</div>
				<div class="steps<?php if($activeSteps == 3) echo " on"; ?>">3 : Installing Backend</div>
				<div class="steps<?php if($activeSteps == 4) echo " on"; ?>">4 : Installing Ajax</div>
				<div class="steps<?php if($activeSteps == 5) echo " on"; ?>">5 : Installing Frontend</div>
				<div class="steps<?php if($activeSteps == 6) echo " on"; ?>">6 : Installing Templates</div>
				<div class="steps<?php if($activeSteps == 7) echo " on"; ?>">7 : Preparing Database</div>
				<div class="steps<?php if($activeSteps == 8) echo " on"; ?>">8 : Updating Database</div>
				<div class="steps<?php if($activeSteps == 9) echo " on"; ?>">9 : Installing Zend Framework</div>
				<div class="steps<?php if($activeSteps == 100) echo " on"; ?>">10 : Installing Plugins</div>
				<div id="stepLast" class="steps<?php if($activeSteps == 0) echo " on"; ?>">11 : Done!</div>	
				<div class="box"></div>
		  	</div>
			<div class="b">
				<div class="b">
					<div class="b"></div>
				</div>
			</div>
	  	</div>
	
		<?php
		 $html = ob_get_contents();
		 ob_end_clean();
		 return $html;
	}
	
	function cInstallDrawTitle($title, $step, $status, $install = 1, $substep = 0) 
	{
		ob_start();
		?>
			<div id="cToolbar-box">
	   			<div class="t">
					<div class="t">
						<div class="t"></div>
					</div>
				</div>
				<div class="m">
				
					<span class="title">
						<?php echo $title; ?>
					</span>
					
					<div style="position: absolute; top: 8px; right: 10px;">
						<div id="communityContainer">
							<?php
							if($status)
							{
							?>
							<form action="?option=com_community" method="POST" name="installform" id="installform">
								<input type="hidden" name="install" value="<?php echo $install; ?>"/>
								<input type="hidden" name="step" value="<?php echo $step; ?>"/>
								<input type="hidden" name="substep" value="<?php echo $substep; ?>"/>
								<div class="button1-left">
									<div class="next" onclick="document.installform.submit();">
										<input type="submit" class="button-next" onclick="" value="Next"/> <span style="margin-right: 30px;" id="timer"></span>
									</div>
								</div>
							</form>
							<?php
							}
							?>
						</div>
					</div>
					
				</div>
				<div class="b">
					<div class="b">
						<div class="b"></div>
					</div>
				</div>
	  		</div>	
	
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
}