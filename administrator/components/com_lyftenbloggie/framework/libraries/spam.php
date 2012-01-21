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
 * LyftenBloggie Akismet class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieSpam extends JObject
{
	var $url;
	var $_key;

	function BloggieSpam()
	{
		$settings 		= & BloggieSettings::getInstance();
		$this->_key  	= $settings->get('AkismetApi');
		$this->url 		= array(
				'check' => 'http://'.$this->_key.'.rest.akismet.com/1.1/comment-check',
				'spam'  => 'http://'.$this->_key.'.rest.akismet.com/1.1/submit-spam',
				'ham'   => 'http://'.$this->_key.'.rest.akismet.com/1.1/submit-ham',
				'verify'=> 'http://'.$this->_key.'.rest.akismet.com/1.1/verify-key'
			);
	}
	
	function _getRequest($type, $params=array())
	{
		$objFetchSite 	= & BloggieFactory::load('http');
		$response		= 'error';

		$query_string = '';
		foreach ( $params as $key => $data )
			$query_string .= $key . '=' . urlencode( stripslashes($data) ) . '&';

		$args = array(
			'method'=>'POST',
			'body'=>$query_string,
			'headers' => array('user-agent'=>"LyftenBloggie/".BLOGGIE_COM_VERSION." | Akismet/".BLOGGIE_COM_VERSION)
		);
		
		//Send Request
		if($request = $objFetchSite->post($this->url[$type], $args))
		{
			$response = $request['body'];

			//Translate Responce
			$trans_re = JText::_('AKISMET_'.strtoupper($response));
			if($trans_re != 'AKISMET_'.strtoupper($response))
			{
				$this->setError($trans_re);
			}
		}else{
			$this->setError(JText::_('AKISMET_NO_CONNECTION'));
		}
		return $response;
	}

	function isspam($ip='',$author='',$email='',$url='',$content='',$type='comment')
	{
		$lang = &JFactory::getLanguage();
		$params = array(
			'blog' => JURI::root(),
			'user_ip' => $ip,
			'user_agent' => @$_SERVER['HTTP_USER_AGENT'],
			'referrer' => @$_SERVER['HTTP_REFERER'],
			'comment_type' => $type,
			'comment_author' => $author,
			'comment_author_email' => $email,
			'comment_author_url' => $url,
			'comment_content' => $content
		);

		return $this->_getRequest('check', $params) == 'true';
	}

	function addham($ip='',$author='',$email='',$url='',$content='',$type='comment')
	{
		$params = array(
			'blog' => JURI::root(),
			'user_ip' => $ip,
			'user_agent' => @$_SERVER['HTTP_USER_AGENT'],
			'referrer' => @$_SERVER['HTTP_REFERER'],
			'comment_type' => $type,
			'comment_author' => $author,
			'comment_author_email' => $email,
			'comment_author_url' => $url,
			'comment_content' => $content
		);

		return $this->_getRequest('ham',$params) == 'true';
	}

	function addspam($ip='',$author='',$email='',$url='',$content='',$type='comment')
	{
		$params = array(
			'blog' => JURI::root(),
			'user_ip' => $ip,
			'user_agent' => @$_SERVER['HTTP_USER_AGENT'],
			'referrer' => @$_SERVER['HTTP_REFERER'],
			'comment_type' => $type,
			'comment_author' => $author,
			'comment_author_email' => $email,
			'comment_author_url' => $url,
			'comment_content' => $content
		);

		return $this->_getRequest('spam',$params) == 'true';
	}

	function disable()
	{
		$settings = & BloggieSettings::getInstance();
		$settings->update('spamCheck', '0');
	}

	function verify()
	{
		$params = array(
			'key' => $this->_key,
			'blog' => JURI::root()
		);

		//No Key!
		if(!$this->_key)
		{
			$this->disable();
			$this->setError(JText::_('AKISMET_EMPTY'));
			return false;
		}

		if($this->_getRequest('verify', $params) != 'valid')
		{
			$this->disable();
			return false;
		}else{
			return true;
		}
	}
}