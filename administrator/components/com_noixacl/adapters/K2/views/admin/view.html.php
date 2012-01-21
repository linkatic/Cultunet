<?php
/**
 * Checking if file is included in Joomla!
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

class K2ViewAdmin extends JView
{
	function display($tpl = null)
	{
        	$db = JFactory::getDBO();
                /**
                 * Get Viewname
                 */
                $viewName = ucfirst($this->getName());
                /**
                 * Adapter Name
                 */
                $adapterName = "k2";
                /**
                 * Sections List
                 */
		$categoriesList = $this->getCategoriesList();

		/**
		 * Instance Adapter Control
		 */
		$adapterControl = new Adapters();
		$xml = $adapterControl->parseXMLInstallFile(JPATH_COMPONENT_ADMINISTRATOR.DS."adapters".DS."k2".DS."k2.xml");
		
		$this->assignRef("categoriesList", $categoriesList);
		$this->assignRef("tasks", $xml["tasks"]["admin"]);
		$this->assignRef("adapterControl", $adapterControl);
		$this->assignRef("viewName", $viewName);
		$this->assignRef("adapterName", $adapterName);

		parent::display($tpl);
	}
	
	/**
	 * Get all structure categories
	 */
	function getCategoriesList()
	{
		$db			=& JFactory::getDBO();
		/**
		 * Get categories
		 */
			$queryCategoryItens = "SELECT cat.*,grp.name "
                            . " AS groupname "
                            . " FROM #__k2_categories AS cat, #__groups AS grp "
                            . " WHERE cat.access = grp.id "
                            . " AND cat.published = 1 "
                            . " AND cat.trash = 0 "
                            . " ORDER BY cat.parent";
            $db->setQuery( $queryCategoryItens );
			$categoryList = $db->loadObjectList();
			
			$levelLimit	= 10;
                        
                        /**
                         * Estabilish the hierarchy of the menu
                         */
			$children = array();
                        /**
                         * First Pass - Collect Children
                         */
			foreach ($categoryList as $v)
			{
				$pt = $v->parent;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
                        /**
                         * Second Pass - Get an ident list of the items
                         */
			return JHTML::_('menu.treerecurse', 0, '', array(), $children, max( 0, $levelLimit-1 ) );
	}

}