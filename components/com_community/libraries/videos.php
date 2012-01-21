<?php
/**
 * @package		JomSocial
 * @subpackage	Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CVideos extends JObject
{
	var $errorMsg	= null;
	var $debug		= null;
	var $ffmpeg		= null;
	var $flvtool2	= null;
	
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		jimport('joomla.filesystem.file');
		CFactory::load('helpers', 'videos');
		$config		= CFactory::getConfig();
		
		$this->errorMsg			= array();
		$this->debug			= $config->get('videodebug');
		$this->ffmpeg			= $config->get('ffmpegPath');
		$this->flvtool2			= $config->get('flvtool2');
	}

	/**
	 *
	 * Cron job will run this function
	 * to do all the pending video conversion
	 * 
	 * @since Jomsocial 1.2.0
	 */
	function runConvert()
	{
		$config		= CFactory::getConfig();
		$videofolder= $config->get('videofolder');
		if (!$config->get('enablevideos'))
		{
			$this->errorMsg[]	= 'Video is disabled. Video conversion will not run. ';
			return;
		}
		
		$model		= CFactory::getModel('videos');
		$videos		= $model->getPendingVideos();

		if (count($videos) < 1)
		{
			$this->errorMsg[] = 'No videos pending for conversion. ';
			return;
		}
		if (!$this->hasFFmpegSupport())
		{
			$this->errorMsg[] = 'FFmpeg not installed or incorrect path. ';
			return;
		}

		// First of all, lock the videos
		$table			= JTable::getInstance( 'Video' , 'CTable' );
		foreach ($videos as $video)
		{
			$table->load($video->id);
			$table->save( array('status' => 'locked') );
			$table->reset();
		}

		// Process each video
		$videoCounter	= 0;
		$videoSize		= CVideosHelper::getVideoSize();
		$deleteOriginal	= $config->get('deleteoriginalvideos');
		$injectMetadata = JFile::exists($this->flvtool2);
		foreach ($videos as $video)
		{
			$videoInFile	= JPATH::clean(JPATH_ROOT.DS.$video->path);
			$videoOutFolder	= JPATH::clean(JPATH_ROOT.DS.$config->get('videofolder').DS.VIDEO_FOLDER_NAME.DS.$video->creator);
			$videoFilename	= $this->convertVideo($videoInFile , $videoOutFolder, $videoSize, $deleteOriginal);

			if ($videoFilename)
			{
				$videoFullPath = $videoOutFolder . DS . $videoFilename;

				if ($injectMetadata)
				{
					$this->injectMetadata($videoFullPath);
				}

				// Read duration
				$videoInfo	= $this->getVideoInfo($videoFullPath);

				if (!$videoInfo)
				{
					$thumbName			= null;
					$this->errorMsg[]	= 'Could not read video information. Video id: ' . $video->id;
				}
				else
				{
					$videoFrame = CVideosHelper::formatDuration( (int) ($videoInfo['duration']['sec'] / 2), 'HH:MM:SS' );
	
					// Create thumbnail
					$thumbFolder	= JPATH::clean( JPATH_ROOT.DS.$config->get('videofolder').DS.VIDEO_FOLDER_NAME.DS.$video->creator.DS.VIDEO_THUMB_FOLDER_NAME );
					$thumbSize		= CVideos::thumbSize();
					$thumbFileName	= $this->createVideoThumb($videoFullPath, $thumbFolder, $videoFrame, $thumbSize);
				}
				
				if (!$thumbFileName)
				{
					$this->errorMsg[]	= 'Could not create thumbnail for video. Video id: ' . $video->id;
				}
				
				// Save into DB
				$config	= CFactory::getConfig();
				$videoFolder	= $config->get('videofolder');
				
				$video->path	= $config->get('videofolder') . '/'
								. VIDEO_FOLDER_NAME . '/'
								. $video->creator . '/'
								. $videoFilename;
				
				$video->thumb	= $config->get('videofolder') . '/'
								. VIDEO_FOLDER_NAME . '/'
								. $video->creator . '/'
								. VIDEO_THUMB_FOLDER_NAME . '/'
								. $thumbFileName;
				
				$video->duration= $videoInfo['duration']['sec'];
				$video->status	= 'ready';
				$table->reset();
				$table->bind($video);
				if (!$table->store())
				{
					$this->errorMsg[]	= $table->getError();
				}

				// Add into activity streams
				if($table->isPublic())
				{
					$my				= CFactory::getUser( $table->creator );
					$act			= new stdClass();
					$act->cmd 		= 'videos.upload';
					$act->actor   	= $my->id;
					$act->target  	= 0;
					$act->title	  	= JText::sprintf('CC ACTIVITIES POST VIDEO' , '{video_url}' , $table->title );
					$act->app		= 'videos';
					$act->cid		= $table->id;
					$act->content	= '<img src="' . $table->getThumbnail() . '" style="border: 1px solid #eee;margin-right: 3px;" />';
					
					$params = new JParameter('');
					$params->set( 'video_url', $table->getViewURI(false) );
					
					// Add activity logging
					CFactory::load ( 'libraries', 'activities' );
					CActivityStream::add( $act, $params->toString() );
				}
				$videoCounter++;
			} // end if video converted
			else
			{
				$this->errorMsg[]	= 'Could not convert video id: ' . $video->id;
			}
			$table->reset();
			unset($video);
		} // end foreach pending videos
		
		// Lastly, unlock the videos
		foreach ($videos as $video)
		{
			$table->load($video->id);
			if ($table->status	== 'locked')
			{
				$table->save( array('status' => 'pending') );
			}
		}
		
		$this->errorMsg[]	= $videoCounter ? $videoCounter . ' videos converted successfully...' : 'No videos was converted';
		
		$returnMsg	= '';
		foreach ($this->errorMsg as $msg)
		{
			$returnMsg	.= "$msg\r\n";
		}
		
		return $returnMsg;
	}

	/**
	 * Convert a Video File into specified format according to the output file name
	 * If $videoOut is a path, a random file name will be generated
	 * 
	 * @params string $videoIn video input path (including file name)
	 * @params string $videoOut video output path (file name optinal)
	 * @params string $videoSize in widthxheight format or any ffmpeg accepted format eg hd480
	 * @return string video file name or false if failed
	 * @since Jomsocial 1.2.0
	 */
	function convertVideo($videoIn, $videoOut, $videoSize = '400x300', $deleteOriginal = false)
	{
		if (!JFile::exists($videoIn))
			return false;

		if (JFile::exists($videoOut)) {
			$videoFullPath = JFile::makeSafe($videoOut);
			$videoFileName = JFile::getName($videoFullPath);
		} else {
			// It is a directory, not a file. Assigns file name
			CFactory::load( 'helpers' , 'file' );
			$videoFileName =  CFileHelper::getRandomFilename($videoOut, '', 'flv');
			$videoFullPath = $videoOut . DS . $videoFileName;
		}

		// Build the ffmpeg command
		$config	= CFactory::getConfig();
		$cmd 	= array();
		$cmd[]	= $this->ffmpeg;
		$cmd[]	= '-y -i ' . $videoIn;
		$cmd[]	= '-g 30'; //group of picture size, for video streaming
		$cmd[]	= '-qscale ' . $config->get('qscale');
		$cmd[]	= '-vcodec flv -f flv -ar 44100';
		$cmd[]	= '-s ' . $videoSize;
		$cmd[]	= $config->get('customCommandForVideo');
		$cmd[]	= $videoFullPath;
		$cmd[]	= '2>&1';

		$command = implode(' ', $cmd);
		$cmdOut	= shell_exec($command);
		
		if (JFile::exists($videoFullPath) && filesize($videoFullPath) > 0)
		{
			if ($deleteOriginal)
			{
				JFile::delete($videoIn);
			}
			return $videoFileName;
		}
		else
		{
			if ($this->debug)
			{
				echo '<pre>FFmpeg could not convert videos</pre>';
				echo '<pre>' . $command . '</pre>';
				echo '<pre>' . $cmdOut . '</pre>';
			}
			return false;
		}
	}

	/*
	 * Create Thumbnail for a video file
	 * 
	 * @params string $videoFile existing video's path + file name
	 * @params string $thumbFile new thumbnail's folder or filename
	 * @params string $videoFrame decide which frame to be taken as thumbnail
	 * @params string $thumbsize height x width of the thumbnail
	 * @return thumbnail's filename or false if failed
	 * @since Jomsosial 1.2.0
	 */
	function createVideoThumb($videoFile, $thumbFile, $videoFrame, $thumbSize='112x84')
	{
		if (!JFile::exists($videoFile))
		{
			return false;
		}
		if (JFile::exists($thumbFile))
		{
			$thumbFullPath = JFile::makeSafe($thumbFile);
			$thumbFileName = JFile::getName($thumbFullPath);
		} else {
			CFactory::load( 'helpers' , 'file' );
			$thumbFileName =  CFileHelper::getRandomFilename($thumbFile, '', 'jpg');
			$thumbFullPath = JPath::clean($thumbFile . DS . $thumbFileName);
		}

		$cmd	= $this->ffmpeg . ' -i ' . $videoFile . ' -ss ' . $videoFrame . ' -t 00:00:01 -s ' . $thumbSize . ' -r 1 -f mjpeg ' . $thumbFullPath;
		$cmdOut = shell_exec($cmd);

		if (JFile::exists($thumbFullPath) && (filesize($thumbFullPath) > 0))
		{
			return $thumbFileName;
		} else {
			if ($this->debug)
			{
				echo '<pre>FFmpeg could not create video thumbs</pre>';
				echo '<pre>' . $cmd . '</pre>';
				echo '<pre>' . $cmdOut . '</pre>';
				if (!$cmdOut) { echo '<pre>Check video thumb folder\'s permission.</pre>'; }
			}
			return false;
		}

	}

	function createVideoThumbFromRemote(&$videoObj)
	{
		$thumbData		= CRemoteHelper::getContent($video->thumb);
		if ($thumbData)
		{
			jimport('joomla.filesystem.file');
			CFactory::load('helpers' , 'file' );
			CFactory::load('helpers' , 'image');
			
			$thumbPath		= CVideos::getPath($table->creator, 'thumb');
			$thumbFileName	=  CFileHelper::getRandomFilename($thumbPath);
			$tmpThumbPath	= $thumbPath . DS . $thumbFileName;
			
			if (JFile::write($tmpThumbPath, $thumbData))
			{
				// Get the image type first so we can determine what extensions to use
				$info		= getimagesize( $tmpThumbPath );
				$mime		= image_type_to_mime_type( $info[2]);
				$thumbExtension	= CImageHelper::getExtension( $mime );
				
				$thumbFilename	= $thumbFileName . $thumbExtension;
				$thumbPath	= $thumbPath . DS . $thumbFilename;
				JFile::move($tmpThumbPath, $thumbPath);
				
				// Resize the thumbnails
				CFactory::load( 'libraries', 'videos' );
				CImageHelper::resizeProportional( $thumbPath , $thumbPath , $mime , CVideos::thumbSize('width') , CVideo::thumbSize('height') );
				
				// Save
				$config	= CFactory::getConfig();
				
				$thumb	= $config->get('videofolder') . '/'
						. VIDEO_FOLDER_NAME . '/'
						. $table->creator . '/'
						. VIDEO_THUMB_FOLDER_NAME . '/'
						. $thumbFilename;
				
				$table->set('thumb', $thumb);
				$table->store();
			}
		}
	}

	/**
	 *	Inject Flash Video Metadata
	 *	
	 *	@params	string	$flv	flash video file
	 *	@since	Jomsocial 1.2.0
	 */
	function injectMetadata($flv)
	{
// 		$info	= $this->getVideoInfo($flv);

		$data	= array();
// 		$data[]	= '-duration:' . $info['duration']['sec'];
// 		$data[]	= '-width:' . $info['video']['width'];
// 		$data[]	= '-height:' . $info['video']['height'];
// 		$data[]	= '-framerate:' . $info['video']['frame_rate'];
		$data[]	= '-canSeekToEnd:true';
// 		$data[]	= '-metadatacreator:' . 'Jomsocial.com';

		$metadata	= implode(' ', $data);

		$cmd	= $this->flvtool2 . ' -UP ' . $metadata . ' ' . $flv . ' 2>&1';
		$cmdOut	= shell_exec($cmd); // use -P to print out metadata to stdout

	}

	/*
	 * Return Video's information
	 * bitrate, duration, video and frame properties
	 * 
	 * @params string $videoFilePath path to the Video
	 * @return array of video's info	 
	 * @since Jomsocial 1.2.0
	 */
	function getVideoInfo($videoFile, $cmdOut = '')
	{
		$data = array();

		if (!is_file($videoFile) && empty($cmdOut))
			return $data;

		if (!$cmdOut) {
			//$cmd	= $this->converter . ' -v 10 -i ' . $videoFile . ' 2>&1';
			// Some FFmpeg version only accept -v value from -2 to 2 
			$cmd	= $this->ffmpeg . ' -i ' . $videoFile . ' 2>&1';
			$cmdOut	= shell_exec($cmd);
		}

		if (!$cmdOut) {
			return $data;
		}

		preg_match_all('/Duration: (.*)/', $cmdOut , $matches);
		if (count($matches) > 0 && isset($matches[1][0]))
		{
			CFactory::load( 'helpers' , 'videos' );
			
			$parts = explode(', ', trim($matches[1][0]));
			
			$data['bitrate']			= intval(ltrim($parts[2], 'bitrate: '));
			$data['duration']['hms']	= substr($parts[0], 0, 8);
			$data['duration']['exact']	= $parts[0];
			$data['duration']['sec']	= $videoFrame = CVideosHelper::formatDuration($data['duration']['hms'], 'seconds');
			$data['duration']['excess']	= intval(substr($parts[0], 9));
		}
		else
		{
			if ($this->debug) {
				echo '<pre>FFmpeg failed to read video\'s duration</pre>';
				echo '<pre>' . $cmd . '<pre>';
				echo '<pre>' . $cmdOut . '</pre>';
			}
			return false;
		}

		preg_match('/Stream(.*): Video: (.*)/', $cmdOut, $matches);
		if (count($matches) > 0 && isset($matches[0]) && isset($matches[2]))
		{
			$data['video']	= array();

			preg_match('/([0-9]{1,5})x([0-9]{1,5})/', $matches[2], $dimensions_matches);
			$data['video']['width']		= floatval($dimensions_matches[1]);
			$data['video']['height']	= floatval($dimensions_matches[2]);

			preg_match('/([0-9\.]+) (fps|tb)/', $matches[0], $fps_matches);

			if (isset($fps_matches[1]))
				$data['video']['frame_rate']= floatval($fps_matches[1]);

			preg_match('/\[PAR ([0-9\:\.]+) DAR ([0-9\:\.]+)\]/', $matches[0], $ratio_matches);
			if(count($ratio_matches))
			{
				$data['video']['pixel_aspect_ratio']	= $ratio_matches[1];
				$data['video']['display_aspect_ratio']	= $ratio_matches[2];
			}

			if (!empty($data['duration']) && !empty($data['video']))
			{
				$data['video']['frame_count'] = ceil($data['duration']['sec'] * $data['video']['frame_rate']);
				$data['frames']				= array();
				$data['frames']['total']	= $data['video']['frame_count'];
				$data['frames']['excess']	= ceil($data['video']['frame_rate'] * ($data['duration']['excess']/10));
				$data['frames']['exact']	= $data['duration']['hms'] . '.' . $data['frames']['excess'];
			}

			$parts			= explode(',', $matches[2]);
			$other_parts	= array($dimensions_matches[0], $fps_matches[0]);

			$formats = array();
			foreach ($parts as $key => $part)
			{
				$part = trim($part);
				if (!in_array($part, $other_parts))
					array_push($formats, $part);
			}
			$data['video']['pixel_format']	= $formats[1];
			$data['video']['codec']			= $formats[0];
		}

		return $data;
	}

	function hasFFmpegSupport()
	{
		return JFile::exists($this->ffmpeg);
	}

	function hasFLVTool2Support()
	{
		return JFile::exists($this->flvtool2);
	}

	/*
	 * Deprecated
	 * Mirror to getVideoFromProvider()
	 */
	function getProvider($videoLink)
	{
		$providerName	= 'invalid';
		
		if (! empty($videoLink))
		{
			$videoLink	= 'http://'.JString::str_ireplace( 'http://' , '' , $videoLink );
			$parsedLink	= parse_url($videoLink);
			
			preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $parsedLink['host'], $matches);
			
			if ( !empty($matches['domain']))
			{
				//$this->setError(JText::_('CC INVALID VIDEO URL'));
				//return false;
				
				$domain		= $matches['domain'];
				$provider		= explode('.', $domain);
				$providerName	= JString::strtolower($provider[0]);
			}
					
		} 
		
		
		$libraryPath	= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'videos' . DS . $providerName . '.php';

		if (!JFile::exists($libraryPath))
		{
			$providerName	= 'invalid';
			$libraryPath	= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'videos' . DS . 'invalid.php';
		}
		
		require_once($libraryPath);
		$className	= 'CTableVideo' . JString::ucfirst($providerName);
		$table		= new $className();
		$table->init($videoLink);

		return $table;
	}
	
	/*
	 * Return a video provider object according to the video link
	 *
	 */
	function getVideoFromProvider($videoLink)
	{
		if (empty($videoLink))
		{
			return false;
		}
		
		$videoLink	= 'http://'.JString::str_ireplace( 'http://' , '' , $videoLink );
		$parsedLink	= parse_url($videoLink);
		
		preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $parsedLink['host'], $matches);
		
		if (empty($matches['domain']))
		{
			$this->setError(JText::_('CC INVALID VIDEO URL'));
			return false;
		}
		$domain		= $matches['domain'];
		
		$provider		= explode('.', $domain);
		$providerName	= JString::strtolower($provider[0]);
		$libraryPath	= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'videos' . DS . $providerName . '.php';

		if (!JFile::exists($libraryPath))
		{
			$this->setError(JText::_('CC VIDEO PROVIDER NOT SUPPORTED'));
			return false;
		}
		
		require_once($libraryPath);
		$className	= 'CTableVideo' . JString::ucfirst($providerName);
		$table		= new $className($db);
		$table->init($videoLink);

		return $table;
	}
	
	/**
	 * Return the content of acitivity stream
	 */	 	
	static function getActivityContentHTML($act)
	{
		$html = '';
		$param = new JParameter($act->params);
		// Legacy issue. We could have either wall or video upload notice here
		$action = $param->get('action', 'upload');
		
		// wall activity have either empty content (old) or $param action = 'wall'
		if($action == 'wall' )
		{
			// Only if the param wallid is specified that we could retrive the wall content
			$wallid = $param->get('wallid', false);
			if($wallid)
			{
			    $config     = CFactory::getConfig(); 
				CFactory::load('models', 'wall');
				$wall		= JTable::getInstance( 'Wall' , 'CTable' );
				$wall->load( $wallid );
				
				$tmpl	= new CTemplate();
				$comment = JString::substr($wall->comment, 0, $config->getInt('streamcontentlength'));
				$tmpl->set( 'comment' , CStringHelper::escape( JString::substr($comment, 0, $config->getInt('streamcontentlength'))) );
				$html	= $tmpl->fetch( 'activity.wall.post' );
				
				//$html = JString::substr($wall->comment, 0, $config->getInt('streamcontentlength'));
			}			
		}
		elseif( $action == 'upload' )
		{
			CFactory::load('models', 'videos');
			$video	= JTable::getInstance( 'Video' , 'CTable' );
			$video->load( $act->cid );

			$url	= $video->getViewUri();

			$template	= 'activity.videos.upload';
			$tmpl	= new CTemplate();
			$tmpl->set( 'url'	, $url );
			$tmpl->set( 'video'	, $video );
			$tmpl->set( 'duration' , CVideosHelper::toNiceHMS(CVideosHelper::formatDuration($video->getDuration())) );
			$html	= $tmpl->fetch( 'videos.activity.upload' );
		}
		
		return $html;
	}
	
	/*
	 * Return the redirect url
	 */	 
	static function getVideoReturnUrlFromRequest($videoType='default')
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
	
	function videoSize($retunType='default', $displayType='display')
	{
		static $default;
		static $width;
		static $height;
		
		if (!isset($thumbsize, $width, $height))
		{
			$config	= CFactory::getConfig();
			switch ($displayType)
			{
				case 'wall':
					$default	= $config->get('wallvideossize');
					break;
				case 'activities':
					$default	= $config->get('activitiesvideosize');
					break;
				case 'display':
				default:
					$default	= $config->get('videosSize');
					break;
			}
			$array	= array();
			$array	= explode('x', $default, 2);
			$width	= $array[0];
			$height	= $array[1];
		}
		
		$retunType	= strtolower($retunType);
		return $$retunType;
	}
	
	/*
	 * Return video thumbnail size for the request
	 */	 
	function thumbSize($param='thumbsize')
	{
		static $thumbsize;
		static $width;
		static $height;
		
		if (!isset($thumbsize, $width, $height))
		{
			$config		= CFactory::getConfig();
			$thumbsize	= $config->get('videosThumbSize');
			$array		= explode('x', $thumbsize, 2);
			$width		= $array[0];
			$height		= $array[1];
		}
		
		$param		= strtolower($param);
		return $$param;
	}
	
	function getPath($userid=null, $folderType='user')
	{
		$config		= CFactory::getConfig();
		
		if (!$userid)
		{
			$my		= CFactory::getUser();
			$userid	= $my->id;
		}
		
		$prefix		= ($folderType=='original') ?  ORIGINAL_VIDEO_FOLDER_NAME : VIDEO_FOLDER_NAME;
		$isThumb	= ($folderType=='thumb') ? DS . VIDEO_THUMB_FOLDER_NAME : '';
		
		$folder		= JPATH_ROOT.DS.$config->get('videofolder').DS.$prefix.DS.$userid.$isThumb;
		$folder		= JPATH::clean($folder);
		
		return $folder;
	}

}

/**
 * Maintain classname compatibility with JomSocial 1.6 below
 */ 
class CVideoLibrary extends CVideos
{}