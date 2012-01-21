<?php
/**
 * Image converter class
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: csvi_class_image_converter.php 869 2009-04-14 14:00:35Z Suami $
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Image converter
 *
 * @package CSVImproved
 */

class ImageConverter {
	
	/**	@var int $bg_red 0-255 - red color variable for background filler */
	private $bg_red = 0;
	/**	@var int $bg_green 0-255 - green color variable for background filler */
	private $bg_green = 0;
	/** @var int $bg_blue 0-255 - blue color variable for background filler */
	private $bg_blue = 0;
	/**	@var int $maxSize 0-1 - true/false - should thumbnail be filled to max pixels */
	private $maxSize = false;
	/** @var string Filename for the thumbnail */
	private $file_out = null;
	
	/**
	 *   Constructor - requires following vars:
	 *	
	 *	@param array $thumb_file_details contains all the variables for creating a new image
	 *	
	 */
	public function __construct($thumb_file_details) {
		
		/* Set all details */
		foreach ($thumb_file_details as $type => $value) {
			switch ($type) {
				case 'maxsize':
					if ($value) $this->maxSize = true;
					else $this->maxSize = false;
					break;
				case 'bgred':
					if ($thumb_file_details['bgred'] >= 0 || $thumb_file_details['bgred'] <= 255) $this->bg_red = $thumb_file_details['bgred'];
					else $this->bg_red = 0;
					break;
				case 'bggreen':
					if($thumb_file_details['bggreen'] >= 0 || $thumb_file_details['bggreen'] <= 255) $this->bg_green = $thumb_file_details['bggreen'];
					else $this->bg_green = 0;
					break;
				case 'bgblue':
					if($thumb_file_details['bgblue'] >= 0 || $thumb_file_details['bgblue'] <= 255) $this->bg_blue = $thumb_file_details['bgblue'];
					else $this->bg_blue = 0;
					break;
				default:
					$this->$type = $value;
					break;
			}
		}
		
		$this->NewImgCreate();
	}
	
	/**
	 * Start creating the new image
	 */
	private function NewImgCreate() {
		/* Clear the cache */
		clearstatcache();
		
		switch($this->file_extension) {
			case "gif":
				if( function_exists("imagecreatefromgif") ) {
					$orig_img = imagecreatefromgif($this->file);
					break;
				}
				else return false;
			case "jpg":
				if( function_exists("imagecreatefromjpeg") ) {
					$orig_img = imagecreatefromjpeg($this->file);
					break;
				}
				else return false;
				break;
			case "png":
				if( function_exists("imagecreatefrompng") ) {
					$orig_img = imagecreatefrompng($this->file);
					break;
				}
				else return false;
				break;
		}
		
		$new_img = $this->NewImgResize($orig_img);
		
		if (!empty($this->file_out)) $this-> NewImgSave($new_img);
		else $this->NewImgShow($new_img);
		
		ImageDestroy($new_img);
		ImageDestroy($orig_img);
	}
	
