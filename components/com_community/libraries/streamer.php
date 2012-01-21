<?php
/**
 * @category	Libraries
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
 
define( 'DS', DIRECTORY_SEPARATOR );
define( 'JPATH_BASE', dirname(dirname(dirname(dirname(__FILE__)))) );
$parts = explode( DS, JPATH_BASE );
define( 'JPATH_ROOT', implode( DS, $parts ) );
define( 'JPATH_SITE', JPATH_ROOT );

require_once( JPATH_BASE.DS.'libraries'.DS.'loader.php' );
require_once( JPATH_BASE.DS.'libraries'.DS.'joomla'.DS.'factory.php'  );
require_once( JPATH_BASE.DS.'libraries'.DS.'joomla'.DS.'base'.DS.'object.php' );
require_once( JPATH_BASE.DS.'libraries'.DS.'joomla'.DS.'environment'.DS.'request.php' );
require_once( JPATH_BASE.DS.'libraries'.DS.'joomla'.DS.'environment'.DS.'response.php' );
require_once( JPATH_BASE.DS.'libraries'.DS.'joomla'.DS.'environment'.DS.'uri.php' );
require_once( JPATH_BASE.DS.'libraries'.DS.'joomla'.DS.'filter'.DS.'filterinput.php' );
require_once( JPATH_BASE.DS.'libraries'.DS.'joomla'.DS.'filesystem'.DS.'file.php' );

$pos	= JRequest::getVar('target', 0);
$file	= JURI::getInstance()->toString();
$file	= str_replace( JURI::root() , '', $file);
$file	= JPATH::clean(JPATH_BASE.DS.base64_decode($file));
$fileName	= JFile::getName($file);

if(!JFile::exists($file))
{
	echo 'file not found: ' . $file;
	exit;
}

$fh		= fopen($file, 'rb') or die ('cannot open file: ' . $file);
$fileSize = filesize($file) - (($pos > 0) ? $pos  + 1 : 0);
fseek($fh, $pos);

$binary_header	= strtoupper(JFile::getExt($file)).pack('C', 1).pack('C', 1).pack('N', 9).pack('N', 9);

session_cache_limiter('none');
JResponse::clearHeaders();
JResponse::setHeader( 'Expires', 'Mon, 26 Jul 1997 05:00:00 GMT', true );
JResponse::setHeader( 'Last-Modified', gmdate("D, d M Y H:i:s") . ' GMT', true );
JResponse::setHeader( 'Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0', true );
JResponse::setHeader( 'Pragma', 'no-cache', true );
JResponse::setHeader( 'Content-Disposition', 'attachment; filename="'.$fileName.'"', true);
JResponse::setHeader( 'Content-Length', ($pos > 0) ? $fileSize + 13 : $fileSize, true );
JResponse::setHeader( 'Content-Type', 'video/x-flv', true );
JResponse::sendHeaders();

if($pos > 0) 
{
	print $binary_header;
}

$limit_bw		= true;
$packet_size	= 90 * 1024;
$packet_interval= 0.3;

while(!feof($fh)) 
{
	if(!$limit_bw)
	{
		print(fread($fh, filesize($file)));
	}
	else
	{
		$time_start = microtime(true);
		print(fread($fh, $packet_size));
		$time_stop = microtime(true);
		$time_difference = $time_stop - $time_start;
		if($time_difference < $packet_interval)
		{
			usleep($packet_interval * 1000000 - $time_difference * 1000000);
		}
	}
}

exit;