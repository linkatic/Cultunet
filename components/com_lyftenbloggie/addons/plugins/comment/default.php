<?php
/**
 * Default Comment Plugin
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

class DefaultCommentPlugin extends BloggiePlugin
{
	/**
	 * Constructor
	 */
	function DefaultCommentPlugin()
	{
		parent::__construct();
	}

	//Gets the Comment Form
	function displayForm($id=null, $total=0)
	{
		$return = '';

		//No ID set
		if(!$id) return;

		// Initialize variables
		$document 	= & JFactory::getDocument();
		$user		= & JFactory::getUser();
		$uri     	=& JFactory::getURI();
		$action		= $uri->toString();

		// Handle Captcha
		if($this->_settings->get('enableCaptcha')) {
			BloggieFactory::import('captcha', 'helpers');
			$captcha 	= BloggieCaptcha::getCaptcha();
		}

		//Get Posted Data
		$data 			= array();
		
		if($data['comment'] = JRequest::getVar('comment')){
			$data['comment'] = base64_decode($data['comment']);
		}

		if($data['name'] = JRequest::getVar('name')){
			$data['name'] = base64_decode($data['name']);
		}
		
		if($data['website'] = JRequest::getVar('website')){
			$data['website'] = base64_decode($data['website']);
		}
		
		if($data['email'] = JRequest::getVar('email')){
			$data['email'] = base64_decode($data['email']);
		}

		//Add Javascript
		$document->addCustomTag('<script type="text/javascript" src="'.JURI::base().'components/com_lyftenbloggie/assets/js/lyftenbloggie.js"></script>');
		$document->addCustomTag('<script type="text/javascript" src="'.JURI::base().'components/com_lyftenbloggie/assets/js/ajax.js"></script>');
		$document->addCustomTag('<script type="text/javascript" src="'.JURI::base().'components/com_lyftenbloggie/assets/js/modal.js"></script>');

		if(!$user->guest || $this->_settings->get('allowAnon'))
		{
			$return .= '<div class="lb_new_comment">
						<a name="respond"></a>
						<form action="'.$action.'" method="post" name="adminForm">
						<h3>'.JText::_('ADD NEW COMMENT').'</h3>
						<div class="lb_postcomment">
							<div style="width: 100%;" class="lb_comoment_content">
								<div class="lb_comoment_txt">
									<textarea class="lb_textarea" name="comment">'.$data['comment'].'</textarea>
								</div>
								<div class="lb_userdata">
									<div style="width: 100%;">
										<div class="lb_postcolumn">
											<label for="txtNameNewThread">'.JText::_('NAME').'</label>';
			if($user->guest && $this->_settings->get('allowAnon'))
			{
				$return .=	'<input class="lb_text" name="name" value="'.$data['name'].'" type="text">';
			}else{
				$return .=	'<input class="lb_text" name="name" disabled="true" value="'.$user->get('name').'" type="text">';
			}
			$return .=	'<span class="req">*</span></div><div class="lb_postcolumn">';
			if($user->guest && $this->_settings->get('allowAnon'))
			{
				$return .=	'<label for="txtEmailNewThread">'.JText::_('EMAIL').'<span class="req">*</span></label>';
			}else{
				$return .=	'<label for="txtEmailNewThread">'.JText::_('EMAIL OPTIONAL').'</label>';
			}
			$return .=	'<input class="lb_text" name="email" value="'.$data['email'].'" type="text" />
										</div>
										<div class="lb_postcolumn">
											<label for="txtURLNewThread">'.JText::_('WEBSITE OPTIONAL').'</label>
											<input class="lb_text" name="website" value="'.$data['website'].'" type="text" />
										</div>';
			if(isset($captcha))
			{
				$return .="<SCRIPT><!--
					function JGetElementById( s ) {
						var o = (document.getElementById ? document.getElementById(s) : document.all[s]);
						return ((o == null) ? false : o);
					}
					function reloadCaptcha() {
						var ocap = JGetElementById('captchaimage');
						if (ocap) {
							var today = new Date(); 
							ocap.setAttribute( 'src', '".$captcha['url_imagecore']."' + today.getTime() + '".str_replace( '&amp;', '&',  $captcha['image_params'] )."' );
						}
						var ocapc = JGetElementById('captchacode');
						if (ocapc) {
							ocapc.value='';
							ocapc.focus();
						}
					}
					--></SCRIPT>";
				$return .= '<div class="lb_postcolumn">
								<label for="txtURLNewThread">'.JText::_('CAPTCHA').'</label>
								
								<input class="lb_text" type="text" id="captchacode" name="captchacode" type="text" /><span class="commentCaptcha"><img id="captchaimage" src="'.$captcha['url_image'].'1" title="'.JText::_( 'CAPTCHACODE_TEXT' ).'" alt="'.JText::_( 'CAPTCHACODE_TEXT' ).'" onclick="reloadCaptcha()" style="cursor: pointer;"></span>
								<input type="hidden" name="captchasessionid" value="'.$captcha['captchasessionid'].'" />
							</div>';
			}
			$return .= '<div class="lb_postcolumn" style="float:right;padding-top:10px;">
						<button class="entry_button" id="dsq-post-button" onclick="document.adminForm.submit();"><span>'.JText::_( 'Post' ).'</span></button>
						<button class="entry_button" id="dsq-post-button" onclick="showHiddenDiv(\'entryComment\');return false;"><span>'.JText::_( 'Cancel' ).'</span></button>
							</div>
						</div></div>
						<input type="hidden" name="id" value="'.$id.'" /><input type="hidden" name="referer" value="'.@$_SERVER['HTTP_REFERER'].'" />
						'.JHTML::_( 'form.token' ).'
						<input type="hidden" name="task" value="postComment" />
						</form>
					</div>';
		}else{
			$url = JRoute::_('index.php?option=com_user&view=login&return='.base64_encode($action));			
			$return .= '<div class="post-comment"><a href="'.$url.'">'.JText::_('Please login to comment').'</a></div>';
		}

		return $return;
	}

	//Gets the Entry's Comment Count
	function getCount($id)
	{
		$output = array();
		$id = (int)$id;

		//Get Total
		$query = "SELECT COUNT(id) FROM #__bloggies_comments WHERE `entry_id`='{$id}'";
		$this->_db->setQuery( $query );
		$output['total'] = $this->_db->loadResult();

		//Get Approved
		$query = "SELECT COUNT(id) FROM #__bloggies_comments WHERE `entry_id`='{$id}' AND `state`='1'";
		$this->_db->setQuery( $query );
		$output['approved'] = $this->_db->loadResult();

		//Get Flagged
		$query = "SELECT COUNT(id) FROM #__bloggies_comments WHERE `entry_id`='{$id}' AND (`state`='-1' OR `state`='3')";
		$this->_db->setQuery( $query );
		$output['moderated'] = $this->_db->loadResult();

		//Get Spam
		$query = "SELECT COUNT(id) FROM #__bloggies_comments WHERE `entry_id`='{$id}' AND `state`='2'";
		$this->_db->setQuery( $query );
		$output['spam'] = $this->_db->loadResult();

		return $output;
	}

	//Gets the Entry's Comments
	function getComments($args = array())
	{
		$where = array();

		if ($args['status'])
		{
			if ( 'hold' == $args['status'] )
				$args['status'] = -1;
			else if ( 'approve' == $args['status'] )
				$args['status'] = 1;
			else if ( 'flagged' == $args['status'] )
				$args['status'] = 2;
			else if ( 'spam' == $args['status'] )
				$args['status'] = 3;

			$where[] = 'c.state = \''.$args['status'].'\'';
		}

		if ($args['post_id'])
		{
			$where[] = 'c.entry_id = \''.$args['post_id'].'\'';
		}

		$where 	= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		$query = 'SELECT c.id FROM #__bloggies_comments AS c'.$where.' ORDER BY c.date DESC';
		$comments = $this->_getList($query, $args['offset'], $args['number']);

		return $comments;
	}

	//Gets the Entry's Comment
	function getComment($id)
	{
		global $mainframe;

		$query = 'SELECT c.*, e.title AS entry_title, u.name AS author_name,'
				. ' CASE WHEN CHAR_LENGTH(c.author_email) THEN c.author_email ELSE u.email END as author_email'
				. ' FROM #__bloggies_comments AS c'
				. ' LEFT JOIN #__bloggies_entries AS e ON e.id = c.entry_id'
				. ' LEFT JOIN #__users AS u ON u.id = c.user_id'
				. ' WHERE c.id = \''.(int)$id.'\''
				. ' ORDER BY c.date DESC';
		$this->_db->setQuery( $query );
		$comment = $this->_db->loadObject();

		if ( isset($comment->id) )
		{
			//Fix XMLRPC JRoute Error
			$comment->link = ($mainframe->_clientId != 3) ? JRoute::_('index.php?&option=com_lyftenbloggie&view=comments&id='.$comment->entry_id.'') : JURI::root().'index.php?&option=com_lyftenbloggie&view=comments&id='.$comment->entry_id;
			if($comment->type == '2'){
				$comment->type = JText::_( 'TRACKBACK' );
			}else{
				$comment->type = JText::_( 'COMMENT' );
			}
			if ( -1 == $comment->state )
				$comment->state = 'hold';
			else if ( 1 == $comment->state )
				$comment->state = 'approve';
			else if ( 2 == $comment->state )
				$comment->state = 'flagged';
			else if ( 3 == $comment->state )
				$comment->state = 'spam';
			else
				$comment->state = $comment->state;
		}

		return $comment;
	}

	//Delete an Entry's Comment
	function deleteComment($id)
	{
		//Remove Comment
		$query = 'DELETE FROM #__bloggies_comments WHERE `id` = \''.$id.'\'';
		$this->_db->setQuery($query);
		if(!$this->_db->query())
		{
			return JText::_('UNABLE TO DELETE COMMENT');
		}

		//Remove Comment Reports
		$query = 'DELETE FROM #__bloggies_reports WHERE `comment_id` = \''.$id.'\'';
		$this->_db->setQuery($query);
		$this->_db->query();

		return false;
	}

	//Edit an Entry's Comment
	function editComment($data)
	{
		if(!is_array($data) || !@$data['id'])
			return JText::_('AN ERROR HAS OCCURRED');

		$sets = array();
		$sets[] = 'c.date = \''.$data['date'].'\'';

		if ($data['content'])
		{
			$sets[] = 'c.content = \''.$data['content'].'\'';
		}else{
			return JText::_('PLEASE ENTER A COMMENT');
		}

		if ($data['status'])
		{
			if ( 'hold' == $data['status'] )
				$data['status'] = -1;
			else if ( 'approve' == $data['status'] )
				$data['status'] = 1;
			else if ( 'flagged' == $data['status'] )
				$data['status'] = 2;
			else if ( 'spam' == $data['status'] )
				$data['status'] = 3;

			$sets[] = 'c.state = \''.$data['status'].'\'';
		}

		if (isset($data['author']))
			$sets[] = 'c.author = \''.$data['author'].'\'';

		if (isset($data['author_url']))
			$sets[] = 'c.author_url = \''.$data['author_url'].'\'';

		if (isset($data['author_email']))
			$sets[] = 'c.author_email = \''.$data['author_email'].'\'';

		$sets 	= ( count( $sets ) ? ' SET ' . implode( ', ', $sets ) : '' );
		$query = 'UPDATE #__bloggies_comments AS c'
			. $sets
			. ' WHERE c.id = \''.$data['id'].'\'';
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			return JText::_('AN ERROR HAS OCCURRED');
		}

		return false;
	}

	//Create a new Entry Comment
	function newComment($data)
	{
		if(!is_array($data) || !@$data['entry_id'])
			return JText::_('AN ERROR HAS OCCURRED');

		$datenow 		= & JFactory::getDate();
		$data['date'] 	= $datenow->toMySQL();
		$data['status'] = 1;

		$query = "INSERT IGNORE INTO `#__bloggies_comments`"
				. " (`entry_id`,`author`,`author_email`,`author_url`,`date`,`content`,`user_id`,`type`,`state`) VALUES"
				. " ('".$data['entry_id']."', '".$data['author']."', '".$data['author_email']."', '".$data['author_url']."',"
				. " '".$data['date']."', '".$data['content']."', '".$data['author_id']."', '1', '".$data['status']."' )";
		$this->_db->setQuery($query);
		if (!$this->_db->query()) {
			return JText::_('AN ERROR HAS OCCURRED');
		}

		return false;
	}

	//Get an Entry's Trackbacks
	function getTrackbacks($id)
	{
		$query = 'SELECT c.content, c.author_url, c.author_ip'
				. ' FROM #__bloggies_comments AS c'
				. ' WHERE c.entry_id = \''.(int)$id.'\''
				. ' AND c.type = \'2\'';
		$this->_db->setQuery( $query );
		$trackbacks = $this->_db->loadObjectList();

		return $trackbacks;
	}

	//Get an Entry's Trackback link
	function getTrackbackLink($options = array())
	{
		global $mainframe;

		$slug 		= (isset($options['slug'])) ? $options['slug'] : '';
		$archive 	= (isset($options['archive'])) ? $options['archive'] : '';
		$url = trim($this->_siteUrl, '/').(($mainframe->_clientId != 3) ? JRoute::_('index.php?option=com_lyftenbloggie&task=trackback'.$archive.'&id='. $slug) : 'index.php?option=com_lyftenbloggie&task=trackback'.$archive.'&id='. $slug);
		return $url;
	}
}