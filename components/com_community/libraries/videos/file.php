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
class CTableVideoFile extends CVideoProvider
{
	var $xmlContent	= null;
	var $url		= '';
	
	/**
	 * Return feedUrl of the video
	 */
	function getFeedUrl()
	{
		return true;
	}
	
	function init($url)
	{
		$this->url			= $url;
	}
	
	/*
	 * Return true if successfully connect to remote video provider
	 * and the video is valid
	 */	 
	function isValid()
	{
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
		//check for embed code format
		//$pos_e = strpos($this->url, "youtube.com/v/");
		$pos_u = strpos($this->url, "video/");

		////TODO: User regular expression instead
		if ($pos_u === false) {
			return null;
		} else if ($pos_u) {
			$pos_u_start = $pos_u + 6;

			$code = substr($this->url, $pos_u_start);
			$code = strip_tags($code );
			$code = preg_replace("/[^a-zA-Z0-9s_-]/", "", $code);
		}
						
		return $code;
	}
	
	
	/**
	 * Return the video provider's name
	 * 
	 */
	function getType()
	{
		return 'file';
	}
	
	function getTitle()
	{		
		return true;
	}
	
	function getDescription()
	{
		return true;
	}
	
	function getDuration()
	{			
		return true;
	}
	
	function getThumbnail()
	{
		return true;
	}
	
	/**
	 * 
	 * 
	 * @return $embedvideo specific embeded code to play the video
	 */
	public function getViewHTML($videoId, $videoWidth, $videoHeight)
	{
		$video	=& JTable::getInstance( 'Video' , 'CTable' );
		$video->load( $videoId );
		
		$tmpl	= new CTemplate();
		$tmpl->setRef( 	'video'		, $video );
		$tmpl->set( 	'switch'	, 'default' );
		
		return $tmpl->fetch( 'videos.flowplayer' );
	}
	
	public function getEmbedCode($videoId, $videoWidth, $videoHeight)
	{
		$video	=& JTable::getInstance( 'Video' , 'CTable' );
		$video->load( $videoId );
		
		$tmpl	= new CTemplate();
		$tmpl->setRef( 	'video'		, $video );
		$tmpl->set( 	'switch'	, 'pureObjectTag' );
		
		return $tmpl->fetch( 'videos.flowplayer' );
	}
}