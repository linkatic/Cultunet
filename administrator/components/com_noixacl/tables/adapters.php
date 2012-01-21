<?php
defined('_JEXEC') or die('Restricted access');

class TableAdapters extends JTable
{
	var $id = NULL;
	var $title = NULL;
	var $adapter = NULL;
	var $ordering = NULL;
	var $enabled = 1;
	
	function __construct(&$db){
		parent::__construct( '#__noixacl_adapters', 'id', $db );
	}
	
}