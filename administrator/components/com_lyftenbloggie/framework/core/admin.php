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
 * LyftenBloggie Framework Admin class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieAdmin
{
	function getUpdateData($type=null)
	{
		// Initialize variables
		$updates 		= BloggieFactory::load('updates');
		$lyftenupdate 	= array();

		//get updates data
		$items = $updates->load('http://api.lyften.com/', $type, 86400);

		if(isset($items['lyftenupdate']))
		{
			$lyftenupdate = $items['lyftenupdate'];

			//Updates
			if(isset($lyftenupdate['update']['upgrade']))
			{
				$lyftenupdate['update']['upgrade'] = base64_encode($lyftenupdate['update']['upgrade']);
			}
			if(isset($lyftenupdate['update']['homepage']))
			{
				$lyftenupdate['update']['homepage'] = base64_encode($lyftenupdate['update']['homepage']);
			}
			if(!isset($lyftenupdate['update']['auto_upgrade']))
			{
				$lyftenupdate['update']['auto_upgrade'] = 0;
			}

			//Patches
			if(!isset($lyftenupdate['patch']['total']))
			{
				$lyftenupdate['patch']['total'] = 0;
			}

			if(isset($lyftenupdate['patch']['regular']) && $lyftenupdate['patch']['regular'] != 0)
			{
				$patches 	= explode(',', $lyftenupdate['patch']['regular']);
				$patches 	= BloggieAdmin::_checkPatches($patches);
				$lyftenupdate['patch']['regular'] = count($patches);
			}else{
				$lyftenupdate['patch']['regular'] = 0;
			}

			if(isset($lyftenupdate['patch']['critical']) && $lyftenupdate['patch']['critical'] != 0)
			{
				$critical 	= explode(',', $lyftenupdate['patch']['critical']);
				$critical 	= BloggieAdmin::_checkPatches($critical);
				$lyftenupdate['patch']['critical'] = count($critical);
			}else{
				$lyftenupdate['patch']['critical'] = 0;
			}
			$lyftenupdate['patch']['total'] = $lyftenupdate['patch']['critical'] + $lyftenupdate['patch']['regular'];
		}else{
			$lyftenupdate = array();
		}
		return $lyftenupdate;
	}

	function _checkPatches($available)
	{
		$settings	=& BloggieSettings::getInstance();
		$installed	= explode(',', $settings->get('patches'));

		if(is_array($installed))
		{
			foreach($installed AS $patch)
			{
				if(in_array($patch, $available))
				{
					foreach($available AS $id => $value)
					{
						if($value == $patch) unset($available[$id]);
					}
				}
			}
		}
		return $available;
	}

	function getRemoteData($action='plugins', $args)
	{
		$return 	= array();
		$siteFetch 	= new BloggieHttp();

		//Query the Server
		if($request = $siteFetch->post('http://api.lyften.com/', array( 'body' => array('task' => $action, 'request' => serialize($args)))))
		{
			$feed = BloggieFactory::getFeed();
			if($feed->loadXML($request['body']))
			{
				$return = $feed->getValues();
			}
			unset($feed);
			unset($request);
		}

		return $return;
	}
}