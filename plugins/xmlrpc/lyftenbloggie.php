<?php
/**
 * Lyftenbloggie XML-RPC System Plugin
 * @package Lyftenbloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.plugin.plugin' );

class plgXMLRPCLyftenbloggie extends JPlugin
{
	function plgXMLRPCLyftenbloggie(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
	}

	/**
	* @return array An array of associative arrays defining the available methods
	*/
	function onGetWebServices()
	{
		global $xmlrpcI4, $xmlrpcInt, $xmlrpcBoolean, $xmlrpcDouble, $xmlrpcString, $xmlrpcDateTime, $xmlrpcBase64, $xmlrpcArray, $xmlrpcStruct, $xmlrpcValue;

		//Check for LyftenBloggie's XML-RPC Helper
		$path = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie'.DS.'framework'.DS.'core'.DS.'xmlrpc.php';

		if(!file_exists($path)) return array();
		include_once($path);

		// Get LyftenBloggie route
		include_once(JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php');

		return array(
			// WordPress API
			'wp.getUsersBlogs'			=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_getUsersBlogs',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'wp.getAuthors'				=> array( //NOT DONE
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_getAuthors',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'wp.getCategories'			=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mw_getCategories',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'wp.getTags'				=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_getTags',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'wp.newCategory'			=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_newCategory',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcStruct ))
										),
			'wp.deleteCategory'			=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_deleteCategory',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'wp.suggestCategories'		=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_suggestCategories',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'wp.uploadFile'				=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mw_newMediaObject',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'wp.getCommentCount'		=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_getCommentCount',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'wp.getPostStatusList'		=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_getPostStatusList',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'wp.getOptions'				=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_getOptions',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcArray ))
										),
			'wp.setOptions'				=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_setOptions',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcStruct ))
										),
			'wp.getComment'				=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_getComment',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'wp.getComments'			=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_getComments',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcStruct ))
										),
			'wp.deleteComment'			=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_deleteComment',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'wp.editComment'			=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_editComment',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcStruct ))
										),
			'wp.newComment'				=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_newComment',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcStruct ))
										),
			'wp.getCommentStatusList' 	=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::wp_getCommentStatusList',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			// Blogger API
			'blogger.getUsersBlogs' 	=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::blogger_getUsersBlogs',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'blogger.getUserInfo' 		=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::blogger_getUserInfo',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'blogger.getPost' 			=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::blogger_getPost',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'blogger.getRecentPosts' 	=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::blogger_getRecentPosts',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'blogger.newPost' 			=> array( //Not sure if this will work
											'function' => 'plgXMLRPCLyftenbloggieServices::blogger_newPost',
											'docstring' => '',
											'signature' => array(array($xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcBoolean))
										),
			'blogger.editPost' 			=> array( //Not sure if this will work
											'function' => 'plgXMLRPCLyftenbloggieServices::blogger_editPost',
											'docstring' => '',
											'signature' => array(array($xmlrpcBoolean, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcBoolean))
										),
			'blogger.deletePost' 		=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::blogger_deletePost',
											'docstring' => '',
											'signature' => array(array($xmlrpcBoolean, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcBoolean))
										),
			// MetaWeblog API (with MT extensions to structs)
			'metaWeblog.newPost' 		=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mw_newPost',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcStruct, $xmlrpcBoolean ))
										),
			'metaWeblog.editPost' 		=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mw_editPost',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'metaWeblog.getPost' 		=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mw_getPost',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'metaWeblog.getRecentPosts' => array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mw_getRecentPosts',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'metaWeblog.getCategories' 	=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mw_getCategories',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'metaWeblog.newMediaObject' => array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mw_newMediaObject',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),

			// MetaWeblog API aliases for Blogger API
			'metaWeblog.deletePost' 	=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::blogger_deletePost',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'metaWeblog.getUsersBlogs' 	=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::blogger_getUsersBlogs',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),

			// MovableType API
			'mt.getCategoryList' 		=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mt_getCategoryList',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'mt.getRecentPostTitles' 	=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mt_getRecentPostTitles',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'mt.getPostCategories' 		=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mt_getPostCategories',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'mt.setPostCategories' 		=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mt_setPostCategories',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'mt.supportedMethods' 		=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mt_supportedMethods',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'mt.supportedTextFilters' 	=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mt_supportedTextFilters',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),
			'mt.getTrackbackPings' 		=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mt_getTrackbackPings',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										),

			'mt.publishPost' 			=> array(
											'function' => 'plgXMLRPCLyftenbloggieServices::mt_publishPost',
											'docstring' => '',
											'signature' => array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString ))
										)
		);
	}
}

class plgXMLRPCLyftenbloggieServices
{
	/* WordPress API functions.
	 * specs on http://codex.wordpress.org/XML-RPC
	 */

	/**
	 * Retrieve the blogs of the user.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function wp_getUsersBlogs( $appkey, $username, $password )
	{
		global $xmlrpcArray;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$user = $helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_("Sorry, you are not an author."));
		}

		$query = 'SELECT e.id, e.title'
			. ' FROM #__bloggies_entries AS e'
			. ' WHERE e.created_by = \''.(int)$user->get('id').'\''
			. ' ORDER BY e.created DESC';
		$helper->_db->setQuery( $query );

		if (!$blogs = $helper->_db->loadObjectList()) {
			return new xmlrpcresp(0, 401, JText::_( 'Unable to get blogs.' ) );
		}

		$struct = array( );
		foreach( $blogs as $blog )
		{
			$blog = new xmlrpcval(array(
				'isAdmin'		=> new xmlrpcval(BloggieFactory::allowAccess('admin.admin_access')),
				'url'			=> new xmlrpcval($helper->host_url . '/'),
				'blogid'		=> new xmlrpcval($blog->id),
				'blogName'		=> new xmlrpcval($blog->title),
				'xmlrpc'		=> new xmlrpcval($helper->host_url.'/index.php?option=com_lyftenbloggie&amp;task=xmlrpc')
			), 'struct');

			array_push($struct, $blog);
		}

		return new xmlrpcresp(new xmlrpcval( $struct , $xmlrpcArray));
	}
	
	/**[NOT DONE]
	 * Retrieve authors list.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function wp_getAuthors($appkey, $username, $password)
	{
		global $xmlrpcArray;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('admin.admin_access')) {
			return new xmlrpcresp(0, 401, JText::_("Sorry, you cannot edit posts on this blog."));
		}

		$struct = array();
		foreach( (array) get_users_of_blog() as $row )
		{
			$author = new xmlrpcval( array(
					"user_id"       => new xmlrpcval($row->user_id),
					"user_login"    => new xmlrpcval($row->user_login),
					"display_name"  => new xmlrpcval($row->display_name)
				), 'struct' );

			array_push($struct, $author);
		}

		return new xmlrpcresp(new xmlrpcval( $struct , $xmlrpcArray));
	}

	/**
	 * Get list of all tags
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function wp_getTags($appkey, $username, $password )
	{
		global $xmlrpcArray;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if( !BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you must be able to edit posts on this blog in order to view tags.' ) );
		}

		//get tags
		$query = 'SELECT t.*, COUNT(rel.id) AS count'
					. ' FROM #__bloggies_tags AS t'
					. ' LEFT JOIN #__bloggies_relations AS rel ON rel.tag = t.id'
					. ' GROUP BY t.id'
					. ' ORDER BY t.name';
		$helper->_db->setQuery( $query );

		if (!$all_tags = $helper->_db->loadObjectList()) {
			return new xmlrpcresp(0, 401, JText::_( 'Unable to get tags.' ) );
		}

		$struct = array();
		foreach( (array)$all_tags as $tag )
		{
			if($tag->name)
			{
				$tag_struct = new xmlrpcval( array(
						'tag_id'	=> new xmlrpcval($tag->id),
						'name'      => new xmlrpcval($tag->name),
						'count'     => new xmlrpcval($tag->count),
						'slug'      => new xmlrpcval($tag->slug),
						'html_url'	=> new xmlrpcval($helper->host_url.$helper->route(LyftenBloggieHelperRoute::getTagRoute($tag->slug)))
					), 'struct' );

				array_push($struct, $tag_struct);
			}
		}

		return new xmlrpcresp(new xmlrpcval( $struct , $xmlrpcArray));
	}

	/**
	 * Create new category.
	 *
	 * @param array $args Method parameters.
	 * @return int Category ID.
	 */
	function wp_newCategory($blog_id, $username, $password, $category)
	{
		global $xmlrpcString;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		// Make sure the user is allowed to add a category.
		if(!BloggieFactory::allowAccess('admin.admin_access')) {
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you do not have the right to add a category.' ) );
		}

		// If no slug was provided make it empty so that
		// LyftenBloggie will generate one.
		if(empty($category["slug"])) {
			$category["slug"] = "";
		}

		// If no parent_id was provided make it empty
		// so that it will be a top level page (no parent).
		if ( !isset($category["parent_id"]) )
			$category["parent_id"] = 0;

		// If no description was provided make it empty.
		if(empty($category["description"])) {
			$category["description"] = "";
		}

		// If no default was provided make it empty.
		if(empty($category["default"])) {
			$category["default"] = 0;
		}

		$new_category = array(
			"title"		=> $category["name"],
			"slug"		=> $category["slug"],
			"parent"	=> $category["parent_id"],
			"default"	=> $category["default"],
			"text"		=> $category["description"]
		);

		//Save it
		$model = $helper->getModel('categories', 'admin');

		if (!$cat_id = $model->store($new_category) )
		{
			return new xmlrpcresp(0, 500, $model->getError());
		}

		//ensure defaults are OK
		$model->ensureDefault();

		return new xmlrpcresp(new xmlrpcval($cat_id, $xmlrpcString));
	}

