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

/**
 * LyftenBloggie Framework HTTP Class for managing HTTP Transports and making HTTP requests
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieHttp extends JObject
{
	var $http_request_timeout 				= 5;
	var $http_request_redirection_count 	= 5;
	var $http_request_version 				= '1.0';
	var $http_headers_useragent 			= 'LyftenBloggie';
	var $http_headers_useragent_ver			= 'Unknown';
	var $http_transport_get_debug 			= false;
	var $rootURL 							= '';
	var $key 								= 'd41d8cd98f00b204e9800998ecf8427e';   //LyftenBloggie Public API Key

	//Debug Settings
	var $http_transport_post_debug 			= false;
	var $http_api_debug 					= false;
	
	/**
	 * PHP4 style Constructor - Calls PHP5 Style Constructor
	 * @since 1.0.2
	 **/
	function BloggieHttp()
	{
		$this->__construct();
	}

	/**
	 * PHP5 style Constructor - Setup available transport if not available.
	 * @since 1.0.2
	 **/
	function __construct()
	{
		//Get Root
		$this->rootURL = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
			$pageURL .= "s";

		$this->rootURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$this->rootURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
		} else {
			$this->rootURL .= $_SERVER["SERVER_NAME"];
		}

		if (!@ini_get('safe_mode')) set_time_limit(180);
		
		if( $this->http_headers_useragent_ver == 'Unknown' )
		{
			$parser		=& JFactory::getXMLParser('Simple');
	
			// Load the local XML file first to get the local version
			$xml		= BLOGGIE_ADMIN_PATH . DS . 'lyftenbloggie.xml';
			
			$parser->loadFile( $xml );
			$document	=& $parser->document;
	
			$element 	=& $document->getElementByPath( 'version' );
			$this->http_headers_useragent_ver = $element->data();
		}
	
		BloggieHttp::_getTransport();
		BloggieHttp::_postTransport();
	}

	/**
	 * Tests the HTTP objects for an object to use and returns it.
	 * @since 1.0.2
	 **/
	function &_getTransport( $args = array() )
	{
		static $working_transport, $blocking_transport, $nonblocking_transport;

		if ( is_null($working_transport) )
		{
			if ( true === BloggieHttp_ExtHttp::test($args) ) {
				$working_transport['exthttp'] = new BloggieHttp_ExtHttp();
				$blocking_transport[] = &$working_transport['exthttp'];
			} else if ( true === BloggieHttp_Curl::test($args) ) {
				$working_transport['curl'] = new BloggieHttp_Curl();
				$blocking_transport[] = &$working_transport['curl'];
			} else if ( true === BloggieHttp_Streams::test($args) ) {
				$working_transport['streams'] = new BloggieHttp_Streams();
				$blocking_transport[] = &$working_transport['streams'];
			} else if ( true === BloggieHttp_Fopen::test($args) ) {
				$working_transport['fopen'] = new BloggieHttp_Fopen();
				$blocking_transport[] = &$working_transport['fopen'];
			} else if ( true === BloggieHttp_Fsockopen::test($args) ) {
				$working_transport['fsockopen'] = new BloggieHttp_Fsockopen();
				$blocking_transport[] = &$working_transport['fsockopen'];
			}

			foreach ( array('curl', 'streams', 'fopen', 'fsockopen', 'exthttp') as $transport ) {
				if ( isset($working_transport[$transport]) )
					$nonblocking_transport[] = &$working_transport[$transport];
			}
		}

		//Display Debug Data
		if ( $this->http_transport_get_debug )
		{
			echo 'WORKING_TRANSPORT: '.$working_transport.'<br>';
			echo 'BLOCKING_TRANSPORT: '.$blocking_transport.'<br>';
			echo 'NONBLOCKING_TRANSPORT: '.$nonblocking_transport.'<br>';
		}

		if ( isset($args['blocking']) && !$args['blocking'] )
			return $nonblocking_transport;
		else
			return $blocking_transport;
	}

	/**
	 * Tests the HTTP objects for an object to use and returns it.
	 * @since 1.0.2
	 **/
	function &_postTransport( $args = array() )
	{
		static $working_transport, $blocking_transport, $nonblocking_transport;

		if ( is_null($working_transport) ) {
			if ( true === BloggieHttp_ExtHttp::test($args) ) {
				$working_transport['exthttp'] = new BloggieHttp_ExtHttp();
				$blocking_transport[] = &$working_transport['exthttp'];
			} else if ( true === BloggieHttp_Curl::test($args) ) {
				$working_transport['curl'] = new BloggieHttp_Curl();
				$blocking_transport[] = &$working_transport['curl'];
			} else if ( true === BloggieHttp_Streams::test($args) ) {
				$working_transport['streams'] = new BloggieHttp_Streams();
				$blocking_transport[] = &$working_transport['streams'];
			} else if ( true === BloggieHttp_Fsockopen::test($args) ) {
				$working_transport['fsockopen'] = new BloggieHttp_Fsockopen();
				$blocking_transport[] = &$working_transport['fsockopen'];
			}

			foreach ( array('curl', 'streams', 'fsockopen', 'exthttp') as $transport ) {
				if ( isset($working_transport[$transport]) )
					$nonblocking_transport[] = &$working_transport[$transport];
			}
		}

		//Display Debug Data
		if ( $this->http_transport_post_debug )
		{
			echo 'WORKING_TRANSPORT: '.$working_transport.'<br>';
			echo 'BLOCKING_TRANSPORT: '.$blocking_transport.'<br>';
			echo 'NONBLOCKING_TRANSPORT: '.$nonblocking_transport.'<br>';
		}

		if ( isset($args['blocking']) && !$args['blocking'] )
			return $nonblocking_transport;
		else
			return $blocking_transport;
	}

	/**
	 * Send a HTTP request to a URI.
	 *
	 * The body and headers are part of the arguments. The 'body' argument is for the body and will
	 * accept either a string or an array. The 'headers' argument should be an array, but a string
	 * is acceptable. If the 'body' argument is an array, then it will automatically be escaped
	 * using http_build_query().
	 *
	 * The only URI that are supported in the HTTP Transport implementation are the HTTP and HTTPS
	 * protocols. HTTP and HTTPS are assumed so the server might not know how to handle the send
	 * headers. Other protocols are unsupported and most likely will fail.
	 *
	 * The defaults are 'method', 'timeout', 'redirection', 'httpversion', 'blocking' and
	 * 'user-agent'.
	 *
	 * Accepted 'method' values are 'GET', 'POST', and 'HEAD', some transports technically allow
	 * others, but should not be assumed. The 'timeout' is used to sent how long the connection
	 * should stay open before failing when no response. 'redirection' is used to track how many
	 * redirects were taken and used to sent the amount for other transports, but not all transports
	 * accept setting that value.
	 *
	 * The 'httpversion' option is used to sent the HTTP version and accepted values are '1.0', and
	 * '1.1' and should be a string. Version 1.1 is not supported, because of chunk response. The
	 * 'user-agent' option is the user-agent and is used to replace the default user-agent, which is
	 * 'LyftenBloggie/Version'.
	 *
	 * 'blocking' is the default, which is used to tell the transport, whether it should halt PHP
	 * while it performs the request or continue regardless. Actually, that isn't entirely correct.
	 * Blocking mode really just means whether the fread should just pull what it can whenever it
	 * gets bytes or if it should wait until it has enough in the buffer to read or finishes reading
	 * the entire content. It doesn't actually always mean that PHP will continue going after making
	 * the request.
	 *
	 * @since 1.0.2
	 **/
	function request( $url, $args = array() )
	{
		$settings 	= & BloggieSettings::getInstance();

		$defaults = array(
			'method' 		=> 'GET',
			'timeout' 		=> $this->http_request_timeout,
			'redirection' 	=> $this->http_request_redirection_count,
			'httpversion' 	=> $this->http_request_version,
			'user-agent' 	=> $this->http_headers_useragent.'/'.$this->http_headers_useragent_ver,
			'blocking' 		=> true,
			'headers' 		=> array(),
			'body' 			=> null,
			'compress' 		=> false,
			'decompress' 	=> true,
			'sslverify' 	=> true
		);

		//Set Args
		$r 		= parseArgs( $args, $defaults );
		$arrURL = parse_url($url);

		// Determine if this is a https call and pass that on to the transport functions
		// so that we can blacklist the transports that do not support ssl verification
		$r['ssl'] = $arrURL['scheme'] == 'https' || $arrURL['scheme'] == 'ssl';

		// Determine if this request is to OUR server
		$homeURL = parse_url($this->rootURL);
		$r['local'] = $homeURL['host'] == $arrURL['host'] || 'localhost' == $arrURL['host'];
		unset($homeURL);

		if ( is_null( $r['headers'] ) )
			$r['headers'] = array();

		if ( ! is_array($r['headers']) )
		{
			$processedHeaders = BloggieHttp::processHeaders($r['headers']);
			$r['headers'] = $processedHeaders['headers'];
		}

		if ( isset($r['headers']['User-Agent']) )
		{
			$r['user-agent'] = $r['headers']['User-Agent'];
			unset($r['headers']['User-Agent']);
		}

		if ( isset($r['headers']['user-agent']) ) {
			$r['user-agent'] = $r['headers']['user-agent'];
			unset($r['headers']['user-agent']);
		}

		if ( BloggieHttp_Encoding::is_available() )
			$r['headers']['Accept-Encoding'] = BloggieHttp_Encoding::accept_encoding();

		if ( is_null($r['body']) ) {
			// Some servers fail when sending content without the content-length
			// header being set.
			$r['headers']['Content-Length'] = 0;
			$transports = BloggieHttp::_getTransport($r);
		} else {
			if ( is_array( $r['body'] ) || is_object( $r['body'] ) ) {
				if ( ! version_compare(phpversion(), '5.1.2', '>=') )
					$r['body'] = _http_build_query($r['body'], null, '&');
				else
					$r['body'] = http_build_query($r['body'], null, '&');
				$r['headers']['Content-Type'] = 'application/x-www-form-urlencoded; charset=' . $settings->get('charset', 'UTF-8');
				$r['headers']['Content-Length'] = strlen($r['body']);
			}

			if ( ! isset( $r['headers']['Content-Length'] ) && ! isset( $r['headers']['content-length'] ) )
				$r['headers']['Content-Length'] = strlen($r['body']);

			$transports = BloggieHttp::_postTransport($r);
		}

		if ( $this->http_api_debug )
			echo 'TRANSPORTS_LIST: '.$transports.'<br>';

		$response = array( 'headers' => array(), 'body' => '', 'response' => array('code' => false, 'message' => false) );
		foreach ( (array) $transports as $transport )
		{
			$response = $transport->request($url, $r);

			if ( $this->http_api_debug )
			{
				echo 'RESPONSE: '.$response.'<br>';
				echo 'CLASS: '.get_class($transport).'<br>';
			}

			if ( !isset($response['error']) )
				return $response;
		}

		return false;
	}

	/**
	 * Uses the POST HTTP method.
	 * @since 1.0.2
	 **/
	function post($url, $args = array())
	{
		$defaults = array('method' => 'POST');
		$r = parseArgs( $args, $defaults );
		$r['body']['key'] = $this->key;

		return $this->request($url, $r);
	}

	/**
	 * Uses the GET HTTP method.
	 * @since 1.0.2
	 **/
	function get($url, $args = array())
	{
		$defaults = array('method' => 'GET');
		$r = parseArgs( $args, $defaults );
		return $this->request($url, $r);
	}

	/**
	 * Uses the HEAD HTTP method.
	 * @since 1.0.2
	 **/
	function head($url, $args = array())
	{
		$defaults = array('method' => 'HEAD');
		$r = parseArgs( $args, $defaults );
		return $this->request($url, $r);
	}

	/**
	 * Parses the responses and splits the parts into headers and body.
	 * @since 1.0.2
	 **/
	function processResponse($strResponse)
	{
		list($theHeaders, $theBody) = explode("\r\n\r\n", $strResponse, 2);
		return array('headers' => $theHeaders, 'body' => $theBody);
	}

	/**
	 * Transform header string into an array.
	 * @since 1.0.2
	 **/
	function processHeaders($headers)
	{
		// split headers, one per array element
		if ( is_string($headers) )
		{
			// tolerate line terminator: CRLF = LF (RFC 2616 19.3)
			$headers = str_replace("\r\n", "\n", $headers);
			// unfold folded header fields. LWS = [CRLF] 1*( SP | HT ) <US-ASCII SP, space (32)>, <US-ASCII HT, horizontal-tab (9)> (RFC 2616 2.2)
			$headers = preg_replace('/\n[ \t]/', ' ', $headers);
			// create the headers array
			$headers = explode("\n", $headers);
		}

		$response = array('code' => 0, 'message' => '');

		$newheaders = array();
		foreach ( $headers as $tempheader ) {
			if ( empty($tempheader) )
				continue;

			if ( false === strpos($tempheader, ':') ) {
				list( , $iResponseCode, $strResponseMsg) = explode(' ', $tempheader, 3);
				$response['code'] = $iResponseCode;
				$response['message'] = $strResponseMsg;
				continue;
			}

			list($key, $value) = explode(':', $tempheader, 2);

			if ( !empty( $value ) ) {
				$key = strtolower( $key );
				if ( isset( $newheaders[$key] ) ) {
					$newheaders[$key] = array( $newheaders[$key], trim( $value ) );
				} else {
					$newheaders[$key] = trim( $value );
				}
			}
		}

		return array('response' => $response, 'headers' => $newheaders);
	}

	/**
	 * Decodes chunk transfer-encoding, based off the HTTP 1.1 specification.
	 * @since 1.0.2
	 **/
	function chunkTransferDecode($body)
	{
		$body = str_replace(array("\r\n", "\r"), "\n", $body);
		// The body is not chunked encoding or is malformed.
		if ( ! preg_match( '/^[0-9a-f]+(\s|\n)+/mi', trim($body) ) )
			return $body;

		$parsedBody = '';
		//$parsedHeaders = array(); Unsupported

		while ( true )
		{
			$hasChunk = (bool) preg_match( '/^([0-9a-f]+)(\s|\n)+/mi', $body, $match );

			if ( $hasChunk ) {
				if ( empty( $match[1] ) )
					return $body;

				$length = hexdec( $match[1] );
				$chunkLength = strlen( $match[0] );

				$strBody = substr($body, $chunkLength, $length);
				$parsedBody .= $strBody;

				$body = ltrim(str_replace(array($match[0], $strBody), '', $body), "\n");

				if ( "0" == trim($body) )
					return $parsedBody; // Ignore footer headers.
			} else {
				return $body;
			}
		}
	}

	/**
	 * Retrieve the description for the HTTP status.
	 * @since 1.0.2
	 **/
	function getStatusHeaderDesc( $code )
	{
		static $HeadertoDesc;

		if ( !isset( $HeadertoDesc ) ) {
			$HeadertoDesc = array(
				100 => JText::_('CONTINUE'),
				101 => JText::_('SWITCHING PROTOCOLS'),
				102 => JText::_('PROCESSING'),

				200 => JText::_('OK'),
				201 => JText::_('CREATED'),
				202 => JText::_('ACCEPTED'),
				203 => JText::_('NONAUTHORITATIVE INFORMATION'),
				204 => JText::_('NO CONTENT'),
				205 => JText::_('RESET CONTENT'),
				206 => JText::_('PARTIAL CONTENT'),
				207 => JText::_('MULTISTATUS'),
				226 => JText::_('IM USED'),

				300 => JText::_('MULTIPLE CHOICES'),
				301 => JText::_('MOVED PERMANENTLY'),
				302 => JText::_('FOUND'),
				303 => JText::_('SEE OTHER'),
				304 => JText::_('NOT MODIFIED'),
				305 => JText::_('USE PROXY'),
				306 => JText::_('RESERVED'),
				307 => JText::_('TEMPORARY REDIRECT'),

				400 => JText::_('BAD REQUEST'),
				401 => JText::_('UNAUTHORIZED'),
				402 => JText::_('PAYMENT REQUIRED'),
				403 => JText::_('FORBIDDEN'),
				404 => JText::_('FILE NOT FOUND'),
				405 => JText::_('METHOD NOT ALLOWED'),
				406 => JText::_('NOT ACCEPTABLE'),
				407 => JText::_('PROXY AUTHENTICATION REQUIRED'),
				408 => JText::_('REQUEST TIMEOUT'),
				409 => JText::_('CONFLICT'),
				410 => JText::_('GONE'),
				411 => JText::_('LENGTH REQUIRED'),
				412 => JText::_('PRECONDITION FAILED'),
				413 => JText::_('REQUEST ENTITY TOO LARGE'),
				414 => JText::_('REQUESTURI TOO LONG'),
				415 => JText::_('UNSUPPORTED MEDIA TYPE'),
				416 => JText::_('REQUESTED RANGE NOT SATISFIABLE'),
				417 => JText::_('EXPECTATION FAILED'),
				422 => JText::_('UNPROCESSABLE ENTITY'),
				423 => JText::_('LOCKED'),
				424 => JText::_('FAILED DEPENDENCY'),
				426 => JText::_('UPGRADE REQUIRED'),

				500 => JText::_('INTERNAL SERVER ERROR'),
				501 => JText::_('NOT IMPLEMENTED'),
				502 => JText::_('BAD GATEWAY'),
				503 => JText::_('SERVICE UNAVAILABLE'),
				504 => JText::_('GATEWAY TIMEOUT'),
				505 => JText::_('HTTP VERSION NOT SUPPORTED'),
				506 => JText::_('VARIANT ALSO NEGOTIATES'),
				507 => JText::_('INSUFFICIENT STORAGE'),
				510 => JText::_('NOT EXTENDED')
			);
		}

		if ( isset( $HeadertoDesc[$code] ) )
			return $HeadertoDesc[$code];
		else
			return '';
	}
}

