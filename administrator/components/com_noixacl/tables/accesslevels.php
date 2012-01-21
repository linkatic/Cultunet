<?php 
defined('_JEXEC') or die('Restricted access');

class TableAccessLevels extends JTable
{	
	var $id = NULL;
	var $name = NULL;
	
	function __construct(&$db)
    {
		parent::__construct( '#__groups', 'id', $db );
	}

	function store()
	{
		$db = $this->getDBO();
		
		if( $this->id == 0 ){
			$sql = "SELECT max(id) + 1 FROM #__groups";
			$db->setQuery( $sql );
			$this->id = $db->loadResult();
			$sql = "INSERT INTO #__groups(id,name) VALUES('{$this->id}','{$this->name}');";
			$db->setQuery( $sql );
			$ret = $db->query();
		}
		else{
			$ret = $this->_db->insertObject( $this->_tbl, $this, $this->_tbl_key );
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
	
	
}