	/**
	 * Remove category.
	 *
	 * @param array $args Method parameters.
	 * @return boolean
	 */
	function wp_deleteCategory($blog_id, $username, $password, $category_id)
	{
		global $xmlrpcBoolean;

		$category_id = array((int)$category_id);

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		// Make sure the user is allowed to add a category.
		if(!BloggieFactory::allowAccess('admin.admin_access')) {
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you do not have the right to add a category.' ) );
		}

		//Save it
		$model = $helper->getModel('categories', 'admin');
		if(!$model->delete($category_id)) {
			return new xmlrpcresp(0, 500, $model->getError());
		}

		//Ensure there is a default
		$model->ensureDefault();

		return new xmlrpcresp(new xmlrpcval('true', $xmlrpcBoolean));
	}

	/**
	 * Retrieve category list.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function wp_suggestCategories($blog_id, $username, $password, $category, $max_results)
	{
		global $xmlrpcArray;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if( !BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you must be able to edit posts to this blog in order to view categories.' ) );
		}

		//get categories
		$query = 'SELECT c.*'
					. ' FROM #__bloggies_categories AS c'
					. ' WHERE (LOWER(c.title) LIKE '.$helper->_db->Quote( '%'.$helper->_db->getEscaped( $category, true ).'%', false )
					. ' OR LOWER(c.text) LIKE '.$helper->_db->Quote( '%'.$helper->_db->getEscaped( $category, true ).'%', false ).')'
					. ' ORDER BY c.id'
					. (($max_results != 0) ? ' LIMIT '.$max_results : '');
		$helper->_db->setQuery( $query );

		if (!$cats = $helper->_db->loadObjectList()) {
			return new xmlrpcresp(0, 401, JText::_( 'Unable to get categories.' ) );
		}

		$struct = array();
		foreach ( $cats as $cat )
		{
			$category_array = new xmlrpcval( array(
					"category_id"	=> new xmlrpcval($cat->id),
					"category_name"	=> new xmlrpcval($cat->title)
				), 'struct' );

			array_push($struct, $category_array);
		}

		return new xmlrpcresp(new xmlrpcval( $struct , $xmlrpcArray));

		return($category_suggestions);
	}

	/**
	 * Retrieve comment.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function wp_getComment($blog_id, $username, $password, $comment_id)
	{
		global $xmlrpcStruct;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('admin.admin_access')) {
			return new xmlrpcresp(0, 401, JText::_("You are not allowed to moderate comments on this blog."));
		}

		// Get Comment Counts from plugins
		$settings 		= & BloggieSettings::getInstance();
		$comment_system = $settings->get('typeComments', 'default');

		if ( !$comment_system )
			return new xmlrpcresp(0, 404, JText::_( 'Comment System Disabled.' ) );

		//Get Comment Plugin
		$plugin = BloggieFactory::getPlugin('comment', $comment_system);
		$comment = $plugin->getComment($comment_id);

		if ( !isset($comment->id) )
			return new xmlrpcresp(0, 404, JText::_( 'Invalid comment ID.' ) );

		// Format page date.
		$comment_date 		= $helper->mysql2date("Ymd\TH:i:s", $comment->date, false);
		$comment_date_gmt 	= $helper->mysql2date("Ymd\TH:i:s", $comment->date, false);

		$struct = new xmlrpcval(
		array(
			"date_created_gmt"		=> new xmlrpcval($comment_date_gmt),
			"user_id"				=> new xmlrpcval($comment->user_id),
			"comment_id"			=> new xmlrpcval($comment->id),
			"parent"				=> new xmlrpcval($comment->parent),
			"status"				=> new xmlrpcval($comment->state),
			"content"				=> new xmlrpcval($comment->content),
			"link"					=> new xmlrpcval($comment->link),
			"post_id"				=> new xmlrpcval($comment->entry_id),
			"post_title"			=> new xmlrpcval($comment->entry_title),
			"author"				=> new xmlrpcval($comment->author_name),
			"author_url"			=> new xmlrpcval($comment->author_url),
			"author_email"			=> new xmlrpcval($comment->author_email),
			"author_ip"				=> new xmlrpcval($comment->author_ip),
			"type"					=> new xmlrpcval($comment->type),
		), $xmlrpcStruct);

		return new xmlrpcresp($struct);
	}

	/**
	 * Retrieve comments.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function wp_getComments($blog_id, $username, $password, $struct)
	{
		global $xmlrpcArray, $xmlrpcStruct;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('admin.admin_access')) {
			return new xmlrpcresp(0, 401, JText::_("You are not allowed to moderate comments on this blog."));
		}

		// Get Comment Counts from plugins
		$settings 		= & BloggieSettings::getInstance();
		$comment_system = $settings->get('typeComments', 'default');

		if ( !$comment_system )
			return new xmlrpcresp(0, 404, JText::_( 'Comment System Disabled.' ) );

		if ( isset($struct['status']) )
			$status = $struct['status'];
		else
			$status = '';

		$post_id = '';
		if ( isset($struct['post_id']) )
			$post_id = abs( intval($struct['post_id']) );

		$offset = 0;
		if ( isset($struct['offset']) )
			$offset = abs( intval($struct['offset']) );

		$number = 10;
		if ( isset($struct['number']) )
			$number = abs( intval($struct['number']) );

		//Get Comment Plugin
		$plugin = BloggieFactory::getPlugin('comment', $comment_system);
		$comments = $plugin->getComments( array('status' => $status, 'post_id' => $post_id, 'offset' => $offset, 'number' => $number ) );

		$num_comments = count($comments);

		if ( ! $num_comments )
			return new xmlrpcresp(0, 404, JText::_('No comments available, or an error has occured.') );

		$structArray = array();
		for ( $i = 0; $i < $num_comments; $i++ )
		{
			$comment = $plugin->getComment($comments[$i]->id);

			if ( isset($comment->id) )
			{
				// Format page date.
				$comment_date 		= $helper->mysql2date("Ymd\TH:i:s", $comment->date, false);
				$comment_date_gmt 	= $helper->mysql2date("Ymd\TH:i:s", $comment->date, false);

				$structArray[] = new xmlrpcval(
					array(
						"date_created_gmt"		=> new xmlrpcval($comment_date_gmt),
						"user_id"				=> new xmlrpcval($comment->user_id),
						"comment_id"			=> new xmlrpcval($comment->id),
						"parent"				=> new xmlrpcval($comment->parent),
						"status"				=> new xmlrpcval($comment->state),
						"content"				=> new xmlrpcval($comment->content),
						"link"					=> new xmlrpcval($comment->link),
						"post_id"				=> new xmlrpcval($comment->entry_id),
						"post_title"			=> new xmlrpcval($comment->entry_title),
						"author"				=> new xmlrpcval($comment->author_name),
						"author_url"			=> new xmlrpcval($comment->author_url),
						"author_email"			=> new xmlrpcval($comment->author_email),
						"author_ip"				=> new xmlrpcval($comment->author_ip),
						"type"					=> new xmlrpcval($comment->type),
					), $xmlrpcStruct);
			}
		}

		return new xmlrpcresp(new xmlrpcval( $structArray , $xmlrpcArray));
	}

	/**
	 * Remove comment.
	 *
	 * @param array $args Method parameters.
	 * @return mixed {@link wp_delete_comment()}
	 */
	function wp_deleteComment($blog_id, $username, $password, $comment_ID)
	{
		global $xmlrpcBoolean;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('admin.admin_access')) {
			return new xmlrpcresp(0, 401, JText::_("You are not allowed to moderate comments on this blog."));
		}

