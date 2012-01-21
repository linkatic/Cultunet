<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class userViewuser extends JView
{
	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();
		parent::display($tpl);
	}
	function modify(){
		$app =& JFactory::getApplication();
		$pathway	=& $app->getPathway();
		$document	=& JFactory::getDocument();
		$listsClass = acymailing::get('class.list');
		$subscriberClass = acymailing::get('class.subscriber');
		$subscriber = $subscriberClass->identify(true);
		if(empty($subscriber)){
			$subscription = $listsClass->getLists('listid');
			$subscriber = null;
			$subscriber->html = 1;
			$subscriber->subid = 0;
			$subscriber->key = 0;
			if(!empty($subscription)){
				foreach($subscription as $id => $onesub){
					$subscription[$id]->status = 1;
				}
			}
			$pathway->addItem(JText::_('SUBSCRIPTION'));
			$document->setTitle( JText::_('SUBSCRIPTION'));
		}else{
			$subscription = $subscriberClass->getSubscription($subscriber->subid,'listid');
			$pathway->addItem(JText::_('MODIFY_SUBSCRIPTION'));
			$document->setTitle( JText::_('MODIFY_SUBSCRIPTION'));
		}
		acymailing::initJSStrings();
		if(acymailing::level(1)){
			$subscription = $listsClass->onlyCurrentLanguage($subscription);
		}
		if(acymailing::level(3)){
			$fieldsClass = acymailing::get('class.fields');
			$this->assignRef('fieldsClass',$fieldsClass);
			$extraFields = $fieldsClass->getFields('frontcomp',$subscriber);
			$this->assignRef('extraFields',$extraFields);
			$requiredFields = array();
			$validMessages = array();
			foreach($extraFields as $oneField){
				if(in_array($oneField->namekey,array('name','email'))) continue;
				if(!empty($oneField->required)){
					$requiredFields[] = $oneField->namekey;
					if(!empty($oneField->options['errormessage'])){
						$validMessages[] = addslashes($fieldsClass->trans($oneField->options['errormessage']));
					}else{
						$validMessages[] = addslashes(JText::sprintf('FIELD_VALID',$fieldsClass->trans($oneField->fieldname)));
					}
				}
			}
			if(!empty($requiredFields)){
				$js = "<!--
				acymailing['reqFieldsComp'] = Array('".implode("','",$requiredFields)."');
				acymailing['validFieldsComp'] = Array('".implode("','",$validMessages)."');
				//-->";
				$doc =& JFactory::getDocument();
				$doc->addScriptDeclaration( $js );
			}
			$my = JFactory::getUser();
			foreach($subscription as $listid => $oneList){
				if(!$subscription[$listid]->published) continue;
				if($oneList->access_sub == 'all') continue;
				if($oneList->access_sub == 'none' OR empty($my->id)){
					$subscription[$listid]->published = false;
					continue;
				}
				if(!acymailing::is_allowed($oneList->access_sub)){
					$subscription[$listid]->published = false;
					continue;
				}
			}
		}
		$displayLists = false;
		foreach($subscription as $oneSub){
			if(!empty( $oneSub->published) AND $oneSub->visible){
				$displayLists = true;
				break;
			}
		}
		$this->assignRef('status',acymailing::get('type.festatus'));
		$this->assignRef('subscription',$subscription);
		$this->assignRef('subscriber',$subscriber);
		$this->assignRef('displayLists',$displayLists);
		$this->assignRef('config',acymailing::config());
	}
	function saveunsub(){
		$subscriberClass = acymailing::get('class.subscriber');
		$subscriber = $subscriberClass->identify();
		$this->assignRef('subscriber',$subscriber);
		$listid = JRequest::getInt('listid');
		if(!empty($listid)){
			$listClass = acymailing::get('class.list');
			$mylist = $listClass->get($listid);
			$this->assignRef('list',$mylist);
		}
	}
	function unsub(){
		$subscriberClass = acymailing::get('class.subscriber');
		$subscriber = $subscriberClass->identify();
		$this->assignRef('subscriber',$subscriber);
		$mailid = JRequest::getInt('mailid');
		$this->assignRef('mailid',$mailid);
		if(!empty($mailid)){
			$classListmail = acymailing::get('class.listmail');
			$lists = $classListmail->getLists($mailid);
			$this->assignRef('lists',$lists);
		}
		$app =& JFactory::getApplication();
		$pathway	=& $app->getPathway();
		$pathway->addItem(JText::_('UNSUBSCRIBE'));
		$document	=& JFactory::getDocument();
		$document->setTitle( JText::_('UNSUBSCRIBE'));
	}
}