<?php
/**
 * @category	Helper
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once (COMMUNITY_COM_PATH.DS.'models'.DS.'videos.php');

/**
 * Class to manipulate data from YouTube
 * 	 	
 * @access	public 
 */
class CTableVideoInvalid extends CVideoProvider
{	
	var $xmlContent = null;
	var $url = '';
	
	/**
	 * Return feedUrl of the video
	 */
	function getFeedUrl()
	{
		return 'http://gdata.youtube.com/feeds/api/videos/' . $this->getId();
	}
	
	function init($url)
	{
		$this->url = $url;
	}
	
	/*
	 * Return true if successfully connect to remote video provider
	 * and the video is valid
	 */	 	 
	function isValid()
	{
		$this->setError(JText::_('CC VIDEO PROVIDER NOT SUPPORTED'));
		return false;
	}
	
	/**
	 * Extract YouTube video id from the video url submitted by the user
	 * 	 	
	 * @access	public
	 * @param	video url
	 * @return videoid	 
	 */
	function getId()
	{
		return '';
	}
	
	/**
	 * Return the video provider's name
	 * 
	 */
	function getType()
	{
		return 'invalid';
	}
	
	function getTitle()
	{
		$title = '';
		return $title;
	}
	
	function getDescription()
	{
		$description = '';
		return $description;
	}
	
	function getDuration()
	{
		$duration = 0;
		return $duration;
	}
	
	/**
	 * Get video's thumbnail URL from videoid
	 * 
	 * @access 	public
	 * @param 	videoid
	 * @return url
	 */
	function getThumbnail()
	{
		return 'http://img.youtube.com/vi/' . $this->getId() . '/default.jpg';
	}
	
	/**
	 * 
	 * 
	 * @return $embedvideo specific embeded code to play the video
	 */
	function getViewHTML($videoId, $videoWidth, $videoHeight)
	{
		return "";
	}
	
	public function getEmbedCode($videoId, $videoWidth, $videoHeight)
	{
		return $this->getViewHTML($videoId, $videoWidth, $videoHeight);
	}

}
