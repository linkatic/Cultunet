<?php
	/**
	* @version $Id: mad4joomla 10041 2008-03-18 21:48:13Z fahrettinkutyol $
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
	
    defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
	remember_cid();
	
	$comp = 'components';
	$j15_query = "";
	$alias = "";
	$error = null;
	$menutype =  mosGetParam($_REQUEST, 'menutype');
	$remember_cid =  mosGetParam($_REQUEST, 'remember_cid');
	$name =  mosGetParam($_REQUEST, 'name');
	$title =  mosGetParam($_REQUEST, 'title');
	$link_name =  mosGetParam($_REQUEST, 'link_name');
	
	if(defined('_JEXEC'))
		{
		JLoader::register('JTableMenu', JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table'.DS.'menu.php');
		include_once(M4J_ABS.'/administrator/components/com_menus/helpers/helper.php');
		$comp = "component";
		$j15_query = "alias, ";
		$alias = str_replace(" ","-",$link_name);
		$change_chars=" �?|A, Â|A, Ă|A, Ä|Ae, Ć|C, Ç|C, Č|C, Ď|D, �?|D, É|E, �?|E, Ë|E, Ě|E, Í|I, Î|I, Ĺ|L, �?|N, Ň|N, Ó|O, Ô|O, �?|O, Ö|Oe, Ŕ|R, �?|R, � |S, Ś|O, Ť|T, Ů|U, Ú|U, Ű|U, Ü|Ue, Ý|Y, Ž|Z, Ź|Z, á|a, â|a, �?|a, ä|ae, ć|c, ç|c, č|c, ď|d, đ|d, é|e, ę|e, ë|e, ě|e, í|i, î|i, ĺ|l, ń|n, �?|n, ó|o, ô|o, ő|o, ö|oe, š|s, ś|s, ř|r, ŕ|r, ť|t, ů|u, ú|u, ű|u, ü|ue, ý|y, ž|z, ź|z, ˙|-, ß|ss, Ą|A, µ|u, �|ss, @|at, #|-, $|s, �|s, *|-, '|-, \"|-, +|-, �|Ae, �|Oe, �|Ue, �|ae, �|oe, �|ue";
		$chars_array = explode(",",$change_chars);
		foreach ($chars_array as $sef_char)
			{
			$split = explode("|",trim($sef_char));
			if(sizeof($split)==2)
				{
				$alias = str_replace($split[0],$split[1],$alias);
				}
			
			}
		$alias .=  "', '";
		
		}
	

	include_once(M4J_INCLUDE_FUNCTIONS);
	
	$link_to_cat = true;
	$link = 'index.php?option=com_mad4joomla';
	if($id==-999)
			{
			if($remember_cid !=-2)
				$link .= "&cid=".$remember_cid;
			}
	else 
		{
		$link .= "&jid=".$id;
		$link_to_cat = false;
		}
	
	if($menutype) define('M4J_LINK_FORM_READY',1);
	
	
   switch($task)
   	{
	case 'new':
	if($link_name)
		{
		$query = "SELECT id FROM #__components WHERE link ='option=com_mad4joomla' AND parent = '0' LIMIT 1";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();	
		$compID = $rows[0]->id;
		
		$parent = intval( mosGetParam( $_REQUEST, 'parent', 0 ) );
		$query = "SELECT MAX(ordering) AS MAX FROM #__menu WHERE parent ='".$parent."' AND menutype = '".$menutype."' ";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();	
		$maxpos = intval($rows[0]->MAX) +1 ;
				
		$query = "INSERT INTO #__menu"
						. "\n ( menutype, name,".$j15_query." link, type, published, parent, componentid, access, ordering, params )"
						. "\n VALUES"
						. "\n ( '".$menutype."', '".
						$link_name."', '".
						$alias.
						$link."','".$comp."' ,'".
						intval( mosGetParam( $_REQUEST, 'published', 1 ) )."', '".
						$parent."', '".$compID."', '".
						intval( mosGetParam( $_REQUEST, 'access', 0 ) )."', '".$maxpos."' ,'')";
		$database->setQuery($query);
		if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
							
		mosRedirect(M4J_JOBS.M4J_REMEMBER_CID_QUERY);
		}
	else
		{
		$error .= M4J_LANG_NO_LINK_NAME;
		}
	
	
	
	break;
	}	
		
		
		
		

  HTML_m4j::head(M4J_LINK,$error);
   
   if($link_to_cat) 
   		{
		switch($remember_cid)
			{
			case -2:
			$helpers->caption(M4J_LANG_LINK_TO_ALL_CAT,null,M4J_LANG_FORMS.' > '.M4J_LANG_LINK);
			break;
			
			case -1:
			$helpers->caption(M4J_LANG_LINK_TO_NO_CAT,null,M4J_LANG_FORMS.' > '.M4J_LANG_LINK);
			break;
			
			default:
			$helpers->caption(M4J_LANG_LINK_TO_CAT.$helpers->span($name,'m4j_green'),null,M4J_LANG_FORMS.' > '.M4J_LANG_LINK);
			break;
			}
		}
   else $helpers->caption(M4J_LANG_LINK_TO_FORM.$helpers->span($name,'m4j_green'),null,M4J_LANG_FORMS.' > '.M4J_LANG_LINK);
   
   
   
   	if(!$menutype) //* SHOW MENUS
   		{
		$helpers->advice(M4J_LANG_CHOOSE_MENU,3);		
		
		if(defined('_JEXEC')){
			$query = "SELECT * FROM #__menu_types ORDER BY `id`";
		}else{
			$query = "SELECT title,params FROM #__modules WHERE module = 'mod_mainmenu'  ORDER BY title";
		}
		
		$database->setQuery( $query );
		$rows = $database->loadObjectList();	
		HTML_m4j::link_menu($rows,$id,$name);	
		}//* EOF SHOW MENUS
	else //* SHOW MASK
		{
		$helpers->advice(M4J_LANG_MENU.$title,2);
		
		if(defined('_JEXEC'))
			{
			$menu = new JTableMenu($db);
			$menu->type='components';
			$menu->menutype = $menutype;
			$menu->browserNav = 0;
			$menu->ordering = 9999;
			$menu->parent =  intval( mosGetParam( $_REQUEST, 'parent', 0 ) );
			$menu->published = intval( mosGetParam( $_REQUEST, 'published', 1 ) );
			$menu->access =  intval( mosGetParam( $_REQUEST, 'access', 0 ) ) ;
			
			$parent = MenusHelper::Parent($menu);
			$access =  JHTML::_('list.accesslevel',  $menu);
			$published = MenusHelper::Published($menu);
			}
		else
			{
			$menu = new mosMenu($database);
			$menu->type='components';
			$menu->menutype = $menutype;
			$menu->browserNav = 0;
			$menu->ordering = 9999;
			$menu->parent =  intval( mosGetParam( $_REQUEST, 'parent', 0 ) );
			$menu->published = intval( mosGetParam( $_REQUEST, 'published', 1 ) );
			$menu->access =  intval( mosGetParam( $_REQUEST, 'access', 0 ) ) ;
			$parent = mosAdminMenus::Parent($menu);
			$access = mosAdminMenus::Access($menu);
			$published = mosAdminMenus::Published($menu);
			}
		
		
		
		HTML_m4j::link_form($id,$parent,$access,$published,$menutype,$name,$title);	
		}//* EOF SHOW MASK
	
	
	
					
   
  HTML_m4j::footer();
?>
