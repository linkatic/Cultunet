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

jimport('joomla.application.component.model');

/**
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.1.0
 */
class LyftenBloggieModelGroups extends JModel
{
	var $_data 			= array();
	var $_total 		= null;
	var $_pagination 	= null;
	var $_id 			= null;

	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();

		$id = JRequest::getInt('group',  0);
		$this->setId((int)$id);
	}

	/**
	 * Method to set the group identifier
	 **/
	function setId($id)
	{
		// Set id and wipe data
		$this->_id	 = $id;
		$this->_data = array();
	}

	/**
	 * Overridden get method to get properties from the author
	 **/
	function get($property, $default=null)
	{
		if ($this->getData()) {
			if(isset($this->_data->$property)) {
				return $this->_data->$property;
			}
		}
		return $default;
	}

	/**
	 * Method to get groups data
	 **/
	function getData()
	{
		global $mainframe;

		if($this->_id > 0 || strtolower(JRequest::getVar('task')) == 'add')
		{
			$level = BloggieFactory::getAccesslevels($this->_id);

			$query = 'SELECT r.*, r.group as group_id'
					. ' FROM #__bloggies_groups as r'
					. ' WHERE r.group = \''.(int)$level->id.'\'';
			$this->_db->setQuery( $query );
			$group = $this->_db->loadObject();

			if(isset($group->id))
			{
				$group->group 				= $level->name;
				$this->_data 				= $group;
				$this->_data->permissions 	= isset($this->_data->permissions) ? unserialize($this->_data->permissions) : array();
				unset($group);
			}else{
				$group->id 					= null;
				$group->group 				= $level->name;
				$group->permissions 		= null;
				$group->group_id 			= $level->id;
				$this->_data->permissions	= array();
				$this->_data 				= $group;
				unset($group);
			}
		} else {
			if(empty($this->_data))
			{
				$levels 		= BloggieFactory::getAccesslevels();
				$filter_state 	= $mainframe->getUserStateFromRequest( 'com_lyftenbloggie.groups.filter_state', 'filter_state', '', 'word' );
				$where 			= '';
				if ( $filter_state ) {
					if ( $filter_state == 'P' ) {
						$where = ' AND r.published = 1';
					} else if ($filter_state == 'U' ) {
						$where = ' AND r.published = 0';
					}
				}
				foreach($levels as $level)
				{
					$query = 'SELECT r.*, r.group as group_id, COUNT(u.id) AS assigned'
							. ' FROM #__bloggies_groups as r'
							. ' LEFT JOIN #__users AS u ON u.gid = \''.(int)$level->value.'\''
							. ' WHERE r.group = \''.(int)$level->value.'\''
							.$where;
					$this->_db->setQuery( $query );
					$group = $this->_db->loadObject();

					if(isset($group->id))
					{
						$group->group 		= $level->text;
						$this->_data[] 		= $group;
						unset($group);
					}else if($where == ''){
						$group->id 			= null;
						$group->group 		= $level->text;
						$group->permissions 	= null;
						$group->group_id 	= $level->value;
						$this->_data[] 		= $group;
						unset($group);
					}
				}
			}
		}

		return $this->_data;
	}

	/**
	 * Method to store the group
	 **/
	function store($data)
	{
		// Update Email only for super administrator
		if($data['admin'] == 1)
		{
			$query = 'SELECT g.permissions'
					. ' FROM #__bloggies_groups as g'
					. ' WHERE g.group = \''.(int)$data['group'].'\'';
			$this->_db->setQuery( $query );
			$group = $this->_db->loadResult();
			$group = unserialize($group);

			//Set Emails
			$group['emails'] = array();
			foreach($data['permissions']['emails'] as $key=>$value)
			{
				$group['emails'][$key] = $value;
			}
			$data['permissions'] = $group;
			unset($group);
		}

		$group  =& $this->getTable('groups', 'Table');

		$data['email_all'] 		= (isset($data['permissions']['emails']['receive_all']) && $data['permissions']['emails']['receive_all']) ? 1 : 0;
		$data['permissions'] 	= serialize($data['permissions']);

		// bind it to the table
		if (!$group->bind($data)) {
			$this->setError(500, $this->_db->getErrorMsg() );
			return false;
		}
		
		// Make sure the data is valid
		if (!$group->check()) {
			$this->setError($group->getError());
			return false;
		}
		
		// Store it in the db
		if (!$group->store()) {
			$this->setError(500, $this->_db->getErrorMsg() );
			return false;
		}

		$this->_data	=& $group;
		
		return true;
	}

	/**
	 * Method to (un)publish a group
	 **/
	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__bloggies_groups'
				. ' SET published = ' . (int) $publish
				. ' WHERE id IN ('. $cids .')';
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}
}
?>