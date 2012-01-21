<?php
/**
* @version		$Id: view.html.php 10381 2008-06-01 03:35:53Z pasamio $
* @package		Joomla
* @subpackage	Users
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Users component
 *
 * @static
 * @package		Joomla
 * @subpackage	Users
 * @since 1.0
 */

class AccesslevelsViewAccesslevels extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;

		$db				=& JFactory::getDBO();
		$currentUser    =& JFactory::getUser();

		$search				= $mainframe->getUserStateFromRequest( "$option.search",			'search', 			'',			'string' );
		$search				= JString::strtolower( $search );

		$limit                          = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart                     = $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

		$where = array();
		if (isset( $search ) && $search!= '')
		{
			$searchEscaped = $db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			$where[] = 'name LIKE '.$searchEscaped;
		}
		
		$query = "SELECT id, name FROM #__groups";
		$db->setQuery( $query );
		$groups = $db->loadObjectList();		

		$total = count($groups);

		jimport('joomla.html.pagination');
		$pagination = new JPagination( $total, $limitstart, $limit );
		
		$where = ( count( $where ) ? ' WHERE (' . implode( ') AND (', $where ) . ')' : '' );
		
		$query .= $where;
		$query .= " ORDER BY name";
		$db->setQuery( $query, $pagination->limitstart, $pagination->limit );
		$rows = $db->loadObjectList();
		
		$n = count( $rows );
		
		/**
		 *  search filter
		 **/
		$lists['search']= $search;

		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('items',		$rows);
		$this->assignRef('pagination',	$pagination);
		
		/**
         * toolbar
         */
		JToolBarHelper::title( JText::_( 'NOIXACL_VIEWS_ACCESSLEVELS_TITLE' ), 'user.png' );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNew();

		parent::display($tpl);
	}
}