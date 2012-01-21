<?php

/**
* @author: GavickPro
* @copyright: 2008-2009
**/
	
// no direct access
defined('_JEXEC') or die('Restricted access');

class Image{	
	// uploading graphic
	function upload($mWidth, $mHeight, $sWidth, $sHeight, $bg, $Quality, $bigThumb, $smallThumb){
		// main variables
		global $mainframe;
		$plugin	= JRequest::getCmd('plugin');
		$option	= JRequest::getCmd('option');
		$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
		// preparing hash
		$randomHash = rand(100000, 999999);
		// Checking format - JPG/PNG/GIF are allowed
		if( $_FILES['image']['type'] == 'image/pjpeg' || 
			$_FILES['image']['type'] == 'image/jpg' || 
			$_FILES['image']['type'] == 'image/jpeg' || 
			$_FILES['image']['type'] == 'image/png' || 
			$_FILES['image']['type'] == 'image/gif' ||
			$_FILES['image']['type'] == 'image/x-png' )
		{
			// Removing from name all problematic chars
			$new_name = preg_replace('/[^a-zA-Z0-9.]/', '_', $_FILES['image']['name']);
			// importing filesystem library
			jimport('joomla.filesystem.file');
			// trying to upload file
			if(!JFile::upload($_FILES['image']['tmp_name'], JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'original'.DS.$randomHash.$new_name)){
				$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$_POST["gid"], JText::_("ERROR_MOVING_FILE"), 'error');
			}
		}
		else
		{
			// jeżeli grafika nie jest formatu JPG/PNG/GIF zwróć odpowiedni komunikat w formacie JSON
			$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view_group&cid[]='.$_POST["gid"], JText::_("INVALID_TYPE"), 'error');	 
		}
		
		// when everything is ok - we create thumbnails
		
