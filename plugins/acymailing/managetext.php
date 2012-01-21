<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
defined('_JEXEC') or die('Restricted access');
class plgAcymailingManagetext extends JPlugin
{
	function plgAcymailingManagetext(&$subject, $config){
		parent::__construct($subject, $config);
		if(!isset($this->params)){
			$plugin =& JPluginHelper::getPlugin('acymailing', 'managetext');
			$this->params = new JParameter( $plugin->params );
		}
    }
    function acymailing_replaceusertagspreview(&$email,&$user){
		return $this->acymailing_replaceusertags($email,$user);
	}
	function acymailing_replaceusertags(&$email,&$user){
		$this->_removetext($email);
		$this->_addfooter($email);
		$this->_tableContent($email);
	}
	function _tableContent(&$email){
		$match = '#{tableofcontents}#Ui';
		if(empty($email->body)) return;
		if(!preg_match_all($match,$email->body,$results)) return;
		$tags = array();
		foreach($results[0] as $i => $oneTag){
			if(isset($tags[$oneTag])) continue;
			$tags[$oneTag] = '';

			preg_match_all('#<a[^>]*name="([^">]*)"[^>]*>((?!</ *a>).)*</ *a>#Uis',$email->body,$anchorresults);
			if(empty($anchorresults)) $tags[$oneTag] = '';
			$links = array();
			foreach($anchorresults[0] as $i => $oneContent){
				$linktext = strip_tags($oneContent);
				if(empty($linktext)) continue;
				$links[] = '<a href="#'.$anchorresults[1][$i].'" >'.$linktext.'</a>';
			}
			if(empty($links)) continue;
			$tags[$oneTag] = '<div class="tableofcontents">'.implode('<br />',$links).'</div>';
		}
		$variables = array('subject','body','altbody');
		foreach($variables as $var){
			$email->$var = str_replace(array_keys($tags),$tags,$email->$var);
		}
	}
	function _removetext(&$email){
		$removetext = $this->params->get('removetext','{reg},{/reg},{pub},{/pub}');
		if(!empty($removetext)){
			$removeArray = explode(',',$removetext);
			if(!empty($email->body)) $email->body = str_replace($removeArray,'',$email->body);
			if(!empty($email->altbody)) $email->altbody = str_replace($removeArray,'',$email->altbody);
		}
	}
	function _addfooter(&$email){
		$footer = $this->params->get('footer');
		if(!empty($footer)){
			if(strpos($email->body,'</body>')){
				$email->body = str_replace('</body>','<br/>'.$footer.'</body>',$email->body);
			}else{
				$email->body .= '<br/>'.$footer;
			}
			if(!empty($email->altbody)){
				$email->altbody .= "\n".$footer;
			}
		}
	}
	 function onAcyDisplayActions(&$type){
	 	$type['addqueue'] = JText::_('ADD_QUEUE');
	 	$type['removequeue'] = JText::_('REMOVE_QUEUE');
	 	$db =& JFactory::getDBO();
		$db->setQuery("SELECT `mailid`,`subject`, `type` FROM `#__acymailing_mail` WHERE `type` NOT IN ('notification','autonews') OR `alias` = 'confirmation' ORDER BY `type`,`subject` ASC ");
		$allEmails = $db->loadObjectList();
		$emailsToDisplay = array();
		$typeNews = '';
		foreach($allEmails as $oneMail){
			if($oneMail->type != $typeNews){
				if(!empty($typeNews)) $emailsToDisplay[] = JHTML::_('select.option',  '</OPTGROUP>');
				$typeNews = $oneMail->type;
				if($oneMail->type == 'notification'){
					$label = JText::_('NOTIFICATIONS');
				}elseif($oneMail->type == 'news'){
					$label = JText::_('NEWSLETTERS');
				}elseif($oneMail->type == 'followup'){
					$label = JText::_('FOLLOWUP');
				}elseif($oneMail->type == 'welcome'){
					$label = JText::_('MSG_WELCOME');
				}elseif($oneMail->type == 'unsub'){
					$label = JText::_('MSG_UNSUB');
				}else{
					$label = $oneMail->type;
				}
				$emailsToDisplay[] = JHTML::_('select.option',  '<OPTGROUP>', $label );
			}
			$emailsToDisplay[] = JHTML::_('select.option', $oneMail->mailid, $oneMail->subject.' ('.$oneMail->mailid.')' );
		}
		$emailsToDisplay[] = JHTML::_('select.option',  '</OPTGROUP>');
	 	$addqueue = '<div id="action__num__addqueue">'.JHTML::_('select.genericlist',  $emailsToDisplay, "action[__num__][addqueue][mailid]", 'class="inputbox" size="1"').'<br /><label for="addqueuesenddate__num__">'.JText::_('SEND_DATE').' </label> <input value="{time}" id="addqueuesenddate__num__" name="action[__num__][addqueue][senddate]" /></div>';
	 	$removequeue = '<div id="action__num__removequeue">'.JHTML::_('select.genericlist',  $emailsToDisplay, "action[__num__][removequeue][mailid]", 'class="inputbox" size="1"').'</div>';
	 	return $addqueue.$removequeue;
	 }
	 function onAcyProcessAction_addqueue($cquery,$action,$num){
	 	$action['mailid'] = intval($action['mailid']);
	 	if(empty($action['mailid'])) return 'mailid not valid';
	 	$action['senddate'] = acymailing::replaceDate($action['senddate']);
	 	if(!is_numeric($action['senddate'])) $action['senddate'] = acymailing::getTime($action['senddate']);
	 	if(empty($action['senddate'])) return 'send date not valid';
	 	$query = 'INSERT IGNORE INTO `#__acymailing_queue` (`mailid`,`subid`,`senddate`,`priority`) '.$cquery->getQuery(array($action['mailid'],'sub.`subid`',$action['senddate'],'3'));
	 	$db =& JFactory::getDBO();
	 	$db->setQuery($query);
	 	$db->query();
	 	return JText::sprintf('ADDED_QUEUE',$db->getAffectedRows());
	 }
	 function onAcyProcessAction_removequeue($cquery,$action,$num){
	 	$action['mailid'] = intval($action['mailid']);
		if(empty($action['mailid'])) return 'mailid not valid';
		$query = 'DELETE queueremove.* FROM `#__acymailing_queue` as queueremove ';
		$query .= ' LEFT JOIN `#__acymailing_subscriber` as sub ON queueremove.subid = sub.subid ';
		if(!empty($cquery->leftjoin)) $query .= ' LEFT JOIN '.implode(' LEFT JOIN ',$cquery->leftjoin);
		$query .= ' WHERE queueremove.mailid = '.$action['mailid'];
		if(!empty($cquery->where)) $query .= ' AND ('.implode(') AND (',$cquery->where).')';
	 	$db =& JFactory::getDBO();
	 	$db->setQuery($query);
	 	$db->query();
	 	return JText::sprintf('SUCC_DELETE_ELEMENTS',$db->getAffectedRows());
	 }
}//endclass