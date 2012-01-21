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
 * LyftenBloggie Framework Feed class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieUpdates extends JObject
{
	var $_paths	= array();

	function BloggieUpdates()
	{
		// Set the path definitions
		$this->set('cache', JPath::clean(JPATH_ROOT.DS.'cache'.DS.'com_lyftenbloggie'));
	}

	/**
	 * Returns a reference to a global Settings object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		$settings = & BloggieUpdates::getInstance();
	 *
	 * @access	public
	 * @return	BloggieUpdates object.
	 */
	function &getInstance()
	{
		static $instance;

		if (!isset($instance)) {
			$instance = new BloggieUpdates();
		}

		return $instance;
	}

	/**
	 * Get author value
	 *
	 * @param	string	setting's name
	 * @param	string	default value
	 * @return	string
	 **/
	function get($variable, $default=null)
	{
		return isset($this->_paths[$variable]) ? $this->_paths[$variable] : $default;
	}

	/**
	 * Set author value
	 *
	 * @param	string	variable
	 * @param	string	value
	 * @return	void
	 **/
	function set($variable, $value=null)
	{
		$this->_paths[$variable] = $value;
	}

	/**
	 * method check author's folders
	 *
	 * @access	public
	 * @return	viod
	 */
	function checkFolder()
	{
		//Check Author's Upload folder
		if (!is_dir($this->get('cache'))){
			@mkdir($this->get('cache'), 0755);
		}
		if (!file_exists($this->get('cache').DS.'index.html')) {
			chmod($this->get('cache'), 0777);
			$handle = fopen($this->get('cache').DS.'index.html', 'x+');
			fclose($handle);
		}
	}

	/**
	 * Load up an RSS feed, parse its contents and return it.
	 *
	 * @param	string	feed url
	 * @param	string	file id
	 * @param	string	cache time
	 * @return	array
	 */
	public function load($FeedURL, $type = null, $CacheTime = 0)
	{
		// Initialize variables
		$feed 		= BloggieFactory::load('rss', 'output');
		$FeedId 	= ($type) ? 'update-'.$type.'.xml' : 'update.xml';
		$reload 	= true;
		$output 	= array();

		//Check for Cached file
		if($CacheTime > 0)
		{
			//Check cache folder
			$this->checkFolder();

			// Using a cached version that hasn't expired yet
			if(file_exists($this->get('cache').DS.$FeedId) && filemtime($this->get('cache').DS.$FeedId) > time()-$CacheTime)
			{
				$contents = file_get_contents($this->get('cache').DS.$FeedId);

				// Cache was good, load it!
				if($contents) {
					$feed->loadXML($contents);

					//get data array
					$output = $feed->getValues();

					return $output;
				}
			}
		}

		//Load URL
		if ($reload === true)
		{
			$objFetchSite 	= & BloggieFactory::load('http');

			$args = array('type'=>$type, 'product'=>'2', 'version'=>BLOGGIE_COM_VERSION);
			if($request = $objFetchSite->post($FeedURL, array( 'body' => array('task' => 'updates', 'request' => serialize($args)))))
			{
				if($feed->loadXML($request['body']))
				{
					//Get XML Data
					$contents = $feed->getXML();

					// Do we need to cache this version?
					if ($CacheTime > 0 && $contents != "")
					{
						//Check cache folder
						$this->checkFolder();

						@file_put_contents($this->get('cache').DS.$FeedId, $contents);
					}

					//get data array
					$output = $feed->getValues();
				}
				return $output;
			}
		}
		return $output;
	}
}
?>