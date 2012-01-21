<?php
/**
 * @category	Helper
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CVideosHelper
{
	static public function validateVideo( $fileName )
	{
		jimport('joomla.filesystem.file');
		$fileExt	= JFile::getExt($fileName);
		
		$fileType	= array('flv', 'avi', 'mov', 'mp4'); // need expansion
		
		return in_array($fileExt, $fileType);
	}

	static public function formatDuration($duration = 0, $format = 'HH:MM:SS')
	{
		if ($format == 'seconds' || $format == 'sec') {
			$arg = explode(":", $duration);
	
			$hour	= isset($arg[0]) ? intval($arg[0]) : 0;
			$minute	= isset($arg[1]) ? intval($arg[1]) : 0;
			$second	= isset($arg[2]) ? intval($arg[2]) : 0;
	
			$sec = ($hour*3600) + ($minute*60) + ($second);
			return (int) $sec;
		}
	
		if ($format == 'HH:MM:SS' || $format == 'hms') {
			$timeUnits = array
			(
				'HH' => $duration / 3600 % 24,
				'MM' => $duration / 60 % 60,
				'SS' => $duration % 60
			);
	
			$arg = array();
			foreach ($timeUnits as $timeUnit => $value) {
				$arg[$timeUnit] = ($value > 0) ? $value : 0;
			}
	
			$hms = '%02s:%02s:%02s';
			$hms = sprintf($hms, $arg['HH'], $arg['MM'], $arg['SS']);
			return $hms;
		}
	}

	/**
	 *	Remove Extra Leading Zeroes
	 *	00:01:30 will became 01:30
	 *	
	 *	@params	string	$hms	HH:MM:SS value
	 *	@return	string	nice HMS
	 */ 
	static public function toNiceHMS($hms)
	{
		$arr	= array();
		$arr	= explode(':', $hms);
	
		if ($arr[0] == '00') {
			array_shift($arr);
		}
	
		return implode(':', $arr);
	}
	
	static public function getVideoLinkPatterns()
	{
		// Pattern for video providers
		$pattern	= array();
	
		$pattern[] = '/http\:\/\/vids.myspace.com\/index.cfm\?fuseaction\=([a-zA-Z0-9][a-zA-Z0-9$_.+!*(),;\/\?:@&]*)\=(\d{1,8})/';
		$pattern[] = '/http\:\/\/(\w{3}\.)?youtube.com\/watch\?v\=([_-])?([a-zA-Z0-9][a-zA-Z0-9$_.+!*(),;\/\?:@&~=%-]*)/';
		$pattern[] = '/http\:\/\/(\w{3}\.)?vimeo.com\/(hd#)?(\d*)/';
		$pattern[] = '/http\:\/\/(\w{2}\.)?video.yahoo.com\/watch\/(\d{1,8})\/(\d{1,8})/';
		$pattern[] = '/http\:\/\/video.google.(\w{2,4})\/videoplay\?docid=(-?\d{1,19})(&.*)?/';
		$pattern[] = '/http\:\/\/(\w{3}\.)?revver.com\/video\/(\d{1,7})\/([a-zA-Z0-9][a-zA-Z0-9$_.+!*(),;\/\?:@&~=%-]*)/';
		$pattern[] = '/http\:\/\/(\w{3}\.)?flickr.com\/photos\/(.*)\/(\d{1,10})/';
		$pattern[] = '/http\:\/\/(\w{3}\.)?viddler.com\/explore\/(.*)\/videos\/(\d{1,3})\//';
		$pattern[] = '/http\:\/\/(\w{3}\.)?liveleak.com\/view\?i\=([a-zA-Z0-9][a-zA-Z0-9$_.+!*(),;\/\?:@&~=%-]*)/';
		$pattern[] = '/http\:\/\/(\w{3}\.)?break.com\/index\/(.*?).html/';
		$pattern[] = '/http\:\/\/(\w{3}\.)?dailymotion.com\/(.*)\/video\/(.*)/';
		$pattern[] = '/http\:\/\/(\w{3}\.)?blip.tv\/file\/(\d{1,7})?([a-zA-Z0-9][a-zA-Z0-9$_.+!*(),;\/\?:@&~=%-]*)/';
		$pattern[] = '/http\:\/\/(\w{3}\.)?metacafe.com\/watch\/(\d{1,7})?([a-zA-Z0-9][a-zA-Z0-9$_.+!*(),;\/\?:@&~=%-]*)/';
		$pattern[] = '/http\:\/\/(media\.)?photobucket.com\/video\/([a-zA-Z0-9][a-zA-Z0-9$_.+!*(),;\/\?:@&~=%-\s]*)/';
		
		return $pattern;
	}

	static public function getVideoLinkMatches( $content )
	{
		$pattern	= array();
		$matches	= array();
		
		$pattern	= CVideosHelper::getVideoLinkPatterns();
	
		for( $i = 0; $i < count( $pattern ); $i++ )
		{
			//Match the first video link
			preg_match($pattern[$i], $content, $match );
	
			if( $match )
			{
				$matches[]	= $match[0];
			}
			
		}
		
		return $matches;
	}

	static public function getVideoLink($content, $videoWidth='425', $videoHeight='344')
	{
		$pattern	= array();
		$videoLinks	= array();
		
		$pattern	= CVideosHelper::getVideoLinkPatterns();
	
		for( $i = 0; $i < count( $pattern ); $i++ )
		{
			//Match all video links
			preg_match_all($pattern[$i], $content, $match );
	
			if( $match )
			{
				$videoLinks[]	= $match[0];
			}
			
		}
		
		foreach($videoLinks as $videoLink)
		{
			// Replace the URL with the embedded code
			foreach($videoLink as $videoLinkUrl)
			{		
				$parsedVideoLink	= parse_url($videoLinkUrl);
				preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $parsedVideoLink['host'], $matches);
				$domain	= $matches['domain'];
			
				if (!empty($domain))
				{
					$provider		= explode('.', $domain);
					$providerName	= JString::strtolower($provider[0]);
					$libraryPath	= COMMUNITY_COM_PATH . DS . 'libraries' . DS . 'videos' . DS . $providerName . '.php';
					
					require_once($libraryPath);
					$className		= 'CTableVideo' . JString::ucfirst($providerName);
					$videoObj		= new $className();
					$videoObj->init($videoLinkUrl);
					$video_id		= $videoObj->getId();
					$videoPlayer	= $videoObj->getViewHTML($video_id, $videoWidth, $videoHeight);
					$content = str_replace( $videoLinkUrl, $videoPlayer, $content );
				}
			}
		}
		
		return $content;
	}
	
	static public function prepareVideos($videos)
	{
		if (empty($videos)) return array();
		
		CFactory::load( 'libraries' , 'activities' );
		CFactory::load( 'helpers', 'owner' );
		CFactory::load('libraries', 'storage');
		
		$my			= CFactory::getUser();
		$config		= CFactory::getConfig();
		$videoUrl	= $config->get('videobaseurl');
		
		// Cycle through each video and add additional properties
		for( $i = 0 ; $i < count( $videos ); $i++ )
		{
			$video			=& $videos[ $i ];
			
			switch($video->creator_type)
			{
				case VIDEO_GROUP_TYPE :
					$url				= 'index.php?option=com_community&view=videos&task=video&groupid='.$video->groupid.'&videoid='.$video->id;
					$video->url 		= CRoute::_( $url );
					$video->permalink	= CRoute::getExternalURL( $url , false );
					break;
				case VIDEO_USER_TYPE :
				default :
					$url				= 'index.php?option=com_community&view=videos&task=video&userid='.$video->creator.'&videoid='.$video->id;
					$video->url 		= CRoute::_( $url );
					$video->permalink	= CRoute::getExternalURL( $url , false );
					break;
			}
			
			$video->isOwner	= COwnerHelper::isMine($my->id, $video->creator);
			$video->isAdmin	= COwnerHelper::isCommunityAdmin();
			$video->canEdit	= $video->isOwner || $video->isAdmin;
			
			if(!isset($video->wallcount) && method_exists ($video, 'getWallCount'))
			{
				$video->wallcount = $video->getWallCount();
			}
			
			// Escape output
			$video->title		= CStringHelper::escape($video->title);
			$video->description	= CStringHelper::escape($video->description);
	
			// If new albums that has just been created and
			// does not contain any images, the lastupdated will always be 0000-00-00 00:00:00:00
			// Try to use the albums creation date instead.
			if (method_exists($video, 'getLastUpdated'))
			{
				$video->lastupdated		= $video->getLastUpdated();
			}
			if( $video->lastupdated == '0000-00-00 00:00:00' || $video->lastupdated == '')
			{
				$video->lastupdated	= $video->created;
	
				if( $video->lastupdated == '' || $video->lastupdated == '0000-00-00 00:00:00')
				{
					$video->lastupdated	= JText::_( 'CC NO LAST ACTIVITY' );
				}
				else
				{
					$lastUpdated	= new JDate( $video->lastupdated );
					$video->lastupdated	= CActivityStream::_createdLapse( $lastUpdated );
				}
			}
			else
			{
				$lastUpdated	= new JDate( $video->lastupdated );
				$video->lastupdated	= CActivityStream::_createdLapse( $lastUpdated );
			}
	
			// Give it a nice duration format			
			if($video->duration != 0)
			{
				$video->durationHMS = CVideosHelper::formatDuration( (int)($video->duration), 'HH:MM:SS' );
				$video->durationHMS	= CVideosHelper::toNiceHMS( $video->durationHMS );
			}
			else
			{
				$video->durationHMS = JText::_('CC VIDEO DURATION NOT AVAILABLE');
			}
			
			
			// Creator's name
			$user					= CFactory::getUser($video->creator);
			$video->creatorName		= $user->getDisplayName();
			
			// Construct proper url to video thumb
			if($video->storage != 'file')
			{
				$storage = CStorage::getStorage($video->storage);
				$video->thumb = $storage->getURI($video->thumb);
			}
			else
			{
				if( !empty( $videoUrl ) )
				{
					// Strip cdn path if necessary
					$path 		= $video->thumb;		
					$cdnpath 	= $config->get('videocdnpath');

					if($cdnpath)
					{
						$path = JString::str_ireplace( $cdnpath , '',$path );
					}
					$path	= ltrim( $path , '/' );
					$video->thumb	= rtrim( $videoUrl , '/' ) . '/' . $path;
				}
				else
				{
					if(!JString::substr($video->thumb, 4) == 'http')
					{
						$video->thumb = JURI::root() . $video->thumb;
					}
				}
			}
		}
	
		return $videos;
	}

	static public function prepareVideo($video)
	{
		if(!$video) return;
		
		$videos	= array($video);
		$videos	= CVideosHelper::prepareVideos($videos);
		return $videos['0'];
	}

	static public function getVideoReturnUrlFromRequest($videoType='default')
	{
		$creator_type	= JRequest::getVar( 'creatortype' , VIDEO_USER_TYPE );
		$groupId		= JRequest::getInt( 'groupid' , 0 );
		$my				= JFactory::getUser();
		
		// we use this if redirect url is defined
		$redirectUrl	= JRequest::getVar( 'redirectUrl' , '' , 'POST' );
		if (!empty($redirectUrl))
		{
			return base64_decode($redirectUrl);
		}
		
		if ($creator_type == VIDEO_GROUP_TYPE || !empty($groupId))
		{
			$defaultUrl	= CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groupId , false );
			$pendingUrl	= CRoute::_('index.php?option=com_community&view=videos&task=mypendingvideos&userid='.$my->id.'&groupid='.$groupId, false);
			return ($videoType == 'pending') ? $pendingUrl : $defaultUrl;
		}
		
		$defaultUrl	= CRoute::_('index.php?option=com_community&view=videos&task=myvideos&userid=' . $my->id , false );
		$pendingUrl	= CRoute::_('index.php?option=com_community&view=videos&task=mypendingvideos&userid='.$my->id, false);
		return ($videoType == 'pending') ? $pendingUrl : $defaultUrl;
	}

	static public function getVideoSize($retunType='default', $displayType='display')
	{
		$config		= CFactory::getConfig();
		
		switch ($displayType)
		{
			case 'wall':
				$videoSize	= $config->get('wallvideossize');
				break;
			case 'activities':
				$videoSize	= $config->get('activitiesvideosize');
				break;
			case 'display':
			default:
				$videoSize	= $config->get('videosSize');
				break;
		}
		
		$arrVideoSize	= array();
		$arrVideoSize	= explode('x', $videoSize, 2);
		
		switch ($retunType)
		{
			case 'width':
				$ret	= $arrVideoSize[0];
				break;
			case 'height':
				$ret	= $arrVideoSize[1];
				break;
			default:
				$ret	= $videoSize;
				break;
		}
		
		return $ret;
	}
}

