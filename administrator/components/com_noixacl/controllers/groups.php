<?php
/**
 * @version		$Id: controller.php 10381 2008-06-01 03:35:53Z pasamio $
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

/**
 * Check Access
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Users Component Controller
 *
 * @package		Joomla
 * @subpackage	NOIX ACL
 * @since 1.5
 */
class GroupsController extends JController
{
	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	function __construct($config = array())
	{
		parent::__construct($config);

                /**
                 * Register Extra Tasks
                 */
		$this->registerTask( 'apply', 	'save'  );
		$this->registerTask( 'add', 	'edit'  );
	}

	/**
	 * Displays a view
	 */
	function display( )
	{
		parent::display();
	}
	
	/**
	 * Edit a Group
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'group' );
		$this->display();
	}


	/**
	 * Saves the record
	 */
	function save()
	{
		global $mainframe;

                /**
                 * Check for request forgeries
                 */
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$option = JRequest::getCmd( 'option' );

                /**
                 * Initialize some variables
                 */
		$me			=& JFactory::getUser();
		$db			=  JFactory::getDBO();
		$acl                    =& JFactory::getACL();
		
		$post = JRequest::get('post');
		$groupID = JRequest::getInt( 'id', 0, 'post', 'int');
		$parentID = JRequest::getInt( 'parent_id', 30, 'post', 'int');
		$groupName = JRequest::getVar('name');
		
                /**
                 * Creating a new group
                 */
		if( $groupID == 0 ){
                        /**
                         * Add New Group
                         */
			$acl->add_group($groupName, $groupName, $parentID);
		}
		else {
                        /**
                         * Get Group Name By ID
                         */
			$queryGetGorupName = "SELECT name FROM #__core_acl_aro_groups WHERE id =" . $groupID;
			$db->setQuery( $queryGetGorupName );
			$oldGroupName = $db->loadResult();
			
                        /**
                         * Edit a Group
                         */
			$acl->edit_group($groupID, $groupName, $groupName, $parentID);
		}
		
                /**
                 * Instance Adapter Helper
                 */
		$adaptersControl = new Adapters();
                /**
                 * Get a List of Adapters
                 */
		$adaptersList = $adaptersControl->listAdapters();
                /**
                 * Gets Total
                 */
		$totalAdapters = count($adaptersList);
		if( $totalAdapters > 0 ){			
			foreach( $adaptersList as $adapters ){
                                /**
                                 * Include Adapters Controller
                                 */
				include($adapters->xmlData["pathController"]);
                                /**
                                 * Get Instance of Adapter Controller
                                 */
				$adapterController = new $adapters->xmlData["controller"];
                                /**
                                 * Call Save Method in Adapter Controller
                                 */
				if( isset($oldGroupName) ){
					$adapterController->delete($oldGroupName);
				}
				$adapterController->save($groupName);
				unset($adapterController);				
			}
		}
		
		/**
		 * Redirect by task
		 */
		switch ( $this->getTask() )
		{
			case 'apply':
				$msg = JText::sprintf( 'NOIXACL_GROUPS_APPLY_SUCCESS' ) ." ".$groupName;
				$this->setRedirect( "index.php?option={$option}&view=group&task=edit&cid[]=". $groupID, $msg );
				break;

			case 'save':
			default:
				$msg = JText::sprintf( 'NOIXACL_GROUPS_SAVE_SUCCESS', $groupName );
				$this->setRedirect( "index.php?option={$option}", $msg );
				break;
		}
	}
	
	/**
	 * Removes the record(s) from the database
	 */
	function remove()
	{
                /**
                 * Check For Request Forgeries
                 */
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$option = JRequest::getCmd( 'option' );

		$db 			=& JFactory::getDBO();
		$currentUser            =& JFactory::getUser();
		$acl			=& JFactory::getACL();
		$cid 			= JRequest::getVar( 'cid', array(), '', 'array' );

		JArrayHelper::toInteger( $cid );

		if( count( $cid ) < 1 ){
			JError::raiseError(500, JText::_( 'NOIXACL_USERS_SELECT_GROUP_DELETE', true ) );
		}
		
		foreach($cid as $id){
			$groupID = intval( $id );

			if( $groupID > 30 ){				
				if( $this->del_group($groupID,$reparentChildren) ){
					$msg = JText::_("NOIXACL_GROUPS_GROUP_REMOVED_SUCESSFULL");
				}
				else{
					$msg = JText::_("NOIXACL_GROUPS_GROUP_REMOVE_ERROR");
				}				
			}			
		}

		$this->setRedirect( "index.php?option={$option}", $msg);
	}
	
