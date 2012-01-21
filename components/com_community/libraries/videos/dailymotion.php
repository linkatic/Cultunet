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
 * Class to manipulate data from Daily Motion
 * 	 	
 * @access	public  	 
 */
class CTableVideoDailymotion extends CVideoProvider
{
	var $xmlContent = null;
	var $url = '';
	
	/**
	 * Return feedUrl of the video
	 */
	function getFeedUrl()
	{
		return 'http://www.dailymotion.com/video/'.$this->getId();
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
		// Connect and get the remote video
		CFactory::load('helpers', 'remote');
		$this->xmlContent = CRemoteHelper::getContent($this->getFeedUrl());
		$videoId = $this->getId();
		if (empty($videoId))
		{
			$this->setError( JText::_('CC INVALID VIDEO ID') );
			return false;
		}
		if($this->xmlContent == FALSE)
		{
			$this->setError( JText::_('CC ERROR FETCHING VIDEO') );
			return false;
		}
		
		return true;
	}	
	
	/**
	 * Extract DailyMotion video id from the video url submitted by the user
	 * 	 	
	 * @access	public
	 * @param	video url
	 * @return videoid	 
	 */
	function getId()
	{
        $pattern    = '/dailymotion.com\/?(.*)\/video\/(.*)/';
        preg_match( $pattern, $this->url, $match);

        return !empty($match[2]) ? $match[2] : null;
	}
	                                 
	
	/**
	 * Return the video provider's name
	 * 
	 */
	function getType()
	{
		return 'dailymotion';
	}
	
	function getTitle()
	{
		$title = '';		
		// Store video title
		$pattern =  "/<h1 class=\"dmco_title\">(.*)(<\/h1>)?(<\/span>)/i";
		preg_match_all($pattern, $this->xmlContent, $matches);

		if( $matches && !empty($matches[1][0]) )
		{
			$title = strip_tags($matches[1][0]);
		}
		
		return $title;
	}
	
	function getDescription()
	{
		$description = '';
		// Store description
		$pattern =  "/<meta name=\"description\" lang=\"en\" content=\"(.*)\" \/>/i";
		preg_match_all($pattern, $this->xmlContent, $matches);
		
		if( $matches && !empty($matches[1][0]) )
		{
			$description = trim(strip_tags($matches[1][0],'<br /><br>'));
		}
		
		return $description;
	}
	
	function getDuration()
	{
		$duration = '';
		// Store duration
		$pattern =  "'DMDURATION=(.*?)&'s";			 
		preg_match_all($pattern, $this->xmlContent, $matches);
	
		if( $matches && !empty($matches[1][0]) )
		{
            $duration   = $matches[1][0];
		}
			
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
		$thumbnail = '';
		
		// Get thumbnail
		$pattern =  "'addVariable\(\"preview\", \"http://ak2.static.dailymotion.com/static/video/(.*?):jpeg_preview_large's";
		preg_match_all($pattern, $this->xmlContent, $matches);
	
		if( $matches && !empty($matches[1][0]) )
		{					
			$thumbnail = 'http://ak2.static.dailymotion.com/static/video/'.urldecode($matches[1][0]).':jpeg_preview_medium.jpg';			
		}
		else
		{
			$thumbnail = 'http://static-00.dailymotion.com/dyn/preview/160x120/0.jpg';
		}

		
		return $thumbnail;
	}
	
	/**
	 * 
	 * 
	 * @return $embedvideo specific embeded code to play the video
	 */
	function getViewHTML($videoId, $videoWidth, $videoHeight)
	{
		if (!$videoId)
		{
			$videoId	= $this->videoId;
		}
		$embedCode = "<embed src=\"http://www.dailymotion.com/swf/".$videoId."\" width=\"".$videoWidth."\" height=\"".$videoHeight."\" wmode=\"transparent\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" allowFullScreen=\"true\"> </embed>";
		
		return $embedCode;
	}
	
	public function getEmbedCode($videoId, $videoWidth, $videoHeight)
	{
		return $this->getViewHTML($videoId, $videoWidth, $videoHeight);
	}
}
