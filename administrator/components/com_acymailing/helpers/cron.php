<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class cronHelper{
	var $report = false;
	var $messages = array();
	var $detailMessages = array();
	var $processed = false;
	var $executed = false;
	function cron(){
		$time = time();
		$config = acymailing::config();
		$firstMessage = JText::sprintf('CRON_TRIGGERED',acymailing::getDate(time()));
		$this->messages[] = $firstMessage;
		if($this->report){
			acymailing::display($firstMessage,'info');
		}
		if($config->get('cron_next') > $time){
			if($config->get('cron_next') > ($time + $config->get('cron_frequency'))){
				$newConfig = null;
				$newConfig->cron_next = $time + $config->get('cron_frequency');
				$config->save($newConfig);
			}
			$nottime = JText::sprintf('CRON_NEXT',acymailing::getDate($config->get('cron_next')));
			$this->messages[] = $nottime;
			if($this->report){
				acymailing::display($nottime,'info');
			}
			return false;
		}
		$queueHelper = acymailing::get('helper.queue');
		if(!$config->get('li')) exit;
		$this->executed = true;
		$newConfig = null;
		$newConfig->cron_next = $config->get('cron_next') + $config->get('cron_frequency');
		if($newConfig->cron_next <= $time OR $newConfig->cron_next> $time + $config->get('cron_frequency')) $newConfig->cron_next = $time + $config->get('cron_frequency');
		$newConfig->cron_last = $time;
		$userHelper = acymailing::get('helper.user');
		$newConfig->cron_fromip = $userHelper->getIP();
		$config->save($newConfig);
		if($config->get('queue_type') != 'manual'){
			$queueHelper->report = false;
			$queueHelper->process();
			if(!empty($queueHelper->messages)){
				$this->detailMessages = array_merge($this->detailMessages,$queueHelper->messages);
			}
			if(!empty($queueHelper->nbprocess)) $this->processed = true;
			$this->messages[] = JText::sprintf('CRON_PROCESS',$queueHelper->nbprocess,$queueHelper->successSend,$queueHelper->errorSend);
			if(!empty($queueHelper->stoptime) AND time()>$queueHelper->stoptime) return true;
		}

		if(acymailing::level(2)){
			$autonewsHelper = acymailing::get('helper.autonews');
			$resultAutonews = $autonewsHelper->generate();
			if(!empty($autonewsHelper->messages)){
				$this->messages = array_merge($this->messages,$autonewsHelper->messages);
				$this->processed = true;
			}
			if(!empty($queueHelper->stoptime) AND time()>$queueHelper->stoptime) return true;
		}
		if(acymailing::level(1)){
			$schedHelper = acymailing::get('helper.schedule');
			$resultSchedule = $schedHelper->queueScheduled();
			if($resultSchedule){
				if(!empty($schedHelper->nbNewsletterScheduled)) $this->messages[] = JText::sprintf('NB_SCHED_NEWS',$schedHelper->nbNewsletterScheduled);
				$this->detailMessages = array_merge($this->detailMessages,$schedHelper->messages);
				$this->processed = true;
			}
			if(!empty($queueHelper->stoptime) AND time()>$queueHelper->stoptime) return true;
		}
		if($config->get('cron_plugins_next') < $time){
			$newConfig = null;
			$newConfig->cron_plugins_next = $config->get('cron_plugins_next',0) + 86400;
			if($newConfig->cron_plugins_next <= $time) $newConfig->cron_plugins_next = $time + 86400;
			$config->save($newConfig);
			JPluginHelper::importPlugin('acymailing');
			$dispatcher = &JDispatcher::getInstance();
			$resultsTrigger = $dispatcher->trigger('onAcyCronTrigger');
			if(!empty($resultsTrigger)) $this->processed = true;
			$this->messages = array_merge($this->messages,$resultsTrigger);
			$filterClass = acymailing::get('class.filter');
			$filterClass->trigger('daycron');
			if(!empty($queueHelper->stoptime) AND time()>$queueHelper->stoptime) return true;
		}
		if(acymailing::level(3) && $config->get('auto_bounce',0) && $time > (int)$config->get('auto_bounce_next',0) && (empty($queueHelper->stoptime) || time() < $queueHelper->stoptime-5)){
			$newConfig = null;
			$newConfig->auto_bounce_next = $time + (int) $config->get('auto_bounce_frequency',0);
			$newConfig->auto_bounce_last = $time;
			$config->save($newConfig);
			$bounceClass = acymailing::get('helper.bounce');
			$bounceClass->report = false;
			$newConfig = null;
			if($bounceClass->init() && $bounceClass->connect()){
				$nbMessages = $bounceClass->getNBMessages();
				$this->messages[] = JText::sprintf('NB_MAIL_MAILBOX',$nbMessages);
				$newConfig->auto_bounce_report = JText::sprintf('NB_MAIL_MAILBOX',$nbMessages);
				$this->detailMessages[] = JText::sprintf('NB_MAIL_MAILBOX',$nbMessages);
				if(!empty($nbMessages)){
					$bounceClass->handleMessages();
					$bounceClass->close();
					$this->processed = true;
				}
				$this->detailMessages = array_merge($this->detailMessages,$bounceClass->messages);
			}else{
				$bounceErrors = $bounceClass->getErrors();
				$newConfig->auto_bounce_report = implode('<br/>',$bounceErrors);
				$this->messages = array_merge($this->messages,$bounceErrors);
				$this->processed = true;
			}
			$config->save($newConfig);
			if(!empty($queueHelper->stoptime) AND time()>$queueHelper->stoptime) return true;
		}

		return true;
	}
	function report(){
		$config = acymailing::config();
		$sendreport = $config->get('cron_sendreport');
		$mailer = acymailing::get('helper.mailer');
		if(($sendreport == 2 && $this->processed) || $sendreport == 1 ){
			$mailer->report = false;
			$mailer->autoAddUser = true;
			$mailer->checkConfirmField = false;
			$mailer->addParam('report',implode('<br/>',$this->messages));
			$mailer->addParam('detailreport',implode('<br/>',$this->detailMessages));
			$receiverString = $config->get('cron_sendto');
			$receivers = explode(',',$receiverString);
			if(!empty($receivers)){
				foreach($receivers as $oneReceiver){
					$mailer->sendOne('report',$oneReceiver);
				}
			}
		}
		if(!$this->executed) return;
		$newConfig = null;
		$newConfig->cron_report = implode('<br/>',$this->messages);
		if(strlen($newConfig->cron_report) > 800) $newConfig->cron_report = substr($newConfig->cron_report,0,795).'...';
		$config->save($newConfig);
		if(!$this->processed) return;
		$saveReport = $config->get('cron_savereport');
		if($this->processed AND !empty($saveReport)){
			$reportPath = JPath::clean(ACYMAILING_ROOT.trim(html_entity_decode($config->get('cron_savepath'))));
			file_put_contents($reportPath, "\r\n"."\r\n".str_repeat('*',150)."\r\n".str_repeat('*',20).str_repeat(' ',5).acymailing::getDate(time()).str_repeat(' ',5).str_repeat('*',20)."\r\n", FILE_APPEND);
			@file_put_contents($reportPath, implode("\r\n",$this->messages), FILE_APPEND);
			if($saveReport == 2 AND !empty($this->detailMessages)){
				@file_put_contents($reportPath, "\r\n"."---- Details ----"."\r\n", FILE_APPEND);
				@file_put_contents($reportPath, implode("\r\n",$this->detailMessages), FILE_APPEND);
			}
		}
	}
}//endclass