/**
 * HTTP request method uses Streams to retrieve the url.
 *
 * Requires PHP 5.0+ and uses fopen with stream context. Requires that 'allow_url_fopen' PHP setting
 * to be enabled.
 *
 * Second preferred method for getting the URL, for PHP 5.
 *
 * @package LyftenBloggie
 * @subpackage HTTP
 * @since 1.0.2
 */
class BloggieHttp_Streams {
	/**
	 * Send a HTTP request to a URI using streams with fopen().
	 *
	 * @access public
	 * @since 2.7.0
	 *
	 * @param string $url
	 * @param str|array $args Optional. Override the defaults.
	 * @return array 'headers', 'body' and 'response' keys.
	 */
	function request($url, $args = array())
	{
		$defaults = array(
			'method' => 'GET', 'timeout' => 5,
			'redirection' => 5, 'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(), 'body' => null, 'cookies' => array()
		);

		$r = parseArgs( $args, $defaults );

		if ( isset($r['headers']['User-Agent']) ) {
			$r['user-agent'] = $r['headers']['User-Agent'];
			unset($r['headers']['User-Agent']);
		} else if( isset($r['headers']['user-agent']) ) {
			$r['user-agent'] = $r['headers']['user-agent'];
			unset($r['headers']['user-agent']);
		}

		$arrURL = parse_url($url);

		if ( false === $arrURL )
			return array( 'error' => sprintf('Malformed URL: %s', $url) );

		if ( 'http' != $arrURL['scheme'] && 'https' != $arrURL['scheme'] )
			$url = preg_replace('|^' . preg_quote($arrURL['scheme'], '|') . '|', 'http', $url);

		// Convert Header array to string.
		$strHeaders = '';
		if ( is_array( $r['headers'] ) )
			foreach ( $r['headers'] as $name => $value )
				$strHeaders .= "{$name}: $value\r\n";
		else if ( is_string( $r['headers'] ) )
			$strHeaders = $r['headers'];

		$is_local = isset($args['local']) && $args['local'];
		$ssl_verify = isset($args['sslverify']) && $args['sslverify'];
		if ( $is_local )
			$ssl_verify = $ssl_verify;
		elseif ( ! $is_local )
			$ssl_verify = $ssl_verify;

		$arrContext = array('http' =>
			array(
				'method' => strtoupper($r['method']),
				'user_agent' => $r['user-agent'],
				'max_redirects' => $r['redirection'],
				'protocol_version' => (float) $r['httpversion'],
				'header' => $strHeaders,
				'timeout' => $r['timeout'],
				'ssl' => array(
						'verify_peer' => $ssl_verify,
						'verify_host' => $ssl_verify
				)
			)
		);

		if ( ! is_null($r['body']) && ! empty($r['body'] ) )
			$arrContext['http']['content'] = $r['body'];

		$context = stream_context_create($arrContext);

		if ( !JDEBUG )
			$handle = @fopen($url, 'r', false, $context);
		else
			$handle = fopen($url, 'r', false, $context);

		if ( ! $handle)
			return array( 'error' => sprintf('Could not open handle for fopen() to %s', $url) );

		$timeout = (int) floor( $r['timeout'] );
		$utimeout = $timeout == $r['timeout'] ? 0 : 1000000 * $r['timeout'] % 1000000;
		stream_set_timeout( $handle, $timeout, $utimeout );

		if ( ! $r['blocking'] ) {
			stream_set_blocking($handle, 0);
			fclose($handle);
			return array( 'headers' => array(), 'body' => '', 'response' => array('code' => false, 'message' => false), 'cookies' => array() );
		}

		$strResponse = stream_get_contents($handle);
		$meta = stream_get_meta_data($handle);

		fclose($handle);

		$processedHeaders = array();
		if ( isset( $meta['wrapper_data']['headers'] ) )
			$processedHeaders = BloggieHttp::processHeaders($meta['wrapper_data']['headers']);
		else
			$processedHeaders = BloggieHttp::processHeaders($meta['wrapper_data']);

		if ( ! empty( $strResponse ) && isset( $processedHeaders['headers']['transfer-encoding'] ) && 'chunked' == $processedHeaders['headers']['transfer-encoding'] )
			$strResponse = BloggieHttp::chunkTransferDecode($strResponse);

		if ( true === $r['decompress'] && true === BloggieHttp_Encoding::should_decode($processedHeaders['headers']) )
			$strResponse = BloggieHttp_Encoding::decompress( $strResponse );

		return array('headers' => $processedHeaders['headers'], 'body' => $strResponse, 'response' => $processedHeaders['response'], 'cookies' => $processedHeaders['cookies']);
	}

