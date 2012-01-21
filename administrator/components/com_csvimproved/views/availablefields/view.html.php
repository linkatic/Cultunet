<?php
/**
 * Available Fields view
 *
 * Gives an overview of all available fields that can be used for import/export
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: view.html.php 945 2009-07-30 07:18:43Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.view' );

/**
 * Available Fields View
 *
 * @package CSVImproved
 */
class CsvimprovedViewAvailableFields extends JView {
	
	/**
	 * About view display method
	 * @return void
	 **/
	function display($tpl = null) {
		global $mainframe, $option;
		
		/* Get the filter */
		$filter = JRequest::getWord('searchtemplatetype', false);
		
		/* Start pagination */
		jimport('joomla.html.pagination');
		$limit      = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		$total 		= $this->get('TotalAvailableFields');
		if ($limitstart > $total) $limitstart = 0;
		$pagination = new JPagination( $total, $limitstart, $limit );
		
		/* Get the list of available fields */
		$model = $this->getModel('availablefields');
		if ($filter) $availablefields = $model->getShowAvailableFields($pagination, $filter);
		else $availablefields = $model->getShowAvailableFields($pagination);
		
		/* Get the list of template types */
		$template_types = $this->get('TemplateTypes', 'templates');
		/* Add a show all */
		$showall = new StdClass();
		$showall->name = '';
		$showall->value = JText::_('SHOW_ALL');
		array_unshift($template_types, $showall);
		$list['templatetypes'] = JHTML::_('select.genericlist', $template_types, 'searchtemplatetype', '', 'name', 'value', JRequest::getWord('searchtemplatetype'));
		
		/* Assign the data */
		$this->assignRef('availablefields', $availablefields);
		$this->assignRef('list', $list);
		$this->assignRef('pagination', $pagination);
		
		/* Show the toolbar */
		$this->toolbar();
		
		/* Display it all */
		parent::display($tpl);
	}
	
	/**
	 * Display the toolbar
	 */
	function toolbar() {
		JToolBarHelper::title(JText::_('AVAILABLEFIELDS'), 'csvivirtuemart_availablefields_48');
	}
}
?>