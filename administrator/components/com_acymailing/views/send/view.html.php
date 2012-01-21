<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class SendViewSend extends JView
{
	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();
		parent::display($tpl);
	}
	function sendconfirm(){
		$mailid = acymailing::getCID('mailid');
		$mailClass = acymailing::get('class.mail');
		$listmailClass = acymailing::get('class.listmail');
		$queueClass = acymailing::get('class.queue');
		$mail = $mailClass->get($mailid);
		$values = null;
		$values->nbqueue = $queueClass->nbQueue($mailid);
		if(empty($values->nbqueue)){
			$lists = $listmailClass->getReceivers($mailid);
			$this->assignRef('lists',$lists);
			$db =& JFactory::getDBO();
			$db->setQuery('SELECT count(subid) FROM `#__acymailing_userstats` WHERE `mailid` = '.intval($mailid));
			$values->alreadySent = $db->loadResult();
		}
		$this->assignRef('values',$values);
		$this->assignRef('mail',$mail);
	}
	function scheduleconfirm(){
		$mailid = acymailing::getCID('mailid');
		$listmailClass = acymailing::get('class.listmail');
		$mailClass = acymailing::get('class.mail');
		$this->assignRef('lists',$listmailClass->getReceivers($mailid));
		$this->assignRef('mail',$mailClass->get($mailid));
	}
	function addqueue(){
		$subid = JRequest::getInt('subid');
		if(empty($subid)) exit;
		$subscriberClass = acymailing::get('class.subscriber');
		$subscriber = $subscriberClass->getFull($subid);
		$db =& JFactory::getDBO();
		$db->setQuery("SELECT `mailid`,CONCAT(`subject`,' ( ',`mailid`,' )') as 'value' FROM `#__acymailing_mail` WHERE `published` = 1 ORDER BY `subject` ASC ");
		$allemails = $db->loadObjectList();
		$emaildrop = JHTML::_('select.genericlist',  $allemails, 'mailid', 'class="inputbox" size="1"', 'mailid', 'value',JRequest::getInt('mailid'));
		$this->assignRef('subscriber',$subscriber);
		$this->assignRef('emaildrop',$emaildrop);
	}
}