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

jimport('joomla.filesystem.file');

/**
 * LyftenBloggie Framework Installer class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.0.2
 **/
class BloggieInstaller extends JObject
{
	var $_db;
	var $_tmp_path;
	var $_package;
	var $_package_location;
	var $_unpack_location;
	var $_extension_type;
	var $_extension_dir;
	var $_extension_name;
	var $_xml;
	var $_xml_filename;
	var $_manifest;
	
	var $_paths 	= array();
	var $_data 		= array();
	var $_tags 		= array();
	var $_stepStack	= array();
	
	var $_error		= null;
	var $_overwrite = false;

	var $mimeTypes = array('tar', 'gz', 'tar', 'z', 'zip');

	function BloggieInstaller()
	{
		// Build the appropriate paths
		$config 			=& JFactory::getConfig();
		$this->_tmp_path 	= $config->getValue('config.tmp_path');
		$this->_db 			= &JFactory::getDBO();
	}

	/**
	 * Get an installer path by name
	 *
	 * @access	public
	 * @param	string	$name		Path name
	 * @param	string	$default	Default value
	 * @return	string	Path
	 * @since	1.1.0
	 */
	function getPath($name, $default=null)
	{
		return (!empty($this->_paths[$name])) ? $this->_paths[$name] : $default;
	}

	/**
	 * Sets an installer path by name
	 *
	 * @access	public
	 * @param	string	$name	Path name
	 * @param	string	$value	Path
	 * @return	void
	 * @since	1.1.0
	 */
	function setPath($name, $value)
	{
		$this->_paths[$name] = $value;
	}

	/**
	 * Pushes a step onto the installer stack for rolling back steps
	 *
	 * @access	public
	 * @param	array	$step	Installer step
	 * @return	void
	 * @since	1.1.0
	 */
	function pushStep($step)
	{
		$this->_stepStack[] = $step;
	}

	/**
	 * Installation abort method
	 *
	 * @access	public
	 * @param	string	$msg	Abort message from the installer
	 * @param	string	$type	Package type if defined
	 * @return	boolean	True if successful
	 * @since	1.1.0
	 */
	function abort($msg=null, $type=null)
	{
		// Initialize variables
		$retval = true;
		$step = array_pop($this->_stepStack);

		// Raise abort warning
		if ($msg) {
			JError::raiseWarning(100, $msg);
		}

		while ($step != null)
		{
			switch ($step['type'])
			{
				case 'file' :
					// remove the file
					$stepval = JFile::delete($step['path']);
					break;

				case 'folder' :
					// remove the folder
					$stepval = JFolder::delete($step['path']);
					break;

				case 'query' :
					// database entry
					$this->_db->setQuery($step['query']);
					$this->_db->query();
					break;
			}

			// Only set the return value if it is false
			if ($stepval === false) {
				$retval = false;
			}

			// Get the next step and continue
			$step = array_pop($this->_stepStack);
		}
		$this->_clean_temp();

		return $retval;
	}

	/**
	 * Tries to find the package manifest file
	 *
	 * @access private
	 * @return boolean True on success, False on error
	 * @since 1.1.0
	 */
	function _findManifest()
	{
		// Get an array of all the xml files from teh installation directory
		$files = JFolder::files($this->getPath('source'), '.xml$', 1, true);

		// If at least one xml file exists
		if (!empty($files))
		{
			foreach ($files as $file)
			{
				// Is it a valid joomla installation manifest file?
				$manifest = $this->_isManifest($file);

				if (!is_null($manifest))
				{
					// If the root method attribute is set to upgrade, allow file overwrite
					$root =& $manifest->document;
					if ($root->attributes('method') == 'upgrade') {
						$this->_overwrite = true;
					}

					// Set the manifest object and path
					$this->_manifest =& $root;
					$this->setPath('manifest', $file);

					// Set the installation source path to that of the manifest file
					$this->setPath('source', dirname($file));
					return true;
				}
			}
			return false;
		} else {

			return false;
		}
	}

	function _clean_name($name)
	{
		$name = strtolower($name);
		$name = str_replace('-', '_', $name);
		$name = str_replace(' ', '_', $name);
		Jfile::makeSafe($name);

		return $name;
	}

