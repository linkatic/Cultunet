<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

/**
 * @package Joomla
 * @subpackage Brezza
 * @since 1.1.0
 */
class LyftenBloggieViewEmail extends JView
{
	/**
	 * @param	string template	Template file name
	 **/	 	
	function display( $tpl = null )
	{
		// Lets get some HELP!!!!
		require_once (JPATH_COMPONENT.DS.'helper.php');

		//initialise variables
		$document	= & JFactory::getDocument();

		//add stuff to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');	
		$document->addScript('components/com_lyftenbloggie/assets/js/help.js');	

		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		//Get data from the model
		$rows    = & $this->get( 'Emails');

		//create the toolbar		
		JToolBarHelper::title( JText::_( 'SETTINGS' ).': '.JText::_('EMAILS'), 'lbconfig' );

		//assign data to template
		$this->assignRef('rows', $rows);

		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		global $mainframe;

		//SET TOOLBAR AND TITLE
		JToolBarHelper::title( JText::_( 'SETTINGS' ).': '.JText::_('EDIT EMAIL'), 'lbconfig' );
		JToolBarHelper::save();
        JToolBarHelper::apply();
        JToolBarHelper::divider();
        JToolBarHelper::cancel();
        JToolBarHelper::spacer();

		$filename 	= JRequest::getVar('file');
		$file 		= BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'emails'.DS.$filename;

		if ($fp = fopen($file, 'r'))
		{
			$content = fread($fp, filesize($file));
			$content = htmlspecialchars($content);
			fclose($fp);
		} else {
			$mainframe->redirect('index.php?option=com_lyftenbloggie&view=email', JText::_("OPERATION FAILED COULDNT OPEN ") . $file);
			return;
		}

		//Vairables Used
		switch ($filename) {
			case 'new_comment.tpl':
				$used = array(JText::_('VALUE_RECEIVER_NAME')=>'{receiver.name}', JText::_('VALUE_ENTRY_ID')=>'{entry.id}', JText::_('VALUE_ENTRY_URL')=>'{entry.url}', JText::_('VALUE_ENTRY_TITLE')=>'{entry.title}', JText::_('VALUE_COMMENT_AUTHOR')=>'{comment.author}', JText::_('VALUE_COMMENT_TEXT')=>'{comment.text}');
				break;
			case 'pending_entry.tpl':
				$used = array(JText::_('VALUE_RECEIVER_NAME')=>'{receiver.name}', JText::_('VALUE_ENTRY_TITLE')=>'{entry.title}', JText::_('VALUE_ENTRY_URL')=>'{entry.url}', JText::_('VALUE_ADMIN_PENDING')=>'{admin.pending}', JText::_('VALUE_ENTRY_AUTHOR')=>'{entry.author}');
				break;
			case 'new_pending_entry.tpl':
				$used = array(JText::_('VALUE_RECEIVER_NAME')=>'{receiver.name}', JText::_('VALUE_ENTRY_TITLE')=>'{entry.title}', JText::_('VALUE_ENTRY_URL')=>'{entry.url}', JText::_('VALUE_ADMIN_PENDING')=>'{admin.pending}', JText::_('VALUE_ENTRY_AUTHOR')=>'{entry.author}');
				break;
			case 'new_entry.tpl':
				$used = array(JText::_('VALUE_RECEIVER_NAME')=>'{receiver.name}', JText::_('VALUE_ENTRY_TITLE')=>'{entry.title}', JText::_('VALUE_ENTRY_URL')=>'{entry.url}', JText::_('VALUE_ENTRY_AUTHOR')=>'{entry.author}');
				break;
			case 'pending_delete.tpl':
				$used = array(JText::_('VALUE_RECEIVER_NAME')=>'{receiver.name}', JText::_('VALUE_ENTRY_TITLE')=>'{entry.title}', JText::_('VALUE_ENTRY_URL')=>'{entry.url}', JText::_('VALUE_ADMIN_PENDING')=>'{admin.pending}');
				break;
			case 'comment_report.tpl':
				$used = array(JText::_('VALUE_RECEIVER_NAME')=>'{receiver.name}', JText::_('VALUE_ENTRY_TITLE')=>'{entry.title}', JText::_('VALUE_REPORT_URL')=>'{report.url}', JText::_('VALUE_REPORT_AUTHOR')=>'{report.author}', JText::_('VALUE_REPORT_TYPE')=>'{report.type}');
				break;
			default:
				$used = array();
				break;
		}

		//assign data to template
		$this->assignRef('content',		$content);
		$this->assignRef('file',		$file);
		$this->assignRef('filename',	$filename);
		$this->assignRef('used',		$used);
		$this->assignRef('writable',	is_writable($file));

		parent::display($tpl);
	}
}
