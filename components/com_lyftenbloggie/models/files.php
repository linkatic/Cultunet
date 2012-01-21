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
 * @since 1.0.3
 */
class LyftenBloggieModelFiles extends JModel
{
	var $_data 			= null;
	var $_basedir 		= null;
	var $_baseurl 		= null;
	
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();

		$params = $mainframe->getParams('com_media');

		//Set Media Path
		$view 			= JRequest::getCmd('view',null);
		$popup_upload 	= JRequest::getCmd('pop_up',null);
		$path 			= "file_path";
		if(substr(strtolower($view),0,6) == "images" || $popup_upload == 1) $path = "image_path";

		$dir 			= JRequest::getVar( 'folderlist' );
		$baseurl		= JURI::root().$params->get($path, 'images/stories').'/'.$dir;
		$basedir		= JPATH_ROOT.DS.$params->get($path, 'images/stories').DS.$dir;
		
		$this->setId($basedir, $baseurl);
	}

	/**
	 * Method to set the faq id
	 **/
	function setId($basedir, $baseurl)
	{
		// Set new faq ID
		$this->_basedir = $basedir;
		$this->_baseurl = $baseurl;
	}

	/**
	 * Overridden get method to get properties from the entry
	 **/
	function get($property, $default=null)
	{
		if ($this->_loadEntry()) {
			if(isset($this->_entry->$property)) {
				return $this->_entry->$property;
			}
		}
		return $default;
	}
	
	/**
	 * Overridden set method to pass properties on to the entry
	 **/
	function set( $property, $value=null )
	{
		if ($this->_loadEntry()) {
			$this->_entry->$property = $value;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Upload a file
	 **/
	function upload()
	{
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );

		$file 		= JRequest::getVar( 'Filedata', '', 'files', 'array' );
		$folder		= JRequest::getVar( 'folder', '', '', 'path' );
		$format		= JRequest::getVar( 'format', 'html', '', 'cmd');
		$return		= JRequest::getVar( 'return-url', null, 'post', 'base64' );
		$err		= null;

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		// Make the filename safe
		jimport('joomla.filesystem.file');
		$file['name']	= JFile::makeSafe($file['name']);

		if (isset($file['name'])) {
			$filepath = JPath::clean(COM_MEDIA_BASE.DS.$folder.DS.strtolower($file['name']));

			if (!MediaHelper::canUpload( $file, $err )) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'Invalid: '.$filepath.': '.$err));
					header('HTTP/1.0 415 Unsupported Media Type');
					jexit('Error. Unsupported Media Type!');
				} else {
					JError::raiseNotice(100, JText::_($err));
					// REDIRECT
					if ($return) {
						$mainframe->redirect(base64_decode($return).'&folder='.$folder);
					}
					return;
				}
			}

			if (JFile::exists($filepath)) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'File already exists: '.$filepath));
					header('HTTP/1.0 409 Conflict');
					jexit('Error. File already exists');
				} else {
					JError::raiseNotice(100, JText::_('Error. File already exists'));
					// REDIRECT
					if ($return) {
						$mainframe->redirect(base64_decode($return).'&folder='.$folder);
					}
					return;
				}
			}

			if (!JFile::upload($file['tmp_name'], $filepath)) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'Cannot upload: '.$filepath));
					header('HTTP/1.0 400 Bad Request');
					jexit('Error. Unable to upload file');
				} else {
					JError::raiseWarning(100, JText::_('Error. Unable to upload file'));
					// REDIRECT
					if ($return) {
						$mainframe->redirect(base64_decode($return).'&folder='.$folder);
					}
					return;
				}
			} else {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = &JLog::getInstance();
					$log->addEntry(array('comment' => $folder));
					jexit('Upload complete');
				} else {
					$mainframe->enqueueMessage(JText::_('Upload complete'));
					// REDIRECT
					if ($return) {
						$mainframe->redirect(base64_decode($return).'&folder='.$folder);
					}
					return;
				}
			}
		} else {
			$mainframe->redirect('index.php', 'Invalid Request', 'error');
		}
	}
	
	/**
	 * Method to get files data
	 *
	 * @access public
	 * @return object
	 */
	function getData()
	{
		global $mainframe;
		
		// get list of images
		$d = @dir( JPath::clean($this->_basedir));
		$return		= array();
		
		if($d) {
			//var_dump($d);
			$images 	= array();
			$allowable 	= 'gif|jpg|png|bmp';

			while (false !== ($entry = $d->read())) {
				$img_file = $entry;
				if(is_file( $this->_basedir.'/'.$img_file) && substr($entry,0,1) != '.' && strtolower($entry) !== 'index.html' ) {
				if (eregi( $allowable, $img_file )) {
						$image_info 				= @getimagesize( $this->_basedir.DS.$img_file);
						$file_details['file'] 		= $this->_basedir."/".$img_file;
						$file_details['img_info'] 	= $image_info;
						$file_details['size'] 		= filesize( $this->_basedir.DS.$img_file);
						$images[$entry] 			= $file_details;
					}
				}
			}
			$d->close();

			if(count($images) > 0 || count($folders) > 0 || count($docs) > 0) {
				//now sort the folders and images by name.
				ksort($images);

				if(count($images)>0) {
					for($i=0; $i<count($images); $i++) {

						$image_name 	= key($images);
						$info 			= $images[$image_name]['img_info'];
						$img_file 		= basename($images[$image_name]['file']);
						$img_url_link 	= $this->_baseurl ."/". rawurlencode( $img_file );
						$filesize 		= $this->_parse_size( $images[$image_name]['size'] );
						if ( ( $info[0] > 120 ) || ( $info[0] > 120 ) ) {
							$img_dimensions = $this->_imageResize($info[0], $info[1], 120);
						} else {
							$img_dimensions = 'width="'. $info[0] .'" height="'. $info[1] .'"';
						}
						
						$return[$i]['img_dimensions'] 	= $img_dimensions;
						$return[$i]['img_url_link'] 	= $img_url_link;
						$return[$i]['px'] 				= $info[0].'x'.$info[1].'px';
						$return[$i]['filesize'] 		= $filesize;
						$return[$i]['file'] 			= htmlspecialchars( substr( $image_name, 0, 20 ) . ( strlen( $image_name ) > 20 ? '...' : ''), ENT_QUOTES );

						next($images);

					}
				}

			}			
		}
	
		return $return;
	}

	/**
	 * Method to get list of folders
	 **/
	function getFolders()
	{
		// Get the list of folders
		jimport('joomla.filesystem.folder');
		$folders = JFolder::folders($this->_basedir, '.', true, true);

		// Load appropriate language files
		$lang = & JFactory::getLanguage();
		$lang->load('', JPATH_ADMINISTRATOR);
		$lang->load(JRequest::getCmd( 'option' ), JPATH_ADMINISTRATOR);

		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('Insert Image'));

		// Build the array of select options for the folder list
		$options[] = JHTML::_('select.option', "","/");
		foreach ($folders as $folder) {
			$folder 	= str_replace($this->_basedir, "", $folder);
			$value		= substr($folder, 1);
			$text	 	= str_replace(DS, "/", $folder);
			$options[] 	= JHTML::_('select.option', $value, $text);
		}

		// Sort the folder list array
		if (is_array($options)) {
			sort($options);
		}

		// Create the drop-down folder select list
		$list = JHTML::_('select.genericlist',  $options, 'folderlist', "class=\"inputbox\" size=\"1\" onchange=\"document.adminForm.submit()\" ", 'value', 'text', $this->_basedir);
		return $list;
	}
	
	/**
	 * Method to sorten the size of the file
	 **/
	function _parse_size($size){
		if($size < 1024) {
			return $size.' bytes';
		} else if($size >= 1024 && $size < 1024*1024) {
			return sprintf('%01.2f',$size/1024.0).' Kb';
		} else {
			return sprintf('%01.2f',$size/(1024.0*1024)).' Mb';
		}
	}
	
	/**
	 * Method to make the image a viewable size
	 **/	
	function _imageResize($width, $height, $target) {

			if ($width > $height) {
				$percentage = ($target / $width);
			} else {
				$percentage = ($target / $height);
			}

			$width = round($width * $percentage);
			$height = round($height * $percentage);

			return "width=\"$width\" height=\"$height\"";

	}
	
	/**
	 * return list of directories
	 **/	
	function _listofdirectories( $listdir ) {

		static $filelist = array();
		static $dirlist = array();

		$path = JPath::clean($listdir);

		if(is_dir($path)) {
			$dh = opendir($path);
			while (false !== ($dir = readdir($dh))) {
				if (is_dir($path.DS.$dir) && $dir !== '.' && $dir !== '..' && strtolower($dir) !== 'cvs' && strtolower($dir) !== '.svn') {
					$subbase = $listdir.'/'.$dir;
					$dirlist[] = $subbase;
					$subdirlist = $this->_listofdirectories($subbase);
				}
			}
			closedir($dh);
		}
		return $dirlist;
	}
}
?>