	/**
	 * Whether this class can be used for retrieving an URL.
	 *
	 * @static
	 * @access public
	 * @since 2.7.0
	 *
	 * @return boolean False means this class can not be used, true means it can.
	 */
	function test($args = array()) {
		if ( ! function_exists('fopen') || (function_exists('ini_get') && true != ini_get('allow_url_fopen')) )
			return false;

		if ( version_compare(PHP_VERSION, '5.0', '<') )
			return false;

		//HTTPS via Proxy was added in 5.1.0
		$is_ssl = isset($args['ssl']) && $args['ssl'];


		return true;
	}
}

/**
 * HTTP request method uses HTTP extension to retrieve the url.
 *
 * Requires the HTTP extension to be installed. This would be the preferred transport since it can
 * handle a lot of the problems that forces the others to use the HTTP version 1.0. Even if PHP 5.2+
 * is being used, it doesn't mean that the HTTP extension will be enabled.
 *
 * @package LyftenBloggie
 * @subpackage HTTP
 * @since 1.0.2
 **/
class BloggieHttp_ExtHTTP
{
	/**
	 * Send a HTTP request to a URI using HTTP extension.
	 * @since 1.0.2
	 **/
	function request($url, $args = array())
	{

		$defaults = array(
			'method' => 'GET', 'timeout' => 5,
			'redirection' => 5, 'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(), 'body' => null
		);

		$r = parseArgs( $args, $defaults );

		if ( isset($r['headers']['User-Agent']) ) {
			$r['user-agent'] = $r['headers']['User-Agent'];
			unset($r['headers']['User-Agent']);
		} else if( isset($r['headers']['user-agent']) ) {
			$r['user-agent'] = $r['headers']['user-agent'];
			unset($r['headers']['user-agent']);
		}

		switch ( $r['method'] ) {
			case 'POST':
				$r['method'] = HTTP_METH_POST;
				break;
			case 'HEAD':
				$r['method'] = HTTP_METH_HEAD;
				break;
			case 'GET':
			default:
				$r['method'] = HTTP_METH_GET;
		}

		$arrURL = parse_url($url);
		if ( 'http' != $arrURL['scheme'] || 'https' != $arrURL['scheme'] )
			$url = preg_replace('|^' . preg_quote($arrURL['scheme'], '|') . '|', 'http', $url);

		$is_local = isset($args['local']) && $args['local'];
		$ssl_verify = isset($args['sslverify']) && $args['sslverify'];

		$options = array(
			'timeout' => $r['timeout'],
			'connecttimeout' => $r['timeout'],
			'redirect' => $r['redirection'],
			'useragent' => $r['user-agent'],
			'headers' => $r['headers'],
			'ssl' => array(
				'verifypeer' => $ssl_verify,
				'verifyhost' => $ssl_verify
			)
		);

		if ( !defined('JDEBUG') || ( defined('JDEBUG') && false === JDEBUG ) ) //Emits warning level notices for max redirects and timeouts
			$strResponse = @http_request($r['method'], $url, $r['body'], $options, $info);
		else
			$strResponse = http_request($r['method'], $url, $r['body'], $options, $info); //Emits warning level notices for max redirects and timeouts

		// Error may still be set, Response may return headers or partial document, and error
		// contains a reason the request was aborted, eg, timeout expired or max-redirects reached.
		if ( false === $strResponse || !empty($info['error']) )
		{
			return array( 'error' => $info['error'] );
		}

		if ( ! $r['blocking'] )
			return array( 'headers' => array(), 'body' => '', 'response' => array('code' => false, 'message' => false) );

		list($theHeaders, $theBody) = explode("\r\n\r\n", $strResponse, 2);
		$theHeaders = BloggieHttp::processHeaders($theHeaders);

		if ( ! empty( $theBody ) && isset( $theHeaders['headers']['transfer-encoding'] ) && 'chunked' == $theHeaders['headers']['transfer-encoding'] ) {
			if ( !defined('JDEBUG') || ( defined('JDEBUG') && false === JDEBUG ) )
				$theBody = @http_chunked_decode($theBody);
			else
				$theBody = http_chunked_decode($theBody);
		}

		if ( true === $r['decompress'] && true === BloggieHttp_Encoding::should_decode($theHeaders['headers']) )
			$theBody = http_inflate( $theBody );

		$theResponse = array();
		$theResponse['code'] = $info['response_code'];
		$theResponse['message'] = BloggieHttp::getStatusHeaderDesc($info['response_code']);

		return array('headers' => $theHeaders['headers'], 'body' => $theBody, 'response' => $theResponse);
	}

