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
 * Class to manipulate data from Truveo
 * 	 	
 * @access	public  	 
 */
class CTableVideoTruveo extends CVideoProvider
{
	var $xmlContent = null;
	var $url 		= '';
	var $videoId	= '';
	
	/**
	 * Return feedUrl of the video
	 */
	function getFeedUrl()
	{
		$videoId = explode("/",$this->videoId);
		return 'http://xml.truveo.com/apiv3?appid=1x1jhj64466mi12ia&method=truveo.videos.getVideos&query='.$videoId[2];
	}
	
	function init($url)
	{
		$this->url 		= $url;
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
		
		$xmlContent = CRemoteHelper::getContent($this->getFeedUrl());
		
		if (empty($this->videoId))
		{
			$this->setError	( JText::_('CC INVALID VIDEO ID') );
			return false;
		}
		if($xmlContent == false)
		{
			$this->setError	( JText::_('CC ERROR FETCHING VIDEO') );
			return false;
		}		
		
		$parser = JFactory::getXMLParser('Simple');
		$parser->loadString($xmlContent);
		$videoElement = $parser->document;
		
		// Get Video Title
		$element = $videoElement->getElementByPath('videoset/video/title');			
		$this->title = $element->data();
		// Get Video description
		$element = $videoElement->getElementByPath('videoset/video/description');
		$this->description = $element ? $element->data() : '';
		// Get Video duration
		if($videoElement->getElementByPath('videoset/video/runtime')==true){   
			$element = $videoElement->getElementByPath('videoset/video/runtime');
			$this->duration = $element->data();	
		}
		// Get Video thumbnail
		$element = $videoElement->getElementByPath('videoset/video/thumbnailurl');
		$this->thumbnail = $element->data();

		return true;
	}
	
	/**
	 * Extract video id from the video url submitted by the user
	 * 	 	
	 * @access	public
	 * @param	video url
	 * @return videoid	 
	 */
	function getId()
	{   
        $pattern    = '/http\:\/\/(\w{3}\.)?truveo.com\/(.*)/';
        preg_match( $pattern, $this->url, $match);

        return !empty($match[2]) ? $match[2] : null;
	}
	
	
	/**
	 * Return the video provider's name
	 * 
	 */
	function getType()
	{
		return 'truveo';
	}
	
	function getTitle()
	{
		return $this->title;
	}
	
	function getDescription()
	{
		return $this->description;
	}
	
	function getDuration()
	{
		return $this->duration;
	}
	
	function getThumbnail()
	{
		return $this->thumbnail;
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
		$videoId	= explode("/",$videoId);
		$xmlContent	= CRemoteHelper::getContent('http://xml.truveo.com/apiv3?appid=1x1jhj64466mi12ia&method=truveo.videos.getVideos&query='.$videoId[2]);
		$parser		= JFactory::getXMLParser('Simple');

		$parser->loadString($xmlContent);
		$videoElement = $parser->document;
		
		//get Video embed code
		$element	= $videoElement->getElementByPath('videoset/video/videoresultembedtag');	
		$embedTag	= $element->data();	

		$pattern	=  "'src=\"(.*?)\"'s";		 
		preg_match_all($pattern, $embedTag, $matches);
		if($matches)
		{
			$flashUrl = ' src="'.rawurldecode($matches[1][0]).'" ';
			$src = $matches[1][0];
		}
// 		$pattern	=  "'FlashVars=\'(.*?)\''s";
// 		$pattern	= '';
// 		preg_match_all($pattern, $embedTag, $matches);
// 		if(!empty($matches))
// 		{
// 			echo JUtility::dump($matches);
// 			echo count($matches);
// 			exit;
// 			$flashVar = ' FlashVars=\''.rawurldecode($matches[1][0]).'\' ';
// 		}
//		$embedCode	= "<embed ".$flashUrl.$flashVar." allowFullScreen='true' width='".$videoWidth."' height='".$videoHeight."' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' allowScriptAccess='always'></embed>";
		$embedCode	= "<embed flashvars='fs=1' allowfullscreen='true' src='$src' type='application/x-shockwave-flash' width='$videoWidth' height='$videoHeight' wmode='transparent'></embed>";
		return $embedCode;
	}
	
	public function getEmbedCode($videoId, $videoWidth, $videoHeight)
	{
		return $this->getViewHTML($videoId, $videoWidth, $videoHeight);
	}
}