		// Get Comment Counts from plugins
		$settings 		= & BloggieSettings::getInstance();
		$comment_system = $settings->get('typeComments', 'default');

		if ( !$comment_system )
			return new xmlrpcresp(0, 404, JText::_( 'Comment System Disabled.' ) );

		//Get Comment Plugin
		$plugin = BloggieFactory::getPlugin('comment', $comment_system);
		$result = $plugin->deleteComment($comment_ID);

		if ( $result )
			return new xmlrpcresp(0, 404, $result );

		return new xmlrpcresp(new xmlrpcval('true', $xmlrpcBoolean));
	}

	/**
	 * Edit comment.
	 *
	 * @param array $args Method parameters.
	 * @return bool True, on success.
	 */
	function wp_editComment($blog_id, $username, $password, $id, $content_struct)
	{
		global $xmlrpcBoolean;

		$comment = array();

		//Set ID for saving
		$comment['id'] = $id;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('admin.admin_access')) {
			return new xmlrpcresp(0, 401, JText::_("You are not allowed to moderate comments on this blog."));
		}

		// Get Comment Counts from plugins
		$settings 		= & BloggieSettings::getInstance();
		$comment_system = $settings->get('typeComments', 'default');

		if ( !$comment_system )
			return new xmlrpcresp(0, 404, JText::_( 'Comment System Disabled.' ) );

		$comment['status'] = 'approve';
		if ( isset($content_struct['status']) )
		{
			//Comment Statuses
			$statuses = array('hold', 'approve', 'spam', 'flagged');
			if ( ! in_array($content_struct['status'], $statuses) )
				return new xmlrpcresp(0, 401, JText::_( 'INVALID COMMENT STATUS' ) );

			$comment['status'] = $content_struct['status'];
		}

		// Do some timestamp voodoo
		if ( !empty( $content_struct['date_created_gmt'] ) )
		{
			$dateCreated = str_replace( 'Z', '', $content_struct['date_created_gmt']->getIso() ) . 'Z';
			$comment['date'] = $helper->get_date_from_gmt($helper->iso8601_to_datetime($dateCreated));
		}else{
			$datenow = & JFactory::getDate();
			$comment['date'] = $datenow->toMySQL();
		}

		if ( isset($content_struct['content']) )
			$comment['content'] = $content_struct['content'];

		if ( isset($content_struct['author']) )
			$comment['author'] = $content_struct['author'];

		if ( isset($content_struct['author_url']) )
			$comment['author_url'] = $content_struct['author_url'];

		if ( isset($content_struct['author_email']) )
			$comment['author_email'] = $content_struct['author_email'];

		//Get Comment Plugin
		$plugin = BloggieFactory::getPlugin('comment', $comment_system);
		$result = $plugin->editComment($comment);

		if ( $result )
			return new xmlrpcresp(0, 500, $result);

		return new xmlrpcresp(new xmlrpcval('true', $xmlrpcBoolean));
	}

	/**
	 * Create new comment.
	 *
	 * @param array $args Method parameters.
	 * @return mixed {@link wp_new_comment()}
	 */
	function wp_newComment($blog_id, $username, $password, $post, $content_struct)
	{
		global $xmlrpcBoolean;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$user = $helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('admin.admin_access')) {
			return new xmlrpcresp(0, 401, JText::_("You are not allowed to moderate comments on this blog."));
		}

		// Get Comment Counts from plugins
		$settings 		= & BloggieSettings::getInstance();
		$comment_system = $settings->get('typeComments', 'default');

		if ( !$comment_system )
			return new xmlrpcresp(0, 404, JText::_( 'Comment System Disabled.' ) );

		if ( !is_numeric($post) )
			return new xmlrpcresp(0, 404, JText::_( 'Invalid post ID.' ) );

		$post_id = abs( intval($post) );
		if ( ! $post_id )
			return new xmlrpcresp(0, 404, JText::_( 'Invalid post ID.' ) );

		//Check for Post
		$helper->_db->setQuery('SELECT e.id FROM #__bloggies_entries AS e WHERE e.id = \''.$post_id.'\'');
		if ( !$helper->_db->loadResult() )
			return new xmlrpcresp(0, 404, JText::_( 'Invalid post ID.' ) );

		$comment['entry_id'] = $post_id;

		if ( !$user->guest ) {
			$comment['author_id'] 		= $user->get('id');
			$comment['author'] 			= $user->get('name');
			$comment['author_email'] 	= $user->get('email');
			$comment['author_url'] 		= @$content_struct['author_url'];
		} else {
			$comment['author'] = '';
			if ( isset($content_struct['author']) )
				$comment['comment_author'] = $content_struct['author'];

			$comment['author_email'] = '';
			if ( isset($content_struct['author_email']) )
				$comment['comment_author_email'] = $content_struct['author_email'];

			$comment['author_url'] = '';
			if ( isset($content_struct['author_url']) )
				$comment['author_url'] = $content_struct['author_url'];

			$comment['author_id'] = 0;
		}

		$comment['parent'] = isset($content_struct['comment_parent']) ? absint($content_struct['comment_parent']) : 0;

		$comment['content'] = $content_struct['content'];

		//Get Comment Plugin
		$plugin = BloggieFactory::getPlugin('comment', $comment_system);
		$result = $plugin->newComment($comment);

		if ( $result )
			return new xmlrpcresp(0, 500, $result);

		return new xmlrpcresp(new xmlrpcval('true', $xmlrpcBoolean));
	}

	/**
	 * Retrieve all of the comment status.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function wp_getCommentStatusList($blog_id, $username, $password)
	{
		global $xmlrpcStruct;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_("Sorry, you are not an author."));
		}

		$struct = new xmlrpcval(
			array(
				'hold'=> 	new xmlrpcval(JText::_('Unapproved')),
				'approve'=> new xmlrpcval(JText::_('Approved')),
				'spam'=> 	new xmlrpcval(JText::_('Spam'))
			), $xmlrpcStruct);

		return new xmlrpcresp($struct);
	}

	/**
	 * Retrieve comment count.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function wp_getCommentCount($blog_id, $username, $password, $post_id)
	{
		global $xmlrpcStruct;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_("Sorry, you are not an author."));
		}

		// Get Comment Counts from plugins
		$settings 		= & BloggieSettings::getInstance();
		$comment_system = $settings->get('typeComments', 'default');

		if ( !$comment_system )
			return new xmlrpcresp(0, 404, JText::_( 'Comment System Disabled.' ) );

		//Get Counts
		$plugin = BloggieFactory::getPlugin('comment', $comment_system);
		$comments = $plugin->getCount($post_id);

		$struct = new xmlrpcval(
			array(
				"approved" 				=> new xmlrpcval($comments['approved']),
				"awaiting_moderation" 	=> new xmlrpcval($comments['moderated']),
				"spam" 					=> new xmlrpcval($comments['spam']),
				"total_comments" 		=> new xmlrpcval($comments['total'])
			), $xmlrpcStruct);

		return new xmlrpcresp($struct);
	}

	/**
	 * Retrieve post statuses.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function wp_getPostStatusList($blog_id, $username, $password)
	{
		global $xmlrpcStruct;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_("Sorry, you are not an author."));
		}

		$struct = new xmlrpcval(
			array(
				'draft'			=> new xmlrpcval(JText::_('Draft')),
				'pending'		=> new xmlrpcval(JText::_('Pending Review')),
				'private'		=> new xmlrpcval(JText::_('Private')),
				'publish'		=> new xmlrpcval(JText::_('Published'))
			), $xmlrpcStruct);

		return new xmlrpcresp($struct);
	}

	/**
	 * Retrieve blog options.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function wp_getOptions($blog_id, $username, $password, $options)
	{
		global $xmlrpcArray;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_("Sorry, you are not an author."));
		}

		// If no specific options where asked for, return all of them
		if (count( $options ) == 0 ) {
			$options = array_keys($helper->blog_options);
		}

		$structArray = $helper->_getOptions($options);
		
		return new xmlrpcresp(new xmlrpcval( $structArray , $xmlrpcArray));
	}

	/**
	 * Update blog options.
	 *
	 * @param array $args Method parameters.
	 * @return unknown
	 */
	function wp_setOptions($blog_id, $username, $password, $options)
	{
		global $xmlrpcArray;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('admin.admin_access')) {
			return new xmlrpcresp(0, 401, JText::_("You are not allowed to update options."));
		}

		$settings = & BloggieSettings::getInstance();

		foreach( $options as $o_name => $o_value )
		{
			$option_names[] = $o_name;
			if( empty( $o_value ) )
				continue;

			if( !array_key_exists( $o_name, $helper->blog_options ) )
				continue;

			if( $helper->blog_options[$o_name]['readonly'] == true )
				continue;

			if( !isset($helper->blog_options[$o_name]['option']) )
				continue;

			$settings->update( $helper->blog_options[$o_name]['option'], $o_value );
		}

		//Now return the updated values
		$structArray = $helper->_getOptions($option_names);
		
		return new xmlrpcresp(new xmlrpcval( $structArray , $xmlrpcArray));
	}

	/* Blogger API functions.
	 * specs on http://plant.blogger.com/api and http://groups.yahoo.com/group/bloggerDev/
	 */

	/**
	 * Retrieve blogs that user owns.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function blogger_getUsersBlogs($appkey, $username, $password)
	{
		global $xmlrpcStruct;

		//get instance of XML-RPC helper
		$blog = BloggieXMLRPC::getInstance();

		if ( !$blog->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $blog->error);
		}

		if(!BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_("Sorry, you are not an author."));
		}

		$struct = new xmlrpcval(
			array(
				'isAdmin'  => new xmlrpcval((BloggieFactory::allowAccess('admin.admin_access'))),
				'url'      => new xmlrpcval($blog->host_url . '/'),
				'blogid'   => new xmlrpcval('1'),
				'blogName' => new xmlrpcval($blog->_blogname),
				'xmlrpc'   => new xmlrpcval($blog->host_url.'/xmlrpc/index.php')
			), $xmlrpcStruct);

		return new xmlrpcresp($struct);
	}

	/**
	 * Retrieve user's data.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function blogger_getUserInfo($blog_id, $username, $password)
	{
		global $xmlrpcStruct;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$user = $helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_("Sorry, you do not have access to user data on this blog."));
		}

		$author = BloggieFactory::getProfile();

		$struct = new xmlrpcval(
			array(
				'nickname'  => new xmlrpcval($author->username),
				'userid'    => new xmlrpcval($author->user_id),
				'url'       => new xmlrpcval($author->url),
				'lastname'  => new xmlrpcval(''),
				'firstname' => new xmlrpcval($user->get('name'))
			), $xmlrpcStruct);

		return new xmlrpcresp($struct);
	}

	/**
	 * Retrieve post.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function blogger_getPost($blog_id, $post_ID, $username, $password)
	{
		global $xmlrpcStruct;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_("Sorry, you are not an author."));
		}

		//get entry object
		$entry 	= &BloggieEntry::getInstance('getPost', $post_ID);
		$entry 	= $entry->getEntry();

		if( !isset($entry->id) || !$entry->id )
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, no such post.' ) );

		if( !$entry->editable )
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you cannot edit this post.' ) );

		//Set categories
		$categories = array();
		$categories[] = $entry->cattitle;

		$content  = '<title>'.stripslashes($entry->title).'</title>';
		$content .= '<category>'.implode(',', $categories).'</category>';
		$content .= stripslashes($entry->introtext.$entry->fulltext);

		$struct = new xmlrpcval(
			array(
				'userid'    	=> new xmlrpcval($entry->created_by),
				'dateCreated' 	=> new xmlrpcval($helper->mysql2date('Ymd\TH:i:s', $entry->created, false)),
				'content'     	=> new xmlrpcval($content),
				'postid'  		=> new xmlrpcval($entry->id)
			), $xmlrpcStruct);

		return new xmlrpcresp($struct);
	}

	/**
	 * Retrieve list of recent posts.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function blogger_getRecentPosts($blog_ID, $username, $password, $num_posts)
	{
		global  $xmlrpcArray;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$user = $helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_("Sorry, you are not an author."));
		}

		$query = 'SELECT e.id, e.title, e.created_by, e.created, c.title AS category,'
			. ' e.introtext, e.fulltext'
			. ' FROM #__bloggies_entries AS e'
			. ' LEFT JOIN #__bloggies_categories AS c ON c.id = e.catid'
			. ' WHERE e.created_by = \''.(int)$user->get('id').'\''
			. ' ORDER BY e.created DESC'
			. ($num_posts ? ' LIMIT '.(int)$num_posts : '');
		$helper->_db->setQuery( $query );
		$posts_list = $helper->_db->loadAssocList();

		if($helper->_db->getErrorMsg()) {
			return new xmlrpcresp(0, 401, JText::_( 'Unable to get blogs.' ) );
		}

		if (empty($posts_list)) {
			return new xmlrpcresp(0, 500, JText::_('Either there are no posts, or something went wrong.'));
		}

		$structArray = array();
		foreach ($posts_list as $entry)
		{
			if( !BloggieFactory::canEdit($entry['id'], $entry['created_by']) )
				continue;

			//Set categories
			$categories = array();
			$categories[] = $entry['category'];

			$content  = '<title>'.stripslashes($entry['title']).'</title>';
			$content .= '<category>'.implode(',', $categories).'</category>';
			$content .= stripslashes($entry['introtext'].$entry['fulltext']);

			$structArray[] = new xmlrpcval(array(
				'userid' 		=> new xmlrpcval($entry['created_by']),
				'dateCreated' 	=> new xmlrpcval($helper->mysql2date('Ymd\TH:i:s', $entry['created'], false)),
				'content' 		=> new xmlrpcval($content),
				'postid' 		=> new xmlrpcval($entry['id']),
			), 'struct');
		}

		return new xmlrpcresp(new xmlrpcval( $structArray , $xmlrpcArray));
	}

	/**
	 * Create new post.
	 *
	 * @Note: Not sure if this will work
	 *
	 * @param array $args Method parameters.
	 * @return int
	 */
	function blogger_newPost($appkey, $blogid, $username, $password, $content, $publish)
	{
		global $xmlrpcString;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		//assign blog content
		$data			= array();
		$data['title'] 	= XMLRPCHelper::xmlrpc_getposttitle($content);
		$data['catid'] 	= XMLRPCHelper::xmlrpc_getpostcategory($content);
		$data['text'] 	= stripslashes(XMLRPCHelper::xmlrpc_removepostdata($content));
		$data['created']= '';
		unset($content);

		if(!BloggieFactory::allowAccess('author.author_access'))
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you are not allowed to publish posts on this blog.' ) );

		//Get Settings
		$settings = BloggieSettings::getInstance();

		//Set categories
		if (is_array($data['catid'])) {
			$data['catid']	= $data['catid'][0];
		}
		if($data['catid'])
		{
			$query = 'SELECT c.id FROM #__bloggies_categories AS c WHERE (c.title = \''.$data['catid'].'\' OR c.slug = \''.$data['catid'].'\')';
			$helper->_db->setQuery($query);
			$data['catid'] = $helper->_db->loadResult();
		}

		//Set state
		$data['state'] = $publish ? 1 : '-1';

		//get entry object
		$entry 	= &BloggieEntry::getInstance();

		//Save Entry
		if (!$entry->store($data, true)) {
			return new xmlrpcresp(0, 500, $entry->getError());
		}

		return new xmlrpcresp(new xmlrpcval($entry->get('id'), $xmlrpcString));
	}

	/**
	 * Edit a post.
	 *
	 * @Note: Not sure if this will work
	 *
	 * @param array $args Method parameters.
	 * @return bool true when done.
	 */
	function blogger_editPost($appkey, $postid, $username, $password, $content, $publish)
	{
		global $xmlrpcBoolean;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		//assign blog content
		$data			= array();
		$data['id'] 	= (int)$postid;
		$data['title'] 	= XMLRPCHelper::xmlrpc_getposttitle($content);
		$data['catid'] 	= XMLRPCHelper::xmlrpc_getpostcategory($content);
		$data['text'] 	= stripslashes(XMLRPCHelper::xmlrpc_removepostdata($content));
		$data['created']= '';
		unset($content);

		if(!BloggieFactory::allowAccess('author.author_access'))
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you are not allowed to publish posts on this blog.' ) );

		//Get Settings
		$settings = BloggieSettings::getInstance();

		//Set categories
		if (is_array($data['catid'])) {
			$data['catid']	= $data['catid'][0];
		}
		if($data['catid'])
		{
			$query = 'SELECT c.id FROM #__bloggies_categories AS c WHERE (c.title = \''.$data['catid'].'\' OR c.slug = \''.$data['catid'].'\')';
			$helper->_db->setQuery($query);
			$data['catid'] = $helper->_db->loadResult();
		}

		//Set state
		$data['state'] = $publish ? 1 : '-1';

		//get entry object
		$entry 	= &BloggieEntry::getInstance();

		return new xmlrpcresp(new xmlrpcval('true', $xmlrpcBoolean));
	}

	/**
	 * Remove a post.
	 *
	 * @param array $args Method parameters.
	 * @return bool True when post is deleted.
	 */
	function blogger_deletePost($appkey, $postid, $username, $password, $publish)
	{
		global $xmlrpcBoolean;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('author.can_delete'))
		{
			if(!BloggieFactory::allowAccess('admin.admin_access'))
			{
				return new xmlrpcresp(0, 401, JText::_('Sorry, you do not have the right to delete this post.'));
			}
		}

		//get entry object
		$entry 	= &BloggieEntry::getInstance('getPost', $postid);
		$result = $entry->delete();

		if ($result['task'] == 'error') {
			return new xmlrpcresp(0, 500, $result['msg']);
		}

		//Relay message
		if ($result['task'] != 'remove') {
			return new xmlrpcresp(0, 401, $result['msg']);
		}

		return new xmlrpcresp(new xmlrpcval('true', $xmlrpcBoolean));
	}

	/**
	 * MetaWeblog API functions
	 */

	/**
	 * Create a new post.
	 *
	 * @param array $args Method parameters.
	 * @return int
	 */
	function mw_newPost($blogid, $username, $password, $content, $publish)
	{
		$blogid = 0;

		$post_ID = plgXMLRPCLyftenbloggieServices::mw_editPost($blogid, $username, $password, $content, $publish);

		return $post_ID;
	}

	/**
	 * Retrieve list of recent posts.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function mw_getRecentPosts($blog_ID, $username, $password, $num_posts)
	{
		global $xmlrpcArray;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$user = $helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_("Sorry, you are not an author."));
		}

		$query = 'SELECT e.id, e.title, e.created_by, e.alias, u.name AS author, e.created, c.title AS category,'
			. ' e.introtext, e.fulltext, e.attribs, e.state,'
			. ' CASE WHEN CHAR_LENGTH(e.alias) THEN CONCAT_WS(":", e.id, e.alias) ELSE e.id END as slug,'
			. ' CASE WHEN CHAR_LENGTH(c.slug) THEN c.slug ELSE 0 END as catslug'
			. ' FROM #__bloggies_entries AS e'
			. ' LEFT JOIN #__bloggies_categories AS c ON c.id = e.catid'
			. ' LEFT JOIN #__users AS u ON u.id = e.created_by'
			. ' WHERE e.created_by = \''.(int)$user->get('id').'\''
			. ' ORDER BY e.created DESC'
			. ($num_posts ? ' LIMIT '.$num_posts : '');
		$helper->_db->setQuery( $query );
		$posts_list = $helper->_db->loadAssocList();

		if($helper->_db->getErrorMsg()) {
			//return new xmlrpcresp(0, 401, $helper->_db->getErrorMsg() );
			return new xmlrpcresp(0, 401, JText::_( 'Unable to get blogs.' ) );
		}

		if (empty($posts_list)) {
			return array();
		}

		$settings 		= & BloggieSettings::getInstance();
		$params 		= & JComponentHelper::getParams('com_lyftenbloggie');
		$comment_system = $settings->get('typeComments', 'default');

		$structArray = array();
		foreach ($posts_list as $entry)
		{
			$post_date = $helper->mysql2date('Ymd\TH:i:s', $entry['created']);
			$categories = array($entry['category']);

			//Get used Tags
			$tagnames = array();
			$query = 'SELECT t.name'
				. ' FROM #__bloggies_relations AS r'
				. ' LEFT JOIN #__bloggies_tags AS t ON t.id = r.tag'
				. ' WHERE r.entry = \''.(int)$entry['id'].'\'';
			$helper->_db->setQuery($query);
			$tags = $helper->_db->loadObjectList();

			if ( !empty( $tags ) ) {
				foreach ( $tags as $tag ) {
					$tagnames[] = $tag->name;
				}
				$tagnames = implode( ', ', $tagnames );
			} else {
				$tagnames = '';
			}

			$link = $helper->host_url.$helper->route( LyftenBloggieHelperRoute::getEntryRoute('&category='.$entry['catslug'], $entry['slug']) );

			// Get the page/component configuration and entry parameters
			$entry['params'] = clone($params);
			$aparams = new JParameter($entry['attribs']);
			$entry['params']->merge($aparams);

			//Allow comments
			$allow_comments = ($comment_system && $entry['params']->get('allow_comments'));

			// figure out states
			if ( $entry['state'] == 1 ) {
				$entry['state'] = JText::_( 'PUBLISHED' );
			} else if ( $entry['state'] == -1 ) {
				$entry['state'] = JText::_( 'UNPUBLISHED' );
			} else if ( $entry['state'] == 2 ) {
				$entry['state'] = JText::_( 'PENDING REVIEW' );
			} else if ( $entry['state'] == 3 ) {
				$entry['state'] = JText::_( 'PENDING DELETION' );
			}

			$structArray[] = new xmlrpcval(array(
				'dateCreated' 		=> new xmlrpcval($post_date),
				'userid' 			=> new xmlrpcval($entry['created_by']),
				'postid' 			=> new xmlrpcval($entry['id']),
				'description' 		=> new xmlrpcval($entry['introtext']),
				'title' 			=> new xmlrpcval($entry['title']),
				'link' 				=> new xmlrpcval($link),
				'permaLink' 		=> new xmlrpcval($link),
				'categories' 		=> new xmlrpcval($categories),
				'mt_excerpt' 		=> new xmlrpcval(''),
				'mt_text_more' 		=> new xmlrpcval($entry['fulltext']),
				'mt_allow_comments' => new xmlrpcval($allow_comments),
				'mt_allow_pings' 	=> new xmlrpcval(1),
				'mt_keywords' 		=> new xmlrpcval($tagnames),
				'wp_slug' 			=> new xmlrpcval($entry['alias']),
				'wp_password' 		=> new xmlrpcval(''),
				'wp_author_id' 		=> new xmlrpcval($entry['created_by']),
				'wp_author_display_name' => new xmlrpcval($entry['author']),
				'date_created_gmt' 	=> new xmlrpcval($post_date),
				'post_status' 		=> new xmlrpcval($entry['state']),
				'custom_fields' 	=> new xmlrpcval('')
			), 'struct');
		}

		return new xmlrpcresp(new xmlrpcval( $structArray , $xmlrpcArray));
	}
////////////////////HERE///////////////////
	/**
	 * Uploads a file
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function mw_newMediaObject($args)
	{
		global $wpdb;

		$blog_ID	= (int)$args[0];
		$username	= $args[1];
		$password	= $args[2];
		$data		= $args[3];

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if( !BloggieFactory::allowAccess('author.can_upload')) {
			return new xmlrpcresp(0, 401, JText::_( 'You are not allowed to upload files to this site.' ) );
		}

		//Load media helper
		BloggieFactory::import('media', 'helpers');

		$name 		= MediaHelper::sanitize_filename( $data['name'] );
		$type 		= $data['type'];
		$bits 		= $data['bits'];
		$overwrite 	= (!empty($data["overwrite"]) && ($data["overwrite"] == true));

		//Write File
		$upload = MediaHelper::uploadBits($name, $type, $bits, $overwrite);
		if ( ! empty($upload['error']) ) {
			$errorString = sprintf(JText::_('Could not write file %1$s (%2$s)'), $name, $upload['error']);
			return new xmlrpcresp(0, 500, $errorString);
		}
		
		return array( 'file' => $name, 'url' => $upload[ 'url' ], 'type' => $type );
	}

	/**
	 * Retrieve the list of categories on a given blog.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function mw_getCategories($blog_ID, $username, $password)
	{
		global $xmlrpcArray;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if (!$helper->login($username, $password)) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('author.author_access'))
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you must be able to edit posts on this blog in order to view categories.' ) );

		//get categories
		$query = 'SELECT c.*'
					. ' FROM #__bloggies_categories AS c'
					. ' ORDER BY c.id';
		$helper->_db->setQuery( $query );

		if (!$cats = $helper->_db->loadObjectList()) {
			return new xmlrpcresp(0, 401, JText::_( 'Unable to get categories.' ) );
		}

		$structArray = array();
		foreach ( (array)$cats as $cat )
		{
			$structArray[] = new xmlrpcval(array(
				'categoryId'			=> new xmlrpcval($cat->id),
				'parentId'				=> new xmlrpcval($cat->parent),
				'description'			=> new xmlrpcval($cat->title),
				'categoryDescription'	=> new xmlrpcval($cat->text),
				'categoryName'			=> new xmlrpcval($cat->title),
				'htmlUrl'				=> new xmlrpcval($helper->host_url.$helper->route(LyftenBloggieHelperRoute::getCategoryRoute($cat->slug))),
				'rssUrl'				=> new xmlrpcval('')
			), 'struct');
		}

		return new xmlrpcresp(new xmlrpcval( $structArray , $xmlrpcArray));
	}

	/**
	 * Retrieve post.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function mw_getPost($args) {
		$post_ID	= (int) $args[0];
		$username	= $args[1];
		$password	= $args[2];

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('author.author_access'))
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you are not allowed to publish posts on this blog.' ) );

		//get entry object
		$entry 	= &BloggieEntry::getInstance('getPost', $post_ID);
		$entry 	= $entry->getEntry();

		if( !isset($entry->id) || !$entry->id )
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, no such post.' ) );

		if( !$entry->editable )
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you cannot edit this post.' ) );

		$post_date = $this->mysql2date('Ymd\TH:i:s', $entry->created, false);
		$post_date_gmt = $this->mysql2date('Ymd\TH:i:s', $entry->created, false);

		// For drafts use the GMT version of the post date
		if ( $entry->state == -1 ) {
			$post_date_gmt = get_gmt_from_date( $this->mysql2date( 'Y-m-d H:i:s', $entry->created ) );
			$post_date_gmt = preg_replace( '|\-|', '', $post_date_gmt );
			$post_date_gmt = preg_replace( '| |', 'T', $post_date_gmt );
		}

		//Set categories
		$categories = array();
		$categories[] = $entry->cattitle;

		//Get used Tags
		$tagnames = array();
		$query = 'SELECT t.name'
			. ' FROM #__bloggies_relations AS r'
			. ' LEFT JOIN #__bloggies_tags AS t ON t.id = r.tag'
			. ' WHERE r.entry = \''.(int)$entry->id.'\'';
		$this->_db->setQuery($query);
		$tags = $this->_db->loadObjectList();
		if ( !empty( $tags ) ) {
			foreach ( $tags as $tag ) {
				$tagnames[] = $tag->name;
			}
			$tagnames = implode( ', ', $tagnames );
		} else {
			$tagnames = '';
		}

		$link = $this->host_url.$helper->route( LyftenBloggieHelperRoute::getEntryRoute('&category='.$entry->catslug, $entry->slug) );

		$resp = array(
			'dateCreated' 	=> new IXR_Date($post_date),
			'userid' 		=> $entry->created_by,
			'postid' 		=> $entry->id,
			'description' 	=> $entry->introtext,
			'title' 		=> $entry->title,
			'link' 			=> $link,
			'permaLink' 	=> $link,
			'categories' 	=> $categories,
			'mt_excerpt' 	=> '',
			'mt_text_more' 	=> $entry->fulltext,
			'mt_allow_comments' => $entry->allowComments,
			'mt_allow_pings' 	=> 1,
			'mt_keywords' 	=> $tagnames,
			'wp_slug' 		=> $entry->alias,
			'wp_password' 	=> '',
			'wp_author_id' 	=> $entry->created_by,
			'wp_author_display_name'	=> $entry->author->username,
			'date_created_gmt' 			=> new IXR_Date($post_date_gmt),
			'post_status' 	=> $entry->state,
			'custom_fields' => ''
		);
		return $resp;
	}

	/**
	 * Edit a post.
	 *
	 * @param array $args Method parameters.
	 * @return bool True on success.
	 */
	function mw_editPost($blogid, $username, $password, $content, $publish)
	{
		$data			= array();
		$data['id'] 	= (int) $blogid;

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if(!BloggieFactory::allowAccess('author.author_access'))
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you are not allowed to publish posts on this blog.' ) );

		//Get Settings
		$settings = BloggieSettings::getInstance();

		if( !empty( $content['post_type'] ) ) {
			if( $content['post_type'] != 'post' ) {
				// No other post_type values are allowed here
				return new xmlrpcresp(0, 401, JText::_( 'Invalid post type.' ) );
			}
		}

		// Let LyftenBloggie manage slug if none was provided.
		$data['alias'] = "";
		if(isset($content["wp_slug"])) {
			$data['alias'] = $content["wp_slug"];
		}

		if(isset($content["mt_allow_comments"])) {
			if(!is_numeric($content["mt_allow_comments"])) {
				switch($content["mt_allow_comments"]) {
					case "closed":
						$data['params']['allow_comments'] = 0;
						break;
					case "open":
						$data['params']['allow_comments'] = 1;
						break;
					default:
						$data['params']['allow_comments'] = $settings->get('typeComments') ? 1 : 0;
						break;
				}
			}
			else {
				switch((int) $content["mt_allow_comments"]) {
					case 0:
					case 2:
						$data['params']['allow_comments'] = 0;
						break;
					case 1:
						$data['params']['allow_comments'] = 1;
						break;
					default:
						$data['params']['allow_comments'] = $settings->get('typeComments') ? 1 : 0;
						break;
				}
			}
		}

		//Set Title and Text
		$data['title'] 	= $content['title'];
		$data['text'] 	= stripslashes($content['description']);
		if (isset($content['mt_text_more']) && $content['mt_text_more']) {
			$data['text'] = $data['text'] . "<!--more-->" . stripslashes($content['mt_text_more']);
		}

		//Set categories
		$data['catid']	= @$content['categories'];
		if (is_array($data['catid'])) {
			$data['catid']	= $data['catid'][0];
		}
		if($data['catid'])
		{
			$query = 'SELECT c.id FROM #__bloggies_categories AS c WHERE (c.title = \''.$data['catid'].'\' OR c.slug = \''.$data['catid'].'\')';
			$helper->_db->setQuery($query);
			$data['catid'] = $helper->_db->loadResult();
		}

		//Set state
		$data['state'] = $publish ? 1 : '-1';
		if( isset( $content["post_status"] ) ) {
			switch( $content["post_status"] ) {
				case 'publish':
					$data['state'] = 1;
					break;
				case 'draft':
				case 'private':
					$data['state'] = -1;
					break;
				case 'pending':
					$data['state'] = 2;
					break;
				default:
					$data['state'] = $publish ? 1 : '-1';
					break;
			}
		}

		//Set tags
		$data['tag'] = array();
		if(isset($content['mt_keywords']))
		{
			$tags = explode(',', $content['mt_keywords']);
			foreach($tags as $tag)
			{
				$query = 'SELECT t.id FROM #__bloggies_tags AS t WHERE (t.name = \''.$tag.'\' OR t.slug = \''.$tag.'\')';
				$helper->_db->setQuery($query);
				$data['tag'][] = $helper->_db->loadResult();
			}
		}

		//Set trackbacks
		$data['trackbacks'] = @$content['mt_tb_ping_urls'];
		if ( is_array($data['trackbacks']) )
			$data['trackbacks'] = implode(' ', $data['trackbacks']);

		// Do some timestamp voodoo
		$data['created'] = @$content['dateCreated'];

		//get entry object
		$entry 	= &BloggieEntry::getInstance();

		//Save Entry
		if (!$entry->store($data, true)) {
			return new xmlrpcresp(0, 500, $entry->getError());
		}

		return $entry->get('id');
	}

	/* MovableType API functions
	 * specs on http://www.movabletype.org/docs/mtmanual_programmatic.html
	 */

	/**
	 * Retrieve list of all categories on blog.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function mt_getCategoryList($args)
	{
		$blog_ID	= (int) $args[0];
		$username	= $args[1];
		$password	= $args[2];

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if( !BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you must be able to edit posts to this blog in order to view categories.' ) );
		}

		//get categories
		$query = 'SELECT c.* FROM #__bloggies_categories AS c ORDER BY c.id';
		$this->_db->setQuery( $query );

		if (!$cats = $this->_db->loadObjectList()) {
			return new xmlrpcresp(0, 401, JText::_( 'Unable to get categories.' ) );
		}

		$categories_struct = array();
		foreach ($cats as $cat)
		{
			$struct['categoryId'] = $cat->id;
			$struct['categoryName'] = $cat->title;
			$categories_struct[] = $struct;
		}

		return $categories_struct;
	}

	/**
	 * Retrieve the post titles of recent posts.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function mt_getRecentPostTitles($args)
	{
		$blog_ID	= (int) $args[0];
		$username	= $args[1];
		$password	= $args[2];
		$num_posts	= (int) $args[3];

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$user = $helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if( !BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you must be able to edit posts to this blog in order to view categories.' ) );
		}

		$query = 'SELECT e.id, e.title, e.created_by, e.created, c.title AS category,'
			. ' e.introtext, e.fulltext'
			. ' FROM #__bloggies_entries AS e'
			. ' LEFT JOIN #__bloggies_categories AS c ON c.id = e.catid'
			. ' WHERE e.created_by = \''.(int)$user->get('id').'\''
			. ' ORDER BY e.created DESC'
			. ($num_posts ? ' LIMIT '.$num_posts : '');
		$this->_db->setQuery( $query );
		$posts_list = $this->_db->loadAssocList();

		if($this->_db->getErrorMsg()) {
			return new xmlrpcresp(0, 401, JText::_( 'Unable to get blogs.' ) );
		}

		if (!$posts_list) {
			$this->error = new xmlrpcresp(0, 500, __('Either there are no posts, or something went wrong.'));
			return $this->error;
		}

		foreach ($posts_list as $entry)
		{
			if( !BloggieFactory::canEdit($entry['id'], $entry['created_by']) )
				continue;

			$post_date 		= $this->mysql2date('Ymd\TH:i:s', $entry['created'], false);
			$post_date_gmt	= $this->mysql2date('Ymd\TH:i:s', $entry['created'], false);

			$struct[] = array(
				'dateCreated' => new IXR_Date($post_date),
				'userid' => $entry['created_by'],
				'postid' => $entry['id'],
				'title' => $entry['title'],
				'date_created_gmt' => new IXR_Date($post_date_gmt)
			);

		}

		$recent_posts = array();
		for ($j=0; $j<count($struct); $j++) {
			array_push($recent_posts, $struct[$j]);
		}

		return $recent_posts;
	}

	/**
	 * Retrieve post categories.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function mt_getPostCategories($args)
	{
		$post_ID	= (int) $args[0];
		$username	= $args[1];
		$password	= $args[2];

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if( !BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you must be able to edit posts to this blog in order to view categories.' ) );
		}

		//get entry object
		$entry 	= &BloggieEntry::getInstance('getPost', $post_ID);
		$entry 	= $entry->getEntry();

		if( !isset($entry->id) || !$entry->id )
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, no such post.' ) );

		if( !$entry->editable )
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you cannot edit this post.' ) );

		//Set categories
		$categories = array();
		$categories[] = array(
			'categoryName' => $entry->cattitle,
			'categoryId' => $entry->catid,
			'isPrimary' => true
		);

		return $categories;
	}

	/**
	 * Sets categories for a post.
	 *
	 * @param array $args Method parameters.
	 * @return bool True on success.
	 */
	function mt_setPostCategories($args)
	{
		global $xmlrpcBoolean;

		$post_ID	= (int) $args[0];
		$username 	= $args[1];
		$password   = $args[2];
		$categories	= $args[3];

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if( !BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you are not an author.' ) );
		}

		//get entry object
		$entry_obj 	= &BloggieEntry::getInstance('getPost', $post_ID);
		$entry 		= $entry_obj->getEntry();

		if( !isset($entry->id) || !$entry->id )
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, no such post.' ) );

		if( !$entry->editable )
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you cannot edit this post.' ) );

		if(!isset($categories[0]['categoryId']))
			return new xmlrpcresp(0, 401, JText::_( 'Please select a category.' ) );

		if(!$entry_obj->setEntryCategory($categories[0]['categoryId']))
			return new xmlrpcresp(0, 401, $entry_obj->getError() );

		return new xmlrpcresp(new xmlrpcval('true', $xmlrpcBoolean));
	}

	/**
	 * Retrieve an array of methods supported by this server.
	 *
	 * @param array $args Method parameters.
	 * @return array
	 */
	function mt_supportedMethods($args)
	{

		$supported_methods = array();
		foreach($this->methods as $key=>$value) {
			$supported_methods[] = $key;
		}

		return $supported_methods;
	}

	/**
	 * Retrieve an empty array because we don't support per-post text filters.
	 *
	 * @param array $args Method parameters.
	 */
	function mt_supportedTextFilters($args)
	{
		return array();
	}

	/**
	 * Retrieve trackbacks sent to a given post.
	 *
	 * @param array $args Method parameters.
	 * @return mixed
	 */
	function mt_getTrackbackPings($args)
	{
		$post_ID = intval($args);

		//get entry object
		$entry_obj 	= &BloggieEntry::getInstance('getPost', $post_ID);
		$entry 		= $entry_obj->getEntry();

		if( !isset($entry->id) || !$entry->id )
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, no such post.' ) );

		// Get Comment Counts from plugins
		$settings 		= & BloggieSettings::getInstance();
		$comment_system = $settings->get('typeComments', 'default');

		if ( !$comment_system )
			return new xmlrpcresp(0, 404, JText::_( 'Comment System Disabled.' ) );

		//Get Comment Plugin
		$plugin = BloggieFactory::getPlugin('comment', $comment_system);

		if ( !method_exists($plugin, 'getTrackbacks') )
			return new xmlrpcresp(0, 404, JText::_( 'Comment System does not support trackbacks.' ) );

		//Get Trackback
		$comments = $plugin->getTrackbacks($post_ID);

		if (!$comments) {
			return array();
		}

		$trackback_pings = array();
		foreach($comments as $comment)
		{
			$content = $comment->content;
			$title = substr($content, 8, (strpos($content, '</strong>') - 8));
			$trackback_pings[] = array(
				'pingTitle' => $title,
				'pingURL'   => $comment->author_url,
				'pingIP'    => $comment->author_ip
			);
		}

		return $trackback_pings;
	}

	/**
	 * Sets a post's publish status to 'publish'.
	 *
	 * @param array $args Method parameters.
	 * @return int
	 */
	function mt_publishPost($args)
	{
		global $xmlrpcBoolean;

		$post_ID 	= (int) $args[0];
		$username	= $args[1];
		$password	= $args[2];

		//get instance of XML-RPC helper
		$helper = BloggieXMLRPC::getInstance();

		if ( !$helper->login($username, $password) ) {
			return new xmlrpcresp(0, 403, $helper->error);
		}

		if( !BloggieFactory::allowAccess('author.author_access')) {
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you are not an author.' ) );
		}

		if( !BloggieFactory::allowAccess('author.can_publish')) {
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you are allow to publish.' ) );
		}

		//get entry object
		$entry_obj 	= &BloggieEntry::getInstance('getPost', $post_ID);
		$entry 		= $entry_obj->getEntry();

		if( !isset($entry->id) || !$entry->id )
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, no such post.' ) );

		if( !$entry->editable )
			return new xmlrpcresp(0, 401, JText::_( 'Sorry, you cannot edit this post.' ) );

		if(!$entry_obj->setEntryState(1))
			return new xmlrpcresp(0, 401, $entry_obj->getError() );

		return new xmlrpcresp(new xmlrpcval('true', $xmlrpcBoolean));
	}
}

