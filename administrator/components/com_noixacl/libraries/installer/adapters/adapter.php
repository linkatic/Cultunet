<?php
/**
 *  Check to ensure this file is within the rest of the framework
 */
defined('JPATH_BASE') or die();

/**
 * Module installer
 *
 * @package		noixACL
 * @subpackage	Installer
 * @since		2.0
 */
class JInstallerAdapter extends JObject
{
	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	object	$parent	Parent object [JInstaller instance]
	 * @return	void
	 * @since	1.5
	 */
	function __construct(&$parent)
	{
		$this->parent =& $parent;
	}

	/**
	 * Custom install method
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function install()
	{
		// Get a database connector object
		$db =& $this->parent->getDBO();

		// Get the extension manifest object
		$manifest =& $this->parent->getManifest();
		$this->manifest =& $manifest->document;

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Manifest Document Setup Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Set the extensions name
		$name =& $this->manifest->getElementByPath('name');
		$name = JFilterInput::clean($name->data(), 'string');
		$this->set('name', $name);

		// Get the component description
		$description = & $this->manifest->getElementByPath('description');
		if (is_a($description, 'JSimpleXMLElement')) {
			$this->parent->set('message', $description->data());
		} else {
			$this->parent->set('message', '' );
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Target Application Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// the target application
        $cname = 'administrator';
        $basePath = JPATH_ADMINISTRATOR.DS."components".DS."com_noixacl";
        $clientId = 0;
        
        // Set the installation path
		$element =& $this->manifest->getElementByPath('files');
		if (is_a($element, 'JSimpleXMLElement') && count($element->children())) {
			$files =& $element->children();
			foreach ($files as $file) {
				if ($file->attributes('adapter')) {
					$aname = $file->attributes('adapter');
					break;
				}
			}
		}
		if (!empty ($aname)) {
			$this->parent->setPath('extension_root', $basePath.DS.'adapters'.DS.$aname);
		} else {
			$this->parent->abort(JText::_('Adapters').' '.JText::_('Install').': '.JText::_('No Adapters file specified'));
			return false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Filesystem Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		/*
		 * If the module directory already exists, then we will assume that the
		 * module is already installed or another module is using that
		 * directory.
		 */
		if (file_exists($this->parent->getPath('extension_root'))&&!$this->parent->getOverwrite()) {
			$this->parent->abort(JText::_('Adapter').' '.JText::_('Install').': '.JText::_('Another adapter is already using directory').': "'.$this->parent->getPath('extension_root').'"');
			return false;
		}

		// If the module directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_root'))) {
			if (!$created = JFolder::create($this->parent->getPath('extension_root'))) {
				$this->parent->abort(JText::_('Adapter').' '.JText::_('Install').': '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_root').'"');
				return false;
			}
		}

		/*
		 * Since we created the adapter directory and will want to remove it if
		 * we have to roll back the installation, lets add it to the
		 * installation step stack
		 */
		if ($created) {
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		if ($this->parent->parseFiles($element, -1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Database Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Check to see if a adapter by the same name is already installed
		$query = 'SELECT `id`' .
				' FROM `#__noixacl_adapters` ' .
				' WHERE adapter = '.$db->Quote($aname);
		$db->setQuery($query);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort(JText::_('Adapter').' '.JText::_('Install').': '.$db->stderr(true));
			return false;
		}
		$id = $db->loadResult();

		// Was there a adapter already installed with the same name?
		// If there was then we wouldn't be here because it would have
		// been stopped by the above. Otherwise the files weren't there
		// (e.g. migration) or its an upgrade (files overwritten)
		// So all we need to do is create an entry when we can't find one
		if (!$id) {
			$pathTable = $basePath .DS.'tables' . DS;
			JTable::addIncludePath( $pathTable );
			$row = & JTable::getInstance('adapters','table');
			$row->title = $this->get('name');
            $row->adapter = $aname;
            $row->enabled = 0;
            $row->ordering = $row->getNextOrder();

			if (!$row->store()) {
				// Install failed, roll back changes
				$this->parent->abort(JText::_('Adapter').' '.JText::_('Install').': '.$db->stderr(true));
				return false;
			}

			// Since we have created a adapter item, we add it to the installation step stack
			// so that if we have to rollback the changes we can undo it.
			$this->parent->pushStep(array ('type' => 'adapter', 'id' => $row->id));
			
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Finalization and Cleanup Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Lastly, we will copy the manifest file to its appropriate place.
		if (!$this->parent->copyManifest(-1)) {
			// Install failed, rollback changes
			$this->parent->abort(JText::_('Adapter').' '.JText::_('Install').': '.JText::_('Could not copy setup file'));
			return false;
		}
		return true;
	}

	/**
	 * Custom uninstall method
	 *
	 * @access	public
	 * @param	int		$id			The id of the module to uninstall
	 * @param	int		$clientId	The id of the client (unused)
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function uninstall( $id, $clientId )
	{
		// Initialize variables
		$row	= null;
		$retval = true;
		$db		=& $this->parent->getDBO();

		// First order of business will be to load the module object table from the database.
		// This should give us the necessary information to proceed.
		$row = & JTable::getInstance('adapters','table');
		if ( !$row->load((int) $id) ) {
			JError::raiseWarning(100, JText::_('ERRORUNKOWNEXTENSION'));
			return false;
		}

		// Get the extension root path
		jimport('joomla.application.helper');
		$client =& JApplicationHelper::getClientInfo($row->client_id);
		if ($client === false) {
			$this->parent->abort(JText::_('Adapter').' '.JText::_('Uninstall').': '.JText::_('Unknown client type').' ['.$row->client_id.']');
			return false;
		}
		$this->parent->setPath('extension_root', $client->path.DS.'adapters'.DS.$row->adapter);

		// Get the package manifest objecct
		$this->parent->setPath('source', $this->parent->getPath('extension_root'));
		$manifest =& $this->parent->getManifest();
		if (!is_a($manifest, 'JSimpleXML')) {
			// Make sure we delete the folders
			JFolder::delete($this->parent->getPath('extension_root'));
			JError::raiseWarning(100, 'Adapter Uninstall: Package manifest file invalid or not found');
			return false;
		}

		// Remove other files
		$root =& $manifest->document;

		// Lets delete all the module copies for the type we are uninstalling
		$query = 'SELECT `id`' .
				' FROM `#__noixacl_adapters`' .
				' WHERE adapter = '.$db->Quote($row->adapter);
		$db->setQuery($query);
		$modules = $db->loadResultArray();

		// Now we will no longer need the module object, so lets delete it and free up memory
		$row->delete($row->id);
		$query = "DELETE FROM `#__noixacl_adapters` WHERE adapter = '".$db->Quote($row->adapter)."'";
		$db->setQuery($query);
		$db->Query(); // clean up any other ones that might exist as well
		unset ($row);

		// Remove the installation folder
		if (!JFolder::delete($this->parent->getPath('extension_root'))) {
			// JFolder should raise an error
			$retval = false;
		}
		return $retval;
	}

	/**
	 * Custom rollback method
	 * 	- Roll back the module item
	 *
	 * @access	public
	 * @param	array	$arg	Installation step to rollback
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _rollback_adapter($arg)
	{
		// Get database connector object
		$db =& $this->parent->getDBO();

		// Remove the entry from the #__modules table
		$query = 'DELETE' .
				' FROM `#__noixacl_adapters`' .
				' WHERE id='.(int)$arg['id'];
		$db->setQuery($query);
		return ($db->query() !== false);
	}
}