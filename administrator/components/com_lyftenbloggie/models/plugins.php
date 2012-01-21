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

jimport( 'joomla.application.component.model' );

/**
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.1.0
 */
class LyftenBloggieModelPlugins extends JModel
{
	var $_id;
	var $_task;
	var $_data;
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		
		$array = JRequest::getVar('cid',  0, '', 'array');
		$task = JArrayHelper::getValue( $_REQUEST, 'task', 0 );
		$this->setId((int)$array[0], $task);		
	}
	
	/**
	 * Method to set the identifier
	 **/
	function setId($id, $task)
	{
		// Set id and wipe data
		$this->_id	    = $id;
		$this->_task	= $task;
		$this->_data	= null;
	}
	
	/**
	 * Overridden get method to get properties
	 **/
	function get($property, $default=null)
	{
		if ($this->_data) {
			if(isset($this->_data->$property)) {
				return $this->_data->$property;
			}
		}
		return $default;
	}
	
	/**
	 * Method to get data
	 **/
	function getPlugins()
	{
		global $mainframe;
		
		if(!$this->_task)
		{		
			$limit				= $mainframe->getUserStateFromRequest('com_lyftenbloggie.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
			$limitstart 		= $mainframe->getUserStateFromRequest('com_lyftenbloggie.limitstart', 'limitstart', 0, 'int');
			$filter_type 		= $mainframe->getUserStateFromRequest('com_lyftenbloggie.plugins.filter_type', 'filter_type', '', 'cmd');
			$filter_state 		= $mainframe->getUserStateFromRequest('com_lyftenbloggie.plugins.filter_state', 'filter_state', '*', 'word');
		
			$filter_order		= $mainframe->getUserStateFromRequest('com_lyftenbloggie.plugins.filter_order', 'filter_order', 'p.type', 'cmd');
			$filter_order_Dir	= $mainframe->getUserStateFromRequest('com_lyftenbloggie.plugins.filter_order_Dir','filter_order_Dir',	'', 'word');

			$where = array();
			if ( $filter_state ) {
				if ( $filter_state == 'P' ) {
					$where[] = 'p.published = 1';
				} else if ($filter_state == 'U' ) {
					$where[] = 'p.published = 0';
				}
			}
			
			if ($filter_type) {
				$where[] = 'LOWER(p.type) = \''.strtolower($filter_type).'\'';
			}

			$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

			$query = 'SELECT p.*'
						. ' FROM #__bloggies_plugins AS p'
						. $where
						. ' GROUP BY p.id'
						. $orderby
						;
			$this->_db->setQuery( $query );
			$rows = $this->_db->loadObjectList();

	    	$total = count( $rows );

			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $total, $limitstart, $limit );

			// slice out elements based on limits
			$this->_data = array_slice( $rows, $this->_pagination->limitstart, $this->_pagination->limit );
		}else{
			if($this->_id) {			
				$query = 'SELECT *'
							. ' FROM #__bloggies_plugins'
							. ' WHERE id ='.$this->_id
							;
				$this->_db->setQuery( $query );
				$this->_data = $this->_db->loadObject();

				$path = JPATH_COMPONENT_SITE.DS.'addons'.DS.'plugins'.DS.$this->_data->type.DS.$this->_data->name.'.xml';
				$data = JApplicationHelper::parseXMLInstallFile($path);
				
				$this->_data->description 	= $data['description'];
				$this->_data->params 		= new JParameter( $this->_data->attribs, $path );
			
			}else{
				$data = new stdClass();
				$data->id 					= 0;
				$data->published			= 0;
				$data->id					= null;
				$data->title				= null;
				$data->name					= null;
				$data->is_default			= null;
				$data->published			= null;
				$data->author				= null;
				$data->email				= null;
				$data->website				= null;
				$data->update_website		= null;
				$data->version				= null;
				$data->license				= null;
				$data->copyright			= null;
				$data->create_date			= null;
				$data->attribs				= null;
				$this->_data				= $data;
			}
		}
		return $this->_data;
	}
	
	/**
	 * Method to (un)publish
	 **/
	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'UPDATE #__bloggies_plugins'
				. ' SET published = ' . (int) $publish
				. ' WHERE id IN ('. $cids .')';
			$this->_db->setQuery( $query );
			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Method to get a pagination object
	 **/
	function &getPagination()
	{
		if ($this->_pagination == null) {
			$this->getData();
		}
		return $this->_pagination;
	}
	
	/**
	 * Method to store
	 **/
	function store($data)
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
				
		$row  			=& $this->getTable('plugins', 'Table');
		$user			=& JFactory::getUser();
		$details		= JRequest::getVar( 'details', array(), 'post', 'array');

		// bind it to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// sanitise id field
		$row->id = (int) $row->id;

		//Set all other defaults to zero
		if($row->is_default) {
			$query = 'UPDATE #__bloggies_plugins'
				. ' SET is_default = 0'
				. ' WHERE is_default = 1'
				;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		
		// Get params string
		$params = JRequest::getVar( 'params', null, 'post', 'array');
		if (is_array($params))
		{
			$txt = array();
			foreach ($params as $k => $v) {
				$txt[] = "$k=$v";
			}
			$row->attribs = implode("\n", $txt);
		}
		
		// Make sure the data is valid
		if (!$row->check()) {
			$this->setError($row->getError());
			return false;
		}

		// Store it in the db
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		$this->_data	=& $row;

		return true;
	}

	/**
	 * Method to remove a tag
	 **/
	function delete($cid = array())
	{
		if (count( $cid ))
		{
			$installer 	= new BloggieInstaller();

			foreach ($cid as $id)
			{
				if(!$installer->uninstall($id, 'plugin'))
				{
					$this->setError($installer->getError());
					return false;
				}
			}
		}
		
		return count($cid).' '.JText::_('PLUGIN_DELETED');
	}

	/**
	 * Method to get Comment systems
	 **/
	function getTypes()
	{
		$options 	= array();
		$options[] 	= JHTML::_('select.option',  '', ' - '.JText::_( 'SELECT TYPE' ).' - ' );

		$query = 'SELECT type'
			. ' FROM #__bloggies_plugins AS p'
			. ' GROUP BY p.type'
			;
		$this->_db->setQuery( $query );
		
		if (!$syetems = $this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
			return $options;
		}
		
		foreach($syetems as $syetem)
		{
			$text = JText::_( 'TYPE_'.strtoupper($syetem->type) );
			$text = ($text == 'TYPE_'.strtoupper($syetem->type)) ? $syetem->type : $text;
			$options[] = JHTML::_('select.option', strtolower($syetem->type), $text );
		}
		unset($syetems);
		return $options;
	}
}