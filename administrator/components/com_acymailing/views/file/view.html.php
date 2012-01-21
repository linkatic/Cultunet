<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class FileViewFile extends JView
{
	function display($tpl = null)
	{
		$doc =& JFactory::getDocument();
		$doc->addStyleSheet( ACYMAILING_CSS.'frontendedition.css' );
		JRequest::setVar('tmpl','component');
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();
		parent::display($tpl);
	}
	function css(){
		$file = JRequest::getCmd('file');
		if(!preg_match('#^([-_A-Za-z0-9]*)_([-_A-Za-z0-9]*)$#i',$file,$result)){
			acymailing::display('Could not load the file '.$file.' properly');
			exit;
		}
		$type = $result[1];
		$fileName = $result[2];
		$content = JRequest::getString('csscontent');
		if(empty($content)) $content = file_get_contents(ACYMAILING_FRONT.'css'.DS.$type.'_'.$fileName.'.css');
		if($fileName == 'default'){
			$fileName = 'custom';
			$i = 1;
			while(file_exists(ACYMAILING_FRONT.'css'.DS.$type.'_'.$fileName.'.css')){
				$fileName = 'custom'.$i;
				$i++;
			}
		}
		$this->assignRef('content',$content);
		$this->assignRef('fileName',$fileName);
		$this->assignRef('type',$type);
	}
	function language(){
		$this->setLayout('default');
		$code = JRequest::getString('code');
		if(empty($code)){
			acymailing::display('Code not specified','error');
			return;
		}
		$file = null;
		$file->name = $code;
		$path = JLanguage::getLanguagePath(JPATH_ROOT).DS.$code.DS.$code.'.com_acymailing.ini';
		$file->path = $path;
		jimport('joomla.filesystem.file');
		$showLatest = true;
		$loadLatest = false;
		if(JFile::exists($path)){
			$file->content = JFile::read($path);
			if(empty($file->content)){
				acymailing::display('File not found : '.$path,'error');
			}
		}else{
			$loadLatest = true;
			acymailing::display(JText::_('LOAD_ENGLISH_1').'<br/>'.JText::_('LOAD_ENGLISH_2').'<br/>'.JText::_('LOAD_ENGLISH_3'),'info');
			$file->content = JFile::read(JLanguage::getLanguagePath(JPATH_ROOT).DS.'en-GB'.DS.'en-GB.com_acymailing.ini');
		}
		if($loadLatest OR JRequest::getString('task') == 'latest'){
			$doc =& JFactory::getDocument();
			$doc->addScript(ACYMAILING_UPDATEURL.'languageload&code='.JRequest::getString('code'));
			$showLatest = false;
		}elseif(JRequest::getString('task') == 'save') $showLatest = false;
		$this->assignRef('showLatest',$showLatest);
		$this->assignRef('file',$file);
	}
	function share(){
		$file = null;
		$file->name = JRequest::getString('code');
		$this->assignRef('file',$file);
	}
}