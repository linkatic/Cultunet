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
 * LyftenBloggie Framework Image class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieImage extends JObject
{
  var $file_src_name;
  var $file_src_name_body;
  var $file_src_name_ext;
  var $file_src_mime;
  var $file_src_size;
  var $file_src_error;
  var $file_src_pathname;
  var $file_dst_path;
  var $file_dst_name;
  var $file_dst_name_body;
  var $file_dst_name_ext;
  var $file_dst_pathname;
  var $image_src_x;  //source width
  var $image_src_y;  //source height
  var $image_dst_x;
  var $image_dst_y;
  var $uploaded;
  var $no_upload_check;
  var $processed;
  var $error;
  var $log;
  var $file_new_name_body;
  var $file_name_body_add;
  var $file_new_name_ext;
  var $file_safe_name;
  var $mime_check;
  var $mime_magic_check;
  var $no_script;
  var $file_auto_rename;
  var $dir_auto_create;
  var $dir_auto_chmod;
  var $dir_chmod;
  var $file_overwrite;
  var $file_max_size;
  var $image_resize;
  var $image_convert;
  var $image_x;  //width
  var $image_y;  //height
  var $image_ratio;
  var $image_ratio_crop;
  var $image_ratio_no_zoom_in;
  var $image_ratio_no_zoom_out;
  var $image_ratio_x;
  var $image_ratio_y;
  var $jpeg_quality;
  var $jpeg_size;
  var $preserve_transparency;

  var $image_flip;
  var $image_rotate;
  var $image_crop;
  var $image_watermark;
  var $image_watermark_position;
  var $image_watermark_x;
  var $image_watermark_y;
  var $allowed;
	

	/**
	 * Init or re-init all the processing variables to their default values
	 *
	 * This function is called in the constructor, and after each call of {@link process}
	 *
	 * @access private
	 */
	function init()
	{
		// overiddable variables
		$this->file_new_name_body	= '';	   	// replace the name body
		$this->file_name_body_add	= '';	   	// append to the name body
		$this->file_new_name_ext	= '';	   	// replace the file extension
		$this->file_safe_name		= true;	 	// format safely the filename
		$this->file_overwrite		= false;	// allows overwritting if the file already exists
		$this->file_auto_rename		= true;	 	// auto-rename if the file already exists
		$this->dir_auto_create		= true;	 	// auto-creates directory if missing
		$this->dir_auto_chmod		= true;	 	// auto-chmod directory if not writeable
		$this->dir_chmod			= 0777;	 	// default chmod to use
		
		$this->mime_check			= true;	 	// don't check the mime type against the allowed list
		$this->mime_magic_check		= false;	// don't double check the MIME type with mime_magic
		$this->no_script			= true;	 	// turns scripts into test files 

		$val = trim(ini_get('upload_max_filesize'));
		$last = strtolower($val{strlen($val)-1});
		switch($last) {
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
		$this->file_max_size = $val;   
		
		$this->image_resize			 	= false;	// resize the image
		$this->image_convert			= '';	   // convert. values :''; 'png'; 'jpeg'; 'gif'

		$this->image_x				  	= 200;
		$this->image_y				  	= 200;
		$this->image_ratio			  	= true;	// keeps aspect ration with x and y dimensions
		$this->image_ratio_crop		 	= true;	// keeps aspect ration with x and y dimensions, filling the space
		$this->image_ratio_no_zoom_in   = false;
		$this->image_ratio_no_zoom_out  = false;
		$this->image_ratio_x			= false;	// calculate the $image_x if true
		$this->image_ratio_y			= false;	// calculate the $image_y if true
		$this->jpeg_quality			 	= 100;
		$this->jpeg_size				= NULL;
		$this->preserve_transparency	= false;
		
		$this->image_watermark		  	= NULL;
		$this->image_watermark_x		= NULL;
		$this->image_watermark_y		= NULL;
		$this->image_watermark_position = NULL; 

		$this->image_flip			   	= NULL; 
		$this->image_rotate			 	= NULL;   
		$this->image_crop			   	= NULL;

		$this->allowed = array("image/gif",
							   "image/jpeg",
							   "image/pjpeg",
							   "image/png",);
	}

	/**
	 * Constructor. Checks if the file has been uploaded
	 *
	 * The constructor takes $_FILES['form_field'] array as argument
	 * where form_field is the form field name
	 *
	 * The constructor will check if the file has been uploaded in its temporary location, and
	 * accordingly will set {@link uploaded} (and {@link error} is an error occurred)
	 *
	 * If the file has been uploaded, the constructor will populate all the variables holding the upload 
	 * information (none of the processing class variables are used here).
	 * You can have access to information about the file (name, size, MIME type...).
	 *
	 *
	 * Alternatively, you can set the first argument to be a local filename (string)
	 * and the second argument to be a MIME type (string) (second argument optional if mime_magic is installed)
	 * This allows processing of a local file, as if the file was uploaded
	 *
	 * @access private
	 * @param  array  $file $_FILES['form_field']
	 *	or   string $file Local filename
	 */
	function BloggieImage($file)
	{
		$this->file_src_name	  = '';
		$this->file_src_name_body = '';
		$this->file_src_name_ext  = '';
		$this->file_src_mime	  = '';
		$this->file_src_size	  = '';
		$this->file_src_error	  = '';
		$this->file_src_pathname  = '';

		$this->file_dst_path	  = '';
		$this->file_dst_name	  = '';
		$this->file_dst_name_body = '';
		$this->file_dst_name_ext  = '';
		$this->file_dst_pathname  = '';

		$this->image_src_x		 = 0;
		$this->image_src_y		 = 0;
		$this->image_dst_type	 = '';
		$this->image_dst_x		 = 0;
		$this->image_dst_y		 = 0;

		$this->uploaded		   	= true;
		$this->no_upload_check	= false;
		$this->processed		= true;
		$this->error			= '';
		$this->log				= '';		
		$this->allowed			= array();
		$this->init();

		if (!$file) {
			$this->uploaded = false;
			$this->error = JText::_("File error. Please try again");
		}

		// check if we sent a local filename rather than a $_FILE element
		if (!is_array($file)) {
			if (empty($file)) {
				$this->uploaded = false;
				$this->error = JText::_("File error. Please try again");
			} else {
				$this->no_upload_check = TRUE;
				// this is a local filename, i.e.not uploaded
				$this->log .= '<b>' . JText::_("source is a local file") . ' ' . $file . '</b><br />';

				if ($this->uploaded && !file_exists($file)) {
					$this->uploaded = false;
					$this->error = JText::_("Local file doesn't exist");
				}
		
				if ($this->uploaded && !is_readable($file)) {
					$this->uploaded = false;
					$this->error = JText::_("Local file is not readable");
				}

				if ($this->uploaded) {
					$this->file_src_pathname   = $file;
					$this->file_src_name	   = basename($file);
					$this->log .= '- ' . JText::_("local file name OK") . '<br />';
					ereg('\.([^\.]*$)', $this->file_src_name, $extension);
					if (is_array($extension)) {
						$this->file_src_name_ext	  = strtolower($extension[1]);
						$this->file_src_name_body	 = substr($this->file_src_name, 0, ((strlen($this->file_src_name) - strlen($this->file_src_name_ext)))-1);
					} else {
						$this->file_src_name_ext	  = '';
						$this->file_src_name_body	 = $this->file_src_name;
					}
					$this->file_src_size = (file_exists($file) ? filesize($file) : 0);
					// we try to retrieve the MIME type
					$info = getimagesize($this->file_src_pathname);
					$this->file_src_mime = (array_key_exists('mime', $info) ? $info['mime'] : NULL); 
					// if we don't have a MIME type, we attempt to retrieve it the old way
					if (empty($this->file_src_mime)) {
						$mime = (array_key_exists(2, $info) ? $info[2] : NULL); // 1 = GIF, 2 = JPG, 3 = PNG
						$this->file_src_mime = ($mime==1 ? 'image/gif' : ($mime==2 ? 'image/jpeg' : ($mime==3 ? 'image/png' : NULL)));
					}
					// if we still don't have a MIME type, we attempt to retrieve it otherwise
					if (empty($this->file_src_mime) && function_exists('mime_content_type')) {
						$this->file_src_mime = mime_content_type($this->file_src_pathname);
					}					 
					$this->file_src_error = 0; 
				}				
				
			}
		} else {
			// this is an element from $_FILE, i.e. an uploaded file
			$this->log .= '<b>' . JText::_("source is an uploaded file") . '</b><br />';
			if ($this->uploaded) {
				$this->file_src_error		 = $file['error'];
				switch($this->file_src_error) {
					case 0:
						// all is OK
						$this->log .= '- ' . JText::_("upload OK") . '<br />';
						break;
					case 1:
						$this->uploaded = false;
						$this->error = JText::_("File upload error (the uploaded file exceeds the upload_max_filesize directive in php.ini)");
						break;
					case 2:
						$this->uploaded = false;
						$this->error = JText::_("File upload error (the uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form)");
						break;
					case 3:
						$this->uploaded = false;
						$this->error = JText::_("File upload error (the uploaded file was only partially uploaded)");
						break;
					case 4:
						$this->uploaded = false;
						$this->error = JText::_("File upload error (no file was uploaded)");
						break;
					default:
						$this->uploaded = false;
						$this->error = JText::_("File upload error (unknown error code)");
				}
			}
	
			if ($this->uploaded) {
				$this->file_src_pathname   = $file['tmp_name'];
				$this->file_src_name	   = $file['name'];
				if ($this->file_src_name == '') {
					$this->uploaded = false;
					$this->error = JText::_("File upload error. Please try again");
				}
			}
			
			// height/width
			$imginfo 			= getimagesize($file['tmp_name']);
			$this->image_src_x	= $imginfo[0];
			$this->image_src_y	= $imginfo[1];				

			if ($this->uploaded) {
				$this->log .= '- ' . JText::_("file name OK") . '<br />';
				ereg('\.([^\.]*$)', $this->file_src_name, $extension);
				if (is_array($extension)) {
					$this->file_src_name_ext	  = strtolower($extension[1]);
					$this->file_src_name_body	 = substr($this->file_src_name, 0, ((strlen($this->file_src_name) - strlen($this->file_src_name_ext)))-1);
				} else {
					$this->file_src_name_ext	  = '';
					$this->file_src_name_body	 = $this->file_src_name;
				}
				$this->file_src_size = $file['size'];
				$this->file_src_mime = $file['type'];
			}
		}

		$this->log .= '- ' . JText::_("source variables") . '<br />';
		$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_src_name		 : ' . $this->file_src_name . '<br />';
		$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_src_name_body	: ' . $this->file_src_name_body . '<br />';
		$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_src_name_ext	 : ' . $this->file_src_name_ext . '<br />';
		$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_src_pathname	 : ' . $this->file_src_pathname . '<br />';
		$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_src_mime		 : ' . $this->file_src_mime . '<br />';
		$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_src_size		 : ' . $this->file_src_size . ' (max= ' . $this->file_max_size . ')<br />';
		$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_src_error		: ' . $this->file_src_error . '<br />';
	}

	/**
	 * Returns the version of GD
	 *
	 * @access public
	 * @return float GD version
	 */
	function gd_version() {
		static $gd_version = null;
		if ($gd_version === null) {
			if (function_exists('gd_info')) {
				$gd = gd_info();
				$gd = $gd["GD Version"];
				$regex = "/([\d\.]+)/i";
			} else {
				ob_start();
				phpinfo(8);
				$gd = ob_get_contents();
				ob_end_clean();
				$regex = "/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i";
			}
			if (preg_match($regex, $gd, $m)) {
				$gd_version = (float) $m[1];
			} else {
				$gd_version = 0;
			}
		}
		return $gd_version;
	} 


	/**
	 * Creates directories recursively
	 *
	 * @access private
	 * @param  string  $path Path to create
	 * @param  integer $mode Optional permissions
	 * @return boolean Success
	 */
	function r_mkdir($path, $mode = 0777) {
		return is_dir($path) || ( $this->r_mkdir(dirname($path), $mode) && @mkdir($path, $mode) );
	}

	/**
	 * Actually uploads the file, and act on it according to the set processing class variables
	 *
	 * This function copies the uploaded file to the given location, eventually performing actions on it.
	 * Typically, you can call {@link process} several times for the same file,
	 * for instance to create a resized image and a thumbnail of the same file.
	 * The original uploaded file remains intact in its temporary location, so you can use {@link process} several times.
	 * You will be able to delete the uploaded file with {@link clean} when you have finished all your {@link process} calls.
	 *
	 * According to the processing class variables set in the calling file, the file can be renamed,
	 * and if it is an image, can be resized or converted.
	 *
	 * When the processing is completed, and the file copied to its new location, the
	 * processing class variables will be reset to their default value.
	 * This allows you to set new properties, and perform another {@link process} on the same uploaded file
	 *
	 * It will set {@link processed} (and {@link error} is an error occurred)
	 *
	 * @access public
	 * @param  string $server_path Path location of the uploaded file, with an ending slash
	 */
	function process($server_path) {

		$this->error		= '';
		$this->processed	= true;

		if(strtolower(substr(PHP_OS, 0, 3)) === 'win') {
			if (substr($server_path, -1, 1) != '\\') $server_path = $server_path . '\\';
		} else {
			if (substr($server_path, -1, 1) != '/') $server_path = $server_path . '/';
		}
		$this->log .= '<b>' . JText::_("process file to") . ' '  . $server_path . '</b><br />';

		// checks file size and mine type
		if ($this->uploaded) {

			if ($this->file_src_size > $this->file_max_size ) {
				$this->processed = false;
				$this->error = JText::_("File too big");
			} else {
				$this->log .= '- ' . JText::_("file size OK") . '<br />';
			}

			// turn dangerous scripts into text files
			if ($this->no_script) {
				if (((substr($this->file_src_mime, 0, 5) == 'text/' || strpos($this->file_src_mime, 'javascript') !== false)  && (substr($this->file_src_name, -4) != '.txt')) 
					|| preg_match('/\.(php|pl|py|cgi|asp)$/i', $this->file_src_name) || empty($this->file_src_name_ext)) {
					$this->file_src_mime = 'text/plain';
					$this->log .= '- ' . JText::_("script") . ' '  . $this->file_src_name . ' ' . JText::_("renamed as") . ' ' . $this->file_src_name . '.txt!<br />';
					$this->file_src_name_ext .= (empty($this->file_src_name_ext) ? 'txt' : '.txt');
				} 
			}

			// checks MIME type with mime_magic
			if ($this->mime_magic_check && function_exists('mime_content_type')) {
				$detected_mime = mime_content_type($this->file_src_pathname);
				if ($this->file_src_mime != $detected_mime) {
					$this->log .= '- ' . JText::_("MIME type detected as") . ' ' . $detected_mime . ' ' . JText::_("but given as") . ' ' . $this->file_src_mime . '!<br />';
					$this->file_src_mime = $detected_mime;
				}
			} 

			if ($this->mime_check && empty($this->file_src_mime)) {
				$this->processed = false;
				$this->error = JText::_("MIME type can't be detected!");
			} else if ($this->mime_check && !empty($this->file_src_mime) && !array_key_exists($this->file_src_mime, array_flip($this->allowed))) {
				$this->processed = false;
				$this->error = JText::_("Incorrect type of file");
			} else {
				$this->log .= '- ' . JText::_("file mime OK") . ' : ' . $this->file_src_mime . '<br />';
			}
		} else {
			$this->error = JText::_("File not uploaded. Can't carry on a process");
			$this->processed = false;
		}

		if ($this->processed) {
			$this->file_dst_path		= $server_path;

			// repopulate dst variables from src
			$this->file_dst_name		= $this->file_src_name;
			$this->file_dst_name_body   = $this->file_src_name_body;
			$this->file_dst_name_ext	= $this->file_src_name_ext;


			if ($this->file_new_name_body != '') { // rename file body
				$this->file_dst_name_body = $this->file_new_name_body;
				$this->log .= '- ' . JText::_("new file name body") . ' : ' . $this->file_new_name_body . '<br />';
			}
			if ($this->file_new_name_ext != '') { // rename file ext
				$this->file_dst_name_ext  = $this->file_new_name_ext;
				$this->log .= '- ' . JText::_("new file name ext") . ' : ' . $this->file_new_name_ext . '<br />';
			}
			   if ($this->file_name_body_add != '') { // append a bit to the name
				$this->file_dst_name_body  = $this->file_dst_name_body . $this->file_name_body_add;
				$this->log .= '- ' . JText::_("file name body add") . ' : ' . $this->file_name_body_add . '<br />';
			}
			if ($this->file_safe_name) { // formats the name
				$this->file_dst_name_body = str_replace(array(' ', '-'), array('_','_'), $this->file_dst_name_body) ;
				$this->file_dst_name_body = ereg_replace('[^A-Za-z0-9_]', '', $this->file_dst_name_body) ;
				$this->log .= '- ' . JText::_("file name safe format") . '<br />';
			}

			$this->log .= '- ' . JText::_("destination variables") . '<br />';
			$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_dst_path		 : ' . $this->file_dst_path . '<br />';
			$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_dst_name_body	: ' . $this->file_dst_name_body . '<br />';
			$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_dst_name_ext	 : ' . $this->file_dst_name_ext . '<br />';

			// do we do some image manipulation?
			$image_manipulation  = ($this->image_resize 
								 || $this->image_convert != '' 
								 || !empty($this->image_watermark)
								 || is_numeric($this->image_rotate)
								 || is_numeric($this->jpeg_size)
								 || !empty($this->image_flip)
								 || !empty($this->image_crop));

			if ($image_manipulation) {
				if ($this->image_convert=='') {
					$this->file_dst_name = $this->file_dst_name_body . (!empty($this->file_dst_name_ext) ? '.' . $this->file_dst_name_ext : '');
					$this->log .= '- ' . JText::_("image operation, keep extension") . '<br />';
				} else {
					$this->file_dst_name = $this->file_dst_name_body . '.' . $this->image_convert;
					$this->log .= '- ' . JText::_("image operation, change extension for conversion type") . '<br />';
				}
			} else {
				$this->file_dst_name = $this->file_dst_name_body . (!empty($this->file_dst_name_ext) ? '.' . $this->file_dst_name_ext : '');
				$this->log .= '- ' . JText::_("no image operation, keep extension") . '<br />';
			}
			
			if (!$this->file_auto_rename) {
				$this->log .= '- ' . JText::_("no auto_rename if same filename exists") . '<br />';
				$this->file_dst_pathname = $this->file_dst_path . $this->file_dst_name;
			} else {
				$this->log .= '- ' . JText::_("checking for auto_rename") . '<br />';
				$this->file_dst_pathname = $this->file_dst_path . $this->file_dst_name;
				$body	 = $this->file_dst_name_body;
				$cpt = 1;
				while (@file_exists($this->file_dst_pathname)) {
					$this->file_dst_name_body = $body . '_' . $cpt;
					$this->file_dst_name = $this->file_dst_name_body . (!empty($this->file_dst_name_ext) ? '.' . $this->file_dst_name_ext : '');
					$cpt++;
					$this->file_dst_pathname = $this->file_dst_path . $this->file_dst_name;
				}			   
				if ($cpt>1) $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("auto_rename to") . ' ' . $this->file_dst_name . '<br />';
			}
			
			$this->log .= '- ' . JText::_("destination file details") . '<br />';
			$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_dst_name		 : ' . $this->file_dst_name . '<br />';
			$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_dst_pathname	 : ' . $this->file_dst_pathname . '<br />';

			if ($this->file_overwrite) {
				 $this->log .= '- ' . JText::_("no overwrite checking") . '<br />';
			} else {
				if (@file_exists($this->file_dst_pathname)) {
					$this->processed = false;
					$this->error = $this->file_dst_name . ' ' . JText::_("already exists. Please change the file name");
				} else {
					$this->log .= '- ' . $this->file_dst_name . ' '  . JText::_("doesn't exist already") . '<br />';
				}
			}
		} else {
			$this->processed = false;
		}

		if (!$this->no_upload_check && !is_uploaded_file($this->file_src_pathname)) {
			$this->processed = false;
			$this->error = JText::_("No correct source file. Can't carry on a process");
		}

		if ($this->processed && !file_exists($this->file_src_pathname)) {
			$this->processed = false;
			$this->error = JText::_("No source file. Can't carry on a process");
		}
	
		// checks if the destination directory is readable	 
		if ($this->processed && !($f = @fopen($this->file_src_pathname, 'r'))) {
			$this->processed = false;
			if (is_readable($this->file_src_pathname)) {
				$this->error = JText::_("Source file is not readable. open_basedir restriction in place?");
			} else {
				$this->error = JText::_("Source file is not readable. Can't carry on a process");
			}
		} else {
			@fclose($f);
		}

		// checks if the destination directory exists, and attempt to create it		
		if ($this->processed && !file_exists($this->file_dst_path)) {
			if ($this->dir_auto_create) {
				$this->log .= '- ' . $this->file_dst_path . ' '  . JText::_("doesn't exist. Attempting creation:");
				if (!$this->r_mkdir($this->file_dst_path, $this->dir_chmod)) {
					$this->log .= ' ' . JText::_("failed") . '<br />';
					$this->processed = false;
					$this->error = JText::_("Destination directory can't be created. Can't carry on a process");
				} else {
					$this->log .= ' ' . JText::_("success") . '<br />';
				}
			} else {
				$this->error = JText::_("Destination directory doesn't exist. Can't carry on a process");
			}
		}

		if ($this->processed && !is_dir($this->file_dst_path)) {
			$this->processed = false;
			$this->error = JText::_("Destination path is not a directory. Can't carry on a process");
		}

		// checks if the destination directory is writeable, and attempt to make it writeable	  
		if ($this->processed && !($f = @fopen($this->file_dst_pathname, 'w+'))) {
			if ($this->dir_auto_chmod) {
				$this->log .= '- ' . $this->file_dst_path . ' '  . JText::_("is not writeable. Attempting chmod:");
				if (!@chmod($this->file_dst_path, $this->dir_chmod)) {
					$this->log .= ' ' . JText::_("failed") . '<br />';
					$this->processed = false;
					$this->error = JText::_("Destination directory can't be made writeable. Can't carry on a process");
				} else {
					$this->log .= ' ' . JText::_("success") . '<br />';
					if (!($f = @fopen($this->file_dst_pathname, 'w+'))) { // we re-check
						$this->processed = false;
						$this->error = JText::_("Destination directory is still not writeable. Can't carry on a process");
					} else {
						@fclose($f);
					}
				}				
			} else {
				$this->processed = false;
				$this->error = JText::_("Destination path is not a writeable. Can't carry on a process");
			}
		} else {
			@fclose($f);
		}

		if ($this->processed)
		{
			if ($image_manipulation) {
			 
				$this->log .= '- ' . JText::_("image resizing or conversion wanted") . '<br />';
				if ($this->gd_version()) {
					switch($this->file_src_mime) {
						case 'image/pjpeg':
						case 'image/jpeg':
						case 'image/jpg':
							if (!function_exists('imagecreatefromjpeg')) {
								$this->processed = false;
								$this->error = JText::_("No create from JPEG support");
							} else {
								$image_src = @imagecreatefromjpeg($this->file_src_pathname);
								if (!$image_src) {
									$this->processed = false;
									$this->error = JText::_("Error in creating JPEG image from source");
								} else {
									$this->log .= '- ' . JText::_("source image is JPEG") . '<br />';
								}
							}
							break;
						case 'image/png':
							if (!function_exists('imagecreatefrompng')) {
								$this->processed = false;
								$this->error = JText::_("No create from PNG support");
							} else {
								$image_src = @imagecreatefrompng($this->file_src_pathname);
								if (!$image_src) {
									$this->processed = false;
									$this->error = JText::_("Error in creating PNG image from source");
								} else {
									$this->log .= '- ' . JText::_("source image is PNG") . '<br />';
								}
							}
							break;
						case 'image/gif':
							if (!function_exists('imagecreatefromgif')) {
								$this->processed = false;
								$this->error = JText::_("Error in creating GIF image from source");
							} else {
								$image_src = @imagecreatefromgif($this->file_src_pathname);
								if (!$image_src) {
									$this->processed = false;
									$this->error = JText::_("No GIF read support");
								} else {
									$this->log .= '- ' . JText::_("source image is GIF") . '<br />';
								}
							}
							break;
						default:
							$this->processed = false;
							$this->error = JText::_("Can't read image source. not an image?");
					}
				} else {
					$this->processed = false;
					$this->error = JText::_("GD doesn't seem to be present");
				}

				if ($this->processed && $image_src) {

					$this->image_src_x = imagesx($image_src);
					$this->image_src_y = imagesy($image_src);
					$this->image_dst_x = $this->image_src_x;
					$this->image_dst_y = $this->image_src_y;
					$gd_version = $this->gd_version();
					$ratio_crop = null;

					if ($this->image_resize) {
						$this->log .= '- ' . JText::_("resizing...") . '<br />';
 
						if ($this->image_ratio_x) {
							$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("calculate x size") . '<br />';
							$this->image_dst_x = round(($this->image_src_x * $this->image_y) / $this->image_src_y);
							$this->image_dst_y = $this->image_y;
						} else if ($this->image_ratio_y) {
							$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("calculate y size") . '<br />';
							$this->image_dst_x = $this->image_x;
							$this->image_dst_y = round(($this->image_src_y * $this->image_x) / $this->image_src_x);
						} else if ($this->image_ratio || $this->image_ratio_crop || $this->image_ratio_no_zoom_in || $this->image_ratio_no_zoom_out) {
							$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("check x/y sizes") . '<br />';
							if ((!$this->image_ratio_no_zoom_in && !$this->image_ratio_no_zoom_out)
								 || ($this->image_ratio_no_zoom_in && ($this->image_src_x > $this->image_x || $this->image_src_y > $this->image_y))
								 || ($this->image_ratio_no_zoom_out && $this->image_src_x < $this->image_x && $this->image_src_y < $this->image_y)) {
								$this->image_dst_x = $this->image_x;
								$this->image_dst_y = $this->image_y;
								if ($this->image_ratio_crop) {
									if (($this->image_src_x/$this->image_x) > ($this->image_src_y/$this->image_y)) {								 
										$this->image_dst_y = $this->image_y;
										$this->image_dst_x = intval($this->image_src_x*($this->image_y / $this->image_src_y));
										$ratio_crop = array();
										$ratio_crop['x'] = $this->image_dst_x -$this->image_x;
										$ratio_crop['l'] = round($ratio_crop['x']/2);
										$ratio_crop['r'] = $ratio_crop['x'] - $ratio_crop['l'];
										$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("ratio_crop_x") . '		 : ' . $ratio_crop['x'] . ' (' . $ratio_crop['l'] . ';' . $ratio_crop['r'] . ')<br />';
										if (is_null($this->image_crop)) $this->image_crop = array(0, 0, 0, 0);
									} else {				 
										$this->image_dst_x = $this->image_x;
										$this->image_dst_y = intval($this->image_src_y*($this->image_x / $this->image_src_x));
										$ratio_crop = array();
										$ratio_crop['y'] = $this->image_dst_y - $this->image_y;
										$ratio_crop['t'] = round($ratio_crop['y']/2);
										$ratio_crop['b'] = $ratio_crop['y'] - $ratio_crop['t'];
										$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("ratio_crop_y") . '		 : ' . $ratio_crop['y'] . ' (' . $ratio_crop['t'] . ';' . $ratio_crop['b'] . ')<br />';
										if (is_null($this->image_crop)) $this->image_crop = array(0, 0, 0, 0);
									}
								} else {
									if (($this->image_src_x/$this->image_x) > ($this->image_src_y/$this->image_y)) {
										$this->image_dst_x = $this->image_x;
										$this->image_dst_y = intval($this->image_src_y*($this->image_x / $this->image_src_x));
									} else {
										$this->image_dst_y = $this->image_y;
										$this->image_dst_x = intval($this->image_src_x*($this->image_y / $this->image_src_y));
									}
								}
							} else {
								$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("doesn't calculate x/y sizes") . '<br />';
								$this->image_dst_x = $this->image_src_x;
								$this->image_dst_y = $this->image_src_y;
							}
						} else {
							$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("use plain sizes") . '<br />';
							$this->image_dst_x = $this->image_x;
							$this->image_dst_y = $this->image_y;
						}

						if ($this->preserve_transparency && $this->file_src_mime != 'image/gif' && $this->file_src_mime != 'image/png') $this->preserve_transparency = false;		

						if ($gd_version >= 2 && !$this->preserve_transparency) {
							$image_dst = imagecreatetruecolor($this->image_dst_x, $this->image_dst_y);
						} else {
							$image_dst = imagecreate($this->image_dst_x, $this->image_dst_y);
						}
		
						if ($this->preserve_transparency) {		
							$this->log .= '- ' . JText::_("preserve transparency") . '<br />';
							$transparent_color = imagecolortransparent($image_src);
							imagepalettecopy($image_dst, $image_src);
							imagefill($image_dst, 0, 0, $transparent_color);
							imagecolortransparent($image_dst, $transparent_color);
						}

						if ($gd_version >= 2 && !$this->preserve_transparency) {
							$res = imagecopyresampled($image_dst, $image_src, 0, 0, 0, 0, $this->image_dst_x, $this->image_dst_y, $this->image_src_x, $this->image_src_y);
						} else {
							$res = imagecopyresized($image_dst, $image_src, 0, 0, 0, 0, $this->image_dst_x, $this->image_dst_y, $this->image_src_x, $this->image_src_y);
						}

						$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("resized image object created") . '<br />';
						$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;image_src_x y		: ' . $this->image_src_x . ' x ' . $this->image_src_y . '<br />';
						$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;image_dst_x y		: ' . $this->image_dst_x . ' x ' . $this->image_dst_y . '<br />';

					} else {
						// we only convert, so we link the dst image to the src image
						$image_dst = & $image_src;
					}

					// we have to set image_convert if it is not already
					if (empty($this->image_convert)) {
						$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("setting destination file type to") . ' ' . $this->file_src_name_ext . '<br />';
						$this->image_convert = $this->file_src_name_ext;
					}

					// crop imag (and also crops if image_ratio_crop is used)
					if ($gd_version >= 2 && (!empty($this->image_crop) || !is_null($ratio_crop))) {
						if (is_array($this->image_crop)) {
							$vars = $this->image_crop;
						} else {
							$vars = explode(' ', $this->image_crop);
						}
						if (sizeof($vars) == 4) {
							$ct = $vars[0]; $cr = $vars[1]; $cb = $vars[2]; $cl = $vars[3];
						} else if (sizeof($vars) == 2) {
							$ct = $vars[0]; $cr = $vars[1]; $cb = $vars[0]; $cl = $vars[1];
						} else {
							$ct = $vars[0]; $cr = $vars[0]; $cb = $vars[0]; $cl = $vars[0];
						} 
						if (strpos($ct, '%')>0) $ct = $this->image_dst_y * (str_replace('%','',$ct) / 100);
						if (strpos($cr, '%')>0) $cr = $this->image_dst_x * (str_replace('%','',$cr) / 100);
						if (strpos($cb, '%')>0) $cb = $this->image_dst_y * (str_replace('%','',$cb) / 100);
						if (strpos($cl, '%')>0) $cl = $this->image_dst_x * (str_replace('%','',$cl) / 100);
						if (strpos($ct, 'px')>0) $ct = str_replace('px','',$ct);
						if (strpos($cr, 'px')>0) $cr = str_replace('px','',$cr);
						if (strpos($cb, 'px')>0) $cb = str_replace('px','',$cb);
						if (strpos($cl, 'px')>0) $cl = str_replace('px','',$cl);
						$ct = (int) $ct;
						$cr = (int) $cr;
						$cb = (int) $cb;
						$cl = (int) $cl;
						// we adjust the cropping if we use image_ratio_crop
						if (!is_null($ratio_crop)) {
							if (array_key_exists('t', $ratio_crop)) $ct += $ratio_crop['t'];
							if (array_key_exists('r', $ratio_crop)) $cr += $ratio_crop['r'];
							if (array_key_exists('b', $ratio_crop)) $cb += $ratio_crop['b'];
							if (array_key_exists('l', $ratio_crop)) $cl += $ratio_crop['l'];
						}
						$this->log .= '- ' . JText::_("crop image") . ' : ' . $ct . ' ' . $cr . ' ' . $cb . ' ' . $cl . ' <br />';
						$this->image_dst_x = $this->image_dst_x - $cl - $cr;
						$this->image_dst_y = $this->image_dst_y - $ct - $cb;
						if ($this->image_dst_x < 1) $this->image_dst_x = 1;
						if ($this->image_dst_y < 1) $this->image_dst_y = 1;

						$tmp=imagecreatetruecolor($this->image_dst_x, $this->image_dst_y);
						imagecopy($tmp, $image_dst, 0, 0, $cl, $ct, $this->image_dst_x, $this->image_dst_y);

						// we transfert tmp into image_dst
						imagedestroy($image_dst);	 
						$image_dst=imagecreatetruecolor($this->image_dst_x, $this->image_dst_y);
						imagecopy($image_dst,$tmp,0,0,0,0,$this->image_dst_x,$this->image_dst_y);
						imagedestroy($tmp);	  
					}
					
					
					// flip image
					if ($gd_version >= 2 && !empty($this->image_flip)) {
						$this->image_flip = strtolower($this->image_flip);
						$this->log .= '- ' . JText::_("flip image") . ' : ' . $this->image_flip . '<br />';
						$tmp=imagecreatetruecolor($this->image_dst_x, $this->image_dst_y);
						for ($x = 0; $x < $this->image_dst_x; $x++) {
							for ($y = 0; $y < $this->image_dst_y; $y++){
								if (strpos($this->image_flip, 'v') !== false) {
									imagecopy($tmp, $image_dst, $this->image_dst_x - $x - 1, $y, $x, $y, 1, 1);
								} else {
									imagecopy($tmp, $image_dst, $x, $this->image_dst_y - $y - 1, $x, $y, 1, 1);
								}
							}
						}

						// we transfert tmp into image_dst
						imagedestroy($image_dst);	 
						$image_dst=imagecreatetruecolor($this->image_dst_x, $this->image_dst_y);
						imagecopy($image_dst,$tmp,0,0,0,0,$this->image_dst_x,$this->image_dst_y);
						imagedestroy($tmp);	  
					}


					// rotate image
					if ($gd_version >= 2 && is_numeric($this->image_rotate)) {
						if (!in_array($this->image_rotate, array(0, 90, 180, 270))) $this->image_rotate = 0;  
						if ($this->image_rotate != 0) {
							if ($this->image_rotate == 90 || $this->image_rotate == 270) {
								$tmp=imagecreatetruecolor($this->image_dst_y, $this->image_dst_x);
							} else {
								$tmp=imagecreatetruecolor($this->image_dst_x, $this->image_dst_y);
							}
							$this->log .= '- ' . JText::_("rotate image") . ' : ' . $this->image_rotate . '<br />';
							for ($x = 0; $x < $this->image_dst_x; $x++) {
								for ($y = 0; $y < $this->image_dst_y; $y++){
									if ($this->image_rotate == 90) {
										imagecopy($tmp, $image_dst, $y, $x, $x, $this->image_dst_y - $y - 1, 1, 1);
									} else if ($this->image_rotate == 180) {
										imagecopy($tmp, $image_dst, $x, $y, $this->image_dst_x - $x - 1, $this->image_dst_y - $y - 1, 1, 1);
									} else if ($this->image_rotate == 270) {
										imagecopy($tmp, $image_dst, $y, $x, $this->image_dst_x - $x - 1, $y, 1, 1);
									} else {
										imagecopy($tmp, $image_dst, $x, $y, $x, $y, 1, 1);
									}
								}
							}
							if ($this->image_rotate == 90 || $this->image_rotate == 270) {
								$t = $this->image_dst_y;
								$this->image_dst_y = $this->image_dst_x;
								$this->image_dst_x = $t;
							}
							
							// we transfert tmp into image_dst
							imagedestroy($image_dst);	 
							$image_dst=imagecreatetruecolor($this->image_dst_x, $this->image_dst_y);
							imagecopy($image_dst,$tmp,0,0,0,0,$this->image_dst_x,$this->image_dst_y);
							imagedestroy($tmp);	  
						}						
					}

					// add watermark image
					if ($this->image_watermark!='' && file_exists($this->image_watermark)) {
						$this->log .= '- ' . JText::_("add watermark") . '<br />';
						$this->image_watermark_position = strtolower($this->image_watermark_position);
						
						$watermark_info = getimagesize($this->image_watermark);
						$watermark_type = (array_key_exists(2, $watermark_info) ? $watermark_info[2] : NULL); // 1 = GIF, 2 = JPG, 3 = PNG
						$watermark_checked = false;

						if ($watermark_type == 1) {
							if (!function_exists('imagecreatefromgif')) {
								$this->error = JText::_("No create from GIF support, can't read watermark");
							} else {
								$filter = @imagecreatefromgif($this->image_watermark);
								if (!$filter) {
									$this->error = JText::_("No GIF read support, can't create watermark");
								} else {
									$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("watermark source image is GIF") . '<br />';
									$watermark_checked = true;
								}
							}
						} else if ($watermark_type == 2) {
							if (!function_exists('imagecreatefromjpeg')) {
								$this->error = JText::_("No create from JPG support, can't read watermark");
							} else {
								$filter = @imagecreatefromjpeg($this->image_watermark);
								if (!$filter) {
									$this->error = JText::_("No JPG read support, can't create watermark");
								} else {
									$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("watermark source image is JPG") . '<br />';
									$watermark_checked = true;
								}
							}
						} else if ($watermark_type == 3) {
							if (!function_exists('imagecreatefrompng')) {
								$this->error = JText::_("No create from PNG support, can't read watermark");
							} else {
								$filter = @imagecreatefrompng($this->image_watermark);
								if (!$filter) {
									$this->error = JText::_("No PNG read support, can't create watermark");
								} else {
									$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("watermark source image is PNG") . '<br />';
									$watermark_checked = true;
								}
							}
						}
						if ($watermark_checked) {
							$watermark_width = imagesx($filter);
							$watermark_height = imagesy($filter);
							$watermark_x = 0;
							$watermark_y = 0;
							if (is_numeric($this->image_watermark_x)) {
								if ($this->image_watermark_x < 0) {
									$watermark_x = $this->image_dst_x - $watermark_width + $this->image_watermark_x;
								} else {
									$watermark_x = $this->image_watermark_x;
								}
							} else {
								if (strpos($this->image_watermark_position, 'r') !== false) {
									$watermark_x = $this->image_dst_x - $watermark_width;
								} else if (strpos($this->image_watermark_position, 'l') !== false) {
									$watermark_x = 0;
								} else {
									$watermark_x = ($this->image_dst_x - $watermark_width) / 2;
								}
							}
		 
							if (is_numeric($this->image_watermark_y)) {
								if ($this->image_watermark_y < 0) {
									$watermark_y = $this->image_dst_y - $watermark_height + $this->image_watermark_y;
								} else {
									$watermark_y = $this->image_watermark_y;
								}
							} else {
								if (strpos($this->image_watermark_position, 'b') !== false) {
									$watermark_y = $this->image_dst_y - $watermark_height;
								} else if (strpos($this->image_watermark_position, 't') !== false) {
									$watermark_y = 0;
								} else {
									$watermark_y = ($this->image_dst_y - $watermark_height) / 2;
								}
							}
							imagecopyresampled ($image_dst, $filter, $watermark_x, $watermark_y, 0, 0, $watermark_width, $watermark_height, $watermark_width, $watermark_height);
						
						} else {
							$this->error = JText::_("Watermark image is of unknown type");
						}						
					}

					if (is_numeric($this->jpeg_size) && $this->jpeg_size > 0 && ($this->image_convert == 'jpeg' || $this->image_convert == 'jpg'))
					{
						// inspired by: JPEGReducer class version 1, 25 November 2004, Author: Huda M ElMatsani, justhuda at netscape dot net
						$this->log .= '- ' . JText::_("JPEG desired file size") . ' : ' . $this->jpeg_size . '<br />';
						//calculate size of each image. 75%, 50%, and 25% quality
						ob_start(); imagejpeg($image_dst,'',75);  $buffer = ob_get_contents(); ob_end_clean();
						$size75 = strlen($buffer);
						ob_start(); imagejpeg($image_dst,'',50);  $buffer = ob_get_contents(); ob_end_clean();
						$size50 = strlen($buffer);
						ob_start(); imagejpeg($image_dst,'',25);  $buffer = ob_get_contents(); ob_end_clean();
						$size25 = strlen($buffer);
				
						//calculate gradient of size reduction by quality
						$mgrad1 = 25/($size50-$size25);
						$mgrad2 = 25/($size75-$size50);
						$mgrad3 = 50/($size75-$size25);
						$mgrad  = ($mgrad1+$mgrad2+$mgrad3)/3;
						//result of approx. quality factor for expected size
						$q_factor=round($mgrad*($this->jpeg_size-$size50)+50);
				
						if ($q_factor<1) {
							$this->jpeg_quality=1;
						} elseif ($q_factor>100) {
							$this->jpeg_quality=100;
						} else {
							$this->jpeg_quality=$q_factor;
						}
						$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("JPEG quality factor set to") . ' ' . $this->jpeg_quality . '<br />';
					}

					// outputs image
					$this->log .= '- ' . JText::_("converting..") . '<br />';
					switch($this->image_convert) {
						case 'jpeg':
						case 'jpg':
							$result = @imagejpeg ($image_dst, $this->file_dst_pathname, $this->jpeg_quality);
							if (!$result) {
								$this->processed = false;
								$this->error = JText::_("No JPEG create support");
							} else {
								$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("JPEG image created") . '<br />';
							}
							break;
						case 'png':
							$result = @imagepng ($image_dst, $this->file_dst_pathname);
							if (!$result) {
								$this->processed = false;
								$this->error = JText::_("No PNG create support");
							} else {
								$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("PNG image created") . '<br />';
							}
							break;
						case 'gif':
							$result = @imagegif ($image_dst, $this->file_dst_pathname);
							if (!$result) {
								$this->processed = false;
								$this->error = JText::_("No GIF create support");
							} else {
								$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("GIF image created") . '<br />';
							}
							break;
						default:
							$this->processed = false;
							$this->error = JText::_("No convertion type defined");
					}
					if ($this->processed) {
						if (is_resource($image_src)) imagedestroy($image_src);
						if (is_resource($image_dst)) imagedestroy($image_dst);
						$this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;' . JText::_("image objects destroyed") . '<br />';
					}
				}

			} else {
				$this->log .= '- ' . JText::_("no image processing wanted") . '<br />';

				if (!$this->no_upload_check) {
					$result = is_uploaded_file($this->file_src_pathname);
				} else {
					$result = TRUE;
				}
				if ($result) {
					$result = file_exists($this->file_src_pathname);
					if ($result) {
						$result = copy($this->file_src_pathname, $this->file_dst_pathname);
						if (!$result) {
							$this->processed = false;
							$this->error = JText::_("Error copying file on the server. Copy failed");
						}
					} else {
						$this->processed = false;
						$this->error = JText::_("Error copying file on the server. Missing source file");
					}
				} else {
					$this->processed = false;
					$this->error = JText::_("Error copying file on the server. Incorrect source file");
				}
			}

		}

		if ($this->processed) {
			$this->log .= '- <b>' . JText::_("process OK") . '</b><br />';

		}
		// we reinit all the var
		$this->init();

	}

	/**
	 * Deletes the uploaded file from its temporary location
	 *
	 * When PHP uploads a file, it stores it in a temporary location.
	 * When you {@link process} the file, you actually copy the resulting file to the given location, it doesn't alter the original file.
	 * Once you have processed the file as many times as you wanted, you can delete the uploaded file.
	 *
	 * You might want not to use this function if you work on local files, as it will delete the source file
	 *
	 * @access public
	 */
	function clean() {
		@unlink($this->file_src_pathname);
	}
}
?>