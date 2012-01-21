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

/**
 * LyftenBloggie Framework Roles class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieAccess extends JObject
{
    var $_db;
    var $_group			= array();
    var $_permissions 	= array();
    var $users_count 	= false;

    /**
     * Construct the object
     *
     * @param 	string $id
     * @return 	Role 
     **/
	function BloggieAccess($id = 1)
	{
		$this->_db = & JFactory::getDBO();

		//Get Current User permissions
		if($id == 1)
		{
			$mine = & JFactory::getUser();
			$id = (!$mine->guest) ? $mine->get('gid') : 1;
		}

		//Get permissions
		$query = 'SELECT r.*'
			. ' FROM #__bloggies_groups as r'
			. ' WHERE r.group = \''.(int)$id.'\'';
		$this->_db->setQuery( $query );
		$this->_group = $this->_db->loadObject();
		$this->_permissions = isset($this->_group->permissions) ? unserialize($this->_group->permissions) : array();
		$this->_permissions['user']['id'] = (isset($mine)) ? $mine->get('id') : '';

		unset($this->_group->permissions);
	}

	/**
	 * Returns a reference to a global access object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		$access = & BloggieAccess::getInstance();
	 *
	 * @param string Group ID to be passed to the access class
	 * @return	BloggieAccess object.
	 **/
	function &getInstance($id=1)
	{
		static $instances;

		if (!isset( $instances )) {
			$instances = array();
		}

		if (empty($instances[$id]))
		{
			$instance[$id] = new BloggieAccess($id);
		}

		return $instance[$id];
	}

	/**
	 * Get group's permission value
	 *
	 * @param	string	setting's name
	 * @param	string	default value
	 * @return	string
	 **/
	function get($permission)
	{
		$section = 'author';
		$parts = explode( '.', $permission );

		if(count($parts) == 2)
		{
			$section 	= $parts[0];
			$perm 		= $parts[1];

			// validate request
			$sections = array('system', 'author', 'admin', 'user', 'emails');
			if (!in_array($section, $sections)) $section = 'author';
		}else{
			$perm = $parts[0];
		}

		return isset($this->_permissions[$section][$perm]) ? $this->_permissions[$section][$perm] : false;
	}

    /**
     * Return group fields array
     *
     * @param void
     * @return array
     */
    function getFields()
	{
		$settings = BloggieSettings::getInstance();
		$data = $settings->get('groups', 'general=system_access,can_publish,can_delete,can_upload\nadmin=admin_access,can_recnoti,can_approve');

		$lines 	= explode("\n", $data);
		$obj 	= array();

		$sec_name = '';
		$unparsed = 0;
		if (!$lines) {
			return $obj;
		}

		foreach ($lines as $line)
		{
			if ($line == '') {
				continue;
			}

			if ($pos = strpos($line, '='))
			{
				$property = trim(substr($line, 0, $pos));

				// property is assumed to be ascii
				if ($property && $property{0} == '"')
				{
					$propLen = strlen( $property );
					if ($property{$propLen-1} == '"') {
						$property = stripcslashes(substr($property, 1, $propLen - 2));
					}
				}

				// get values
				$value = trim(substr($line, $pos +1));
				$obj[$property] = explode(",", $value);
			}
		}

		return $obj;
	}

    /**
     * Return number of users that have this role
     *
     * @param group id
     * @return integer
     */
    function getUsersCount($group)
	{
		if($this->users_count === false)
		{
			$query = 'SELECT COUNT(u.id)'
					. ' FROM #__users AS u'
					. ' WHERE u.gid = \''.(int)$group.'\'';
			$this->_db->setQuery( $query );
			$this->users_count = $this->_db->loadResult();
		}
		return $this->users_count;
    }

   /**
    * Delete this role
    *
    * @param group id
    * @return boolean
	*/
    function delete($group)
	{
      if($this->getUsersCount($group) > 0)
	  {
        return JText::_('Group cannot be deleted as long as there are users assigned to it');
      }
      
      return;
    }
}
?>