	/**
	 * function from original ACL code corrected
	 */
	function del_group($groupID, $reparentChildren=TRUE, $groupType='ARO') {
		$acl = JFactory::getACL();
		
		switch( strtolower( trim($groupType) ) ) {
			case 'axo':
				$groupType = 'axo';
				$table = $acl->_db_table_prefix .'axo_groups';
				$groupsMapTable = $acl->_db_table_prefix .'axo_groups_map';
				$groupsObjectMapTable = $acl->_db_table_prefix .'groups_axo_map';
				break;
			default:
				$groupType = 'aro';
				$table = $acl->_db_table_prefix .'aro_groups';
				$groupsMapTable = $acl->_db_table_prefix .'aro_groups_map';
				$groupsObjectMapTable = $acl->_db_table_prefix .'groups_aro_map';
				break;
		}
	
		$acl->debug_text("del_group(): ID: $groupID Reparent Children: $reparentChildren Group Type: $groupType");
	
		if( empty($groupID) ){
			$acl->debug_text("del_group(): Group ID ($groupID) is empty, this is required");
			return false;
		}
	
                /**
                 * Get Details of This Group
                 */
		$query = 'SELECT id, parent_id, name, lft, rgt FROM '. $table .' WHERE id='. (int) $groupID;
		$groupDetails = $acl->db->GetRow($query);
	
		if( !is_array($groupDetails) ){
			$acl->debug_db('del_group');
			return false;
		}
	
		$parentID = $groupDetails[1];
	
		$left = $groupDetails[3];
		$right = $groupDetails[4];
	
		$acl->db->BeginTrans();
	
                /**
                 * Grab List of All Children
                 */
		$childrenIDs = $acl->get_group_children($groupID, $groupType, 'RECURSE');
	
                /**
                 * Prevent Deletion of Root Group & Reparent of Children if it Has More Than One Immediate Child
                 */
		if( $parentID == 0 ){
			$query = 'SELECT count(*) FROM '. $table .' WHERE parent_id='. (int) $groupID;
			$childCount = $acl->db->GetOne($query);
	
			if ( ($childCount > 1) AND $reparentChildren ) {
				$acl->debug_text ('del_group (): You cannot delete the root group and reparent children, this would create multiple root groups.');
				$acl->db->RollbackTrans();
				return FALSE;
			}
		}
	
		$success = FALSE;
	
		/*
		 * Handle children here.
		 */
		switch (TRUE) {
                        /**
                         * There are no child groups, just delete group
                         */
			case !is_array($childrenIDs):
			case count($childrenIDs) == 0:
                                /**
                                 * Remove group object maps
                                 */
				$query = 'DELETE FROM '. $groupsObjectMapTable .' WHERE group_id='. (int) $groupID;
				$rs = $acl->db->Execute($query);
	
				if (!is_object($rs)) {
					break;
				}
	
                                /**
                                 * Remove group
                                 */
				$query = 'DELETE FROM '. $table .' WHERE id='. (int) $groupID;
				$rs = $acl->db->Execute($query);
	
				if (!is_object($rs)) {
					break;
				}
	
                                /**
                                 * Move all groups right of deleted group left by width of deleted group
                                 */
				$query = 'UPDATE '. $table .' SET lft=lft-'. (int)($right-$left+1) .' WHERE lft>'. (int) $right;
				$rs = $acl->db->Execute($query);
	
				if (!is_object($rs)) {
					break;
				}
	
				$query = 'UPDATE '. $table .' SET rgt=rgt-'. (int)($right-$left+1) .' WHERE rgt>'. (int) $right;
				$rs = $acl->db->Execute($query);
	
				if (!is_object($rs)) {
					break;
				}
	
				$success = TRUE;
				break;
  			case $reparentChildren == TRUE:
                                /**
                                 * Remove group object maps
                                 */
				$query = 'DELETE FROM '. $groupsObjectMapTable .' WHERE group_id='. (int) $groupID;
				$rs = $acl->db->Execute($query);
	
				if (!is_object($rs)) {
					break;
				}
	
                                /**
                                 * Remove group
                                 */
				$query = 'DELETE FROM '. $table .' WHERE id='. (int) $groupID;
				$rs = $acl->db->Execute($query);
	
				if (!is_object($rs)) {
					break;
				}
	
                                /**
                                 * Set parent of immediate children to parent group
                                 */
				$query = 'UPDATE '. $table .' SET parent_id='. (int) $parentID .' WHERE parent_id='. (int) $groupID;
				$rs = $acl->db->Execute($query);
	
				if (!is_object($rs)) {
					break;
				}
	
                                /**
                                 * move all children left by 1
                                 */
				$query = 'UPDATE '. $table .' SET lft=lft-1, rgt=rgt-1 WHERE lft>'. (int) $left .' AND rgt<'. (int) $right;
				$rs = $acl->db->Execute($query);
	
				if (!is_object($rs)) {
					break;
				}
	
                                /**
                                 * move all groups right of deleted group left by 2
                                 */
				$query = 'UPDATE '. $table .' SET lft=lft-2 WHERE lft>'. (int) $right;
				$rs = $acl->db->Execute($query);
	
				if (!is_object($rs)) {
					break;
				}
	
				$query = 'UPDATE '. $table .' SET rgt=rgt-2 WHERE rgt>'. (int) $right;
				$rs = $acl->db->Execute($query);
	
				if (!is_object($rs)) {
					break;
				}
	
				$success = TRUE;
				break;
			default:
                                /**
                                 * make list of groups and all children
                                 */
				$groupIDs = $childrenIDs;
				$groupIDs[] = (int) $groupID;
                                /**
                                 * remove group object maps
                                 */
				$query = 'DELETE FROM '. $groupsObjectMapTable .' WHERE group_id IN ('. implode (',', $groupIDs) .')';
				$rs = $acl->db->Execute($query);
	
				if (!is_object($rs)) {
					break;
				}
	
                                /**
                                 * remove groups
                                 */
				$query = 'DELETE FROM '. $table .' WHERE id IN ('. implode (',', $groupIDs) .')';
				$rs = $acl->db->Execute($query);
	
				if (!is_object($rs)) {
					break;
				}
	
                                /**
                                 *  move all groups right of deleted group left by width of deleted group
                                 */
				$query = 'UPDATE '. $table .' SET lft=lft-'. ($right - $left + 1) .' WHERE lft>'. (int) $right;
				$rs = $acl->db->Execute($query);
	
				if (!is_object($rs)) {
					break;
				}
	
				$query = 'UPDATE '. $table .' SET rgt=rgt-'. ($right - $left + 1) .' WHERE rgt>'. (int) $right;
				$rs = $acl->db->Execute($query);
	
				if (!is_object($rs)) {
					break;
				}
	
				$success = TRUE;
		}
	
                 /**
                  * if delete failed , rollback the trans and return false
                  */
		if (!$success) {
	
			$acl->debug_db('del_group');
			$acl->db->RollBackTrans();
			return false;
		}
	
		$acl->debug_text("del_group(): deleted group ID: $groupID");
		$acl->db->CommitTrans();
	
		if ($acl->_caching == TRUE AND$acl->_force_cache_expire == TRUE) {
                         /**
                          * Expire all cache
                          */
			$acl->Cache_Lite->clean('default');
		}
	
		return true;
	
	}

	/**
	 * Cancels an edit operation
	 */
	function cancel()
	{
		$option = JRequest::getCmd( 'option' );
		
		$this->setRedirect( "index.php?option={$option}" );
	}	
}