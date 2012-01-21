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
 * Class to manipulate data from Viddler
 * 	 	
 * @access	public  	 
 */
class CTableVideoViddler extends CVideoProvider
{
	var $xmlContent = null;
	var $url 		= '';
	var $videoId	= '';
	/**
	 * Return feedUrl of the video
	 */
	function getFeedUrl()
	{
		return 'http://www.viddler.com/explore/' .$this->videoId;
	}
	
	function init($url)
	{
		$this->url 		= $url;
		$this->videoId 	= $this->getId();
	}
	
	/**
	 * 
	 * 
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
	 * Extract Viddler video id from the video url submitted by the user
	 * 	 	
	 * @access	public
	 * @param	video url
	 * @return videoid	 
	 */
	function getId()
	{			
        $pattern    = '/http\:\/\/(\w{3}\.)?viddler.com\/explore\/(.*)?(\/)/';
        preg_match($pattern, $this->url, $match);
      
        return !empty($match[2]) ? $match[2] : null;
	}
	
	
	/**
	 * Return the video provider's name
	 * 
	 */
	function getType()
	{
		return 'viddler';
	}
	
	function getTitle()
	{		
		$title	= '';
		
		// Get title
		$pattern =  "'<div class=\"pagetitles\">(.*?)<\/div>'s";
		preg_match_all($pattern, $this->xmlContent, $matches);
		if($matches)
		{
			$pattern =  "'rel=\"bookmark\">(.*?)<\/a>'s";
			preg_match_all($pattern, $matches[1][0], $matches);
			$title = trim($matches[1][0]);
		}

		return $title;
	}
	
	function getDescription()
	{		
		$description	= '';

		// Get description
		$pattern =  "'<div id=\"smDesShown\">(.*?)<\/div>'s";
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
		$pattern =  "'<link rel=\"image_src\"(.*?)\/>'s";
		preg_match_all($pattern, $this->xmlContent, $matches);
		if($matches)
		{
			$pattern =  "'href=\"(.*?)\"'s";
			preg_match_all($pattern, $matches[1][0], $matches);
			$thumbnail = rawurldecode($matches[1][0]);
		}
		if (!$thumbnail)
		{
			$pattern =  "'<link rel=\"image_src\"(.*?)\/>'s";
			preg_match_all($pattern, $this->xmlContent, $matches);
			if($matches)
			{
				$pattern =  "'href=\"(.*?)\"'s";
				preg_match_all($pattern, $matches[1][0], $matches);
				$thumbnail = rawurldecode($matches[1][0]);
			}
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
		$this->videoId	= $videoId;
		CFactory::load('helpers', 'remote');
		$xmlContent = CRemoteHelper::getContent($this->getFeedUrl());
		
		$pattern =  "'<link rel=\"video_src\"(.*?)\/>'s";		 
		preg_match_all($pattern, $xmlContent, $matches);
		if($matches)
		{
			$pattern =  "'href=\"(.*?)\"'s";
			preg_match_all($pattern, $matches[1][0], $matches);
			$videoUrl= rawurldecode($matches[1][0]); 
		}

		$embedCode = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$videoWidth.'" height="'.$videoHeight.'" id="viddler"><param name="movie" value="'.$videoUrl.'" /><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="true" /><param name="wmode" value="transparent"/><embed src="'.$videoUrl.'" width="'.$videoWidth.'" height="'.$videoHeight.'" type="application/x-shockwave-flash" allowScriptAccess="always" allowFullScreen="true" name="viddler" wmode="transparent"></embed></object>';
				
		return $embedCode;
	}
	
	public function getEmbedCode($videoId, $videoWidth, $videoHeight)
	{
		return $this->getViewHTML($videoId, $videoWidth, $videoHeight);
	}

}
