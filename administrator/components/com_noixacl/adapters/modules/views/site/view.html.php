<?php
/**
* No Direct Access
*/
defined('_JEXEC') or die( 'Restricted access' );

/**
 * HTML View class for the Menus Adapter
 *
 * @static
 * @package		Joomla
 * @subpackage	Adapters
 * @since 1.0
 */

require_once JPATH_COMPONENT_ADMINISTRATOR.DS."controllers".DS."adapters.php";

class ModulesViewSite extends JView
{
	function display($tpl = null)
	{
		$db = JFactory::getDBO();

        /**
         * Get Viewname
         */
        $viewName = ucfirst($this->getName());
        /**
         * Get Adapter Name
         */
        $adapterName = "modules";
		
 		/**
 		 * Positions List
 		 */
		$positions = $this->getPositionsModulesList();
		
		$adapterControl = new Adapters();
		$xml = $adapterControl->parseXMLInstallFile(NOIXACL_APADTER_PATH.DS."modules".DS."modules.xml");
		
		$list["positions"] = JHTML::_('select.genericlist', $positions, $adapterName.$viewName.'TableModules', 'class="inputbox" size="1" onchange="showPositionModules();"', 'id', 'title');
		
		$this->assignRef("positionsList", $positions);
		$this->assignRef("tasks", $xml["tasks"]["site"]);
                $this->assignRef("adapterControl", $adapterControl);
		$this->assignRef("lists",$list);
		$this->assignRef("viewName", $viewName);
		$this->assignRef("adapterName", $adapterName);

		parent::display($tpl);
	}
	
	/**
	 * Get all Positions and modules
	 */
	function getPositionsModulesList()
	{
		$db			=& JFactory::getDBO();
		
		/**
		 * Get Positions
		 */
		$queryPositions = "SELECT DISTINCT m.position as id, m.position as title "
                  . "FROM #__modules AS m WHERE m.client_id = 0 ORDER BY m.position";
		$db->setQuery( $queryPositions );
		$modulePositions = $db->loadObjectList();
		
		/**
		 * Get menu itens
		 */
		foreach($modulePositions as $position){
			$queryModules = "SELECT m.*, g.name as groupname "
                        . "FROM #__modules m, #__groups g "
                        . "WHERE m.position = '{$position->title}' AND m.client_id = 0 AND m.published = 1 AND m.access = g.id "
                        . "ORDER BY m.ordering ASC";
                        
            $db->setQuery( $queryModules );
			$modulesItens = $db->loadObjectList();
			
			$position->modulesList = $modulesItens;
		}
		
		return $modulePositions;
	}
	
}