	function _is_installed($name, $type)
	{
		switch ($type)
		{
			case 'plugin':
				$table = "#__bloggies_plugins";
				break;
	
			case 'theme':
				$table = "#__bloggies_themes";
				break;
		}

		$query = "SELECT id FROM $table WHERE name = '$name' AND type = '$type' LIMIT 1";
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}

	function parseFiles($element)
	{
		$e = false;
		
		if (!is_a($element, 'JSimpleXMLElement') || !count($element->children())){
			return 0;
		}

		// Get the array of file nodes to process
		$files = $element->children();
		if (count($files) == 0) {
			// No files to process
			return 0;
		}

		//Does the element have a folder attribute?
		if ($folder = $element->attributes('folder')) {
			$source = $this->getPath('source').DS.$folder;
		} else {
			$source = $this->getPath('source');
		}

		foreach ($files AS $file)
		{
			$path['src']	= $source.DS.$file->data();
			$path['dest']	= $this->getPath('install_root').$file->data();

			// Is this path a file or folder?
			$path['type']	= ( $file->name() == 'folder') ? 'folder' : 'file';

			/*
			 * Before we can add a file to the copyfiles array we need to ensure
			 * that the folder we are copying our file to exits and if it doesn't,
			 * we need to create it.
			 */
			if (basename($path['dest']) != $path['dest'])
			{
				$newdir = dirname($path['dest']);

				if (!JFolder::create($newdir)) {
					JError::raiseWarning(1, JText::_('INSTALLER ERROR15').' "'.$newdir.'"');
					return false;
				}
			}
			// Add the file to the copyfiles array
			$copyfiles[] = $path;
		}

		return $this->copyFiles($copyfiles);
	}

	/**
	 * Copy files from source directory to the target directory
	 *
	 * @access	public
	 * @param	array $files array with filenames
	 * @param	boolean $overwrite True if existing files can be replaced
	 * @return	boolean True on success
	 * @since	1.1.0
	 */
	function copyFiles($files, $overwrite=null)
	{
		/*
		 * To allow for manual override on the overwriting flag, we check to see if
		 * the $overwrite flag was set and is a boolean value.  If not, use the object
		 * allowOverwrite flag.
		 */
		if (is_null($overwrite) || !is_bool($overwrite)) {
			$overwrite = $this->_overwrite;
		}

		/*
		 * $files must be an array of filenames.  Verify that it is an array with
		 * at least one file to copy.
		 */
		if (is_array($files) && count($files) > 0)
		{
			foreach ($files as $file)
			{
				// Get the source and destination paths
				$filesource	= JPath::clean($file['src']);
				$filedest	= JPath::clean($file['dest']);
				$filetype	= array_key_exists('type', $file) ? $file['type'] : 'file';

				if (!file_exists($filesource)) {
					/*
					 * The source file does not exist.  Nothing to copy so set an error
					 * and return false.
					 */
					JError::raiseWarning(1, JText::sprintf('INSTALLER ERROR16', $filesource));
					return false;
				} elseif (file_exists($filedest) && !$overwrite) {

						/*
						 * It's okay if the manifest already exists
						 */
						if ($this->getPath( 'manifest' ) == $filesource) {
							continue;
						}

						/*
						 * The destination file already exists and the overwrite flag is false.
						 * Set an error and return false.
						 */
						JError::raiseWarning(1, JText::sprintf('UNINSTALL ERROR7', $filedest));
						return false;
				} else {

					// Copy the folder or file to the new location.
					if ( $filetype == 'folder') {

						if (!(JFolder::copy($filesource, $filedest, null, $overwrite))) {
							JError::raiseWarning(1, JText::sprintf('FAILED TO COPY FOLDER TO', $filesource, $filedest));
							return false;
						}

						$step = array ('type' => 'folder', 'path' => $filedest);
					} else {

						if (!(JFile::copy($filesource, $filedest))) {
							JError::raiseWarning(1, JText::sprintf('FAILED TO COPY FOLDER TO', $filesource, $filedest));
							return false;
						}

						$step = array ('type' => 'file', 'path' => $filedest);
					}

					/*
					 * Since we copied a file/folder, we want to add it to the installation step stack so that
					 * in case we have to roll back the installation we can remove the files copied.
					 */
					$this->_stepStack[] = $step;
				}
			}
		} else {

			/*
			 * The $files variable was either not an array or an empty array
			 */
			return false;
		}
		return count($files);
	}

