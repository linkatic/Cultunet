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
 * LyftenBloggie Framework Plugin class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggiePlugin extends JObject
{
	var	$params		= null;
	var $_name		= null;
	var $_type		= null;
	var $_db		= null;
	var $_settings	= null;

	/**
	 * PHP4 Constructor
	 **/
	function BloggiePlugin()
	{
		$this->__construct();
	}

	/**
	 * Constructor
	 **/
	function __construct()
	{
		global $mainframe;

		$this->_db 			=& JFactory::getDBO();
		$this->_settings 	=& BloggieSettings::getInstance();
		$this->_siteUrl 	= $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
		parent::__construct();
	}

	/**
	 * Default Display
	 **/
	function display($id, $title)
	{
		return;
	}

	/**
	 * Get the plugin object
	 *
	 * @access public
	 * @param string 	$type 	The plugin type, relates to the sub-directory in the plugins directory
	 * @param string 	$plugin	The plugin name
	 * @return mixed 	An array of plugin data objects, or a plugin data object
	 * @since	1.1.0
	 */
	function &getPlugin($type, $plugin = 'default')
	{
		$plugin_obj = array();

		$query = 'SELECT type, name, title, attribs, published'
				. ' FROM #__bloggies_plugins'
				. ' WHERE name = \''.$plugin.'\''
				. ' AND LOWER(type) = \''.strtolower($type).'\'';
		$this->_db->setQuery( $query );

		if (!$plugin = $this->_db->loadObject()) {
			JError::raiseWarning( 'SOME_ERROR_CODE', "Error loading Plugins: " . $this->_db->getErrorMsg());
			return $plugin_obj;
		}

		if (!$plugin->published) {
			return $plugin_obj;
		}
			
		//Fix Name and Type
		$plugin->type = strtolower(preg_replace('/[^A-Z0-9_\.-]/i', '', $plugin->type));
		$plugin->name  = strtolower(preg_replace('/[^A-Z0-9_\.-]/i', '', $plugin->name));
			
		//Call Plugin
		$path	= BLOGGIE_SITE_PATH.DS.'addons'.DS.'plugins'.DS.$plugin->type.DS.$plugin->name.'.php';
		if (file_exists( $path ))
		{
			require_once( $path );

			$className = $plugin->name.$plugin->type.'Plugin';
			if(class_exists($className))
			{
				$params = new JParameter($plugin->attribs);

				// create the plugin
				$plugin_obj = new $className($params);
			}
		}

		return $plugin_obj;
	}
	
	/**
	 * Returns an object list
	 *
	 * @param	string The query
	 * @param	int Offset
	 * @param	int The number of records
	 * @return	array
	 */
	function &_getList( $query, $limitstart = 0, $limit = 0 )
	{
		$this->_db->setQuery( $query, $limitstart, $limit );
		$result = $this->_db->loadObjectList();

		return $result;
	}
}
?>