	/**
	 * Resize the image
	 *
	 * Includes function ImageCreateTrueColor and ImageCopyResampled which are available only under GD 2.0.1 or higher !
	 */
	private function NewImgResize($orig_img) {
		$orig_size = getimagesize($this->file);
		
		$maxX = $this->file_out_width;
		$maxY = $this->file_out_height;
		
		if ($orig_size[0] < $orig_size[1]) {
			$this->file_out_width = $this->file_out_height * ($orig_size[0]/$orig_size[1]);
			$adjustX = ($maxX - $this->file_out_width)/2;
			$adjustY = 0;
		}
		else {
			$this->file_out_height = $this->file_out_width / ($orig_size[0]/$orig_size[1]);
			$adjustX = 0;
			$adjustY = ($maxY - $this->file_out_height)/2;
		}
		
		while ($this->file_out_width < 1 || $this->file_out_height < 1) {
			$this->file_out_width *= 2;
			$this->file_out_height *= 2;
		}
		
		/* See if we need to create an image at maximum size */
		if ($this->maxSize) {
			if (function_exists("imagecreatetruecolor")) $im_out = imagecreatetruecolor($maxX,$maxY);
			else $im_out = imagecreate($maxX,$maxY);
			/* Need to image fill just in case image is transparent, don't always want black background */
			$bgfill = imagecolorallocate( $im_out, $this->bg_red, $this->bg_green, $this->bg_blue );
			
			if (function_exists("imageAntiAlias")) imageAntiAlias($im_out,true);
            imagealphablending($im_out, false);
            
			if (function_exists("imagesavealpha")) imagesavealpha($im_out,true);
			
			if (function_exists( "imagecolorallocatealpha")) $transparent = imagecolorallocatealpha($im_out, 255, 255, 255, 127);
			
			if (function_exists("imagecopyresampled")) ImageCopyResampled($im_out, $orig_img, $adjustX, $adjustY, 0, 0, $this->file_out_width, $this->file_out_height,$orig_size[0], $orig_size[1]);
			else ImageCopyResized($im_out, $orig_img, $adjustX, $adjustY, 0, 0, $this->file_out_width, $this->file_out_height,$orig_size[0], $orig_size[1]);
		}
		else {
			if (function_exists("imagecreatetruecolor")) $im_out = ImageCreateTrueColor($this->file_out_width,$this->file_out_height);
			else $im_out = imagecreate($this->file_out_width,$this->file_out_height);
			  
			if (function_exists("imageAntiAlias")) imageAntiAlias($im_out,true);
 		    imagealphablending($im_out, false);
			
		    if (function_exists("imagesavealpha")) imagesavealpha($im_out,true);
		    if (function_exists("imagecolorallocatealpha")) $transparent = imagecolorallocatealpha($im_out, 255, 255, 255, 127);
			  
			if (function_exists("imagecopyresampled")) ImageCopyResampled($im_out, $orig_img, 0, 0, 0, 0, $this->file_out_width, $this->file_out_height,$orig_size[0], $orig_size[1]);
			else ImageCopyResized($im_out, $orig_img, 0, 0, 0, 0, $this->file_out_width, $this->file_out_height,$orig_size[0], $orig_size[1]);
		}
		
		return $im_out;
	}
	
	/**
	 * Save the new image
	 *
	 */
	private function NewImgSave($new_img) {
		switch($this->file_out_extension) {
			case "gif":
			if (!function_exists("imagegif")) {
				if (strtolower(substr($this->file_out,strlen($this->file_out)-4,4)) != ".gif") $this->file_out .= ".png";
				return imagepng($new_img,$this->file_out);
			}
			else {
				if (strtolower(substr($this->file_out,strlen($this->file_out)-4,4)) != ".gif") $this->file_out .= ".gif";
				return imagegif ($new_img, $this->file_out);
			}
			break;
			case "jpg":
				if (strtolower(substr($this->file_out,strlen($this->file_out)-4,4)) != ".jpg")
				$this->file_out .= ".jpg";
				return imagejpeg($new_img, $this->file_out, 100);
				break;
			case "png":
				if (strtolower(substr($this->file_out,strlen($this->file_out)-4,4)) != ".png")
				$this->file_out .= ".png";
				return imagepng($new_img,$this->file_out);
				break;
		}
	}
	
	/**
	 * Show the new image in the browser
	 *
	 */
	private function NewImgShow($new_img) {
		switch($this->file_out_extension) {
			case "gif":
				if (function_exists("imagegif")) {
					header ("Content-type: image/gif");
					return imagegif($new_img);
					break;
				}
				else $this->NewImgShow( $new_img, "jpg" );
				break;
			case "jpg":
				header ("Content-type: image/jpeg");
				return imagejpeg($new_img);
				break;
			case "png":
				header ("Content-type: image/png");
				return imagepng($new_img);
				break;
		}
	}
}
?>
