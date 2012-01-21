<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
require_once( JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');

class plgCommunityAcymailing_jomsocial extends CApplications
{
	var $name	= 'AcyMailing';
	var $_name	= 'AcyMailing';

	function plgCommunityAcymailing_jomsocial(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	function onUserRegisterFormDisplay(&$body)
	{
		if(!include_once(rtrim(JPATH_ADMINISTRATOR,DS).DS.'components'.DS.'com_acymailing'.DS.'helpers'.DS.'helper.php')) return;

		unset($_SESSION['acysub']);

		$visibleLists = $this->params->get('lists','None');
		if($visibleLists == 'None') return;

		$visibleListsArray = array();
		$listsClass = acymailing::get('class.list');
		$allLists = $listsClass->getLists('listid');
		if(acymailing::level(1)){
			$allLists = $listsClass->onlyCurrentLanguage($allLists);
		}

		if(strpos($visibleLists,',') OR is_numeric($visibleLists)){
			$allvisiblelists = explode(',',$visibleLists);
			foreach($allLists as $oneList){
				if($oneList->published AND in_array($oneList->listid,$allvisiblelists)) $visibleListsArray[] = $oneList->listid;
			}
		}elseif(strtolower($visibleLists) == 'all'){
			foreach($allLists as $oneList){
				if($oneList->published){$visibleListsArray[] = $oneList->listid;}
			}
		}

		if(empty($visibleListsArray)) return;

		//Check lists...
		$checkedLists = $this->params->get('listschecked','All');
		if(strtolower($checkedLists) == 'all'){ $checkedListsArray = $visibleListsArray;}
		elseif(strpos($checkedLists,',') OR is_numeric($checkedLists)){ $checkedListsArray = explode(',',$checkedLists);}
		else{ $checkedListsArray = array();}

		$subText = $this->params->get('subscribetext');
		if(empty($subText)){
			$lang =& JFactory::getLanguage();
			$lang->load(ACYMAILING_COMPONENT,JPATH_SITE);
			$subText = JText::_('SUBSCRIPTION');
		}

		if($this->params->get('overlay',0)) JHTML::_('behavior.tooltip');

		$after = ($this->params->get('fieldafter','password') == 'email') ? 'jsemail' : 'jspassword2';

		$return = '';
		foreach($visibleListsArray as $oneList){
			$check = in_array($oneList,$checkedListsArray) ? 'checked="checked"' : '';
			$return .= '<tr><td><input type="checkbox" id="acy_list_'.$oneList.'" class="acymailing_checkbox" name="acysub[]" '.$check.' value="'.$oneList.'"/></td><td nowrap="nowrap"><label for="acy_list_'.$oneList.'" class="acylabellist">';
			if($this->params->get('overlay',0)){
				$return .= acymailing::tooltip($allLists[$oneList]->description,$allLists[$oneList]->name,'',$allLists[$oneList]->name);
			}else{
				$return .= $allLists[$oneList]->name;
			}
			$return .= '</label></td></tr>';
		}
		if(preg_match('#(name="'.$after.'".{0,300}</tr>)#Uis',$body)){
			$return = '<tr class="acysubscribe"><td class="paramlist_key" valign="top">'.$subText.'</td><td class="paramlist_value"><table>'.$return.'</table></td></tr>';
			$body = preg_replace('#(name="'.$after.'".{0,300}</tr>)#Uis','$1'.$return,$body,1);
			return;
		}
	}

	function onBeforeControllerCreate( &$controllerClassName )
	{
		if($controllerClassName != 'CommunityRegisterController' || empty($_POST['task']) || $_POST['task'] != 'register_save') return false;

		$acysub = JRequest::getVar( 'acysub', array(), '', 'array' );
		if(!empty($acysub)) $_SESSION['acysub'] = $acysub;
		return false;
	}
}