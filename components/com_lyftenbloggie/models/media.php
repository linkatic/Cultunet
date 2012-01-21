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

jimport( 'joomla.application.component.view');

/**
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.0.3
 */
class LyftenBloggieModelMedia extends JModel
{
	function getState($property = null)
	{
		static $set;

		if (!$set) {
			$folder = JRequest::getVar( 'folder', '', '', 'path' );
			$this->setState('folder', $folder);

			$parent = str_replace("\\", "/", dirname($folder));
			$parent = ($parent == '.') ? null : $parent;
			$this->setState('parent', $parent);
			$set = true;
		}
		return parent::getState($property);
	}

	function getImage()
	{
		$this->checkFolder();

		$list = $this->getList();
		return $list['images'];
	}

	function getFlash()
	{
		$this->checkFolder();

		$list = $this->getList();
		return $list['flash'];
	}

	function checkFolder()
	{
		// Get some paths from the request
		$base 		= COM_MEDIA_BASE;
		$mediaBase 	= str_replace(DS, '/', COM_MEDIA_BASE.'/');

		if (!is_dir($base)){
			@mkdir($base, 0755);
		}
				
		if (!file_exists($base.DS.'index.html')) {
			chmod($base, 0777);
			$handle = fopen($base.DS."index.html", 'x+');
			fclose($handle);
		}
	}

	function getDocuments()
	{
		$list = $this->getList();
		return $list['docs'];
	}

	/**
	 * Build imagelist
	 **/
	function getList()
	{
		static $list;

		// Only process the list once per request
		if (is_array($list)) {
			return $list;
		}

		jimport('joomla.filesystem.file');

		// Get current path from request
		$current = $this->getState('folder');

		// If undefined, set to empty
		if ($current == 'undefined') {
			$current = '';
		}

		// Initialize variables
		if (strlen($current) > 0) {
			$basePath = COM_MEDIA_BASE.DS.$current;
		} else {
			$basePath = COM_MEDIA_BASE;
		}
		$mediaBase = str_replace(DS, '/', COM_MEDIA_BASE.'/');

		$images 	= array ();
		$flash 		= array ();

		// Get the list of files and folders from the given folder
		$fileList 	= JFolder::files($basePath);

		// Iterate over the files if they exist
		if ($fileList !== false) {
			foreach ($fileList as $file)
			{
				if (is_file($basePath.DS.$file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html') {
					$tmp = new JObject();
					$tmp->name = $file;
					$tmp->path = str_replace(DS, '/', JPath::clean($basePath.DS.$file));
					$tmp->path_relative = str_replace($mediaBase, COM_MEDIA_BASEURL.'/', $tmp->path);
					$tmp->size = filesize($tmp->path);

					$ext = strtolower(JFile::getExt($file));
					switch ($ext)
					{
						// Image
						case 'jpg':
						case 'png':
						case 'gif':
						case 'xcf':
						case 'odg':
						case 'bmp':
						case 'jpeg':
							$info = @getimagesize($tmp->path);
							$tmp->width		= @$info[0];
							$tmp->height	= @$info[1];
							$tmp->type		= @$info[2];
							$tmp->mime		= @$info['mime'];

							$filesize		= MediaHelper::parseSize($tmp->size);

							if (($info[0] > 100) || ($info[1] > 100)) {
								$dimensions = MediaHelper::imageResize($info[0], $info[1], 100);
								$tmp->dimensions_100 = $dimensions;
							} else {
								$tmp->dimensions_100 	= " width=\"$tmp->width\" height=\"$tmp->height\"";
							}
							$images[] = $tmp;
							break;
						
						// Flash
						case 'swf':
							$tmp->icon				= JURI::base().'components/com_lyftenbloggie/assets/images/flash.png';
							$tmp->dimensions_100 	= " width=\"100px\" height=\"100px\"";
							$flash[] = $tmp;
							break;

						// Non-image document
						default:
							break;
					}
				}
			}
		}

		$list = array('flash' => $flash, 'images' => $images);

		return $list;
	}
	
	function SendUploadResults( $errorNumber, $fileUrl = '', $customMsg = '', $ajax=true )
	{
		global $mainframe;

		if(!$ajax)
			return ($customMsg) ? $customMsg : JText::_('FILE UPLOADED');
		
		//Clean up the path
		$rpl 		= array( '\\' => '\\\\', '"' => '\\"' );
		$fileUrl 	= strtr( $fileUrl, $rpl );
		
		//initialize variables
		$ckednum 	= JRequest::getVar( 'CKEditorFuncNum', 1);		

		//Return Message
		echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(".$ckednum.", '".$fileUrl."', '".$customMsg."');</script>";
			
		$mainframe->close();
	}
	
	function upload($ajax=true)
	{
		global $mainframe;

		$file 	= JRequest::getVar( 'upload', '', 'files', 'array' );
		$err	= null;

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		// Make the filename safe
		jimport('joomla.filesystem.file');

		if (isset($file['name'])) {
			$file['name']	= JFile::makeSafe($file['name']);
			$filepath 		= JPath::clean(COM_MEDIA_BASE.DS.strtolower($file['name']));
			$fileurl 		= COM_MEDIA_BASEURL.'/'.$file['name'];

			//Check filetype
			if (!MediaHelper::canUpload( $file, $err )) {
				return $this->SendUploadResults( '202', $fileurl, JText::_('UNSUPPORTED MEDIA TYPE'), $ajax ) ;
			}

			//Check if file exists
			if (JFile::exists($filepath)) {
				return $this->SendUploadResults( '202', $fileurl, JText::_('FILE ALREADY EXISTS'), $ajax ) ;
			}
			
			//Check Upload
			if (!JFile::upload($file['tmp_name'], $filepath)) {
				return $this->SendUploadResults( '202', $fileurl, JText::_('UNABLE TO UPLOAD FILE'), $ajax ) ;
			} else {
				return $this->SendUploadResults( 0, $fileurl, null, $ajax ) ;
			}
		}

		return $this->SendUploadResults( '202', JText::_('NO FILE SELECTED'), null, $ajax ) ;
	}
}