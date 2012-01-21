<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/

 //initialize variables
$captchacode = '';
@$sid = ($_GET['sid'])?$_GET['sid']:'939117';

//Open Session
@session_id( $sid );
session_start();

//Get captcha code
@$captchaslist = (array) $_SESSION[ 'captchaslist' ];
if (count($captchaslist) > 1) {
	$captchacode = array_pop( $captchaslist );
}else{
	$captchacode = $captchaslist[0];
}

//Set Captcha code
@$_SESSION [ 'captcha' ] = $captchacode;
@$_SESSION [ 'captchaslist' ] = $captchaslist;

//Close Session
session_write_close();

// image only >>>>>>>
ob_start();
header( 'Expires: Thu, 01 Jan 1980 00:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
header("Content-type: image/png"); 
$img_handle = @ImageCreate(65, 20); 
$back_color = @ImageColorAllocate($img_handle, 255, 255, 255);
$transparent_bg = @ImageColorTransparent($img_handle, $back_color);
  
for($i = 0; $i < strlen( $captchacode ); ++$i)
{
	$x_axis = 5 + ($i * 10);
	$y_axis = rand(0, 7);
	$color1 = rand(001, 150);
	$color2 = rand(001, 150);
	$color3 = rand(001, 150);
	$txt_color[$i] = @ImageColorAllocate($img_handle, $color1, $color2, $color3); 
	$size = rand(3,5);
	@ImageString($img_handle, $size, $x_axis, $y_axis, $captchacode{$i}, $txt_color[$i]); 
}

$pixel_color = @ImageColorAllocate($img_handle, 100, 100, 100); 

@ImagePng($img_handle); 
ImageDEstroy( $img_handle);
ob_end_flush();