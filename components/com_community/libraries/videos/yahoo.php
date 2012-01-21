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
 * Class to manipulate data from Yahoo
 * 	 	
 * @access	public  	 
 */
class CTableVideoYahoo extends CVideoProvider
{
	var $xmlContent = null;
	var $url 		= '';
	var $videoId	= '';
	/**
	 * Return feedUrl of the video
	 */
	function getFeedUrl()
	{
		return 'http://video.yahoo.com/watch/'.$this->videoId;
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
	 * Extract Yahoo video id from the video url submitted by the user
	 * 	 	
	 * @access	public
	 * @param	video url
	 * @return videoid	 
	 */
	function getId()
	{
	
	    $videoId    = null;
	    
        $pattern    = '/http\:\/\/(\w{2}\.)?video.yahoo.com\/watch\/(\d{1,8})\/(\d{1,8})/';
        preg_match($pattern, $this->url, $match);
        
        if( !empty($match[2]) && !empty($match[3]) )
            $videoId    = $match[2] . '/' . $match[3];

        return $videoId;
	}
	
	/**
	 * Return the video provider's name
	 * 
	 */
	function getType()
	{
		return 'yahoo';
	}
	
	function getTitle()
	{
		$title	= '';
		
		// Get Title
		$videoId = explode("/",$this->videoId);
		$pattern =  "'<h1 id=\"vidTitle_".$videoId[0]."\" class=\"ti-3\">(.*)<\/h1>'s";
		preg_match_all($pattern, $this->xmlContent, $matches);
		if($matches)
		{
			$title = $matches[1][0];
		}
		
		return $title;
	}
	
	function getDescription()
	{
		$description	= '';
		
		// Get description
		$pattern =  "'<p id=\"desc_trunc\" class=\"description\">(.*?)<\/p>'s";
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
	
	/**
	 * Get video's thumbnail URL from videoid
	 * 
	 * @access 	public
	 * @param 	videoid
	 * @return url
	 */
	function getThumbnail()
	{
		$thumbnail	= '';
		
		// Connect and get the remote video
		CFactory::load('helpers', 'remote');
		$this->xmlContent = CRemoteHelper::getContent($this->getFeedUrl());
		
		// Get thumbnail
		$this->thumbnail	= '';
		$pattern =  "'addVariable\(\"thumbUrl\", \"(.*?)\"\);'s";
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
	function getViewHTML($videoId, $videoWidth, $videoHeight)
	{
    	$videoId = explode("/",$videoId);
		$embedCode = '<object width="'.$videoWidth.'" height="'.$videoHeight.'"><param name="movie" value="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=2.2.40" /><param name="allowFullScreen" value="true" /><param name="AllowScriptAccess" VALUE="always" /><param name="bgcolor" value="#000000" /><param name="flashVars" value="id='.$videoId[1].' &vid='.$videoId[0].'&lang=en-us&intl=us" /><embed src="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=2.2.40" type="application/x-shockwave-flash" width="'.$videoWidth.'" height="'.$videoHeight.'" allowFullScreen="true" AllowScriptAccess="always" bgcolor="#000000" flashVars="id='.$videoId[1].' &vid='.$videoId[0].'&lang=en-us&intl=us&embed=1" wmode="transparent" ></embed></object>';		
		return $embedCode;
	}
	
	public function getEmbedCode($videoId, $videoWidth, $videoHeight)
	{
		return $this->getViewHTML($videoId, $videoWidth, $videoHeight);
	}
}
