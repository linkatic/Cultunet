<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class listsubClass extends acymailingClass{
	var $type = 'list';
	var $gid;
	var $checkAccess = true;
	var $sendNotif = true;
	var $sendConf = true;
	function updateSubscription($subid,$lists){
		$result = true;
		$time = time();
		$listHelper = acymailing::get('helper.list');
		$listHelper->sendNotif = $this->sendNotif;
		$listHelper->sendConf = $this->sendConf;
		foreach($lists as $status => $listids){
			if(empty($listids)) continue;
			JArrayHelper::toInteger($listids);
			//-1 is unsubscribe
			if($status == '-1') $column = 'unsubdate';
			else $column = 'subdate';
			$query = 'UPDATE '.acymailing::table('listsub').' SET `status` = '.intval($status).','.$column.'='.$time.' WHERE subid = '.intval($subid).' AND listid IN ('.implode(',',$listids).')';
			$this->database->setQuery($query);
			$result = $this->database->query() && $result;
			if($status == 1){
				$listHelper->subscribe($subid,$listids);
			}elseif($status == -1){
				$listHelper->unsubscribe($subid,$listids);
			}
		}
		return $result;
	}
	function removeSubscription($subid,$listids){
		JArrayHelper::toInteger($listids);
		$query = 'DELETE FROM '.acymailing::table('listsub').' WHERE subid = '.intval($subid).' AND listid IN ('.implode(',',$listids).')';
		$this->database->setQuery($query);
		$this->database->query();
		$listHelper = acymailing::get('helper.list');
		$listHelper->sendNotif = $this->sendNotif;
		$listHelper->unsubscribe($subid,$listids);
		return true;
	}
	function addSubscription($subid,$lists){
		$app =& JFactory::getApplication();
		$my = JFactory::getUser();
		$result = true;
		$time = time();
		$subid = intval($subid);
		$listHelper = acymailing::get('helper.list');
		foreach($lists as $status => $listids){
			$status = intval($status);
			JArrayHelper::toInteger($listids);
			$this->database->setQuery('SELECT `listid`,`access_sub` FROM '.acymailing::table('list').' WHERE `listid` IN ('.implode(',',$listids).') AND `type` = \'list\'');
			$allResults = $this->database->loadObjectList('listid');
			$listids = array_keys($allResults);
			//-1 is unsubscribe
			if($status == '-1') $column = 'unsubdate';
			else $column = 'subdate';
			$values = array();
			foreach($listids as $listid){
				if(empty($listid)) continue;
				if($status > 0 && acymailing::level(3)){
					if(!$app->isAdmin() && $this->checkAccess && $allResults[$listid]->access_sub != 'all'){
						$gid = (empty($my->id) OR empty($my->gid)) ? $this->gid : $my->gid;
						if($allResults[$listid]->access_sub == 'none' OR empty($gid)) continue;
						if(!in_array($gid,explode(',',$allResults[$listid]->access_sub))) continue;
					}
				}
				$values[] = intval($listid).','.$subid.','.$status.','.$time;
			}
			if(empty($values)) continue;
			$query = 'INSERT INTO '.acymailing::table('listsub').' (listid,subid,`status`,'.$column.') VALUES ('.implode('),(',$values).')';
			$this->database->setQuery($query);
			$result = $this->database->query() && $result;
			if($status == 1){
				$listHelper->subscribe($subid,$listids);
			}
		}
		return $result;
	}
	function getSubscription($subid){
		$query = 'SELECT * FROM '.acymailing::table('listsub').' as a LEFT JOIN '.acymailing::table('list').' as b on a.listid = b.listid WHERE a.subid = '.intval($subid).' AND b.type = \''.$this->type.'\' ORDER BY b.ordering ASC';
		$this->database->setQuery($query);
		return $this->database->loadObjectList('listid');
	}
}