	/**
	 * Whether this class can be used for retrieving an URL.
	 * @since 1.0.2
	 **/
	function test($args = array()) {
		return function_exists('http_request');
	}
}

/**
 * HTTP request method uses Curl extension to retrieve the url.
 *
 * Requires the Curl extension to be installed.
 *
 * @package LyftenBloggie
 * @subpackage HTTP
 * @since 1.0.2
 */
class BloggieHttp_Curl
{
	/**
	 * Send a HTTP request to a URI using cURL extension.
	 * @since 1.0.2
	 **/
	function request($url, $args = array())
	{
		$defaults = array(
			'method' => 'GET', 'timeout' => 5,
			'redirection' => 5, 'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(), 'body' => null
		);

		$r = parseArgs( $args, $defaults );

		$errors = null;

		if ( isset($r['headers']['User-Agent']) ) {
			$r['user-agent'] = $r['headers']['User-Agent'];
			unset($r['headers']['User-Agent']);
		} else if( isset($r['headers']['user-agent']) ) {
			$r['user-agent'] = $r['headers']['user-agent'];
			unset($r['headers']['user-agent']);
		}

		// cURL extension will sometimes fail when the timeout is less than 1 as it may round down
		// to 0, which gives it unlimited timeout.
		if ( $r['timeout'] > 0 && $r['timeout'] < 1 )
			$r['timeout'] = 1;

		$handle = curl_init();

		$is_local = isset($args['local']) && $args['local'];
		$ssl_verify = isset($args['sslverify']) && $args['sslverify'];

		curl_setopt( $handle, CURLOPT_URL, $url);
		curl_setopt( $handle, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $handle, CURLOPT_SSL_VERIFYHOST, $ssl_verify );
		curl_setopt( $handle, CURLOPT_SSL_VERIFYPEER, $ssl_verify );
		curl_setopt( $handle, CURLOPT_USERAGENT, $r['user-agent'] );
		curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, $r['timeout'] );
		curl_setopt( $handle, CURLOPT_TIMEOUT, $r['timeout'] );
		curl_setopt( $handle, CURLOPT_MAXREDIRS, $r['redirection'] );

