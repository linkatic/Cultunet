<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

/**
 * False JSite class used to fool the frontend search plugins because they route the results
 */
class JSite extends JObject
{
	/**
	 * False method to fool the frontend search plugins
	 */
	function getMenu()
	{
		$result = new JSite;
		return $result;
	}

	/**
	 * False method to fool the frontend search plugins
	 */
	function getItems()
	{
		return array();
	}
}