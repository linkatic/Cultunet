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

jimport('joomla.application.component.controller');

/**
 * Main Component Controller
 **/
class LyftenBloggieController extends JController
{
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Display the view
	 **/
	function display()
	{
		$document 		=& JFactory::getDocument(); 
		$viewName 		= JRequest::getVar('view', 'lyftenbloggie');
		$viewLayout		= JRequest::getCmd( 'layout', 'default' );
		$views 			= array('comments', 'entry', 'lyftenbloggie', 'author');
		$layouts 		= array('default', 'form');
	
		// validate view
		if (!in_array($viewName, $views)) $viewName = 'lyftenbloggie';
		if (!in_array($viewLayout, $layouts)) $viewLayout = 'default';

		$viewType 		= $document->getType(); 
		$view 			= &$this->getView($viewName, $viewType); 
		$model 			=&$this->getModel( $viewName, 'LyftenBloggieModel' );
		if (!in_array($viewName, $views)) $viewName = 'lyftenbloggie';

		if (!JError::isError( $model )) { 
			$view->setModel( $model, true ); 
		}

		$view->setLayout($viewLayout); 
		$view->display(); 
	}

	/**
	 * Perform Display Feed
	 * @since 1.1.0
	 **/
	function feed()
	{
		JRequest::setVar('view', 'lyftenbloggie');
		parent::display();
	}

	/**
	 * Perform Ajax Call
	 * @since 1.1.0
	 **/
	function ajax()
	{
		global $mainframe;

		//Run Called Function
		$model 	= & $this->getModel('ajax');
		$model->execute( JRequest::getVar('act', null, 'default', 'cmd') );
	
		$mainframe->close();
	}

	/**
	 * Get list of images
	 * @since 1.0.3
	 **/
	function viewImages()
	{
		//Check User Access
		BloggieFactory::allowAccess('author.author_access', true);

		// Load the Media helper
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'media.php' );

		// Initialize variables
		$view 	= & $this->getView('media', 'html');
		$user 	= & JFactory::getUser();
	
		// Set the path definitions
		define('COM_MEDIA_BASE',    JPath::clean(JPATH_ROOT.DS.'images'.DS.'lyftenbloggie'.DS.$user->get('id')));
		define('COM_MEDIA_BASEURL', JURI::root(true).'/images/lyftenbloggie/'.$user->get('id'));

		// Get/Create the model
		$model = & $this->getModel('media');
	
		// Push the model into the view (as default)
		$view->setModel($model, true);
	