		switch ( $r['method'] ) {
			case 'HEAD':
				curl_setopt( $handle, CURLOPT_NOBODY, true );
				break;
			case 'POST':
				curl_setopt( $handle, CURLOPT_POST, true );
				curl_setopt( $handle, CURLOPT_POSTFIELDS, $r['body'] );
				break;
		}

		if ( true === $r['blocking'] )
			curl_setopt( $handle, CURLOPT_HEADER, true );
		else
			curl_setopt( $handle, CURLOPT_HEADER, false );

		// The option doesn't work with safe mode or when open_basedir is set.
		if ( !ini_get('safe_mode') && !ini_get('open_basedir') )
			curl_setopt( $handle, CURLOPT_FOLLOWLOCATION, true );

		if ( !empty( $r['headers'] ) ) {
			// cURL expects full header strings in each element
			$headers = array();
			foreach ( $r['headers'] as $name => $value ) {
				$headers[] = "{$name}: $value";
			}
			curl_setopt( $handle, CURLOPT_HTTPHEADER, $headers );
		}

		if ( $r['httpversion'] == '1.0' )
			curl_setopt( $handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );
		else
			curl_setopt( $handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );

		// We don't need to return the body, so don't. Just execute request and return.
		if ( ! $r['blocking'] ) {
			curl_exec( $handle );
			curl_close( $handle );
			return array( 'headers' => array(), 'body' => '', 'response' => array('code' => false, 'message' => false) );
		}

