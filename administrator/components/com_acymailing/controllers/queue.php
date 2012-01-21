<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class QueueController extends acymailingController{
	function remove(){
		JRequest::checkToken() or die( 'Invalid Token' );
		$mailid = JRequest::getVar('filter_mail',0,'post','int');
		$queueClass = acymailing::get('class.queue');
		$search = JRequest::getString('search');
		$filters = array();
		if(!empty($search)){
			$db = JFactory::getDBO();
			$searchVal = '\'%'.$db->getEscaped($search,true).'%\'';
			$searchFields = array('b.name','b.email','c.subject','a.mailid','a.subid');
			$filters[] = implode(" LIKE $searchVal OR ",$searchFields)." LIKE $searchVal";
		}
		if(!empty($mailid)){
			$filters[] = 'a.mailid = '.intval($mailid);
		}
		$total = $queueClass->delete($filters);
		$app =& JFactory::getApplication();
		$app->enqueueMessage(JText::sprintf( 'SUCC_DELETE_ELEMENTS',$total ), 'message');
		JRequest::setVar('filter_mail',0,'post');
		JRequest::setVar('search','','post');
		return $this->listing();
	}
	function process(){
		JRequest::setVar( 'layout', 'process'  );
		return parent::display();
	}
}