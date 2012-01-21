<?php
defined('_JEXEC') or die('Restricted access');

class TableGroupLevel extends JTable
{	
	var $id_group = NULL;
	var $id_levels = NULL;
	
	function __construct(&$db)
    {
		parent::__construct( '#__noixacl_groups_level', 'id_groups_level', $db );
	}
	
	function bind( $idg,$idl  )
	{
		$this->id_group = $idg;
		
		$db = $this->getDBO();
		$query = "SELECT id_levels FROM {$this->_tbl} WHERE id_group = {$idg}";
		$db->setQuery( $query );
		$this->id_levels = $db->loadResult();
		
		if( empty($this->id_levels) ){
			$this->create = true;
		}
		else{
			$this->create = false;
		}
		
		if( strpos($this->id_levels,$idl) === false ){
			if( !empty($this->id_levels) ){
				$this->id_levels .= ','.$idl;
			}
			else{
				$this->id_levels = $idl;
			}
		}
	}
	
	function save()
	{
		$db = $this->getDBO();
		
		if( !$this->create ){
			$query = "UPDATE {$this->_tbl} SET id_levels = '{$this->id_levels}' WHERE id_group = {$this->id_group}";
			$db->setQuery($query);
			$ret = $db->query();
		}
		else{
			$query = "INSERT INTO {$this->_tbl} VALUES({$this->id_group},'{$this->id_levels}');";
			$db->setQuery($query);
			$ret = $db->query();
		}
		
		if( !$ret )
		{
			$this->setError(get_class( $this ).'::store failed - '.$this->_db->getErrorMsg());
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function remove( $idLevel )
	{
		$db = $this->getDBO();
		
		$query = "SELECT * FROM {$this->_tbl} WHERE id_levels like '%{$idLevel}%'";
		$db->setQuery($query);
		$groups = $db->loadObjectList();
		
		if( !empty($groups) )
		{
			foreach($groups as $group)
			{
				$arrayLevels = explode(',',$group->id_levels);
				
				$key = array_search($idLevel,$arrayLevels);
				
				if( !empty($key) ){
					unset( $arrayLevels[$key] );
				}
				
				$group->id_levels = implode(',',$arrayLevels);
				
				$query = "UPDATE {$this->_tbl} SET id_levels = '{$group->id_levels}' WHERE id_group = {$group->id_group}";
				$db->setQuery($query);
				$db->query();
				
			}
		}
	}
}