	function _registerAddon($pname, $table, $group=null)
	{
		//Get table
		$row =& JTable::getInstance($table, 'Table');
		
		//Is New?
		if($id = $this->_is_installed($pname, 'plugin'))
		{
			$row->load($id);
		}

		//Set Data
		$data = $this->getData($pname, $group);
		$row->bind($data);

		if (!$row->store()) {
			// Install failed, roll back changes
			$this->abort(JText::_('INSTALLER ERROR').': '.$db->stderr(true));
			return false;
		}

		// Since we have created a plugin item, we add it to the installation step stack
		$this->pushStep(array ('type' => 'plugin', 'id' => $row->id));

		return $row->id;
	}

	function getData($name, $type=null)
	{
		$data = array();

		$data['name'] = $name;
		
		if($type) $data['type'] = $type;

		$item =& $this->_manifest->getElementByPath('title');
		$data['title'] = (is_object($item)) ? JFilterInput::clean($item->data(), 'string') :'';

		$item =& $this->_manifest->getElementByPath('author');
		$data['author'] = (is_object($item)) ? JFilterInput::clean($item->data(), 'string') :'';

		$item =& $this->_manifest->getElementByPath('email');
		$data['email'] = (is_object($item)) ? JFilterInput::clean($item->data(), 'string') :'';

		$item =& $this->_manifest->getElementByPath('website');
		$data['website'] = (is_object($item)) ? JFilterInput::clean($item->data(), 'string') :'';

		$item =& $this->_manifest->getElementByPath('version');
		$data['version'] = (is_object($item)) ? JFilterInput::clean($item->data(), 'string') :'';

		$item =& $this->_manifest->getElementByPath('license');
		$data['license'] = (is_object($item)) ? JFilterInput::clean($item->data(), 'string') :'';

		$item =& $this->_manifest->getElementByPath('copyright');
		$data['copyright'] = (is_object($item)) ? JFilterInput::clean($item->data(), 'string') :'';

		$item =& $this->_manifest->getElementByPath('date');
		$data['create_date'] = (is_object($item)) ? JFilterInput::clean($item->data(), 'string') :'';
		
		return $data;
	}

	function removeFiles($type, $element)
	{
		jimport('joomla.filesystem.file');
		
		// Initialize variables
		$removefiles = array ();
		$retval = true;

		if (!is_a($element, 'JSimpleXMLElement') || !count($element->children())) {
			// Either the tag does not exist or has no children therefore we return zero files processed.
			return true;
		}

		// Get the array of file nodes to process
		$files = $element->children();
		if (count($files) == 0) {
			// No files to process
			return true;
		}

		// Process each file in the $files array (children of $tagName).
		foreach ($files as $file)
		{
			$path = $this->getPath('extension_root').DS.$file->data();

			/*
			 * Actually delete the files/folders
			 */
			if (is_dir($path)) {
				$val = JFolder::delete($path);
			} else {
				$val = JFile::delete($path);
			}

			if ($val === false) {
				$retval = false;
			}
		}

		return $retval;
	}

	function _clean_temp()
	{
		JFolder::delete($this->getPath('source'));
		JFile::delete($this->getPath('package'));
	}

	function setError($msg = null)
	{
		if(!$msg) { return false; }
		$this->_error 		= $msg;
	}

	function getError()
	{
		return $this->_error;
	}

	function setPackage($install_package = 'install_package')
	{
		$this->_package = JRequest::getVar($install_package, null, 'files', 'array' );

		if(is_null($this->_package)) {
			$this->setError(JText::_('INSTALLER ERROR2'));
			return false;
		}
		return true;
	}

	function check($install_package = 'install_package')
	{
		if (!(bool) ini_get('file_uploads')) {
			$this->setError(JText::_('INSTALLER ERROR1'));
			return false;
		}

		if (!extension_loaded('zlib')) {
			$this->setError(JText::_('INSTALLER ERROR3'));
			return false;
		}

		if ( $this->_package['error'] ){
			$this->setError(JText::_('INSTALLER ERROR4'));
			return false;
		}

		return true;
	}