		// Display the view
		$view->display();
	}

	/**
	 * Upload File
	 * @since 1.0.3
	 **/
	function uploadfile()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		//Check User Access
		BloggieFactory::allowAccess('author.can_upload', true);

		//initialize variables
		$user 				= & JFactory::getUser(); 
		$type 				= JRequest::getVar( 'type', 'image');
		$CKEditor 			= JRequest::getVar( 'CKEditor', 'text');
		$CKEditorFuncNum 	= JRequest::getVar( 'CKEditorFuncNum', '1');
		$langCode 			= JRequest::getVar( 'langCode', 'en');

		// validate type
		$types = array('image', 'flash');
		if (!in_array($type, $types)) $type = 'image';

		// Load the Media helper
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'media.php' );

		// Set the path definitions
		define('COM_MEDIA_BASE',    JPath::clean(JPATH_ROOT.DS.'images'.DS.'lyftenbloggie'.DS.$user->get('id')));
		define('COM_MEDIA_BASEURL', JURI::root(true).'/images/lyftenbloggie/'.$user->get('id'));

		// Get/Create the model
		$model = & $this->getModel('media');
	
		// Upload file
		$msg = $model->upload(false);
		$msg = ($msg) ? '&msg='.urlencode($msg) : '';

		$link = JRoute::_('index.php?option=com_lyftenbloggie&task=viewImages&format=raw&CKEditor='.$CKEditor.'&CKEditorFuncNum='.$CKEditorFuncNum.'&langCode='.$langCode.'&type='.$type.$msg, false);
		$this->setRedirect($link);
	}

	/**
	 * Upload File
	 * @since 1.0.3
	 **/
	function ajaxuploadfile()
	{
		// Check for request forgeries
		$token	= JUtility::getToken();

		//Check User Access
		BloggieFactory::allowAccess('author.can_upload', true);

		if(!JRequest::getVar( $token )) {
			$session = JFactory::getSession();
			if(!$session->isNew()) {
				echo JText::_('SESSION_EXPIRED');
				$mainframe->close();
			} else {
				echo JText::_('Invalid Token');
				$mainframe->close();
			}
		}

		// Load the Media helper
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'media.php' );

		//initialize variables
		$user = & JFactory::getUser(); 

		// Set the path definitions
		define('COM_MEDIA_BASE',    JPath::clean(JPATH_ROOT.DS.'images'.DS.'lyftenbloggie'.DS.$user->get('id')));
		define('COM_MEDIA_BASEURL', JURI::root(true).'/images/lyftenbloggie/'.$user->get('id'));

		// Get/Create the model
		$model = & $this->getModel('media');
	
		// Upload file
		$model->upload();

		$mainframe->close();
	}

	/**
	 * Logic to save a author
	 **/
	function saveauthor()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		//Sanitize
		$post = JRequest::get( 'post' );
	
		$author	= &BloggieAuthor::getInstance();

		if ( $author->store($post) ) {
			$msg 	= JText::_( 'AUTHOR SAVED' );
			$cache 	= &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		} else {
			$msg 	= JText::_( 'ERROR SAVING AUTHOR' );
		}

		$link = JRoute::_('index.php?option=com_lyftenbloggie&view=author', false);
		$this->setRedirect($link, $msg);
	}

	/**
	 * Edits an article
	 * @since	1.0.0
	 */
	function edit()
	{
		// Create the view
		$view = & $this->getView('entry', 'html');

		// Get/Create the model
		$model = & $this->getModel('entry');

		// Push the model into the view (as default)
		$view->setModel($model, true);

		// Set the layout
		$view->setLayout('form');

		// Display the view
		$view->display();
	}

	/**
	 * Saves the item
	 **/
	function save()
	{
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		//Check User Access
		BloggieFactory::allowAccess('author.author_access', true);

		//get entry object
		$entry 	= &BloggieEntry::getInstance();

		//get data from request
		$post 	= JRequest::get('post');
		$Itemid = @$post['Itemid'];

		//new post
		$isNew = ((int) $post['id'] < 1);

		if (!$entry->store($post)) {
			$msg = JText::_( 'ERROR STORING ITEM' );
			JError::raiseError( 500, $entry->getError() );
		}

		$entry->checkin();

		if (!$isNew)
		{
			// If the item isn't new, then we need to clean the cache so that our changes appear realtime
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}

		//Check to see if the user is allowed to post
		if (BloggieFactory::allowAccess('author.can_publish'))
		{
			$msg = JText::_( 'ENTRY SAVED' );
		}
		else
		{
			$msg = JText::_('BLOG ENTRY REVIEW');
		}

		//Return to author section if started there
		if(!$post['return']){
			$link = JRoute::_(JURI::base().'index.php?option=com_lyftenbloggie&view=author&Itemid='.$Itemid, false);
		}else{
			$return = base64_decode($post['return']);
			$link = $return;
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * Logic to delete entries
	 **/
	function remove()
	{
		global $mainframe;

		// Check for request forgeries
		$token	= JUtility::getToken();
		if(!JRequest::getVar( $token ))
		{
			$session = JFactory::getSession();
			if($session->isNew()) {
				//Redirect to login screen
				$return = JRoute::_('index.php');
				$mainframe->redirect($return, JText::_('SESSION_EXPIRED'));
				$mainframe->close();
			} else {
				jexit( 'Invalid Token' );
			}
		}

		$id		= JArrayHelper::getValue( $_REQUEST, 'id', 0 );
		$msg	= '';

		if (!$id) {
			$msg = JText::_( 'SELECT A BLOG ENTRY TO DELETE' );
		}
	
		if(!$msg)
		{
			//get entry object
			$entry 	= &BloggieEntry::getInstance();

			$action = $entry->delete($id);
		
			switch ($action['task'])
			{
				case 'error' :
					$msg = $action['msg'];
					break;

				case 'change' :
					$msg = $action['msg'];
					break;
				
				case 'remove' :
					$msg 	= JText::_('ENTRY DELETED');
					$cache 	= &JFactory::getCache('com_lyftenbloggie');
					$cache->clean();
					break;
				
				default :
					$msg = JText::_('AN ERROR ACCURED');
					break;
			}		
		}

		$link = JRoute::_('index.php?option=com_lyftenbloggie&view=author', false);
		$this->setRedirect($link, $msg);
	}

	/**
	 * Cancels an edit item operation
	 **/
	function cancel()
	{
		// Initialize some variables
		$user	= & JFactory::getUser();
		$db 	= & JFactory::getDBO();
	
		// Get an item table object and bind post variabes to it
		$item = & JTable::getInstance('entries', 'Table');
		$item->bind(JRequest::get('post'));

		//Check to see if the user is allowed to post
		$query = 'SELECT user_id'
				.' FROM #__bloggies_authors'
				.' WHERE user_id = '.$user->get('id')
				.' AND published = 1';
		$db->setQuery($query);
	
		// todo: add task checks
		if ($db->loadResult()) {
			$item->checkin();
		}

		// If the task was edit or cancel, we go back to the item
		$referer = JRequest::getString('referer', JURI::base(), 'post');
		$this->setRedirect($referer);
	}

	/**
	 * Cancels an edit item operation
	 **/
	function cancelprofile()
	{
		$url = JRoute::_('index.php?option=com_lyftenbloggie&view=author');
		$this->setRedirect($url);
	}

	/**
	 * Handle Trackbacks and Pingbacks sent to LyftenBloggie
	 * @since 1.1.0
	 **/
	function trackback()
	{
		// trackback is done by a POST
		$posts = JRequest::get('post');
		if (empty($posts)) {
			$component =& JComponentHelper::getComponent('com_lyftenbloggie');
			$menus	= &JApplication::getMenu('site', array());
			$items	= $menus->getItems('componentid', $component->id);
			$url = JRoute::_('index.php?option=com_lyftenbloggie&Itemid='.$items[0]->id);
			$this->setRedirect($url);
			return;
		}

		//Get trackback object
		$trackback = BloggieFactory::load('trackback');
		$trackback->process();
	}

	/**
	 * Safely extracts not more than the first $count characters from html string
	 **/
	function _makeExcerpt( $str, $count )
	{
		$str = strip_tags( $str );
		$str = mb_strcut( $str, 0, $count );
		// remove part of an entity at the end
		$str = preg_replace( '/&[^;\s]{0,6}$/', '', $str );
		return $str;
	}

	/**
	 * Method to save comment
	 **/
	function postComment()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Initialize variables
		$db			= & JFactory::getDBO();
		$msg 		= JText::_( 'COMMENT SAVED' );
		$return		= '';

		//get model
		$model = $this->getModel('comments');

		//get data from request
		$post = JRequest::get('post');
	
		if (!$model->store($post) ) {
			$msg = '';
			$return = $model->_return;
		}

		if (JRequest::getVar('view') == 'comments') {
			$link = JRoute::_('index.php?option=com_lyftenbloggie&view=comments'.$model->get('archive').'&id='.$model->get('slug').$return, false);
		}else{
			$link = JRoute::_('index.php?option=com_lyftenbloggie&view=entry'.$model->get('archive').'&id='.$model->get('slug').$return, false);
		}
		$this->setRedirect($link, $msg);
	}

	/**
	 * Cancels an edit item operation
	 **/
	function publish()
	{
		// Initialize variables
		$return = base64_decode(JRequest::getVar('return'));
		$msg 	= JText::_('UNABLE TO PERFORM ACTION');

		//Check to see if the user is allowed to (un)publish
		if (BloggieFactory::allowAccess('author.can_publish') && $return)
		{
			// Initialize variables
			$db		= & JFactory::getDBO();
			$user 	=& JFactory::getUser();
			$id 	= JRequest::getInt('id');
			$pub	= JRequest::getInt('publish');

			if ($id)
			{
				$query = 'UPDATE #__bloggies_entries'
					. ' SET state = \''. (int)$pub .'\''
					. ' WHERE id = \''. (int)$id .'\''
					. ' AND ( checked_out = 0 OR ( checked_out = ' . (int) $user->get('id'). ' ) )'
				;
				$db->setQuery( $query );
				if ($db->query())
				{
					$query = 'SELECT e.title'
						. ' FROM #__bloggies_entries AS e'
						. ' WHERE e.id = \''.(int)$id.'\'';
					$db->setQuery($query);
					$title = $db->loadResult();
					$msg =  ($pub) ? JText::sprintf('ENTRY PUBLISHED', $title) : JText::sprintf('ENTRY UNPUBLISHED', $title);
				}
			}
		}

		//Ensure return URL
		if(!$return){
			$component =& JComponentHelper::getComponent('com_lyftenbloggie');
			$menus	= &JApplication::getMenu('site', array());
			$items	= $menus->getItems('componentid', $component->id);
			$return = JRoute::_('index.php?option=com_lyftenbloggie&Itemid='.$items[0]->id);
		}
		$this->setRedirect($return, $msg);
	}

}
?>