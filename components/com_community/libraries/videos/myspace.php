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
 * Class to manipulate data from MySpace
 * 	 	
 * @access	public  	 
 */
class CTableVideoMyspace extends CVideoProvider
{
	var $xmlContent = null;
	var $url		= '';
	var $videoId	= '';
	/**
	 * Return feedUrl of the video
	 */
	function getFeedUrl()
	{
		return 'http://vids.myspace.com/index.cfm?fuseaction=vids.individual&videoid='.$this->videoId;
	}
	
	function init($url)
	{
		$this->url = $url;
		$this->videoId 	= $this->getId();
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
	
		if (empty($this->videoId))
		{
			$this->setError	( JText::_('CC INVALID VIDEO ID') );
			return false;
		}
		if($this->xmlContent == false)
		{
			$this->setError	( JText::_('CC ERROR FETCHING VIDEO') );
			return false;
		}

		return true;
	}	
	
	/**
	 * Extract MySpace video id from the video url submitted by the user
	 * 	 	
	 * @access	public
	 * @param	video url
	 * @return videoid	 
	 */
	function getId()
	{			
        $pattern    = '/videoid=(\d{9})/';
        preg_match( $pattern, $this->url, $match);

        return !empty($match[1]) ? $match[1] : null;
	}
	
	
	/**
	 * Return the video provider's name
	 * 
	 */
	function getType()
	{
		return 'myspace';
	}
	
	function getTitle()
	{
		$title		= '';	

		// Get title
		$pattern	= "'<h1 id=\"tv_tbar_title\">(.*?)<\/h1>'s";
		preg_match_all($pattern, $this->xmlContent, $matches);
		if($matches)
		{
			$title	= $matches[1][0];
		}

		return $title;
	}
	
	function getDescription()
	{
		$description	= '';
		
		// Get description
		$pattern		= 	"'<b id=\"tv_vid_vd_truncdesc_text\">(.*?)<\/b>'s";
		preg_match_all($pattern, $this->xmlContent, $matches);
		if($matches)
		{
			$description = trim(strip_tags($matches[1][0]));
		}
		
		return $description;
	}
	
	function getDuration()
	{
		return false;
	}

	function getThumbnail()
	{
		$thumbnail	= '';
		
		// Get thumbnail
		$pattern	= "'<link rel=\"image_src\" href=\"(.*?)\" \/>'s";
		preg_match_all($pattern, $this->xmlContent, $matches);
		if($matches)
		{
			$thumbnail = rawurldecode($matches[1][0]);
		}
	
		return $thumbnail;
	}
	
	/**
	 * 
	 * 
	 * @return $embedvideo specific embeded code to play the video
	 */
	function getViewHTML($videoId, $videoWidth , $videoHeight)
	{
		if (!$videoId)
		{
			$videoId	= $this->videoId;
		}
		if(strpos($videoId, "&") == true)
		{
			$videoId_tmp = substr($videoId, strpos($videoId, "&"));
			$videoId     = JString::str_ireplace($videoId_tmp,"",$videoId);
		}
		
		$embedCode   = '<object width="'.$videoWidth.'px" height="'.$videoHeight.'px" ><param name="allowFullScreen" value="true"/><param name="wmode" value="transparent"/><param name="movie" value="http://mediaservices.myspace.com/services/media/embed.aspx/m='.$videoId.',t=1,mt=video"/><embed src="http://mediaservices.myspace.com/services/media/embed.aspx/m='.$videoId.',t=1,mt=video" width="'.$videoWidth.'" height="'.$videoHeight.'" allowFullScreen="true" type="application/x-shockwave-flash" wmode="transparent"/></object>';

		return $embedCode;
	}
	
	public function getEmbedCode($videoId, $videoWidth, $videoHeight)
	{
		return $this->getViewHTML($videoId, $videoWidth, $videoHeight);
	}
}
