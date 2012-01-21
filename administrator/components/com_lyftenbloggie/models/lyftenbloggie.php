<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * @package Joomla
 * @subpackage JomProducts
 * @since 1.0
 */
class LyftenBloggieModelLyftenBloggie extends JModel
{
	var $_data = null;

	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();
	}

	function getLocalBuild()
	{
		$tmpArray		= explode( '.' , BLOGGIE_COM_VERSION );

		if( isset($tmpArray[2]) )
		{
			return $tmpArray[2];
		}
		
		// Unknown build number.
		return 0;
	}

	function getLocalVersion()
	{
		$tmpArray		= explode( '.' , BLOGGIE_COM_VERSION );

		if( isset($tmpArray[0] ) && isset( $tmpArray[1] ) )
		{
			return doubleval( $tmpArray[0] . '.' . $tmpArray[1] ); 
		}
		return 0;
	}

	function getIncomingLinks()
	{
		global $mainframe;
		
		$rssurl='http://blogsearch.google.com/blogsearch_feeds?hl=en&scoring=d&ie=utf-8&num=10&output=rss&partner=wordpress&q=link:'.$mainframe->getSiteURL();

		//Get Feed Instance
		$feed = BloggieFactory::getFeed();
		if(!$feed->load($rssurl))
		{
			return array();
		}
		$items = $feed->getItems();
		return $items;
	}

	/**
	 * Method to get general stats
	 **/
	function getGeneralstats()
	{
		$_products 	= array();
		$settings	=& BloggieSettings::getInstance();

		/*
		* Get total number of entries
		*/
		$query = 'SELECT count(id)'
					. ' FROM #__bloggies_entries'
					;

		$this->_db->SetQuery($query);
  		$_products['total'] = $this->_db->loadResult();

 		/*
		* Get total number of pending entries
		*/
		$query = 'SELECT count(id)'
					. ' FROM #__bloggies_entries'
					. " WHERE state = 2"
					;

		$this->_db->SetQuery($query);
  		$_products['pending'] = $this->_db->loadResult();

 		/*
		* Get total number of approved entries
		*/
		$query = 'SELECT count(id)'
					. ' FROM #__bloggies_entries'
					. " WHERE state = 1"
					;

		$this->_db->SetQuery($query);
  		$_products['approved'] = $this->_db->loadResult();
  		
		/*
		* Get total number of tags
		*/
		$query = 'SELECT count(id)'
					. ' FROM #__bloggies_tags'
					;

		$this->_db->SetQuery($query);
  		$_products['tags'] = $this->_db->loadResult();
  		
  		/*
		* Get number of all trackback
		*/
		$query = "SELECT count(id)"
					. " FROM #__bloggies_comments"
					. " WHERE type = 2"
					. " AND state = 1"
					;

		$this->_db->SetQuery($query);
  		$_products['trackback'] = $this->_db->loadResult();

 		/*
		* Get number of pending comments
		*/
		$query = "SELECT count(id)"
					. " FROM #__bloggies_comments"
					. " WHERE type = 1"
					. " AND state = -1"
					;

		$this->_db->SetQuery($query);
  		$_products['compending'] = $this->_db->loadResult();

		if($settings->get( 'spamCheck' ))
		{
			/*
			* Get number of comment spam
			*/
			$query = "SELECT count(id)"
						. " FROM #__bloggies_comments"
						. " WHERE type = 1"
						. " AND state = 0"
						;

			$this->_db->SetQuery($query);
			$_products['comspam'] = $this->_db->loadResult();
		}

  		/*
		* Get number of all Reports
		*/
		$query = "SELECT count(id)"
					. " FROM #__bloggies_reports"
					;

		$this->_db->SetQuery($query);
  		$_products['reports'] = $this->_db->loadResult();
  		
  		/*
		* Get number of all categories
		*/
		$query = "SELECT count(id)"
					. " FROM #__bloggies_categories"
					. " WHERE published = '1'"
					;

		$this->_db->SetQuery($query);
  		$_products['categories'] = $this->_db->loadResult();
  		
  		/*
		* Get number of all comments
		*/
		$query = "SELECT count(id)"
					. " FROM #__bloggies_comments"
					. " WHERE type = '1'"
					;

		$this->_db->SetQuery($query);
  		$_products['comments'] = $this->_db->loadResult();
  		
		return $_products;
	}

	/**
	 * Method to get popular data
	 **/
	function getPopular()
	{
		$query = 'SELECT id, title, hits'
				. ' FROM #__bloggies_entries'
				. ' ORDER BY hits DESC'
				. ' LIMIT 5'
				;

		$this->_db->SetQuery($query);
  		$hits = $this->_db->loadObjectList();
  		
  		return $hits;
	}
}
?>