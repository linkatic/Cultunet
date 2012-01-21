<?php
/**
 *  Check to ensure this file is included in Joomla!
 */
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Media component
 *
 * @static
 * @package		Joomla
 * @subpackage	Group
 * @since 1.0
 */
class GroupsViewGroup extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;

                /**
                 * load lib mootools
                 */
		JHTML::_('behavior.mootools');

		/**
                 * load mootree to list adapters
                 */
                 JHTML::script('mootree.js');
		JHTML::stylesheet('mootree.css');

		/**
                 * load Squeezebox
                 */
                JHTML::_('behavior.modal');
		/**
                 * not implemented
                 */

		$cid		= JRequest::getVar( 'cid', array(0), '', 'array' );
		$db			=& JFactory::getDBO();
		$acl		=& JFactory::getACL();
		$myuser		=& JFactory::getUser();
		
		$group 	= $acl->get_group_data( $cid[0] );
		
		$gtree = $acl->get_group_children_tree( null, 'USERS', false );
		
		$lists['gid'] 	= JHTML::_('select.genericlist',   $gtree, 'parent_id', 'size="1"', 'value', 'text', $group[1] );
        
		$adapters = new Adapters();

		$this->assignRef('Adapters', $adapters);
		$this->assignRef('group', $group);
		$this->assignRef('lists', $lists);
		
		/**
                 * adding noixACL javascript
                 */
                $uri = JFactory::getURI();
                /**
                 * get base URL
                 */
                $baseURL = $uri->base();


		$document = &JFactory::getDocument();
		//add noixACL javascript
                $document->addScript( $baseURL."/components/{$option}/libraries/js/noixACL.js" );

        
		parent::display($tpl);
	}
}