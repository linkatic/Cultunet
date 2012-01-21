<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
jimport('joomla.utilities.utility');

require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');

class CImageHelper
{
	// Resize the given image to a dest path. Src must exist
	// If original size is smaller, do not resize just make a copy
	static public function resize($srcPath, $destPath, $destType, $destWidth, $destHeight, $sourceX	= 0, $sourceY	= 0, $currentWidth=0, $currentHeight=0)
	{
	
		// See if we can grab image transparency
		$image				= CImageHelper::open( $srcPath , $destType );
		$transparentIndex	= imagecolortransparent( $image );
	
		// Create new image resource
		$image_p			= ImageCreateTrueColor( $destWidth , $destHeight );
		$background			= ImageColorAllocate( $image_p , 255, 255, 255 );
		
		// test if memory is enough
		if($image_p == FALSE){
			echo 'Image resize fail. Please increase PHP memory';
			return false;
		} 
		
		// Set the new image background width and height
		$resourceWidth		= $destWidth;
		$resourceHeight		= $destHeight;
		
		if(empty($currentHeight) && empty($currentWidth))
		{
			list($currentWidth , $currentHeight) = getimagesize( $srcPath );
		}
		// If image is smaller, just copy to the center
		$targetX = 0;
		$targetY = 0;
	
		// If the height and width is smaller, copy it to the center.
		if( $destType != 'image/jpg' &&	$destType != 'image/jpeg' && $destType != 'image/pjpeg' )
		{
			if( ($currentHeight < $destHeight) && ($currentWidth < $destWidth) )
			{
				$targetX = intval( ($destWidth - $currentWidth) / 2);
				$targetY = intval( ($destHeight - $currentHeight) / 2);
		
				// Since the 
		 		$destWidth = $currentWidth;
		 		$destHeight = $currentHeight;
			}
		}
		
		// Resize GIF/PNG to handle transparency
		if( $destType == 'image/gif' )
		{
			$colorTransparent = imagecolortransparent($image);
			imagepalettecopy($image, $image_p);
			imagefill($image_p, 0, 0, $colorTransparent);
			imagecolortransparent($image_p, $colorTransparent);
			imagetruecolortopalette($image_p, true, 256);
			imagecopyresized($image_p, $image, $targetX, $targetY, $sourceX, $sourceY, $destWidth , $destHeight , $currentWidth , $currentHeight );
		}
		else if( $destType == 'image/png' || $destType == 'image/x-png')
		{
			// Disable alpha blending to keep the alpha channel
			imagealphablending( $image_p , false);
			imagesavealpha($image_p,true);
			$transparent		= imagecolorallocatealpha($image_p, 255, 255, 255, 127);
			
			imagefilledrectangle($image_p, 0, 0, $resourceWidth, $resourceHeight, $transparent);
			imagecopyresampled($image_p , $image, $targetX, $targetY, $sourceX, $sourceY, $destWidth, $destHeight, $currentWidth, $currentHeight);
		}
		else
		{
			// Turn off alpha blending to keep the alpha channel
			imagealphablending( $image_p , false );
			imagecopyresampled( $image_p , $image, $targetX, $targetY, $sourceX, $sourceY, $destWidth , $destHeight , $currentWidth , $currentHeight );
		}
	
		// Output
		ob_start();
		
		// Test if type is png
		if( $destType == 'image/png' || $destType == 'image/x-png' )
		{
			imagepng( $image_p );
		}
		elseif ( $destType == 'image/gif')
		{		
			imagegif( $image_p );
		}
		else
		{
			// We default to use jpeg
			imagejpeg($image_p, null, 80);
		}
		
		$output = ob_get_contents();
		ob_end_clean();
		
		// @todo, need to verify that the $output is indeed a proper image data
		return JFile::write( $destPath , $output );
	}

