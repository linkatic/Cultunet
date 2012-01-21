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
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.0
 */
class LyftenBloggieViewComments extends JView {

	function display($tpl = null)
	{
		global $mainframe, $option;

		// get layout
		$task = strtolower(JRequest::getVar('task'));
		if($task == 'edit' || $task == 'add')
		{
			$this->setLayout('form');
			$this->_displayForm($tpl);
			return;
		}

		//initialise variables
		$user 		= & JFactory::getUser();
		$db  		= & JFactory::getDBO();
		$document	= & JFactory::getDocument();
		
		JHTML::_('behavior.tooltip');

		//get vars
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.comments.filter_order', 		'filter_order', 	'c.author', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.comments.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
		$filter_state 		= $mainframe->getUserStateFromRequest( $option.'.comments.filter_state', 		'filter_state', 	'*', 'cmd' );
		$filter_type 		= $mainframe->getUserStateFromRequest( $option.'.comments.filter_type', 		'filter_type', 		'*', 'cmd' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.comments.search', 				'search', 			'', 'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );

		//add css and submenu to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');

		//create the toolbar
		JToolBarHelper::title( JText::_( 'COMMENTS' ), 'lbcomments' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::editList();
		JToolBarHelper::divider();
		JToolBarHelper::deleteList();
		JToolBarHelper::spacer();

		//Get data from the model
		$rows      	= & $this->get( 'Data');
		$pageNav 	= & $this->get( 'Pagination' );

		// build the html for published		
		$states[] = JHTML::_('select.option',  '', ' - '.JText::_( 'SELECT STATE' ).' - ' );
		$states[] = JHTML::_('select.option',  '1', JText::_( 'Approved' ) );
		$states[] = JHTML::_('select.option',  '-1', JText::_( 'UNAPPROVED' ) );
		$states[] = JHTML::_('select.option',  '2', JText::_( 'FLAGGED' ) );
		$states[] = JHTML::_('select.option',  '3', JText::_( 'SPAM' ) );
		$lists['state'] = JHTML::_('select.genericlist', $states, 'filter_state', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $filter_state );		
		
		// build the html for published		
		$types[] = JHTML::_('select.option',  '', ' - '.JText::_( 'SELECT TYPE' ).' - ' );
		$types[] = JHTML::_('select.option',  '1', JText::_( 'REGULAR COMMENT' ) );
		$types[] = JHTML::_('select.option',  '2', JText::_( 'TRACKBACK' ) );
		$lists['type'] = JHTML::_('select.genericlist', $types, 'filter_type', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $filter_type );		
		
		// search filter
		$lists['search']= $search;

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$ordering = ($lists['order'] == 'c.author');

		//assign data to template
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('rows'      	, $rows);
		$this->assignRef('pageNav' 		, $pageNav);
		$this->assignRef('ordering'		, $ordering);
		$this->assignRef('user'			, $user);

		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		global $mainframe, $option;

		//Load pane behavior
		jimport('joomla.html.pane');

		//initialise variables
		$editor 	= & JFactory::getEditor();
		$document	= & JFactory::getDocument();
		$user 		= & JFactory::getUser();
		$db 		= & JFactory::getDBO();
		$tabs 		= & JPane::getInstance('tabs');
		
		JHTML::_('behavior.tooltip');		
		
		//get vars
		$cid 		= JRequest::getVar( 'cid' );

		//add css to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');
		
		//create the toolbar
		JToolBarHelper::title( JText::_( 'EDIT COMMENT' ), 'lbcomment' );
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::divider();
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();
		
		//Get data from the model
		$row = & $this->get('Data');
	
		//If commment has reports get them
		if($row->reports)
		{
			//get vars
			$filter_order		= $mainframe->getUserStateFromRequest( $option.'.comment.filter_order', 		'filter_order', 	'c.author', 'cmd' );
			$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.comment.filter_order_Dir',		'filter_order_Dir',	'', 		'word' );

			// table ordering
			$lists['order_Dir'] = $filter_order_Dir;
			$lists['order'] = $filter_order;
		
			$reports    = & $this->get( 'Reports' );
			$pageNav 	= & $this->get( 'Pagination' );

			$this->assignRef('reports'      	, $reports);
			$this->assignRef('pageNav'      	, $pageNav);
		}

		//clean data
		JFilterOutput::objectHTMLSafe( $row, ENT_QUOTES, 'text' );

		//make author list
		if(($row->type == 1) && ($row->commenter)) {
			$lists['author'] = '<input name="author" value="'.$row->commenter.'" disabled="true" size="50" maxlength="100" />';
		}else{
			$lists['author'] = '<input name="author" value="'.$row->author.'" size="50" maxlength="100" />';
		}
	
		// build the state html		
		$states[] = JHTML::_('select.option',  '', ' - '.JText::_( 'SELECT STATE' ).' - ' );
		$states[] = JHTML::_('select.option',  '1', JText::_( 'APPROVED' ) );
		$states[] = JHTML::_('select.option',  '-1', JText::_( 'UNAPPROVED' ) );
		$states[] = JHTML::_('select.option',  '2', JText::_( 'FLAGGED' ) );
		$states[] = JHTML::_('select.option',  '3', JText::_( 'SPAM' ) );
		$lists['state'] = JHTML::_('select.genericlist', $states, 'state', 'class="inputbox" size="1"', 'value', 'text', $row->state );		
		
		//assign vars to view
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('row'      	, $row);
		$this->assignRef('editor'		, $editor);
		$this->assignRef('tabs'			, $tabs);

		parent::display($tpl);
	}
	
	function _reportCount($id)
	{
		$database = & JFactory::getDBO();
		$query = 'SELECT COUNT(r.comment_id)'
					. ' FROM #__bloggies_reports AS r'
					. ' WHERE r.comment_id = \''.(int)$id.'\'';
		$database->setQuery( $query );
		return $database->loadResult();
	}
}
?>