<?php
/**
 *  no direct access
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Users component
 *
 * @static
 * @package		Joomla
 * @subpackage	Users
 * @since 1.0
 */

class AdaptersViewAdapters extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;
		
		JToolBarHelper::title( JText::_( 'NOIXACL_VIEWS_ADAPTERS_TITLE_ADAPTERS' ) , 'install.png' );
		JToolBarHelper::deleteList( '', 'remove', 'Uninstall' );
		
		$db = & JFactory::getDBO();
		
		$controlAdapter = new Adapters();
		
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart     = $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

                $where = array();
		$sql = "SELECT id, title, adapter, enabled FROM #__noixacl_adapters";

		$where = ( count( $where ) ? ' WHERE (' . implode( ') AND (', $where ) . ')' : '' );
		
		$sql .= $where;
		$db->setQuery( $sql );
		$adapters = $db->loadObjectList();
		
		$total = count($adapters);

		jimport('joomla.html.pagination');
		$pagination = new JPagination( $total, $limitstart, $limit );
		
		$sql .= " ORDER BY ordering";
		$db->setQuery( $sql, $pagination->limitstart, $pagination->limit );
		$adapters = $db->loadObjectList();
		
		foreach($adapters as $adapter){
			$adapterFile = JPATH_COMPONENT_ADMINISTRATOR.DS."adapters".DS.$adapter->adapter.DS.$adapter->adapter.".xml";
			$adapter->xmlData = $controlAdapter->parseXMLInstallFile($adapterFile);
			
			if( empty($adapter->xmlData) || !$adapter->xmlData ){
				unset($adapter);		
			}
		}
		
		
		$this->assignRef('items', $adapters);
		$this->assignRef('pagination',	$pagination);
		
		parent::display($tpl);
	}
}