	// if dest height/width is empty, then resize propotional to origianl width/height	
	static public function resizeProportional($srcPath, $destPath, $destType, $destWidth=0, $destHeight=0)
	{
		list($currentWidth, $currentHeight) = getimagesize( $srcPath );
		
		$config =& CFactory::getConfig();
		
		if($destWidth == 0)
		{
			// Calculate the width if the width is not set.
			$destWidth = intval($destHeight/$currentHeight * $currentWidth);
		}
		else
		{
			// Calculate the height if the width is set.
			$destHeight = intval( $destWidth / $currentWidth * $currentHeight);
		}
		
		$imageEngine	= $config->get('imageengine');
		$magickPath		= $config->get( 'magickPath' );
	
		// Use imageMagick if available
		if( class_exists('Imagick') && !empty( $magickPath ) && ($imageEngine == 'auto' || $imageEngine == 'imagick') )
		{
			$thumb = new Imagick();
			$thumb->readImage($srcPath);    
			$thumb->resizeImage($destWidth,$destHeight, MAGICK_FILTER ,1);
			$thumb->writeImage($destPath);
			$thumb->clear();
			$thumb->destroy();
			return true; 
		}
		else if( !empty( $magickPath ) && !class_exists( 'Imagick' ) )
		{
			// Execute the command to resize. In windows, the commands are executed differently.
			if( JString::strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' )
			{
				$file		= rtrim( $config->get( 'magickPath' ) , '/' ) . DS . 'convert.exe';
				$command	= '"' . rtrim( $config->get( 'magickPath' ) , '/' ) . DS . 'convert.exe"';
			}
			else
			{
				$file		= rtrim( $config->get( 'magickPath' ) , '/' ) . DS . 'convert';
				$command	= '"' . rtrim( $config->get( 'magickPath' ) , '/' ) . DS . 'convert"';
			}
			
			
			if( JFile::exists( $file ) && function_exists( 'exec') )
			{
				$execute	= $command . ' -resize ' . $destWidth . 'x' . $destHeight . ' ' . $srcPath . ' ' . $destPath;
				exec( $execute );
	
				// Test if the files are created, otherwise we know the exec failed.
				if( JFile::exists( $destPath ) )
				{
					return true;
				}
			}
		}
		
		// IF all else fails, we try to use GD
		return CImageHelper::resize($srcPath, $destPath, $destType, $destWidth, $destHeight);
	}
	
	/**
	 * Method to create a thumbnail for an image
	 *
	 * @param	$srcPath	The original source of the image.
	 * @param	$destPath	The destination path for the image
	 * @param	$destType	The destination image type.
	 * @param	$destWidth	The width of the thumbnail.
	 * @param	$destHeight	The height of the thumbnail.
	 * 
	 * @return	bool		True on success.
	 */ 
	static public function createThumb($srcPath, $destPath, $destType, $destWidth=64, $destHeight=64)
	{
		// Get the image size for the current original photo
		list( $currentWidth , $currentHeight )	= getimagesize( $srcPath );
		$config  = CFactory::getConfig();
		$jconfig = JFactory::getConfig();
		
		// Find the correct x/y offset and source width/height. Crop the image squarely, at the center.
		if( $currentWidth == $currentHeight )
		{
			$sourceX = 0;
			$sourceY = 0;
		}
		else if( $currentWidth > $currentHeight )
		{
			$sourceX			= intval( ( $currentWidth - $currentHeight ) / 2 );
			$sourceY 			= 0;
			$currentWidth		= $currentHeight;
		}
		else
		{
			$sourceX		= 0;
			$sourceY		= intval( ( $currentHeight - $currentWidth ) / 2 );
			$currentHeight	= $currentWidth;
		}
		
		$imageEngine 	= $config->get('imageengine');
		$magickPath		= $config->get( 'magickPath' );
		// Use imageMagick if available
		if( class_exists('Imagick') && ($imageEngine == 'auto' || $imageEngine == 'imagick' ) )
		{
			// Put the new image in temporary dest path, and move them using
			// Joomla API to ensure new folder is created
			$tempFilename = $jconfig->getValue('tmp_path'). DS . md5($destPath);
			
			$thumb = new Imagick();
			$thumb->readImage($srcPath);
			$thumb->cropThumbnailImage($destWidth, $destHeight); 
			$thumb->writeImage($tempFilename);
			$thumb->clear();
			$thumb->destroy(); 
			
			// Move to the correct path
			JFile::move($tempFilename,$destPath);
			return true;
		}
		else if( !empty( $magickPath ) && !class_exists( 'Imagick' ) )
		{
			// Execute the command to resize. In windows, the commands are executed differently.
			if( JString::strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' )
			{
				$file		= rtrim( $config->get( 'magickPath' ) , '/' ) . DS . 'convert.exe';
				$command	= '"' . rtrim( $config->get( 'magickPath' ) , '/' ) . DS . 'convert.exe"';
			}
			else
			{
				$file		= rtrim( $config->get( 'magickPath' ) , '/' ) . DS . 'convert';
				$command	= '"' . rtrim( $config->get( 'magickPath' ) , '/' ) . DS . 'convert"';
			}
			
			
			if( JFile::exists( $file ) && function_exists( 'exec') )
			{
				$execute	= $command . ' -resize ' . $destWidth . 'x' . $destHeight . ' ' . $srcPath . ' ' . $destPath;
				exec( $execute );
	
				// Test if the files are created, otherwise we know the exec failed.
				if( JFile::exists( $destPath ) )
				{
					return true;
				}
			}
		}
		
		// IF all else fails, we try to use GD
		return CImageHelper::resize( $srcPath , $destPath , $destType , $destWidth , $destHeight , $sourceX , $sourceY , $currentWidth , $currentHeight);
	}

	static public function getExtension( $type )
	{
		$type = JString::strtolower($type);
	
		if( $type == 'image/png' || $type == 'image/x-png' )
		{
			return '.png';
		}
		elseif ( $type == 'image/gif')
		{
			return '.gif';
		}
		
		// We default to use jpeg
		return '.jpg';
	}
	
	static public function isValidType( $type )
	{
        $type = JString::strtolower($type);
        $validType = array('image/png', 'image/x-png', 'image/gif', 'image/jpeg', 'image/pjpeg');

        return in_array($type, $validType );
	}
	
	public function isValid( $file )
	{
		$config			=& CFactory::getConfig();
		
		// Use imagemagick if available
		$imageEngine 	= $config->get('imageengine');
		$magickPath		= $config->get( 'magickPath' );
	
		if( class_exists('Imagick') && ($imageEngine == 'auto' || $imageEngine == 'imagick' ) )
		{
			$thumb = new Imagick();
			
			try
			{
				$imageOk = $thumb->readImage($file);
				$thumb->destroy(); 
			}
			catch( Exception $e) 
			{
				$imageOk	= false;
			}
			return $imageOk;
		}
		else if( !empty( $magickPath ) && !class_exists( 'Imagick' ) )
		{
			// Execute the command to resize. In windows, the commands are executed differently.
			if( JString::strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' )
			{
				$identifyFile	= rtrim( $config->get( 'magickPath' ) , '/' ) . DS . 'identify.exe';
				$command		= '""' . rtrim( $config->get( 'magickPath' ) , '/' ) . DS . 'identify.exe" -ping "' . $file . '""';
			}
			else
			{
				$identifyFile	= rtrim( $config->get( 'magickPath' ) , '/' ) . DS . 'identify';
				$command		= '"' . rtrim( $config->get( 'magickPath' ) , '/' ) . DS . 'identify" -ping "' . $file . '"';
			}
	
			if( JFile::exists( $identifyFile ) && function_exists( 'exec') )
			{
				$output		= exec( $command );
	
				// Test if there's any output, otherwise we know the exec failed.
				if( !empty( $output ) )
				{
					return true;
				}
			}
		}
		
		
		# JPEG:
		if( function_exists( 'imagecreatefromjpeg' ) )
		{
			$im = @imagecreatefromjpeg($file);
			if ($im !== false){ return true; }
		}
	
		if( function_exists( 'imagecreatefromgif' ) )
		{
			# GIF:
			$im = @imagecreatefromgif($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefrompng' ) )
		{
			# PNG:
			$im = @imagecreatefrompng($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromgd' ) )
		{
			# GD File:
			$im = @imagecreatefromgd($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromgd2' ) )
		{
			# GD2 File:
			$im = @imagecreatefromgd2($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromwbmp' ) )
		{
			# WBMP:
			$im = @imagecreatefromwbmp($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromxbm' ) )
		{
			# XBM:
			$im = @imagecreatefromxbm($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromxpm' ) )
		{
			# XPM:
			$im = @imagecreatefromxpm($file);
			if ($im !== false) { return true; }
		}
		
		// If all failed, this photo is invalid
		return false;
	}
	
	static public function open($file , $type)
	{
		// @rule: Test for JPG image extensions
		if( function_exists( 'imagecreatefromjpeg' ) && ( ( $type == 'image/jpg') || ( $type == 'image/jpeg' ) || ( $type == 'image/pjpeg' ) ) )
		{
			$im	= @imagecreatefromjpeg( $file );
	
			if( $im !== false ) { return $im; }
		}
		
		// @rule: Test for png image extensions
		if( function_exists( 'imagecreatefrompng' ) && ( ( $type == 'image/png') || ( $type == 'image/x-png' ) ) )
		{
			$im	= @imagecreatefrompng( $file );
	
			if( $im !== false ) { return $im; }
		}
	
		// @rule: Test for png image extensions
		if( function_exists( 'imagecreatefromgif' ) && ( ( $type == 'image/gif') ) )
		{
			$im	= @imagecreatefromgif( $file );
	
			if( $im !== false ) { return $im; }
		}
		
		if( function_exists( 'imagecreatefromgd' ) )
		{
			# GD File:
			$im = @imagecreatefromgd($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromgd2' ) )
		{
			# GD2 File:
			$im = @imagecreatefromgd2($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromwbmp' ) )
		{
			# WBMP:
			$im = @imagecreatefromwbmp($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromxbm' ) )
		{
			# XBM:
			$im = @imagecreatefromxbm($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromxpm' ) )
		{
			# XPM:
			$im = @imagecreatefromxpm($file);
			if ($im !== false) { return true; }
		}
		
		// If all failed, this photo is invalid
		return false;
	}
	
	static public function getSize( $source )
	{
		$obj		= new stdClass();
		list( $obj->width , $obj->height) = getimagesize( $source );
		return $obj;
	}
	
	static public function addWatermark( $backgroundImagePath , $destinationPath , $destinationType , $watermarkImagePath , $positionX = 0 , $positionY = 0 )
	{
		$watermarkInfo		= getimagesize( $watermarkImagePath );
		$backgroundImage	= CImageHelper::open( $backgroundImagePath , $destinationType );
		$watermarkImage		= CImageHelper::open( $watermarkImagePath , $watermarkInfo['mime'] );
		
		// Try to make the watermark image transparent
		imagecolortransparent( $watermarkImage ,imagecolorat( $watermarkImage , 0 , 0 ) );
	
		// Get overlay image width and hight
		$watermarkWidth		= imagesx( $watermarkImage );
		$watermarkHeight	= imagesy( $watermarkImage );
	
		// Combine background image and watermark into a single output image
		imagecopymerge( $backgroundImage , $watermarkImage , $positionX , $positionY , 0 , 0 , $watermarkWidth , $watermarkHeight , 100 );
	
		// Output
		ob_start();
	
		// Test if type is png
		if( $destinationType == 'image/png' || $destinationType == 'image/x-png' )
		{
			imagepng( $backgroundImage );
		}
		elseif ( $destinationType == 'image/gif')
		{
			imagegif( $backgroundImage );
		}
		else
		{
			// We default to use jpeg
			imagejpeg($backgroundImage, null, 80);
		}
		
		$output = ob_get_contents();
		ob_end_clean();
		
		
		// Delete old image
		JFile::delete( $backgroundImagePath );
		
		// Free any memory from the existing image resources
		imagedestroy( $backgroundImage );
		imagedestroy( $watermarkImage );
		
		return JFile::write( $destinationPath , $output );
	}
}

/**
 * Deprecated since 1.8
 * Use CImageHelper::resize instead. 
 */
function cImageResize($srcPath, $destPath, $destType, $destWidth, $destHeight, $sourceX	= 0, $sourceY	= 0, $currentWidth=0, $currentHeight=0)
{
	return CImageHelper::resize($srcPath, $destPath, $destType, $destWidth, $destHeight, $sourceX , $sourceY, $currentWidth, $currentHeight );
}

/**
 * Deprecated since 1.8
 * Use CImageHelper::resizeProportional instead. 
 */
function cImageResizePropotional($srcPath, $destPath, $destType, $destWidth=0, $destHeight=0)
{
	return CImageHelper::resizeProportional( $srcPath , $destPath , $destType , $destWidth , $destHeight );
}

/**
 * Deprecated since 1.8
 * Use CImageHelper::createThumb instead. 
 */
function cImageCreateThumb($srcPath, $destPath, $destType, $destWidth=64, $destHeight=64)
{
	return CImageHelper::createThumb($srcPath, $destPath, $destType, $destWidth, $destHeight);
}

/**
 * Deprecated since 1.8
 * Use CImageHelper::getExtension instead. 
 */
function cImageTypeToExt($type)
{
	return CImageHelper::getExtension( $type );
}

/**
 * Deprecated since 1.8
 * Use CImageHelper::isValidType instead. 
 */
function cValidImageType( $type )
{
	return CImageHelper::isValidType( $type );
}

/**
 * Deprecated since 1.8
 * Use CImageHelper::isValid instead. 
 */
function cValidImage( $file )
{
	return CImageHelper::isValid( $file );
}

/**
 * Deprecated since 1.8
 * Use CImageHelper::open instead. 
 */
function cImageOpen( $file , $type )
{
	return CImageHelper::open( $file , $type );
}

/**
 * Deprecated since 1.8
 * Use CImageHelper::getSize instead. 
 */
function cImageGetSize( $source )
{
	return CImageHelper::getSize( $source );
}

/**
 * Deprecated since 1.8
 * Use CImageHelper::addWatermark instead. 
 */
function cImageAddWatermark( $backgroundImagePath , $destinationPath , $destinationType , $watermarkImagePath , $positionX = 0 , $positionY = 0 )
{
	return CImageHelper::addWatermark( $backgroundImagePath , $destinationPath , $destinationType , $watermarkImagePath , $positionX , $positionY );
}