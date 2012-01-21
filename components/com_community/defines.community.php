<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

define( 'COMMUNITY_FREE_VERSION' , false );
define( 'COMMUNITY_FREE_VERSION_APPS_LIMIT' , 4 );
define('COMMUNITY_DEFAULT_VIEW', 'profile');

define('COMMUNITY_COM_PATH', JPATH_ROOT .DS.'components'.DS.'com_community'); 

define('COMMUNITY_PRIVACY_PRIVATE'	, 0);	
define('COMMUNITY_PRIVACY_PUBLIC'	, 1);
define('COMMUNITY_PRIVACY_FRIENDS'	, 2);
define('COMMUNITY_PRIVACY_CUSTOM'	, 3);

define('COMMUNITY_OVERSAMPLING_FACTOR', 2);
define('COMMUNITY_SMALL_AVATAR_WIDTH', 64);
define('COMMUNITY_PHOTO_THUMBNAIL_SIZE', 128);

define('PRIVACY_PUBLIC',  10);
define('PRIVACY_MEMBERS', 20);
define('PRIVACY_FRIENDS', 30);
define('PRIVACY_PRIVATE', 40);

// Application privacy constants.
define('PRIVACY_APPS_PUBLIC' , 0 );
define('PRIVACY_APPS_FRIENDS' , 10 );
define('PRIVACY_APPS_SELF' , 20 );

define('CONNECTION_FRIENDS', 1);
define('CONNECTION_HUSBAND', 2);
define('CONNECTION_WIFE', 	 3);

define( 'CC_RANDOMIZE', true);

define( 'ACTIVITY_INTERVAL_DAY', 1);
define( 'ACTIVITY_INTERVAL_WEEK', 7);
define( 'ACTIVITY_INTERVAL_MONTH', 30);

define( 'MAGICK_FILTER' , 13 );

define( 'COMMUNITY_PRIVATE_GROUP' , 1 );
define( 'COMMUNITY_PUBLIC_GROUP' , 0 );

define( 'SUBMENU_LEFT', False);
define( 'SUBMENU_RIGHT', True);

define( 'FACEBOOK_FAVICON' , COMMUNITY_COM_PATH . DS . 'assets' . DS . 'favicon' . DS . 'facebook.gif' );
define( 'FACEBOOK_BUTTON_CSS' , 'http://www.facebook.com/css/connect/connect_button.css' );
define( 'FACEBOOK_LOGIN_NOT_REQUIRED' , false );

define( 'DEFAULT_USER_AVATAR' , 'components/com_community/assets/default.jpg' );
define( 'DEFAULT_USER_THUMB' , 'components/com_community/assets/default_thumb.jpg' );

define( 'DEFAULT_GROUP_AVATAR' , 'components/com_community/assets/group.jpg');
define( 'DEFAULT_GROUP_THUMB' , 'components/com_community/assets/group_thumb.jpg' );

define( 'TOOLBAR_HOME', 'HOME');
define( 'TOOLBAR_PROFILE', 'PROFILE');
define( 'TOOLBAR_FRIEND', 'FRIEND');
define( 'TOOLBAR_APP', 'APP');
define( 'TOOLBAR_INBOX', 'INBOX');

define( 'FEATURED_GROUPS' , 'groups' );
define( 'FEATURED_USERS' , 'users' );
define( 'FEATURED_VIDEOS' , 'videos' );
define( 'FEATURED_ALBUMS' , 'albums' );

define( 'PHOTOS_USER_TYPE' , 'user' );
define( 'PHOTOS_GROUP_TYPE' , 'group' );
define( 'COMMUNITY_GROUP_ADMIN' , 1 );
define( 'COMMUNITY_GROUP_MEMBER' , 0 );

define( 'VIDEO_USER_TYPE' , 'user' );
define( 'VIDEO_GROUP_TYPE' , 'group' );

define( 'DISCUSSION_ORDER_BYCREATION' , 1 );
define( 'DISCUSSION_ORDER_BYLASTACTIVITY' , 0 );


define( 'GROUP_PHOTO_PERMISSION_DISABLE' , -1 );
define( 'GROUP_PHOTO_PERMISSION_MEMBERS' , 0 );
define( 'GROUP_PHOTO_PERMISSION_ADMINS' , 1 );

define( 'GROUP_VIDEO_PERMISSION_DISABLE' , -1 );
define( 'GROUP_VIDEO_PERMISSION_MEMBERS' , 0 );
define( 'GROUP_VIDEO_PERMISSION_ADMINS' , 1 );

define( 'FRIEND_SUGGESTION_LEVEL' , 2 );

define( 'GROUP_PHOTO_RECENT_LIMIT' , 6 );
define( 'GROUP_VIDEO_RECENT_LIMIT' , 6 );

define( 'VIDEO_FOLDER_NAME', 'videos' );
define( 'ORIGINAL_VIDEO_FOLDER_NAME', 'originalvideos' );
define( 'VIDEO_THUMB_FOLDER_NAME', 'thumbs' );

define( 'STREAM_CONTENT_LENGTH', 150 );
define( 'PROFILE_MAX_FRIEND_LIMIT', 12 );

define( 'VIDEO_TIPS_LENGTH' , 450 );

define( 'WALLS_GROUP_TYPE' , 'groups' );
define( 'SHOW_GROUP_ADMIN' , true );

define( 'COMMUNITY_PRIVATE_EVENT' , 1 );
define( 'COMMUNITY_PUBLIC_EVENT' , 0 );

define( 'COMMUNITY_TEMPLATE_XML' , 'templateDetails.xml' );

define( 'COMMUNITY_EVENT_ADMINISTRATOR' 		, -1 );
define( 'COMMUNITY_EVENT_STATUS_INVITED' 		, 0 );
define( 'COMMUNITY_EVENT_STATUS_ATTEND' 		, 1 );
define( 'COMMUNITY_EVENT_STATUS_WONTATTEND' 	, 2 );
define( 'COMMUNITY_EVENT_STATUS_MAYBE' 			, 3 );
define( 'COMMUNITY_EVENT_STATUS_BLOCKED' 		, 4 );
define( 'COMMUNITY_EVENT_STATUS_IGNORE'			, 5 );
define( 'COMMUNITY_EVENT_STATUS_REQUESTINVITE' 	, 6 );
define( 'COMMUNITY_EVENT_STATUS_NOTINVITED'		, 7 );

define( 'COMMUNITY_RAW_STATUS' , true );
define( 'COMMUNITY_WALLS_EDIT_INTERVAL' , 900 );

/* Location */
define( 'COMMUNITY_LOCATION_NULL' 	, 255 );


class CDefined
{
	const STREAM_CONTENT_LENGTH = 150;
}