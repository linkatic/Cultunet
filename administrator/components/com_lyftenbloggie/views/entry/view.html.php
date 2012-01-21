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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the entry View
 *
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.0
 */
class LyftenBloggieViewEntry extends JView {

	function display($tpl = null)
	{
		global $mainframe;

		//Load pane behavior
		jimport('joomla.html.pane');

		//initialise variables
		$editor 	= & JFactory::getEditor();
		$document	= & JFactory::getDocument();
		$user 		= & JFactory::getUser();
		$db  		= & JFactory::getDBO();
		$pane 		= & JPane::getInstance('sliders');
		$cid 		= JRequest::getVar( 'cid' );
		$nullDate 	= $db->getNullDate();		
		$lists 		= array();
		$settings 	= & BloggieSettings::getInstance();

		JHTML::_('behavior.tooltip');
		
		//add css/ & js to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');
		$document->addScript('components/com_lyftenbloggie/assets/js/rester.js');
		
		//create the toolbar
		if ( $cid ) {
			JToolBarHelper::title( JText::_( 'EDIT ENTRY' ), 'lbentry' );

		} else {
			JToolBarHelper::title( JText::_( 'NEW ENTRY' ), 'lbentry' );
		}
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::divider();
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();

		//get entry object
		$entry 	= &BloggieEntry::getInstance();

		$row    = & $entry->getEntry();

		// fail if checked out not by 'me'		
		if ($row->id) {
			if ($entry->isCheckedOut( $user->get('id') )) {
				JError::raiseWarning( 'SOME_ERROR_CODE', $row->title.' '.JText::_( 'EDITED BY ANOTHER ADMIN' ));
				$mainframe->redirect( 'index.php?option=com_lyftenbloggie&view=entrys' );
			}
		}
		
		//Get more data
		$lists['catid']   	= & $entry->getCategories();
		$lists['tags']		= & $entry->getTagsList();		

		// build the html for published		
		$states[] = JHTML::_('select.option',  '1', JText::_( 'PUBLISHED' ) );
		$states[] = JHTML::_('select.option',  '2', JText::_( 'PENDING REVIEW' ) );
		$states[] = JHTML::_('select.option',  '-1', JText::_( 'UNPUBLISHED' ) );
		$states[] = JHTML::_('select.option',  '3', JText::_( 'PENDING DELETION' ) );
		$lists['state'] = JHTML::_('select.genericlist', $states, 'state', 'class="inputbox" size="1"', 'value', 'text', $row->state );		

		// Create the form
		$form = new JParameter('', JPATH_COMPONENT.DS.'models'.DS.'entry.xml');

		// Details Group
		$active = (intval($row->created_by) ? intval($row->created_by) : $user->get('id'));
		$form->set('created_by', $active);
		$form->set('access', $row->access);
		$form->set('created_by_alias', $row->created_by_alias);
		$form->set('created', $row->created);

		/*
		 * We need to unify the introtext and fulltext fields and have the
		 * fields separated by the {readmore} tag, so lets do that now.
		 */
		if (JString::strlen($row->fulltext) > 1) {
			$row->text = $row->introtext . "<hr id=\"system-readmore\" />" . $row->fulltext;
		} else {
			$row->text = $row->introtext;
		}
		
		// Advanced Group
		$form->loadINI($row->attribs);
		
		//New entry set allowing comments to default
		if($form->get('allow_comments') == null) {
			$form->set('allow_comments', ($settings->get('typeComments') != 0));
		}
		
		// Make trackback list
		$pinged 		= trim($row->pinged);
		$row->pinged 	= explode("\n", $pinged);

		// Metadata Group
		$form->set('description', $row->metadesc);
		$form->set('keywords', $row->metakey);
		$form->loadINI($row->metadata);

		//assign data to template
		$this->assignRef('lists'      			, $lists);
		$this->assignRef('row'      			, $row);
		$this->assignRef('editor'				, $editor);
		$this->assignRef('pane'					, $pane);
		$this->assignRef('form'					, $form);
		$this->assignRef('nullDate'				, $nullDate);

		parent::display($tpl);
	}
}
?>