		$theResponse = curl_exec( $handle );

		if ( !empty($theResponse) ) {
			$parts = explode("\r\n\r\n", $theResponse);

			$headerLength = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
			$theHeaders = trim( substr($theResponse, 0, $headerLength) );
			$theBody = substr( $theResponse, $headerLength );
			if ( false !== strrpos($theHeaders, "\r\n\r\n") ) {
				$headerParts = explode("\r\n\r\n", $theHeaders);
				$theHeaders = $headerParts[ count($headerParts) -1 ];
			}
			$theHeaders = BloggieHttp::processHeaders($theHeaders);
		} else {
			if ( $curl_error = curl_error($handle) )
			{
				$errors = $curl_error;
			}
			if ( in_array( curl_getinfo( $handle, CURLINFO_HTTP_CODE ), array(301, 302) ) )
			{
				$errors = 'Too many redirects.';
			}
			$theHeaders = array( 'headers' => array() );
			$theBody = '';
		}

		$response = array();
		$response['code'] = curl_getinfo( $handle, CURLINFO_HTTP_CODE );
		$response['message'] = BloggieHttp::getStatusHeaderDesc($response['code']);

		curl_close( $handle );

		if ( true === $r['decompress'] && true === BloggieHttp_Encoding::should_decode($theHeaders['headers']) )
			$theBody = BloggieHttp_Encoding::decompress( $theBody );

