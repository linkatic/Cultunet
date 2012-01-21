<?php
/**
 * No Direct Access
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PluginModules extends Adapters
{
    function site(){
        $db = JFactory::getDBO();
        $user = JFactory::getUSER();
        
    	//geting usertype from user
		$arrMultiGroups[] = $user->usertype;

		//get multigrop names if user have it
		$sqlGetMultigroups = "SELECT grp.name FROM #__core_acl_aro_groups as grp, #__noixacl_multigroups multigrp WHERE grp.id = multigrp.id_group AND multigrp.id_user = {$user->id}";
		$db->setQuery( $sqlGetMultigroups );
		$multiGroups = $db->loadObjectList();

		if( !empty($multiGroups) ){
			foreach($multiGroups as $mgrp){
				$arrMultiGroups[] = $mgrp->name;
			}
		}
        
        
        $queryModules = "SELECT axo_section, axo_value FROM #__noixacl_rules WHERE aco_section = 'com_modules' AND aco_value = 'block' AND aro_value IN ('". implode("','",$arrMultiGroups) ."')";
		$db->setQuery( $queryModules );
		$hideModules = $db->loadObjectList();
		
		if( !empty($hideModules) ){
			jimport('joomla.application.module.helper');
			
			foreach($hideModules as $module){
				$moduleInstance =& JModuleHelper::getModule(str_replace('mod_','',$module->axo_section),$module->axo_value);
				$moduleInstance->position = NULL;
			}
		}
    }
}