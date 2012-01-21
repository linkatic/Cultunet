<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/

//Set Headers
if(!@ob_start ("ob_gzhandler")) ob_start();
header('Content-type: text/css; charset: UTF-8');
header('Cache-Control: must-revalidate');
header('Expires: '.gmdate('D, d M Y H:i:s', time() + (3600*60)).' GMT');

//Set Theme
$BlogTheme 	= (!empty($_GET['theme'])) 	? $_GET['theme'] 	: 'default';
$dash 		= (!empty($_GET['dash'])) 	? $_GET['dash'] 	: 0;
$comment	= (!empty($_GET['comment']))? $_GET['comment'] 	: 0;
$style		= (!empty($_GET['style']))	? $_GET['style'] 	: 0;

// Define Paths
define('DS', DIRECTORY_SEPARATOR);

// Initialize variables
$cssContent = null;
$cssFiles	= array();
$CSSPath 	= dirname(__FILE__).DS.$BlogTheme.DS.'css'.DS;
$SystemPath = dirname(__FILE__).DS.'system'.DS.'css'.DS;

if(!is_dir($CSSPath))
{
	$CSSPath = dirname(__FILE__).DS.'default'.DS.'css'.DS;
	$BlogTheme = 'default';
}

//Set Styles
$cssFiles[] = $CSSPath."|style.css";

//Check for Dashboard
if($dash)
{
	//Check for theme Override
	if( file_exists( $CSSPath.'dashboard.css' ) )
	{
		$cssFiles[] = $CSSPath."|dashboard.css";
	}else{
		$cssFiles[] = $SystemPath."|dashboard.css";
	}
}

//Check for Style Override
if($style)
{
	//Check for theme Override
	if( file_exists( $CSSPath.$style.'.css' ) )
	{
		$cssFiles[] = $CSSPath."|".$style.".css";
	}
}

//Check for Comment
if($comment)
{
	//Check for theme Override
	if( file_exists( $CSSPath.'comment.css' ) )
	{
		$cssFiles[] = $CSSPath."|comment.css";
	}else{
		$cssFiles[] = $SystemPath."|comment.css";
	}
}

// Set IE browser fixes
if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie 6') !== false)
{
	$cssFiles[] = $CSSPath."|ie6.css";
}else if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie 7') !== false){
	$cssFiles[] = $CSSPath."|ie7.css";
}else if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie 8') !== false){
	$cssFiles[] = $CSSPath."|ie8.css";
}

//Add Basic Layout Style
if( file_exists( $SystemPath.'layout.css' ) )
{
	if( filesize( $SystemPath.'layout.css' ) )
	{
		$handle = fopen( $SystemPath.'layout.css', "r" );
		$cssContent .= fread( $handle, filesize( $SystemPath.'layout.css' ) );
		fclose( $handle );
	}
}

//Load Theme Styles
foreach($cssFiles as $cssFile)
{
	//Check if Required
	$css = explode("|", $cssFile);
	if( file_exists( $css[0].$css[1] ) )
	{
		if( filesize( $css[0].$css[1] ) )
		{
			$handle = fopen( $css[0].$css[1], "r" );
			$cssContent .= fread( $handle, filesize( $css[0].$css[1] ) );
			fclose( $handle );
		}
	}
}
unset($assets);

// Remove comments
$cssContent = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $cssContent);

// Remove tabs, spaces, newlines etc.
$cssContent = str_replace(array("\r\n", "\r", "\n", "\t", '	', '		', '			'), '', $cssContent);

echo $cssContent;

@ob_end_flush();
?>