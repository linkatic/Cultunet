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

class noixaclViewnoixacl extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;
		
		JToolBarHelper::title( JText::_( 'NOIXACL_VIEWS_NOIXACL_TITLE_NOIXACL' ) , 'install.png' );

		parent::display($tpl);
	}
}