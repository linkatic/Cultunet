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
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.0.3
 */
class MediaHelper
{
	/**
	 * Checks if the file is an image
	 */
	function isImage( $fileName )
	{
		static $imageTypes = 'xcf|odg|gif|jpg|png|bmp';
		return preg_match("/$imageTypes/i",$fileName);
	}

	/**
	 * Checks if the file is an image
	 */
	function toAbsolute($relative, $absolute = null)
	{
		global $mainframe;

		//Get absolute
		$absolute = ($absolute) ? $absolute : ($mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base());

		// If relative URL has a scheme, clean path and return.
		$r = parse_url( $relative );
		if ( $r === FALSE ) return FALSE;
		if(isset($r["scheme"]))return $relative;

		// Make sure the base URL is absolute.
		$b = parse_url($absolute);
	
		if($relative{0} == '/') {
			$cparts = array_filter(explode("/", $relative));
		} else {
			$aparts = array_filter(explode("/", $b['path']));
			$rparts = array_filter(explode("/", $relative));
			$cparts = array_merge($aparts, $rparts);
			foreach($cparts as $i => $part)
			{
				if($part == '.') {
					$cparts[$i] = null;
				}
				if($part == '..') {
					$cparts[$i - 1] = null;
					$cparts[$i] = null;
				}
			}
			$cparts = array_filter($cparts);
		}

		$path = implode("/", $cparts);
		$url = "";
		if($b['scheme']) {
			$url = $b['scheme'].'://';
		}
		if($b['host']) {
			$url .= $b['host'].'/';
		}
		$url .= $path;

		return $url;
	}

	/**
	 * Checks if the file is an image
	 */
	function getTypeIcon( $fileName )
	{
		// Get file extension
		return strtolower(substr($fileName, strrpos($fileName, '.') + 1));
	}

	/**
	 * Checks if the file can be uploaded
	 * @param array File information
	 * @param string An error message to be returned
	 * @return boolean
	 */
	function canUpload( $file )
	{
		$params = &JComponentHelper::getParams( 'com_media' );

		jimport('joomla.filesystem.file');
		$format = JFile::getExt($file['name']);

		$allowable = explode( ',', $params->get( 'upload_extensions' ));
		if (!in_array($format, $allowable))
		{
			return 'This file type is not supported - '.$format;
		}

		$maxSize = (int) $params->get( 'upload_maxsize', 0 );
		if ($maxSize > 0 && (int) $file['size'] > $maxSize)
		{
			return 'This file is too large to upload';
		}
		return false;
	}

	function parseSize($size)
	{
		if ($size < 1024) {
			return $size . ' bytes';
		}
		else
		{
			if ($size >= 1024 && $size < 1024 * 1024) {
				return sprintf('%01.2f', $size / 1024.0) . ' Kb';
			} else {
				return sprintf('%01.2f', $size / (1024.0 * 1024)) . ' Mb';
			}
		}
	}

	function imageResize($width, $height, $target)
	{
		//takes the larger size of the width and height and applies the
		//formula accordingly...this is so this script will work
		//dynamically with any size image
		if ($width > $height) {
			$percentage = ($target / $width);
		} else {
			$percentage = ($target / $height);
		}

		//gets the new value and applies the percentage, then rounds the value
		$width 	= round($width * $percentage);
		$height = round($height * $percentage);

		//returns the new sizes in html image tag format...this is so you
		//can plug this function inside an image tag and just get the
		return " width=\"$width\" height=\"$height\"";
	}

	function countFiles( $dir )
	{
		$total_file = 0;
		$total_dir = 0;

		if (is_dir($dir)) {
			$d = dir($dir);

			while (false !== ($entry = $d->read())) {
				if (substr($entry, 0, 1) != '.' && is_file($dir . DIRECTORY_SEPARATOR . $entry) && strpos($entry, '.html') === false && strpos($entry, '.php') === false) {
					$total_file++;
				}
				if (substr($entry, 0, 1) != '.' && is_dir($dir . DIRECTORY_SEPARATOR . $entry)) {
					$total_dir++;
				}
			}

			$d->close();
		}

		return array ( $total_file, $total_dir );
	}

	/**
	 * Create a file in the upload folder with given content.
	 *
	 * If there is an error, then the key 'error' will exist with the error message.
	 * If success, then the key 'file' will have the unique file path, the 'url' key
	 * will have the link to the new file. and the 'error' key will be set to false.
	 *
	 * @param string $name
	 * @param null $deprecated Not used. Set to null.
	 * @param mixed $bits File content
	 * @param string $overwrite Optional
	 * @return array
	 */
	function uploadBits( $name, $deprecated, $bits, $overwrite = false )
	{
		if ( empty( $name ) )
			return array( 'error' => JText::_( 'Empty filename' ) );

		if ( $wp_filetype = MediaHelper::canUpload( array('name'=>$name, 'size'=>strlen($bits)) ) )
			return array( 'error' => $wp_filetype );

		$author = &BloggieAuthor::getInstance();
		$upload = $author->checkFolders();

		$filename = MediaHelper::uniqueFilename( $upload['base'], $name, $overwrite );

		$new_file = $upload['base'].DS.$filename;

		$ifp = @fopen( $new_file, 'wb' );
		if ( ! $ifp )
			return array( 'error' => sprintf( JText::_( 'Could not write file %s' ), $new_file ) );

		@fwrite( $ifp, $bits );
		fclose( $ifp );
		// Set correct file permissions
		$stat = @stat( dirname( $new_file ) );
		$perms = $stat['mode'] & 0007777;
		$perms = $perms & 0000666;
		@chmod( $new_file, $perms );

		return array( 'file' => $new_file, 'url' => $upload['url'].'/'.$filename, 'error' => false );
	}

	/**
	 * Get a filename that is sanitized and unique for the given directory.
	 *
	 * @param string $dir
	 * @param string $filename
	 * @param string $overwrite
	 * @return string New filename, if given wasn't unique.
	 */
	function uniqueFilename( $dir, $filename, $overwrite = false )
	{
		// sanitize the file name before we begin processing
		$filename = MediaHelper::sanitize_filename($filename);

		// separate the filename into a name and extension
		$info = pathinfo($filename);
		$ext = !empty($info['extension']) ? $info['extension'] : '';
		$name = basename($filename, ".{$ext}");

		// edge case: if file is named '.ext', treat as an empty name
		if( $name === ".$ext" )
			$name = '';

		// Increment the file number until we have a unique file to save in $dir.
		$number = '';

		if ( !empty( $ext ) )
			$ext = ".$ext";

		//delete old file
		if($overwrite)
		{
			if(file_exists($dir.DS.$filename))
			{
				@unlink($dir.DS.$filename);
			}
		}

		while ( file_exists($dir.DS.$filename) ) {
			if ( '' == "$number$ext" )
				$filename = $filename . ++$number . $ext;
			else
				$filename = str_replace( "$number$ext", ++$number . $ext, $filename );
		}
		return $filename;
	}

	/**
	 * Sanitizes a filename replacing whitespace with dashes
	 *
	 * @param string $filename The filename to be sanitized
	 * @return string The sanitized filename
	 */
	function sanitize_filename( $filename )
	{
		$special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}");
		$filename = str_replace($special_chars, '', $filename);
		$filename = preg_replace('/[\s-]+/', '-', $filename);
		$filename = trim($filename, '.-_');
		return $filename;
	}
}