class XMLRPCHelper
{
	/**
	 * Retrieve post title from XMLRPC XML.
	 *
	 * @param string $content XMLRPC XML Request content
	 * @return string Post title
	 */
	function xmlrpc_getposttitle( $content )
	{
		if ( preg_match( '/<title>(.+?)<\/title>/is', $content, $matchtitle ) ) {

			$post_title = $matchtitle[1];
		} else {
			$post_title = '';
		}
		return $post_title;
	}

	/**
	 * Retrieve the post category or categories from XMLRPC XML.
	 *
	 * @param string $content XMLRPC XML Request content
	 * @return string|array List of categories or category name.
	 */
	function xmlrpc_getpostcategory( $content )
	{
		if ( preg_match( '/<category>(.+?)<\/category>/is', $content, $matchcat ) ) {
			$post_category = trim( $matchcat[1], ',' );
			$post_category = explode( ',', $post_category );
		} else {
			$post_category = '';
		}
		return $post_category;
	}

	/**
	 * XMLRPC XML content without title and category elements.
	 *
	 * @param string $content XMLRPC XML Request content
	 * @return string XMLRPC XML Request content without title and category elements.
	 */
	function xmlrpc_removepostdata( $content )
	{
		$content = preg_replace( '/<title>(.+?)<\/title>/si', '', $content );
		$content = preg_replace( '/<category>(.+?)<\/category>/si', '', $content );
		$content = trim( $content );
		return $content;
	}
}