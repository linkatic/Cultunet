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
 * LyftenBloggie Framework Ping class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggiePing extends JObject
{
	/**
	 * Method to Send TrackBacks
	 **/
	function doTrackback(&$trackbacks, $row, $archive, $slug)
	{
		global $mainframe;
		
		$db 	= & JFactory::getDBO(); 
		$ermsg	= array();
		
		//Load trackback functions
		BloggieFactory::import('trackback', 'libraries');
		
		$trackbacks = explode('/\s/', $trackbacks);

		//Get old trackbacks
		$db->setQuery("SELECT pinged from #__bloggies_entries WHERE id='$row->id'");
		$pinged = $db->loadResult();
		$pinged = trim($pinged);
		$pinged = preg_split('/\s/', $pinged);

		if($mainframe->isAdmin()) {
			jimport( 'joomla.application.router' );
			require_once( JPATH_SITE.DS.'includes'.DS.'application.php');
		}
				
		// Send all new trackbacks
		foreach ($trackbacks as $trackback_url) {
			$trackback_url = trim($trackback_url);

			// do not send trackback twice
			if ( !in_array($trackback_url, $pinged) ) {
				$trackback 			= new Trackback($mainframe->getCfg('sitename'), $row->title, 'UTF-8');
				$trackback_url 		= trim(strip_tags($trackback_url));
				
				//Get SEF URL
				$content_url		= LyftenBloggieHelperRoute::getEntryRoute($archive, $slug);
				$content_url 		= BloggieFactory::getSEFLink($content_url);
		
				$content_title 		= $trackback->cut_short($row->title);
				$content_excerpt 	= $trackback->cut_short($row->fulltext);

				// Send trackback ping
				if ($trackback->ping($trackback_url, $content_url, $content_title, $content_excerpt)) {
					$pinged[] 		= $trackback_url;
					}else{
					$ermsg[] = $trackback_url;
				}
			} 
		}			

		if(!empty($ermsg)) {
			$ermsg = implode("\n", $ermsg);
			$mainframe->enqueueMessage(JText::_('UNABLE TO PING').' '.$ermsg);
		}			
		
		//Add all new trackback to database
		$pinged 		= implode("\n", $pinged);
		$pinged 		= stripslashes($pinged);
			
		return $pinged;
	}

	/**
	 * Sends pings to all of the ping site services.
	 **/
	function pingUpdate($services)
	{
		global $mainframe;
		
		$services 	= explode("\n", $services);
		$url 		= $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
		$sitename	= $mainframe->getCfg('sitename');
		$ermsg		= '';
		
		foreach ( (array) $services as $service ) {
			if($ermsg) $ermsg .= '<br>';
			$service = trim($service);
			if ( '' != $service )
				$ermsg .= BloggiePing::_sendPing($service, $url, $sitename);
		}
		
		if($ermsg) {
			$mainframe->enqueueMessage(JText::_('UNABLE TO PING').' '.$ermsg);
		}	
	}

	/**
	 * Send a pingback.
	 */
	function _sendPing($server = '', $url, $sitename)
	{
		//Load IXR Server
		BloggieFactory::import('ixr', 'libraries');

		if (!@ini_get('safe_mode')) set_time_limit(180);
		
		$rssURL = JRoute::_($url.'index.php?option=com_lyftenbloggie&format=feed&type=rss');
		
		// using a timeout of 3 seconds should be enough to cover slow servers
		$client = new IXR_Client($server, false);
		$client->timeout = 3;
		$client->useragent .= ' -- LyftenBloggie/'.BLOGGIE_COM_VERSION;

		// when set to true, this outputs debug messages by itself
		$client->debug = false;
		if ( !$client->query('weblogUpdates.extendedPing', $sitename, $url, $rssURL) )
		{
			// if there was an error then try a normal ping
			if ( !$client->query('weblogUpdates.ping', $sitename, $url) ) {
				return $client->getErrorMessage();
			}
		}
		return;
	}
}
?>