	function install()
	{
		jimport('joomla.filesystem.file');

		if(!class_exists('JArchive')) {
			jimport('joomla.filesystem.archive');
		}

		if(!class_exists('JInstallerHelper')) {
			jimport('joomla.installer.helper');
		}

		// Check if already extracted
		if(!$this->getPath('package') && !$this->getPath('source'))
		{
			// upload the package
			JFile::upload($this->_package['tmp_name'], $this->_tmp_path.DS.$this->_package['name']);

			// unpack it
			$tmpdir = uniqid('install_');
			$this->setPath('source', JPath::clean($this->_tmp_path.DS.$tmpdir));
			$this->setPath('package', JPath::clean($this->_tmp_path.DS.$this->_package['name']));

			if (!JArchive::extract( $this->getPath('package'), $this->getPath('source') ))
			{
				$this->setError(JText::_('INSTALLER ERROR5'));
				$this->_clean_temp();
				return false;
			}
		}

		$dirList = array_merge(JFolder::files($this->getPath('source'), ''), JFolder::folders($this->getPath('source'), ''));

		if (count($dirList) == 1)
		{
			if (JFolder::exists($this->getPath('source').DS.$dirList[0])) {
				$this->setPath('source', JPath::clean($this->getPath('source').DS.$dirList[0]));
			}
		}

		//Get Type and check it
		if ($type = JInstallerHelper::detectType($this->getPath('source')))
		{
			if(!in_array($type, array('plugin', 'theme')))
			{
				$this->setError(JText::_('INSTALLER ERROR6'));
				$this->_clean_temp();
				return false;
			}
		}else {
			$this->setError(JText::_('INSTALLER ERROR6'));
			$this->_clean_temp();
			return false;
		}

		// We need to find the installation manifest file
		if (!$this->_findManifest())
		{
			$this->setError(JText::_('INSTALLER ERROR6'));
			$this->_clean_temp();
			return false;
		}

		// Set the installation path
		$element =& $this->_manifest->getElementByPath('files');

		if (is_a($element, 'JSimpleXMLElement') && count($element->children()))
		{
			$files = $element->children();
			foreach ($files as $file)
			{
				if ($file->attributes($type))
				{
					$pname = strtolower($file->attributes($type));
					break;
				}
			}
		}

		//Make sure we have a filename
		if(!isset($pname))
		{
			$this->_clean_temp();
			$this->setError(JText::_('INSTALLER ERROR6'));
			return false;
		}

		// clean name
		$pname = $this->_clean_name($pname);

		// check if already installed?
		if(!$this->_overwrite && $this->_is_installed($pname, $type))
		{
			$this->_clean_temp();
			$this->setError(JText::_('INSTALLER ERROR13'));
			return false;
		}

		//Set Path by extension type
		switch ($type)
		{
			case 'plugin':
				$group = $this->_manifest->attributes('group');
				// check for group
				if(!$group)
				{
					$this->_clean_temp();
					$this->setError(JText::_('INSTALLER ERROR6'));
					return false;
				}
				$this->setPath('install_root', JPATH_COMPONENT_SITE.DS.'addons'.DS.'plugins'.DS.strtolower($group).DS);
				break;
	
			case 'theme':
				$this->setPath('install_root', JPATH_COMPONENT_SITE.DS.'addons'.DS.'themes'.DS.$pname.DS);
				break;
		}

		// check plugin group
		$created = false;
		if($type == 'plugin')
		{
			//create group folder
			if(!is_dir($this->getPath('install_root')))
			{ 
				mkdir($this->getPath('install_root'), 0777); 
				chmod($this->getPath('install_root'), 0777); 
			}

			//create blank index file
			if(!file_exists($this->getPath('install_root').'index.html'))
			{ 
				$handle = fopen($this->getPath('install_root').'index.html', 'x+');
				fclose($handle);
			}
		}

		/*
		 * If we created the plugin directory and will want to remove it if we
		 * have to roll back the installation, lets add it to the installation
		 * step stack
		 */
		if ($created) {
			$this->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		if ($this->parseFiles($element) === false) {
			// Install failed, roll back changes
			$this->abort();
			return false;
		}

		// register extension
		switch ($type)
		{
			case 'plugin':
				$success = $this->_registerAddon($pname, 'plugins', $group);
				break;
	
			case 'theme':
				$success = $this->_registerAddon($pname, 'themes');
				break;
		}

		if(!$success) {
			$this->abort();
			return false;
		}

		// copy the manifest file
		if (!$this->copyManifest())
		{
			$this->abort(JText::_('INSTALLER ERROR18'));
			return false;
		}

		$this->_clean_temp();
		return true;
	}

	function uninstall($id, $type)
	{
		$id	  = (int)$id;

		// check id & type
		if(!$id || !$type) {
			$this->setError(JText::_('UNINSTALL ERROR1'));
			return false;
		}

		// load xml file
		switch ($type)
		{
			case 'plugin':
				$row =& JTable::getInstance('plugins', 'Table');
				$row->load($id);
				$this->setPath('extension_root', JPATH_COMPONENT_SITE.DS.'addons'.DS.'plugins'.DS.$row->type);
				$manifestFile = JPATH_COMPONENT_SITE.DS.'addons'.DS.'plugins'.DS.$row->type.DS.$row->name.".xml";
				break;
	
			case 'theme':
				$row =& JTable::getInstance('themes', 'Table');
				$row->load($id);
				$this->setPath('extension_root', JPATH_COMPONENT_SITE.DS.'addons'.DS.'themes'.DS.$row->name);
				$manifestFile = JPATH_COMPONENT_SITE.DS.'addons'.DS.'themes'.DS.$row->name.DS.$row->name.".xml";
				break;
				
			default:
				return false;
		}

		if(!$row) {
			$this->setError(JText::_('UNINSTALL ERROR1'));
			return false;
		}

		// Is the plugin we are trying to uninstall a core one?
		if (isset($row->iscore) && $row->iscore) {
			JError::raiseWarning(100, JText::_('UNINSTALL ERROR4'));
			return false;
		}

		if (file_exists($manifestFile))
		{
			$xml =& JFactory::getXMLParser('Simple');

			// If we cannot load the xml file return null
			if (!$xml->loadFile($manifestFile)) {
				JError::raiseWarning(100, JText::_('UNINSTALL ERROR5'));
				return false;
			}

			/*
			 * Check for a valid XML root tag.
			 * @todo: Remove backwards compatability in a future version
			 * Should be 'install', but for backward compatability we will accept 'mosinstall'.
			 */
			$root =& $xml->document;
			if ($root->name() != 'install') {
				JError::raiseWarning(100, JText::_('UNINSTALL ERROR6'));
				return false;
			}

			// Remove the plugin files
			$this->removeFiles($type, $root->getElementByPath('files'));
			
			JFile::delete($manifestFile);

		} else {
			JError::raiseWarning(100, JText::_('UNINSTALL ERROR3'));
			return false;
		}

		// Now we will no longer need the plugin object, so lets delete it
		$row->delete($row->id);
		unset($row);

		// If the folder is empty, let's delete it
		$files = JFolder::files($this->getPath('extension_root'));
		if (!count($files)) {
			JFolder::delete($this->getPath('extension_root'));
		}

		return $retval;
	}

	//Download update
	function download($package)
	{
		if ( ! preg_match('!^(http|https|ftp)://!i', $package) && file_exists($package) ) //Local file or remote?
			return $package; //must be a local file..

		//WARNING: The file is not automatically deleted, The script must unlink() the file.
		if ( ! $package )
			return $this->setError(JText::_('Invalid URL Provided'));

		//Create temporary file
		if ( empty($dir) )
			$dir = JPATH_ROOT.DS.'tmp';
		$download_file = basename($package);
		if ( empty($download_file) ) $download_file = time().'.zip';

		// Fix file ext.
		$ext = JFile::getExt($download_file);
		if(!in_array($ext, $this->mimeTypes)) $download_file = str_replace('.'.$ext , '.zip', $download_file);

		$download_file = $dir.DS.$download_file;
		@touch($download_file);

		//Set Error file not created
		if ( ! $download_file )
			return $this->setError(JText::_('Could not create Temporary file'));

		$handle = @fopen($download_file, 'wb');
		if ( ! $handle )
			return $this->setError(JText::_('Could not create Temporary file'));

		//Get Package
		$objFetchSite 	= & BloggieFactory::load('http');
		$response		= $objFetchSite->get($package, array('timeout' => 60));

		if ( $response['error'] ) {
			fclose($handle);
			unlink($download_file);
			return $this->setError($response['error']);
		}

		if ( $response['response']['code'] != '200' ){
			fclose($handle);
			unlink($download_file);
			return $this->setError(trim($response['response']['message']));
		}

		fwrite($handle, $response['body']);
		fclose($handle);

		return $this->_installDownloaded($download_file);
	}

	function _installDownloaded($download_file)
	{
		global $mainframe;

		jimport( 'joomla.installer.installer' );
		jimport( 'joomla.installer.helper' );
		jimport( 'joomla.filesystem.archive' );

		if (!file_exists($download_file))
		{
			$this->setError(JText::_('INSTALLER ERROR4'));
			return false;
		}

		// Unpack it
		$tmpdir = uniqid('install_');
		$this->setPath('source', JPath::clean($this->_tmp_path.DS.$tmpdir));
		$this->setPath('package', JPath::clean($download_file));

		$result = JArchive::extract( $this->getPath('package'), $this->getPath('source') );

		if ($result === false) {
			$this->setError(JText::_('INSTALLER ERROR5'));
			$this->_clean_temp();
			return false;
		}

		// Detect the install type
		if($this->_isAddon($this->getPath('source')))
		{
			// Install LyftenBloggie specific plugin
			return $this->install();
		}

		// Detect the package type
		$type = JInstallerHelper::detectType($this->getPath('source'));

		// Did you give us a valid package?
		if (!$type) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('INSTALLER ERROR7'));
			$this->_clean_temp();
			return false;
		}

