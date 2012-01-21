<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class plgSystemRegacymailing extends JPlugin
{
	function plgSystemRegacymailing(&$subject, $config){
		parent::__construct($subject, $config);
    }
	function onAfterRender(){
		$option = JRequest::getString('option');
		$view = JRequest::getString('view',JRequest::getString('task'));
		if(empty($option) OR empty($view)) return;
		$components = array();
		$components['com_user'] = array('view' => 'register','lengthafter' => 200);
		$components['com_alpharegistration'] = array('view' => 'register','lengthafter' => 250);
		$components['com_ccusers'] = array('view' => 'register','lengthafter' => 500);
		if(!isset($components[$option]) || $view != $components[$option]['view']) return;
		if(!include_once(rtrim(JPATH_ADMINISTRATOR,DS).DS.'components'.DS.'com_acymailing'.DS.'helpers'.DS.'helper.php')) return;
		if(!isset($this->params)){
			$plugin =& JPluginHelper::getPlugin('system', 'regacymailing');
			$this->params = new JParameter( $plugin->params );
		}
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
		$checkedLists = $this->params->get('listschecked','All');
		if(strtolower($checkedLists) == 'all'){ $checkedListsArray = $visibleListsArray;}
		elseif(strpos($checkedLists,',') OR is_numeric($checkedLists)){ $checkedListsArray = explode(',',$checkedLists);}
		else{ $checkedListsArray = array();}
		$subText = $this->params->get('subscribetext');
		if(empty($subText)){
			$lang =& JFactory::getLanguage();
			$lang->load(ACYMAILING_COMPONENT,JPATH_SITE);
			$subText = JText::_('SUBSCRIPTION').':';
		}
		$body = JResponse::getBody();
		$after = ($this->params->get('fieldafter','password') == 'email') ? 'email' : 'password2';
		$return = '';
		foreach($visibleListsArray as $oneList){
			$check = in_array($oneList,$checkedListsArray) ? 'checked="checked"' : '';
			$return .= '<tr><td><input type="checkbox" id="acy_list_'.$oneList.'" class="acymailing_checkbox" name="acysub[]" '.$check.' value="'.$oneList.'"/></td><td nowrap="nowrap"><label for="acy_list_'.$oneList.'" class="acylabellist">';
			$return .= $allLists[$oneList]->name;
			$return .= '</label></td></tr>';
		}
		if(preg_match('#(name="'.$after.'".{0,'.$components[$option]['lengthafter'].'}</tr>)#Uis',$body)){
			$return = '<tr class="acysubscribe"><td valign="top">'.$subText.'</td><td><table>'.$return.'</table></td></tr>';
			$body = preg_replace('#(name="'.$after.'".{0,'.$components[$option]['lengthafter'].'}</tr>)#Uis','$1'.$return,$body,1);
			JResponse::setBody($body);
			return;
		}
		if(preg_match('#(name="'.$after.'".{0,'.$components[$option]['lengthafter'].'}</div>)#Uis',$body)){
			$return = '<div class="acysubscribe"><label>'.$subText.'</label><table>'.$return.'</table></div>';
			$body = preg_replace('#(name="'.$after.'".{0,'.$components[$option]['lengthafter'].'}</div>)#Uis','$1'.$return,$body,1);
			JResponse::setBody($body);
			return;
		}
		if(preg_match('#(name="'.$after.'".{0,'.$components[$option]['lengthafter'].'}</p>)#Uis',$body)){
			$return = '<div class="acysubscribe"><label>'.$subText.'</label><table>'.$return.'</table></div>';
			$body = preg_replace('#(name="'.$after.'".{0,'.$components[$option]['lengthafter'].'}</p>)#Uis','$1'.$return,$body,1);
			JResponse::setBody($body);
			return;
		}
	 }
	function onBeforeStoreUser($user, $isnew){
		$this->oldUser = $user;
		return true;
	}
	function onAfterStoreUser($user, $isnew, $success, $msg){
		if($success===false) return false;
		if(!include_once(rtrim(JPATH_ADMINISTRATOR,DS).DS.'components'.DS.'com_acymailing'.DS.'helpers'.DS.'helper.php')) return true;
		if(!isset($this->params)){
			$plugin =& JPluginHelper::getPlugin('system', 'regacymailing');
			$this->params = new JParameter( $plugin->params );
		}
		$joomUser = null;
		$joomUser->email = trim(strip_tags($user['email']));
		if(!empty($user['name'])) $joomUser->name = trim(strip_tags($user['name']));
		if(empty($user['block'])) $joomUser->confirmed = 1;
		$joomUser->enabled = 1 - (int)$user['block'];
		$joomUser->userid = $user['id'];
		$userClass = acymailing::get('class.subscriber');
		if(!$isnew AND !empty($this->oldUser['email']) AND $user['email'] != $this->oldUser['email']){
			$joomUser->subid = $userClass->subid($this->oldUser['email']);
		}
		if(empty($joomUser->subid)){
			$joomUser->subid = $userClass->subid($joomUser->userid);
		}
		$userClass->checkVisitor = false;
		$userClass->sendConf = false;
		if(isset($joomUser->email)){
			$userHelper = acymailing::get('helper.user');
			if(!$userHelper->validEmail($joomUser->email)) return true;
		}
		$subid = $userClass->save($joomUser);
		if($isnew || empty($joomUser->subid)){
			$listsToSubscribe = $this->params->get('autosub','all');
			$currentSubscription = $userClass->getSubscriptionStatus($subid);
			$config = acymailing::config();
			$listsClass = acymailing::get('class.list');
			$allLists = $listsClass->getLists('listid');
			if(acymailing::level(1)){
				$allLists = $listsClass->onlyCurrentLanguage($allLists);
			}
			$visiblelistschecked = JRequest::getVar( 'acysub', array(), '', 'array' );
			if(empty($visiblelistschecked) AND !empty($_SESSION['acysub'])){
				$visiblelistschecked = $_SESSION['acysub'];
				unset($_SESSION['acysub']);
			}
			$listsArray = array();
			if(strpos($listsToSubscribe,',') OR is_numeric($listsToSubscribe)){
				$listsArrayParam = explode(',',$listsToSubscribe);
				foreach($allLists as $oneList){
					if($oneList->published AND (in_array($oneList->listid,$visiblelistschecked) || in_array($oneList->listid,$listsArrayParam))){$listsArray[] = $oneList->listid;}
				}
			}elseif(strtolower($listsToSubscribe) == 'all'){
				foreach($allLists as $oneList){
					if($oneList->published){$listsArray[] = $oneList->listid;}
				}
			}elseif(!empty($visiblelistschecked)){
				foreach($allLists as $oneList){
					if($oneList->published AND in_array($oneList->listid,$visiblelistschecked)){$listsArray[] = $oneList->listid;}
				}
			}
			$statusAdd = (empty($joomUser->enabled) OR (empty($joomUser->confirmed) AND $config->get('require_confirmation',false))) ? 2 : 1;
			$addlists = array();
			if(!empty($listsArray)){
				foreach($listsArray as $idOneList){
					if(!isset($currentSubscription[$idOneList])){
						$addlists[$statusAdd][] = $idOneList;
					}
				}
			}
			if(!empty($addlists)) {
				$listsubClass = acymailing::get('class.listsub');
				if(!empty($user['gid'])) $listsubClass->gid = $user['gid'];
				$listsubClass->addSubscription($subid,$addlists);
			}
		}else{
			if(!empty($this->oldUser['block']) AND !empty($joomUser->confirmed)){
				$userClass->confirmSubscription($subid);
			}
		}
		return true;
	}
	function onAfterDeleteUser($user, $success, $msg){
		if($success===false) return false;
		if(!include_once(rtrim(JPATH_ADMINISTRATOR,DS).DS.'components'.DS.'com_acymailing'.DS.'helpers'.DS.'helper.php')) return true;
		$userClass = acymailing::get('class.subscriber');
		$subid = $userClass->subid($user['email']);
		if(!empty($subid)){
			$userClass->delete($subid);
		}
		return true;
	}
}//endclass