		return array('headers' => $theHeaders['headers'], 'body' => $theBody, 'response' => $response, 'error' => $errors);
	}

	/**
	 * Whether this class can be used for retrieving an URL.
	 * @since 1.0.2
	 **/
	function test($args = array()) {
		if ( function_exists('curl_init') && function_exists('curl_exec') )
			return true;

		return false;
	}
}

/**
 * Implementation for deflate and gzip transfer encodings.
 *
 * Includes RFC 1950, RFC 1951, and RFC 1952.
 *
 * @package LyftenBloggie
 * @subpackage HTTP
 * @since 1.0.2
 **/
class BloggieHttp_Encoding
{
	/**
	 * Compress raw string using the deflate format.
	 * @since 1.0.2
	 **/
	function compress( $raw, $level = 9, $supports = null ) {
		return gzdeflate( $raw, $level );
	}

	/**
	 * Decompression of deflated string.
	 * @since 1.0.2
	 **/
	function decompress( $compressed, $length = null ) {
		$decompressed = gzinflate( $compressed );

		if ( false !== $decompressed )
			return $decompressed;

		$decompressed = gzuncompress( $compressed );

		if ( false !== $decompressed )
			return $decompressed;

		if ( function_exists('gzdecode') ) {
			$decompressed = gzdecode( $compressed );

			if ( false !== $decompressed )
				return $decompressed;
		}

		return $compressed;
	}

