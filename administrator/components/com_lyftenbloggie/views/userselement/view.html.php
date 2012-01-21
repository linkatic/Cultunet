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
 * @subpackage Brezza
 * @since 1.0
 */
class LyftenBloggieViewUsersElement extends JView
{
	/**
	 * Creates the Filemanagerview
	 *
	 * @since 1.0
	 */
	function display( $tpl = null )
	{
		global $mainframe, $option;

		//initialise variables
		$db  			= & JFactory::getDBO();
		$document		= & JFactory::getDocument();
		$settings 		= & BloggieSettings::getInstance();

		//get vars
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.userselement.filter_order', 	'filter_order', 	'a.name', 	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.userselement.filter_order_Dir',	'filter_order_Dir',	'', 		'word' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.userselement.search', 			'search', 			'', 		'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );

		//add css and submenu to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');

		//add js to document
		$js =  'function sendtomain(id, name) {
							parent.document.forms[\'adminForm\'].name.value = name;
							parent.document.forms[\'adminForm\'].user_id.value = id;
							window.parent.document.getElementById(\'sbox-window\').close();;
							return false;
						}';
		$document->addScriptDeclaration($js);
		
		//Get data from the model
		$rows      	= & $this->get( 'Data');
		$pageNav 	= & $this->get( 'Pagination' );

		$lists['adminLevel'] 	= $settings->get('adminLevel', '0');
		
		// search filter
		$lists['search']= $search;

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$ordering = ($lists['order'] == 'a.name');

		//assign data to template
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('rows'      	, $rows);
		$this->assignRef('pageNav' 		, $pageNav);
		$this->assignRef('ordering'		, $ordering);
		$this->assignRef('user'			, $user);

		parent::display($tpl);
	}
}
?>