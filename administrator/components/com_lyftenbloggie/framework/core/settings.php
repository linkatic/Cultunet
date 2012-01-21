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
 * LyftenBloggie Framework Settings class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieSettings extends JObject
{
	var $_config	= array();

	function BloggieSettings()
	{
		if (empty($_config))
		{
			// Initialize variables
			$database = & JFactory::getDBO(); 

			// Get settings and place into array
			$query = 'SELECT name, value FROM #__bloggies_settings';
			$database->setQuery($query);
			$settings = $database->loadAssocList();

			if(empty($settings)) return;

			foreach($settings as $setting)
			{
				$this->_config[$setting['name']] = $setting['value'];
			}
		}
	}

	/**
	 * Returns a reference to a global Settings object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		$settings = & BloggieSettings::getInstance();
	 *
	 * @access	public
	 * @return	BloggieSettings	The Settings object.
	 */
	function &getInstance()
	{
		static $instance;

		if (!isset($instance)) {
			$instance = new BloggieSettings();
		}

		return $instance;
	}

	/**
	 * Get setting's value
	 *
	 * @param	string	setting's name
	 * @param	string	default value
	 * @return	string
	 **/
	function get($setting, $default=null)
	{
		return isset($this->_config[$setting]) ? $this->_config[$setting] : $default;
	}

	/**
	 * Save settings to database
	 *
	 * @param	array	an array or space separated list of fields not to bind
	 * @return	boolean
	 **/
	function save($data)
	{
		// Initialize variables
		$errors	= false;

		if (!is_array($data)) {
			$this->setError('BloggieSettings::save failed. Invalid data argument');
			return false;
		}

		//get database instance
		$database = & JFactory::getDBO(); 

		foreach ($data as $name => $value)
		{
			if(!$this->update($name, $value))
			{
				$errors = true;
			}
		}
		
		//Set Errors
		if($errors) return false;

		return true;
	}

	/**
	 * Update or Create setting
	 *
	 * @return	boolean
	 **/
	function update($name, $value)
	{
		if(!$name && !$value)
			return false;

		// Initialize variables
		$database = & JFactory::getDBO(); 

		// Create Setting
		if (!isset($this->_config[$name]))
		{
			$query = 'INSERT INTO #__bloggies_settings ( name, value )'
					.' VALUES ( '.$database->Quote($name).', '.$database->Quote($value).' )';
			$database->setQuery($query);
			if (!$database->query()) {
				$this->setError( JText::_('There was an error creating').': '.$name );
				return false;
			}
			$this->_config[$name] = $value;
			return true;

		}else{

			//Update Setting's value
			$query = 'UPDATE #__bloggies_settings'
					.' SET value = '.$database->Quote($value)
					.' WHERE name = '.$database->Quote($name);
			$database->setQuery($query);
			if (!$database->query()) {
				$this->setError( JText::_('There was an error updating').': '.$name );
				return false;
			}
			$this->_config[$name] = $value;
			return true;
		}
	}
}
?>