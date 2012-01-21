<?PHP
/**
* @version $Id: mad4joomla.php 10041 2008-03-18 21:48:13Z fahrettinkutyol $
* @package joomla
* @copyright Copyright (C) 2008 Mad4Media. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
* @copyright (C) mad4media , www.mad4media.de
*/
if(defined('_JEXEC')) 
	define('_VALID_MOS',1);

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

 error_reporting(E_ERROR | E_WARNING | E_PARSE);

	//*ABSOLUTE PATH
	if(defined('_JEXEC'))
		{
		define("M4J_ABS", JPATH_ROOT);
		if(!defined('_JLEGACY')) require_once(M4J_ABS . '/administrator/components/com_mad4joomla/includes/evolution.php');
		}
	else define("M4J_ABS", $mainframe->getCfg('absolute_path'));
	
	
	require_once(M4J_ABS . '/administrator/components/com_mad4joomla/defines.mad4joomla.php');
	require_once(M4J_ABS . '/administrator/components/com_mad4joomla/admin.mad4joomla.html.php');
	
	if(file_exists(M4J_INCLUDE_CONFIGURATION)) require_once(M4J_INCLUDE_CONFIGURATION);
	else require_once(M4J_INCLUDE_RESET_CONFIGURATION);
	
	require_once(M4J_INCLUDE_REMEMBER);

	if(file_exists(M4J_LANG.$mosConfig_lang.'.php')) include_once(M4J_LANG.$mosConfig_lang.'.php');
	else include_once(M4J_LANG.'english.php');
	
	$helpers = new HTML_HELPERS_m4j();
	$GLOBALS['helpers'] = $helpers;

	$section = mosGetParam( $_REQUEST, 'section');
	$task = mosGetParam( $_REQUEST, 'task');
	$id = mosGetParam( $_REQUEST,'id');
	$GLOBALS['id'] = $id;
	$GLOBALS['task'] = $task;
	$GLOBALS['section'] = $section;


	if ($id==null) $id = -1;
	else $id = intval($id);
	
	if( mosGetParam( $_REQUEST, 'nobar')==1)define("M4J_NOBAR",1);
	if($task =='edit') define("M4J_EDITFLAG",1);

	switch($section){
		case 'jobs':
		default:
		require_once(M4J_INCLUDE_JOBS);
		break;
		
		case 'jobs_new':
		require_once(M4J_INCLUDE_JOBS_NEW);
		break;
		
		case 'forms':
		require_once(M4J_INCLUDE_FORMS);
		break;
		
		case 'form_new':
		require_once(M4J_INCLUDE_FORM_NEW);
		break;
				
		case 'formelements':
		require_once(M4J_INCLUDE_FORM_ELEMENTS);
		break;
		
		case 'element':
		require_once(M4J_INCLUDE_ELEMENT);
		break;

		case 'category':
		require_once(M4J_INCLUDE_CATEGORY);
		break;

		case 'category_new':
		require_once(M4J_INCLUDE_CATEGORY_NEW);
		break;
				
		case 'config':
		require_once(M4J_INCLUDE_CONFIG);
		break;
		
		case 'help':
		require_once(M4J_INCLUDE_HELP);
		break;
		
		case 'link':
		require_once(M4J_INCLUDE_LINK);
		break;		

	}

    if($preview) $preview->make(init_preview_calendar());
?>