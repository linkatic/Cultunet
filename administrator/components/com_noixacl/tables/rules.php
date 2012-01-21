<?php
defined('_JEXEC') or die('Restricted access');

class TableRules extends JTable
{	
	var $id = NULL;
	var $aco_section = NULL;
	var $aco_value = NULL;
	var $aro_section = "users";
	var $aro_value = NULL;
	var $axo_section = NULL;
	var $axo_value = NULL;
	
	/**
	 * Get ID if rule exists and return false if aro_value no exists in core_acl_aro_groups
	 */
	function check()
    {
		$db = JFactory::getDBO();
		
		/**
                 * get the rule id
                 */
		$query_get_id = "SELECT id FROM #__noixacl_rules WHERE aco_section = '". $this->aco_section ."' AND aco_value = '". $this->aco_value ."' AND aro_section = 'users' AND aro_value = '". $this->aro_value ."' AND axo_section = '". $this->axo_section ."' AND axo_value = '". $this->axo_value ."'";
		$db->setQuery( $query_get_id );
		$this->id = intval( $db->loadResult() );
		
		/**
                 * check if exist aro_value( GROUP )
                 */
		$query_check_group = "SELECT id FROM #__core_acl_aro_groups WHERE name = '". $this->aro_value ."'";
		$db->setQuery( $query_check_group );
		$group_exists = $db->loadResult();
		
		if( empty($group_exists) ){
			return false;
		}
	}
	
	function __construct(&$db)
    {
		parent::__construct( '#__noixacl_rules', 'id', $db );
	}
	
}