	/**
	 * What encoding types to accept and their priority values.
	 * @since 1.0.2
	 **/
	function accept_encoding() {
		$type = array();
		if ( function_exists( 'gzinflate' ) )
			$type[] = 'deflate;q=1.0';

		if ( function_exists( 'gzuncompress' ) )
			$type[] = 'compress;q=0.5';

		if ( function_exists( 'gzdecode' ) )
			$type[] = 'gzip;q=0.5';

		return implode(', ', $type);
	}

	/**
	 * What enconding the content used when it was compressed to send in the headers.
	 * @since 1.0.2
	 **/
	function content_encoding() {
		return 'deflate';
	}

	/**
	 * Whether the content be decoded based on the headers.
	 * @since 1.0.2
	 **/
	function should_decode($headers) {
		if ( is_array( $headers ) ) {
			if ( array_key_exists('content-encoding', $headers) && ! empty( $headers['content-encoding'] ) )
				return true;
		} else if( is_string( $headers ) ) {
			return ( stripos($headers, 'content-encoding:') !== false );
		}

		return false;
	}

	/**
	 * Whether decompression and compression are supported by the PHP version.
	 * @since 1.0.2
	 **/
	function is_available() {
		return ( function_exists('gzuncompress') || function_exists('gzdeflate') || function_exists('gzinflate') );
	}
}

function parseArgs( $args, $defaults = '' ) {
	if ( is_object( $args ) )
	{
		$r = get_object_vars( $args );
	}elseIf ( is_array( $args ) )
	{
		$r =& $args;
	}else{
		parse_str( $string, $r );
		if ( get_magic_quotes_gpc() )
			$r = stripslashes_deep( $r );
	}
	if ( is_array( $defaults ) )
		return array_merge( $defaults, $r );
	return $r;
}
?>