/**
 * Deprecated since 1.8
 */
function cValidateVideo($fileName)
{
	return CVideosHelper::validateVideo( $fileName );
}

/**
 * Deprecated since 1.8
 */
function cFormatDuration ($duration = 0, $format = 'HH:MM:SS')
{
	return CVideosHelper::formatDuration( $duration , $format );
}

/**
 * Deprecated since 1.8
 */
function cToNiceHMS($hms)
{
	return CVideosHelper::toNiceHMS( $hms );
}

/**
 * Deprecated since 1.8
 */
function cGetVideoLinkPatterns()
{
	return CVideosHelper::getVideoLinkPatterns();
}

/**
 * Deprecated since 1.8
 */
function CGetVideoLinkMatches( $content )
{
	return CVideosHelper::getVideoLinkMatches( $content );
}

/**
 * Deprecated since 1.8
 */
function cGetVideoLink($content, $videoWidth='425', $videoHeight='344')
{
	return CVideosHelper::getVideoLink($content, $videoWidth , $videoHeight);
}

/**
 * Deprecated since 1.8
 */
function cPrepareVideos($videos)
{
	return CVideosHelper::prepareVideos( $videos );
}

/**
 * Deprecated since 1.8
 */
function cPrepareVideo($video)
{
	return CVideosHelper::prepareVideo( $video );
}

/**
 * Deprecated since 1.8
 */
function cGetVideoReturnUrlFromRequest($videoType='default')
{
	return CVideosHelper::getVideoReturnUrlFromRequest( $videoType );
}

/**
 * Deprecated since 1.8
 */
function cGetVideoSize($retunType='default', $displayType='display')
{
	return CVideosHelper::getVideoSize( $retunType , $displayType );
}