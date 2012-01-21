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
 * Class to manipulate data from Break
 * 	 	
 * @access	public  	 
 */
class CTableVideoBreak extends CVideoProvider
{
	var $xmlContent = null;
	var $url = '';
	
	/**
	 * Return feedUrl of the video
	 */
	function getFeedUrl()
	{
		return 'http://www.break.com/' . $this->getId();
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
		$this->xmlContent	= CRemoteHelper::getContent($this->getFeedUrl());
		$videoId = $this->getId();
		if (empty($videoId))
		{
			$this->setError	( JText::_('CC INVALID VIDEO ID') );
			return false;
		}
		if($this->xmlContent == FALSE)
		{
			$this->setError	( JText::_('CC ERROR FETCHING VIDEO') );
			return false;
		}
		
		// Return Break error
		$pattern =  "'<span id=\"sitemap404msg\">(.*?)<\/span>'s";
		preg_match_all($pattern, $this->xmlContent, $matches);

		if( !empty($matches[1][0]) )
		{
			$errormsg = 'CC ERROR FETCHING VIDEO ' . strip_tags( $matches[1][0] );
			$this->setError	( JText::_($errormsg) );
			return false;
		}
		
		return true;			
	}	
	
	/**
	 * Extract Break video id from the video url submitted by the user
	 * 	 	
	 * @access	public
	 * @param	video url
	 * @return videoid	 
	 */
	function getId()
	{
	   $pattern    = '/http\:\/\/(\w{3}\.)?break.com\/(index\/.*?\.html)/';
	   preg_match( $pattern, $this->url, $match );
	   
       return !empty( $match[2] ) ? $match[2] : null;
	}
	
	
	/**
	 * Return the video provider's name
	 * 
	 */
	function getType()
	{
		return 'break';
	}
	
	function getTitle()
	{
		$title = '';		
		// Store video title
		$pattern =  "'<meta name=\"embed_video_title\" id=\"vid_title\" content=\"(.*?)\"( \/)?(>)'s";
		preg_match_all($pattern, $this->xmlContent, $matches);
		if($matches)
		{
			$title = $matches[1][0];
		}
		
		return $title;
	}
	
	function getDescription()
	{
		$description = '';
		// Store description
		$pattern =  "'<meta name=\"embed_video_description\" id=\"vid_desc\" content=\"(.*?)\"( \/)?(>)'s";
		preg_match_all($pattern, $this->xmlContent, $matches);
		if($matches)
		{
			$description = $matches[1][0];
		}
		
		return $description;
	}
	
	function getDuration()
	{			
		return 0;
	}
	
	/**
	 * Get video's thumbnail
	 * 
	 * @access 	public
	 * @param 	videoid
	 * @return url
	 */
	function getThumbnail()
	{
		$thumbnail = '';
		// Store thumbnail
		$pattern =  "'<meta name=\"embed_video_thumb_url\" content=\"(.*?)\"( \/)?(>)'s";
		preg_match_all($pattern, $this->xmlContent, $matches);
		if($matches)
		{
			$thumbnail = $matches[1][0];
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
		CFactory::load('helpers', 'remote');
		$remoteFile	= 'http://www.break.com/'.$videoId;
		$xmlContent = CRemoteHelper::getContent($remoteFile);
 		
		$pattern =  "'<meta name=\"embed_video_url\" content=\"(.*?)\"( /)?(>)'s";
		preg_match_all($pattern, $xmlContent, $matches);
		if($matches)
		{
				$videoUrl = $matches[1][0];
		}
		
		return "<embed src=\"".$videoUrl."\" width=\"".$videoWidth."\" height=\"".$videoHeight."\" wmode=\"transparent\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" allowFullScreen=\"true\"> </embed>";
	}
	
	public function getEmbedCode($videoId, $videoWidth, $videoHeight)
	{
		return $this->getViewHTML($videoId, $videoWidth, $videoHeight);
	}
}
