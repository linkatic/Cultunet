<?php
/**
 * @copyright (C) 2009 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
include_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'storage' . DS . 's3_lib.php');

class File_CStorage
{
	
	function _init(){
	}
	
	/**
	 * Check if the given storage id exist. We perform local check via db since
	 * checking remotely is time consuming
	 * 
	 * @return true is file exits	 	 	 
	 **/	 	
	function exists($storageid, $checkRemote = false)
	{
		return JFile::exists(JPATH_ROOT.DS.$storageid);
	}	
	
	/**
	 * Put the file into remote storage, 
	 * @return true if successful
	 */	
	function put($storageid, $file)
	{
		$storageid = JPATH_ROOT.DS.$storageid;
		JFile::copy($file, $storageid);
		return true;
	
	
	}
	
	/**
	 * Retrive the file from remote location and store it locally
	 * @param storageid The unique file we want to retrive
	 * @param file String	filename where we want to save the file	 	 
	 */	 	
	function get($storageid, $file)
	{
		$storageid = JPATH_ROOT.DS.$storageid;
		JFile::copy($storageid, $file);
		return true;
	}

	/**
	 * Return the absolute URI path to the resource  
	 */	 	
	function getURI($storageId)
	{
		return JURI::root().'/'.$storageId; 
	}
	
	/**
	 * Remove the given file
	 */	 	
	function delete($storageid)
	{
		$storageid = JPATH_ROOT.DS.$storageid;
		JFile::delete($storageid);
		return true;
	}
}

