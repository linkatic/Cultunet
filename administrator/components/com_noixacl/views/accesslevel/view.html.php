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
 * @subpackage	AccessLevel
 * @since 2.0.5
 */
class AccesslevelsViewAccesslevel extends JView
{
	function display($tpl = null)
	{
		$cid		= JRequest::getVar( 'cid', array(0), '', 'array' );
		$id			= intval($cid[0]);
		$db			=& JFactory::getDBO();
		$acl		= JFactory::getACL();
		$query = "SELECT id, name FROM #__groups WHERE id = {$id}";
		$db->setQuery( $query );
		$row = $db->loadObject();
		
		$query = "SELECT id_group FROM #__noixacl_groups_level WHERE id_levels like '%{$id}%'";
		$db->setQuery( $query );
		$rowGroups = $db->loadObjectList();
		$levelGroups = JArrayHelper::getColumn($rowGroups,'id_group');
		/**
		 * get groups
		 */
		$aclGroups = $acl->sort_groups();
		/**
		 * format groups
		 */
		$groups = $acl->format_groups( $aclGroups,'html',28 );
		
		$this->assignRef('accesslevel', $row);
		$this->assignRef('levelGroups', $levelGroups);
		$this->assignRef('groups',$groups);
		
		$text = !$cid[0] ? JText::_( 'NOIXACL_VIEW_ACCESSLEVEL_TEXT_EDIT' ) : JText::_( 'NOIXACL_VIEW_ACCESSLEVEL_TEXT_NEW' );

		JToolBarHelper::title( JText::_( 'NOIXACL_VIEW_ACCESSLEVEL_TEXT_GROUP' ) . ': <small><small>[ '. $text .' ]</small></small>' , 'user.png' );
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
		
		parent::display($tpl);
	}
}