		// Load installer Language file
		$lang =& JFactory::getLanguage();
		$lang->load( 'com_installer', JPATH_BASE, $lang->getTag(), true );

		// Get an installer instance
		$installer =& JInstaller::getInstance();

		// Install the package
		if (!$installer->install($this->getPath('source'))) {
			// There was an error installing the package
			$msg = JText::sprintf('INSTALLEXT', JText::_($type), JText::_('Error'));
			$result = false;
		} else {
			// Package installed sucessfully
			$msg = JText::sprintf('INSTALLEXT', JText::_($type), JText::_('Success'));
			$result = true;
		}

		// Set some model state values
		$mainframe->enqueueMessage($msg);

		// Cleanup the install files
		$this->_clean_temp();

		return $result;
	}

	/**
	 * Copies the installation manifest file to the extension folder in the given client
	 *
	 * @access	public
	 * @return	boolean	True on success, False on error
	 * @since	1.1.0
	 */
	function copyManifest()
	{
		$path['src'] 	= $this->getPath('manifest');
		$path['dest']  	= $this->getPath('install_root').DS.basename($this->getPath('manifest'));
		return $this->copyFiles(array ($path), true);
	}

	/**
	 * Is the xml file a valid Joomla installation manifest file
	 *
	 * @access	private
	 * @param	string	$file	An xmlfile path to check
	 * @return	mixed	A JSimpleXML document, or null if the file failed to parse
	 * @since	1.1.0
	 */
	function &_isManifest($file)
	{
		// Initialize variables
		$null	= null;
		$xml	=& JFactory::getXMLParser('Simple');

		// If we cannot load the xml file return null
		if (!$xml->loadFile($file)) {
			// Free up xml parser memory and return null
			unset ($xml);
			return $null;
		}

		/*
		 * Check for a valid XML root tag.
		 */
		$root =& $xml->document;
		if (!is_object($root) || $root->name() != 'install') {
			// Free up xml parser memory and return null
			unset ($xml);
			return $null;
		}

		// Valid manifest file return the object
		return $xml;
	}

	/**
	 * Method to detect the addon component (Joomla! or LyftenBloggie)
	 */
	function _isAddon($p_dir)
	{
		// Search the install dir for an xml file
		$files = JFolder::files($p_dir, '\.xml$', 1, true);

		// If at least one xml file exists
		if (!empty($files))
		{
			foreach ($files as $file)
			{
				// Is it a valid LyftenBloggie installation manifest file?
				$manifest = $this->_isManifest($file);

				if (!is_null($manifest))
				{
					$root = &$manifest->document;
					if ($root->attributes('component') == 'lyftenbloggie') {
						return true;
					}
					return false;
				}
			}
		}
		return false;
	}
}
?>