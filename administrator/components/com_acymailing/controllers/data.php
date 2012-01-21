<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class DataController extends acymailingController{
	function import(){
		JRequest::setVar( 'layout', 'import'  );
		JRequest::setVar('hidemainmenu',1);
		return parent::display();
	}
	function export(){
		JRequest::setVar('hidemainmenu',1);
		JRequest::setVar( 'layout', 'export'  );
		return parent::display();
	}
	function doimport(){
		JRequest::checkToken() or die( 'Invalid Token' );
		$function = JRequest::getCmd('importfrom');
		$importHelper = acymailing::get('helper.import');
		if(!$importHelper->$function()){
			return $this->import();
		}
		$this->setRedirect(acymailing::completeLink('subscriber',false,true));
	}
	function doexport(){
		JRequest::checkToken() or die( 'Invalid Token' );
		acymailing::increasePerf();
		$filtersExport = JRequest::getVar('exportfilter');
		$listsToExport = JRequest::getVar('exportlists');
		$fieldsToExport = JRequest::getVar('exportdata');
		$exportLists = array();
		if(!empty($filtersExport['subscribed'])){
			foreach($listsToExport as $listid => $checked){
				if(!empty($checked)) $exportLists[] = (int) $listid;
			}
		}
		$exportFields = array();
		foreach($fieldsToExport as $fieldName => $checked){
			if(!empty($checked)) $exportFields[] = acymailing::secureField($fieldName);
		}
		$where = array();
		if(empty($exportLists)){
			$querySelect = 'SELECT s.`'.implode('`,s.`',$exportFields).'` FROM '.acymailing::table('subscriber').' as s';
		}else{
			$querySelect = 'SELECT DISTINCT s.`'.implode('`,s.`',$exportFields).'` FROM '.acymailing::table('listsub').' as a LEFT JOIN '.acymailing::table('subscriber').' as s on a.subid = s.subid';
			$where[] = 'a.listid IN ('.implode(',',$exportLists).')';
			$where[] = 'a.status = 1';
		}
		if(!empty($filtersExport['confirmed'])) $where[] = 's.confirmed = 1';
		if(!empty($filtersExport['registered'])) $where[] = 's.userid > 0';
		if(JRequest::getInt('sessionvalues') AND !empty($_SESSION['acymailing']['exportusers'])){
			$where[] = 's.subid IN ('.implode(',',$_SESSION['acymailing']['exportusers']).')';
		}
		$query = $querySelect;
		if(!empty($where)) $query .= ' WHERE ('.implode(') AND (',$where).')';
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$allData = $db->loadAssocList();
		$exportFormat = JRequest::getString('exportformat');
		$encodingClass = acymailing::get('helper.encoding');
 		@ob_clean();
		header("Pragma: public");
		header("Expires: 0"); // set expiration time
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment; filename=acymailingexport.csv;");
		header("Content-Transfer-Encoding: binary");
		$eol= "\r\n";
		$before = '"';
		$separator = '";"';
		$after = '"';
		echo $before.implode($separator,$exportFields).$after.$eol;
		for($i=0,$a=count($allData);$i<$a;$i++){
			if(!empty($allData[$i]['created'])) $allData[$i]['created'] = acymailing::getDate($allData[$i]['created'],'%Y-%m-%d %H:%M:%S');
			echo $before.$encodingClass->change(implode($separator,$allData[$i]),'UTF-8',$exportFormat).$after.$eol;
		}
		exit;
	}
}