		// big thumbnail
		if($bigThumb){
			$this->createThumbnail(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'original'.DS.$randomHash.$new_name, $randomHash.$new_name,$mWidth,$mHeight,'s_big',false,$bg,JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS, $Quality);
		}
		// small thumbnail
		if($smallThumb){
			$this->createThumbnail(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'original'.DS.$randomHash.$new_name, $randomHash.$new_name,$sWidth,$sHeight,'s_small',false,$bg,JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS, $Quality);
		}
		// returning filename
		return $randomHash.$new_name;
	}

	/*
		Creating thumbnails
	*/
	
	function createThumbnail($path, $name, $baseWidth, $baseHeight, $size, $str, $bg, $pathB, $Quality){
		//script configuration - increase memory limit to 64MB
		ini_set('memory_limit', '64M');
		// Duplicate path
		$imageToChange = $path;
		// Stretching
		$stretch = ($str == false && isset($_POST['stretch'])) ? $_POST['stretch'] : (($str == 2) ? 2 : (($str == 1 || $str == true) ? true : false)); 
		// Getting informations about image
		$imageData = getimagesize($path);
		// Creating blank canvas
		$imageBG = imagecreatetruecolor($baseWidth, $baseHeight);
		// If image is JPG or GIF
		if($imageData['mime'] == 'image/jpeg' || $imageData['mime'] == 'image/pjpeg' || $imageData['mime'] == 'image/jpg' || $imageData['mime'] == 'image/gif') 
		{
			// when bg is set to transparent - use black background
			if($bg == 'transparent')
			{
				$bgColorR = 0;
				$bgColorG = 0;
				$bgColorB = 0;				
			} 
			else // in other situation - translate hex to RGB
			{
				if(strlen($bg) == 4) $bg = $bg[0].$bg[1].$bg[1].$bg[2].$bg[2].$bg[3].$bg[3];
				$hex_color = strtolower(trim($bg,'#;&Hh'));
	  			$bg = array_map('hexdec',explode('.',wordwrap($hex_color, ceil(strlen($hex_color)/3),'.',1)));
				$bgColorR = $bg[0];
				$bgColorG = $bg[1];
				$bgColorB = $bg[2];
			}
			// Creating color
			$rgb = imagecolorallocate($imageBG, $bgColorR, $bgColorG, $bgColorB);
			// filling canvas with new color
			imagefill($imageBG, 0, 0, $rgb);	
		}
		else // for PNG images
		{	
			// enable transparent background 
			if($bg == 'transparent')
			{
				// create transparent color
				$rgb = imagecolorallocatealpha($imageBG, 0, 0, 0, 127);
			}
			else // create normal color
			{
				// translate hex to RGB
				$hex_color = strtolower(trim($bg,'#;&Hh'));
	  			$bg = array_map('hexdec',explode('.',wordwrap($hex_color, ceil(strlen($hex_color)/3),'.',1)));
				$bgColorR = $bg[0];
				$bgColorG = $bg[1];
				$bgColorB = $bg[2];				
				// creating color
				$rgb = imagecolorallocate($imageBG, $bgColorR, $bgColorG, $bgColorB);
			}
			// filling the canvas
			imagefill($imageBG, 0, 0, $rgb);
			// enabling transparent settings for better quality
			imagealphablending($imageBG, false);
			imagesavealpha($imageBG, true);
		}
		
		// loading image depends from type of image		
		if($imageData['mime'] == 'image/jpeg' || $imageData['mime'] == 'image/pjpeg' || $imageData['mime'] == 'image/jpg') $imageSource = @imagecreatefromjpeg($path);
		elseif($imageData['mime'] == 'image/gif') $imageSource = @imagecreatefromgif($path);
		else $imageSource = @imagecreatefrompng($path); 
		// here can be exist an error when image is to big - then class return blank page	
	
		// setting image size in variables
		$imageSourceWidth = imagesx($imageSource);
		$imageSourceHeight = imagesy($imageSource);
		
		// when stretching is disabled		
		if($stretch == 2){
			// calculate ratio for first scaling
			$ratio = ($imageSourceWidth > $imageSourceHeight) ? $baseWidth/$imageSourceWidth : $baseHeight/$imageSourceHeight;
			// calculate new image size
			$imageSourceNWidth = $imageSourceWidth * $ratio;
			$imageSourceNHeight = $imageSourceHeight * $ratio;

			$base_x = 0;
			$base_y = 0;
		}else if($stretch == 0){
			// calculate ratio for first scaling
			$ratio = ($imageSourceWidth > $imageSourceHeight) ? $baseWidth/$imageSourceWidth : $baseHeight/$imageSourceHeight;
			// calculate new image size
			$imageSourceNWidth = $imageSourceWidth * $ratio;
			$imageSourceNHeight = $imageSourceHeight * $ratio;
			// calculate ratio for second scaling
			if($baseWidth > $baseHeight){					
				if($imageSourceNHeight > $baseHeight){
					$ratio2 = $baseHeight / $imageSourceNHeight;
					$imageSourceNHeight *= $ratio2;
					$imageSourceNWidth *= $ratio2;
				}
			}else{
				if($imageSourceNWidth > $baseWidth){
					$ratio2 = $baseWidth / $imageSourceNWidth;
					$imageSourceNHeight *= $ratio2;
					$imageSourceNWidth *= $ratio2;
				}
			}
			// setting position of putting thumbnail on canvas
			$base_x = floor(($baseWidth - $imageSourceNWidth) / 2);
			$base_y = floor(($baseHeight - $imageSourceNHeight) / 2);
		}
		else{ // when stretching is enabled
			$imageSourceNWidth = $baseWidth;
			$imageSourceNHeight = $baseHeight;
			$base_x = 0;
			$base_y = 0;
		}
		
		// copy image	
		imagecopyresampled($imageBG, $imageSource, $base_x, $base_y, 0, 0, $imageSourceNWidth, $imageSourceNHeight, $imageSourceWidth, $imageSourceHeight);
		// save image depends from MIME type	
		if($imageData['mime'] == 'image/jpeg' || $imageData['mime'] == 'image/pjpeg' || $imageData['mime'] == 'image/jpg') imagejpeg($imageBG,$pathB.DS.'thumb'.$size.DS.$name, $Quality);
		elseif($imageData['mime'] == 'image/gif') imagegif($imageBG, $pathB.'thumb'.$size.DS.$name); 
		else imagepng($imageBG, $pathB.'thumb'.$size.DS.$name/*, $Quality*/);
		// return final value 
		return ($stretch) ? 1 : 0;
	}
}

?>