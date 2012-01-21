<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

// Set defines
define('BLOGGIE_ADMIN_PATH', 	JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie');
define('BLOGGIE_SITE_PATH', 	JPATH_ROOT.DS.'components'.DS.'com_lyftenbloggie');

// Register framework
JLoader::register('BloggieFactory', 	BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'factory.php');
JLoader::register('BloggieSettings', 	BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'settings.php');
JLoader::register('BloggieAccess', 		BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'access.php');
JLoader::register('BloggiePlugin', 		BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'plugin.php');
JLoader::register('BloggieAuthor', 		BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'author.php');
JLoader::register('BloggieEntry', 		BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'entry.php');

// Set the table directory
JTable::addIncludePath(BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'tables');

error_reporting(E_ALL);
ini_set('display_errors', '1');

/**
 * LyftenBloggie Framework XMLRPC class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieXMLRPC
{
	var $methods;
	var $error;
	var $_db;
	var $host_url;
	var $blog_charset;
	var $_blogname;

	/**
	 * Constructor for PHP4
	 **/
	function BloggieXMLRPC()
	{
		// Initialize variables
		$this->_db 		= & JFactory::getDBO(); 
		$this->host_url = trim(JURI::base(), '/');
		$this->host_url = str_replace('/xmlrpc', '', $this->host_url);

		//set charset
		$settings 			= BloggieSettings::getInstance();
		$this->blog_charset = $settings->get('charset');
		$this->_blogname 	= $settings->get('title');

		$this->initialise_blog_option_info();
	}

	/**
	 * Setup blog options property.
	 */
	function initialise_blog_option_info()
	{
		global $mainframe;

		$this->blog_options = array(
			// Read only options
			'software_name'		=> array(
				'desc'			=> new xmlrpcval(JText::_( 'Software Name' )),
				'readonly'		=> true,
				'value'			=> new xmlrpcval('LyftenBloggie')
			),
			'software_version'	=> array(
				'desc'			=> new xmlrpcval(JText::_( 'Software Version' )),
				'readonly'		=> true,
				'value'			=> new xmlrpcval('1.1.0')
			),
			'blog_url'			=> array(
				'desc'			=> new xmlrpcval(JText::_( 'Blog URL' )),
				'readonly'		=> true,
				'value'			=> new xmlrpcval(JURI::base())
			),

			// Updatable options
			'time_zone'			=> array(
				'desc'			=> new xmlrpcval(JText::_( 'Time Zone' )),
				'readonly'		=> false,
				'value'			=> new xmlrpcval($mainframe->getCfg('offset'))
			),
			'blog_title'		=> array(
				'desc'			=> new xmlrpcval(JText::_( 'Blog Title' )),
				'readonly'		=> false,
				'option'		=> 'title'
			),
			'blog_tagline'		=> array(
				'desc'			=> new xmlrpcval(JText::_( 'Blog Tagline' )),
				'readonly'		=> false,
				'option'		=> 'mainBlogDesc'
			),
			'date_format'		=> array(
				'desc'			=> new xmlrpcval(JText::_( 'Date Format' )),
				'readonly'		=> false,
				'value'			=> new xmlrpcval('F j, Y')
			),
			'time_format'		=> array(
				'desc'			=> new xmlrpcval(JText::_( 'Time Format' )),
				'readonly'		=> false,
				'value'			=> new xmlrpcval('g:i a')
			)
		);
	}

	/**
	 * Returns a reference to a global Settings object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		$xmlrpc = & BloggieXMLRPC::getInstance();
	 *
	 * @access	public
	 * @return	BloggieXMLRPC
	 */
	function &getInstance()
	{
		static $instance;

		if (!isset($instance)) {
			$instance = new BloggieXMLRPC();
		}

		return $instance;
	}

	/**
	 * Log user in.
	 *
	 * @param string $username User's username.
	 * @param string $password User's password.
	 * @return mixed WP_User object if authentication passed, false otherwise
	 */
	function login($username, $password)
	{
		static $instance;

		if (!isset($instance)) {
			$instance = array();
		}

		if (!isset($instance[$username]))
		{
			$instance[$username] = & JFactory::getUser();

			if($instance[$username]->guest)
			{
				global $mainframe;

				$options 		= array();
				$credentials 	= array();
				$credentials['username'] = $username;
				$credentials['password'] = $password;

				//preform the login action
				$user = $mainframe->login($credentials, $options);

				if(JError::isError($user)) {
					$this->error = JText::_('Bad login/pass combination.');
					return false;
				}

				$instance[$username] = & JFactory::getUser();
			}
		}

		return $instance[$username];
	}

	/**
	 * Retrieve blog options value from list.
	 *
	 * @param array $options Options to retrieve.
	 * @return array
	 */
	function _getOption($option)
	{
		$value 		= '';
		$settings 	= & BloggieSettings::getInstance();

		if( array_key_exists( $option, $this->blog_options ) )
		{
			//Is the value static or dynamic?
			if( isset( $this->blog_options[$option]['option'] ) ) {
				$value = $settings->get($this->blog_options[$option]['option']);
				unset($data[$option]['option']);
			}else{
				$value = $this->blog_options[$option]['value'];
			}
		}

		return new xmlrpcval($value);
	}

	/**
	 * Retrieve blog options value from list.
	 *
	 * @param array $options Options to retrieve.
	 * @return array
	 */
	function _getOptions($options)
	{
		$data 		= array( );
		$settings 	= & BloggieSettings::getInstance();

		for( $i=0; $i < count($options); $i++ )
		{
			if( array_key_exists( $options[$i], $this->blog_options ) )
			{
				$data[$i] = $this->blog_options[$options[$i]];

				//Is the value static or dynamic?
				if( isset( $data[$i]['option'] ) ) {
					$data[$i]['value'] = new xmlrpcval($settings->get($data[$i]['option']));
					unset($data[$i]['option']);
				}
				$data[$i]['readonly'] = new xmlrpcval($data[$i]['readonly']);
				$data[$i] = new xmlrpcval($data[$i], 'struct');
			}
		}

		return $data;
	}

	/**
	 *  Function to route to an internal URI
	 *
	 * @access public
	 */
	function route($uri)
	{
		global $mainframe;

		BloggieFactory::import('site', 'helpers');

		$router  = &$mainframe->getRouter('site');
		$url = $router->build($uri);
		$url = $url->toString();
		$url = str_replace('xmlrpc/', '', $url);
		return $url;		
	}

	/**
	 * Sanitize string or array of strings for database.
	 *
	 * @param string|array $array Sanitize single string or array of strings.
	 * @return string|array Type matches $array and sanitized for the database.
	 */
	function escape(&$array)
	{
		if(!is_array($array)) {
			return(addslashes($array));
		}
		else {
			foreach ( (array) $array as $k => $v )
			{
				if (is_array($v)) {
					$this->escape($array[$k]);
				} else if (is_object($v)) {
					$this->escape($array->$k);
				} else {
					$array[$k] = addslashes($v);
				}
			}
		}
	}

	/**
	 * Escaping for HTML blocks.
	 *
	 * @param string $text
	 * @return string
	 */
	function esc_html( $string )
	{
		$string 		= (string)$string;
		$quote_style 	= ENT_QUOTES;
		$_quote_style 	= ENT_QUOTES;

		if ( 0 === strlen( $string ) ) {
			return '';
		}

		// Don't bother if there are no specialchars - saves some processing
		if ( !preg_match( '/[&<>"\']/', $string ) ) {
			return $string;
		}

		if ( $quote_style === 'double' ) {
			$quote_style = ENT_COMPAT;
			$_quote_style = ENT_COMPAT;
		} elseif ( $quote_style === 'single' ) {
			$quote_style = ENT_NOQUOTES;
		}

		$string = @htmlspecialchars( $string, $quote_style, $this->blog_charset );

		// Backwards compatibility
		if ( 'single' === $_quote_style ) {
			$string = str_replace( "'", '&#039;', $string );
		}

		return $string;
	}

	/**
	 * Computes an offset in seconds from an iso8601 timezone.
	 *
	 * @param string $timezone Either 'Z' for 0 offset or '±hhmm'.
	 * @return int|float The offset in seconds.
	 */
	function iso8601_timezone_to_offset($timezone)
	{
		// $timezone is either 'Z' or '[+|-]hhmm'
		if ($timezone == 'Z') {
			$offset = 0;
		} else {
			$sign    = (substr($timezone, 0, 1) == '+') ? 1 : -1;
			$hours   = intval(substr($timezone, 1, 2));
			$minutes = intval(substr($timezone, 3, 4)) / 60;
			$offset  = $sign * 3600 * ($hours + $minutes);
		}
		return $offset;
	}

	/**
	 * Converts an iso8601 date to MySQL DateTime format used by post_date[_gmt].
	 *
	 * @param string $date_string Date and time in ISO 8601 format {@link http://en.wikipedia.org/wiki/ISO_8601}.
	 * @param string $timezone Optional. If set to GMT returns the time minus gmt_offset. Default is 'user'.
	 * @return string The date and time in MySQL DateTime format - Y-m-d H:i:s.
	 */
	function iso8601_to_datetime($date_string, $timezone = 'user')
	{
		$timezone = strtolower($timezone);

		if ($timezone == 'gmt') {

			preg_match('#([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})(Z|[\+|\-][0-9]{2,4}){0,1}#', $date_string, $date_bits);

			if (!empty($date_bits[7])) { // we have a timezone, so let's compute an offset
				$offset = iso8601_timezone_to_offset($date_bits[7]);
			} else { // we don't have a timezone, so we assume user local timezone (not server's!)
				$offset = $this->_getOption('time_zone');
			}

			$timestamp = gmmktime($date_bits[4], $date_bits[5], $date_bits[6], $date_bits[2], $date_bits[3], $date_bits[1]);
			$timestamp -= $offset;

			return gmdate('Y-m-d H:i:s', $timestamp);

		} else if ($timezone == 'user') {
			return preg_replace('#([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})(Z|[\+|\-][0-9]{2,4}){0,1}#', '$1-$2-$3 $4:$5:$6', $date_string);
		}
	}

	/**
	 * Converts MySQL DATETIME field to user specified date format.
	 *
	 * @param string $dateformatstring Either 'G', 'U', or php date format.
	 * @param string $mysqlstring Time from mysql DATETIME field.
	 * @param bool $translate Optional. Default is true. Will switch format to locale.
	 * @return string Date formated by $dateformatstring or locale (if available).
	 */
	function mysql2date( $dateformatstring, $mysqlstring )
	{
		$m = $mysqlstring;
		if ( empty( $m ) )
			return false;

		if( 'G' == $dateformatstring ) {
			return strtotime( $m . ' +0000' );
		}

		$i = strtotime( $m );

		if( 'U' == $dateformatstring )
			return $i;

		return date( $dateformatstring, $i );
	}

	/**
	 * Bastardized Model Caller.
	 *
	 * @param string $name
	 * @param string $path
	 * @param array  $config
	 * @return object
	 */
	function &getModel( $name, $path=null, $config = array())
	{
		$name		= preg_replace('/[^A-Z0-9_\.-]/i', '', $name);
		$modelClass	= 'LyftenBloggieModel'.ucfirst($name);
		$result		= false;

		if (!class_exists( $modelClass ))
		{
			$path = ($path == 'admin') ? BLOGGIE_ADMIN_PATH.DS.'models'.DS.$name.'.php' : BLOGGIE_SITE_PATH.DS.'models'.DS.$name.'.php' ;

			if (file_exists($path))
			{
				require_once $path;

				if (!class_exists( $modelClass ))
				{
					$this->error = new IXR_Error(403, 'Model class ' . $modelClass . ' not found in file.');
					return $result;
				}
			}
			else return $result;
		}

		$result = new $modelClass($config);
		return $result;
	}
}